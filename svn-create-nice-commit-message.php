#!/usr/bin/php
<?php

	exec( '`which svn` up' );
	exec( '`which svn` info --xml ', $infodata );
	$InfoXML = simplexml_load_string( join( "\n", $infodata ) );
	$trunkurl = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : (string)$InfoXML->entry->repository->root . '/trunk';

	if (!isset($_SERVER['argv'][1])) {
		$infodata = '';
		exec( '`which svn` info --xml ', $infodata );
		$InfoXML = simplexml_load_string( join( "\n", $infodata ) );
		$mergeUrl = (string)$InfoXML->entry->repository->root . '/trunk';
	} else {
		$mergeUrl = $_SERVER['argv'][1];
	}

	exec( '`which svn` mergeinfo --show-revs eligible ' . $mergeUrl, $revs );

	$log = '';
	$start = null;
	$stop = null;
	foreach( $revs as $r ) {
		$data = null;
		if ( $start === null ) $start = (int)substr( $r, 1 ) - 1;
		$stop = substr( $r, 1 );
		exec('`which svn` log --xml -' . $r . ' ' . $mergeUrl, $data );
		$XML = simplexml_load_string( join( "\n", $data ) );

		$attrs = $XML->logentry->attributes();
		$log .= '[' . $attrs[ 'revision' ] . '] (' . $XML->logentry->author . ') ' . trim( $XML->logentry->msg ) . "\n";
		$log .= "\n";
	}

	$out = 'Merging changes r' . $start . ':' . $stop . ' from ' . $mergeUrl . "\n";
	$out .= "\n";
	$out .= $log;

	file_put_contents( 'changes.txt', $out );
	echo $out;
	echo "\n----------------------------------------\nSaved to changes.txt\n";
