<?php

class FileModifier {

	private $inputFile;
	private $outputFile;

	private $replaceLines = array();
	private $optionalReplaceLines = array();

	private $addAfterLines = array();
	private $optionalAddAfterLines = array();

	private $deleteLines = array();
	private $optionalDeleteLines = array();

	private $missingLines;

	function __construct($inputFile, $outputFile = null) {
		$this->inputFile = $inputFile;

		if($outputFile == null) {
			$this->outputFile = $inputFile;
		}
		else {
			$this->outputFile = $outputFile;
		}
	}

	public function replaceLine($search, $replace, $optional = false) {
		$this->replaceLines[$search] = $replace;

		if($optional === true) {
			$this->optionalReplaceLines[$search] = $replace;
		}
	}

	public function addAfterLine($search, $add, $optional = false) {
		$this->addAfterLines[$search] = $add;

		if($optional === true) {
			$this->optionalAddAfterLines[$search] = $add;
		}
	}

	public function deleteLine($search, $numberOfLines, $optional = false) {
		$this->deleteLines[$search] = $numberOfLines;

		if($optional === true) {
			$this->optionalDeleteLines[$search] = $numberOfLines;
		}
	}

	public function execute() {
		$lines = file($this->inputFile);
		$newLines = '';
		$numberOfLinesToDelete = 0;

		foreach($lines as $line) {
			$delete = false;

			if($numberOfLinesToDelete != 0) {
				$numberOfLinesToDelete--;
				$delete = true;
			}

			// Delete
			if ($delete === false) {
				foreach ($this->deleteLines as $key => $numberOfLines) {
					if(strpos($line, $key) !== false) {
						$delete = true;
						$numberOfLinesToDelete = $numberOfLines - 1;
						unset($this->deleteLines[$key]);
						break;
					}
				}
			}

			if($delete === false) {
				// Replace
				foreach ($this->replaceLines as $key => $replaceLine) {
					if(strpos($line, $key) !== false) {
						$line = str_replace($key, $replaceLine, $line);
						unset($this->replaceLines[$key]);
					}
				}

				$newLines .= $line;
			}

			// Add
			foreach ($this->addAfterLines as $key => $addAfterLine) {
				if(strpos($line, $key) !== false) {
					$newLines .= $addAfterLine;
					unset($this->addAfterLines[$key]);
					break;
				}
			}
		}
		
		$missingReplaceLines = array_diff_key($this->replaceLines, $this->optionalReplaceLines);
		$missingDeleteLines = array_diff_key($this->deleteLines, $this->optionalDeleteLines);
		$missingAddAfterLines = array_diff_key($this->addAfterLines, $this->optionalAddAfterLines);

		$this->missingLines = array_merge($missingReplaceLines, $missingDeleteLines, $missingAddAfterLines);

		$success = empty($this->missingLines);

		if($success) {
			$file = fopen($this->outputFile, 'w');

			fwrite($file, $newLines);
			fclose($file);
		}

		return $success;
	}

	public function getMissingLines() {
		return $this->missingLines;
	}
}

?>