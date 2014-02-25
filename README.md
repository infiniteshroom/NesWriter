NesWriter
=========

A simple php script to replace text within NES Roms. Windows/Mac/*nix

Can be run from the cli using run.php e.g php run.php or the writer.php class can be used standalone for embedding in a website - for instance.

Replace Text: [romfile] --replace [text] [replace]
Dump Text(Hex View): [romfile] --dump

Notes
======

* The script doesn't currently support NES pointers, this means that the text you intend to replace can only be replaced with text of the same length or smaller.
* The script has only been tested with Legend of Zelda NES and Super Mario Bros. NES. You can add custom mappings for iNES header titles or filenames in the "config/mappings.php" file and font tables for these mappings can be created based on "./tables/generic.php". If a font table cannot be found, NesWriter will prompt you for a file path to one. Support for .tpl...soon ;)
* This is very experimental and was more of a learning process for me than anything else - so no promises that this script is anywhere near perfect ;)
