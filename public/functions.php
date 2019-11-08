<?php

function file_get_lines($filepath, $n=1) {
	$result = ""; 
	$fn = fopen($filepath,"r");

	for ($i=0; $i<$n; $i++) {
		$result .= fgets($fn);
	}
	
	fclose($fn);
	return $result;
}

function parse_toml($lines) {
	$lines = trim($lines);
	if (is_string($lines)) {
		$lines = explode("\n", $lines);
		$header = [];
		foreach($lines as $line) {
			$line = explode(":", $line);
			$line[0] = trim($line[0]);
			$line[1] = trim($line[1]);

			if (strpos($line[1], '[') !== false) {
				$line[1] = json_decode($line[1], true);
			}
			$header[$line[0]] = $line[1];
		}
	}
	return $header;
}
