<?php

	/* 102 111 114 032 116 104 111 115 101 032 119 104 111 032 115 097 105 100 032 105 032 099 111 117 108 100 110 039 116 032 119 101 108 108 032 105 032 100 105 100 032 058 051 */
	class NesWriterConstants {
		const INES_MAGIC_STRING = "NES";
		const ROM_INVALID =  "Rom file does not exist :( \n";
		const CORRUPT_HEADER = "iNES header appears to be corrupt or invalid NES ROM :( \n";
		const TOO_LONG = "Sorry replacement text must be the same length or smaller then original text :( \n";
		const TABLE_FILE_MISSING = "Font table file is missing or not valid :(\n";
		const SAVE_PERMISSIONS_ERROR = "Cannot save the rom - no write permissions :(\n";
		const READ_PREMISSIONS_ERROR = "Cannot read the rom - no read permissions :(\n";
		const TABLE_NO_MAPPING = "Cannot find a font table, press enter to use [generic.php] or type path to file: ";
	}

	class NesWriter {
		private $file = null;
		private $rom = null;
		private $table = null;

		public function __construct($table = null) {

			if($this->table != null) {
				$this->table = include_once($table);
			}
		}

		private function detectTable() {
			/* detect correct font Table to load */

			include_once('./config/mappings.php');

			if(count($iNes_tables) > 0) {
				/* check for a iNes title */
				foreach($iNes_tables as $key => $value) {
					if(strpos($this->rom, $key) !== false) {
						return $value;
					}
				}
			}

			/* check for filename mapping */
			if(count($filename_tables) > 0) {
				foreach($filename_tables as $key => $value) {
					if(strpos($this->file, $key) !== false) {
						return $value;
					}
				}
			}

			return null;

		}	


		public function loadRom($path) {
			$this->file = $path;

			if(!file_exists($path)) {
				exit(NesWriterConstants::ROM_INVALID);
			}

			if(!is_readable($path)) {
				exit(NesWriterConstants::READ_PREMISSIONS_ERROR);
			}


			$this->rom = file_get_contents($this->file);

			/* let's check the iNES header to make sure, it's a NES ROM - first 3 bytes should be 'NES' */
			if(substr($this->rom, 0, 3) != NesWriterConstants::INES_MAGIC_STRING) {
				exit(NesWriterConstants::CORRUPT_HEADER);
			}

			/* detect font table */
			if($this->table == null) {
				$table = $this->detectTable();

				/* did we correctly detect the font table */
				if($table == null) {
					echo NesWriterConstants::TABLE_NO_MAPPING;

					/* wait for user to input font path or press enter */
					$handle = fopen ("php://stdin","r");
					$line = fgets($handle);

					/* user pressed enter load generic font table */
					if(trim($line) == "") {
					
						if(!file_exists('./tables/generic.php')) {
							exit(NesWriterConstants::TABLE_FILE_MISSING);
						}

						$this->table = include_once('./tables/generic.php');
					}

					else {
						
						/* user inputs a path, attempt font table load from path */
						if(!file_exists(trim($line))) {
							exit(NesWriterConstants::TABLE_FILE_MISSING);
						}


						$this->table = include_once(trim($line));
					}
				}

				/* if font was correctly detected load it now */
				else {
					
					if(!file_exists('./tables/' . $table)) {
						exit(NesWriterConstants::TABLE_FILE_MISSING);
					}

					$this->table = include_once('./tables/' . $table);
				}
			}
		}

		public function dumpText() {
			/* dump all text from game, garbage may be generated at some point in the rom */
			$rom_unpacked = unpack("H*", $this->rom);

			/* spilt into each byte */
			$hex_pairs = str_split($rom_unpacked[1], 2);

			/* we need to flip the table array - key=value - value=key */
			$font_table = $this->table;
		
			$font_table = array_flip($font_table);

			/* result string */
			$text_results = array();

			$hex_results = array();


			$output = "";
			$count = 0;

			foreach($hex_pairs as $pair) {

				if($count == 17 && $count != 0) {
					$text_results[] = "\n";
					$hex_results[] = "\n";
					$count = 0;
				}


				if(isset($font_table[$pair])) {

					$text_results[] = $font_table[$pair];
					$hex_results[] = strtoupper($pair) . ' ';
				}

				else {
					$text_results[] = '#';
					$hex_results[] = strtoupper($pair) . ' ';
				}

				$count++;


			}

			/* implode on \n for 17th byte */

			$joined_hex = implode("", $hex_results);
			$joined_text = implode("", $text_results);

			$spilt_hex = explode("\n", $joined_hex);
			$spilt_text = explode("\n", $joined_text);


			foreach($spilt_hex as $key => $value) {
				$output .= "{$spilt_hex[$key]} || {$spilt_text[$key]}\n";
			}

			echo $output;

		}

		public function replaceText($find, $replace) {

			if(strlen($replace) > strlen($find)) {
				exit(NesWriterConstants::TOO_LONG);
			}
			/* unpack rom */
			$rom_unpacked = unpack("H*", $this->rom);

			/* generate hex for both bits of text */
			$find_hex = "";

			foreach(str_split($find) as $char) {
				$find_hex .= $this->table[strtolower($char)];
			}

			$replace_hex = "";

			/* if replacement string is bigger then find string, this cannot be done - TODO: NES pointers :D */
			foreach(str_split($replace) as $char) {
				$replace_hex .= $this->table[strtolower($char)];
			}

			/* check if replace string is smaller than hex string */
			if(strlen($replace) < strlen($find)) {
				/* so the replace string is smaller we need to pad it out with spaces */

				$padding_requried = strlen($find) - strlen($replace);

				for($i = 0;$i < $padding_requried; $i++) {
					$replace_hex .= $this->table[" "];
				}
			}

			/* replace text */
			$rom_unpacked = str_replace($find_hex, $replace_hex, $rom_unpacked[1]);


			/* repack rom */
			$this->rom = pack('H*', $rom_unpacked);

		}

		public function saveRom() {

			/* do we have write permission to the rom */
			if(!is_writable($this->file)) {
				exit(NesWriterConstants::SAVE_PERMISSIONS_ERROR);
			}

			file_put_contents($this->file, $this->rom);
		}
	}

?>