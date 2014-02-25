<?php

include_once('writer.php');

const ROM_FILE_ARG_OFFSET = 1;
const SETTING_ARG_OFFSET = 2;
const TEXT_ARG_OFFSET = 3;
const REPLACE_ARG_OFFSET = 4;
const REPLACE_SETTING_STRING = '--replace';
const DUMP_SETTING_STRING = '--dump';

/*const GENERATE_SETTING_STRING = '--generate';*/


$writer = null;

if(!isset($argv[ROM_FILE_ARG_OFFSET])) {
	syntaxMessage();
}

if(isset($argv[SETTING_ARG_OFFSET])) {

	if($argv[SETTING_ARG_OFFSET] != REPLACE_SETTING_STRING && $argv[SETTING_ARG_OFFSET] != DUMP_SETTING_STRING) {
		syntaxMessage();
	}

	if(isset($argv[TEXT_ARG_OFFSET]) && isset($argv[REPLACE_ARG_OFFSET])) {
		$writer = new NesWriter();
		$writer->loadRom($argv[ROM_FILE_ARG_OFFSET]);

		$text = $argv[TEXT_ARG_OFFSET];
		$replace = $argv[REPLACE_ARG_OFFSET];

		
		$writer->replaceText($text, $replace);
		$writer->saveRom();
		successMessage($argv[ROM_FILE_ARG_OFFSET]);
	}

	if($argv[SETTING_ARG_OFFSET] == DUMP_SETTING_STRING) {
		$writer = new NesWriter();
		$writer->loadRom($argv[ROM_FILE_ARG_OFFSET]);
		$writer->dumpText();
	}

	else {
		syntaxMessage();
	}
}

else {
	syntaxMessage();
}


function syntaxMessage() {
	echo "NesWriter V0.1 By infiniteshroom 2014\n";
	echo "======================================\n";
	echo "Replace text: [romfile] --replace [text] [replace]\n";
	echo "Dump text: [romfile] --dump\n";
	exit;
}

function successMessage($file) {
	echo "File: $file text has been replaced :) \n";
}

?>