#!/bin/env python3

"""
Sort a list of args provided on the command line.

Example:

    sortArgs.py Python, Apache, MySQL

Returns:

    Apache, MySQL, Python
"""

import sys
import unittest

def sortArgs(args):
    return ", ".join(list(map(lambda x: x.rstrip(" ,"), sorted(args, key=str.lower))))

class TestSortArgs(unittest.TestCase):

    def test_sort(self):
        self.assertEqual('Apache, MySQL, Python', sortArgs(['Python,', 'Apache,', 'MySQL']))

if __name__ == '__main__':
    print(sortArgs(sys.argv[1:]))
