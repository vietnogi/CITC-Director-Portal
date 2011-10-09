<?
// 11:09 AM 9/3/2010
// Minify for CSS / JS
class Minify {
	// Base file extension
	private $languageFileExtension = '.php';
	// Valid filetypes
	private $validFileTypes = array('css' => 'css'
									, 'javascript' => 'js'
									);
	// Current filetype
	private $fileType;
	// Full extension (eg. .css.php)
	private $fullFileExtension;
	
	// Record file for file modification times
	private $recordFile = 'record.txt';
	// Output file to be read on user end (eg. minify.css.php)
	private $outputFile;
		
	// All files
	private $files = array();
	// Previous batch of files
	private $previousFiles = array();
	// Priority files to go at beginning of output file
	private $priorityFiles = array();
	// Files to skip (eg. conditional CSS: wordpress, ie, ie6)
	private $skipFiles = array();
	// Normal files to output after priority files
	private $normalFiles = array();
	
	// Constructor
	public function __construct($priorityFiles, $skipFiles = array()) {
		$bc = new Breadcrumbs();
		$fileType = $bc->crumbs[0];
		
		if (!in_array($fileType, $this->validFileTypes)) {
			trigger_error('Invalid file extension.', E_USER_ERROR);
		}
	
		// Set file extension and minify output file
		$this->fileType = $fileType;
		$this->fullFileExtension = '.' . $this->fileType . $this->languageFileExtension;
		$this->outputFile = 'minify' . $this->fullFileExtension;
		
		// Set priority and skip files
		$this->setFileGroup('priorityFiles', $priorityFiles);
		$this->setFileGroup('skipFiles', $skipFiles);
		
		// Read directory for files
		$this->getFiles();
		
		// If update needed, write record and output files
		if ($this->needUpdate()) {
			$this->writeRecordfile();
			$this->writeOutputfile();
		}
		
		// Output the file
		$this->output();
	}
	
	// Get alphabetically sorted files and modification time
	private function getFiles() {
		foreach (glob('*' . $this->fullFileExtension) as $fileName) {
			$this->files[$fileName] = filemtime($fileName);
		}
		
		// Remove output file (eg. minify.css.php)
		unset($this->files[$this->outputFile]);
		
		$this->normalFiles = $this->files;
		// Remove priority and skip files
		foreach (array_merge($this->priorityFiles, $this->skipFiles) as $file) {
			unset($this->normalFiles[$file]);
		}
	}
	
	// Set file group (priority and skip)
	private function setFileGroup($fileGroup, $groupFiles) {
		if (is_array($groupFiles) && !empty($groupFiles)) {
			foreach ($groupFiles as $groupFile) {
				array_push($this->$fileGroup, $groupFile . $this->fullFileExtension);
			}
		}
	}
	
	// Read/unserialize record file array
	private function getPreviousFiles() {
		$recordFileContents = @file_get_contents($this->recordFile);// or die('Unable to get record file contents.');
		$this->previousFiles = unserialize($recordFileContents);
	}
	
	// Check if output file needs to be updated
	private function needUpdate() {
		// If no record or output file
		if (!file_exists($this->recordFile) || !file_exists($this->outputFile)) {
			return true;
		}
		// Else check file modification times
		else {
			$this->getPreviousFiles();
			// If newer files
			if ($this->previousFiles != $this->files) {
				return true;
			}
		}
		return false;
	}
		
	// Save serialized array of files
	private function writeRecordFile() {
		$fileHandle = @fopen($this->recordFile, "w") or die('Unable to open record file.');
		fwrite($fileHandle, serialize($this->files));
		fclose($fileHandle);
	}
	
	// Write minified/output file file
	private function writeOutputFileFile($fileHandle, $fileName) {
		// Need workaround for '//' comments in js & css/php
		//fwrite($fileHandle, "\n/* " . $fileName . " */\n" . preg_replace('/[ \\t\\r\\n]+/', ' ', file_get_contents($fileName)));
		//fwrite($fileHandle, "\n/* " . $fileName . " */\n" . file_get_contents($fileName));
		fwrite($fileHandle, "\necho \"\n\n/* " . $fileName . " */\n\"; \n\nrequire DR . '/" . $this->fileType . "/" . $fileName . "';\n");
	}
	
	// Write minified/output file
	private function writeOutputFile() {
		$fileHandle = @fopen($this->outputFile, "w") or die('Unable to open minify file.');
		
		//dont need define since this file already has it
		//fwrite($fileHandle, "<?\nrequire '../config/define.php';\n") or die('Unable to write to minify file.');
		fwrite($fileHandle, "<?\n") or die('Unable to write to minify file.');
		
		// Write priority files first
		foreach ($this->priorityFiles as $fileName) {
			$this->writeOutputFileFile($fileHandle, $fileName);
		}
		// Write remaining files
		foreach ($this->normalFiles as $fileName => $modificationTime) {
			$this->writeOutputFileFile($fileHandle, $fileName);
		}
		
		fwrite($fileHandle, "?>") or die('Unable to write to minify file.');
		
		fclose($fileHandle);
	}
	
	// Set header and output
	private function output() {
		session_start();
		header('Content-type: text/' . array_search($this->fileType, $this->validFileTypes));
		require $this->outputFile;
	}
}
?>