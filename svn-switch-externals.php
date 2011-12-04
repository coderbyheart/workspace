#!/usr/bin/php
<?php

    /**
    * Script to switch hostnames in svn:externals
    *
    * Usage: ./svn-switch-externals.php OLD_HOST NEW_HOST
    */

    $from = $_SERVER['argv'][1];
    $to = $_SERVER['argv'][2];

    // Find all dirs below
    exec('find ./ -type d | grep -v ".svn"', $dirs);

    // Create temporary fild to store new svn:externals
    $tmp = tempnam('/tmp', 'svn-switch-externals');

    foreach ($dirs as $dir) {
        // Get current externals
        $externals = array();
        exec('svn pg svn:externals ' . escapeshellarg($dir), $externals);
        if (empty($externals)) continue;
        $externals = join("\n", $externals);
        echo '# Externals on ' . $dir . ":\n";
        echo '> ' . preg_replace("/\n/", "\n> ", trim($externals)) . "\n";
        // Replace OLD_HOST with NEW_HOST
        $new_externals = str_replace($from, $to, $externals);
        if ($new_externals != $externals) {
            // Set new externals
            file_put_contents($tmp, $new_externals);
            exec('svn ps svn:externals -F ' . escapeshellarg($tmp) . ' ' . escapeshellarg($dir));
            echo $dir . "\n";
        }
    }

    // Remove temp file
    unlink($tmp);

?>
