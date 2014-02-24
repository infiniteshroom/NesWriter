NesWriter
=========

A simple php script to replace text within NES Roms.

Can be run from the cli using run.php e.g php run.php or the writer.php class can be used standalone for embedding in a website - for instance.

Syntax: [romfile] --replace [text] [replace]

Notes
======

* The script doesn't currently support NES pointers, this means that the text you intend to replace can only be replaced with text of the same length or smaller.
* The script has only been tested with Legend of Zelda NES and Super Mario Bros. NES. Although the table.php script can be updated with a known font table for another ROM. In the future would be nice to detect the ROM from the NES header and load the correct font table.
* This is very experimental and was more of a learning process for me than anything else - so no promises that this script is anywhere near perfect ;)
