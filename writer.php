<?php

	/* 102 111 114 032 116 104 111 115 101 032 119 104 111 032 115 097 105 100 032 105 032 099 111 117 108 100 110 039 116 032 119 101 108 108 032 105 032 100 105 100 032 058 051 */
	class NesWriter {
		private $file;
		private $rom;
		private $table;

		public function __construct($table = 'table.php') {
			$this->table = include_once($table);
		}	


		public function loadRom($path) {
			$this->file = $path;

			if(!file_exists($path)) {
				exit("Rom is invalid or file does not exist :( \n");
			}

			$this->rom = file_get_contents($this->file);
		}

		public function replaceText($find, $replace) {

			if(strlen($replace) > strlen($find)) {
				echo "Sorry replacement text must be the same length or smaller then original text :( \n";
				exit;
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
			file_put_contents($this->file, $this->rom);
		}
	}

?>