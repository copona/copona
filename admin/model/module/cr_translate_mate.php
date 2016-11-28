<?php
/**
 * Class ModelModuleCrTranslateMate
 *
 * Used to retrieve and save translations
 */
class ModelModuleCrTranslateMate extends Model {
	protected $model; // instance of the model - used to avoid OpenCart's proxy system

	public function __construct($registry) {
		$this->model = new CrTranslateMateModel($registry);
		parent::__construct($registry);
	}

	public function install() {
		$this->model->install();
	}

	public function uninstall() {
		$this->model->uninstall();
	}

	/**
	 * Get a real instance of this model (as opposed to the proxied version OpenCart creates)
	 *
	 * @return CrTranslateMateModel
	 */
	public function getInstance() {
		return $this->model;
	}

}
/**
 * Class CrTranslateMateModel
 *
 * OpenCart's proxy system creates a new reflected model object each time a model function is called.
 * That means data is not preserved in the model from one function to another. To fix this, I've created
 * a custom model outside the official model class above. The Translate Mate controller will ask for an
 * instance of this class instead of using OpenCart's proxied version.
 */
class CrTranslateMateModel extends model {
	protected $modName = 'cr_translate_mate';
	protected $dirs = array();
	protected $langs = array();
	protected $basePath = ''; // base path of the application
	protected $adminPath = ''; // relative path to the admin folder
	protected $catalogPath = ''; // relative path to the catalog folder
	protected $mainLangFileKey = '_main_lang_file';
	protected $mainLangFileStr = ''; // the localized string that will represent the main language file
	protected $lastLoadedFile = ''; // the name of the last loaded file

	public function __construct($registry) {
		$this->basePath = str_replace('/system', '', DIR_SYSTEM);
		$this->adminPath = str_replace($this->basePath, '', DIR_APPLICATION);
		$this->catalogPath = str_replace($this->basePath, '', DIR_CATALOG);
		$this->dirs = array(
			'admin'		 => DIR_LANGUAGE, // location of the admin language directory
			'catalog'	 => DIR_CATALOG . 'language/', // catalog language directory
		);
		parent::__construct($registry);
	}

	public function install() {
		// Language folder names changed in OpenCart 2.2.0.0, so make sure the language strings for this extension
		// are in the correct directory and remove the directory that isn't needed.
		if (version_compare(VERSION, '2.2.0.0', '<') && file_exists($this->dirs['admin'] . 'en-gb/module/cr_translate_mate.php')) {
			unlink($this->dirs['admin'] . 'en-gb/module/cr_translate_mate.php');
			rmdir($this->dirs['admin'] . 'en-gb/module');
			rmdir($this->dirs['admin'] . 'en-gb');
		} else if (file_exists($this->dirs['admin'] . 'english/module/cr_translate_mate.php')) {
			unlink($this->dirs['admin'] . 'english/module/cr_translate_mate.php');
			rmdir($this->dirs['admin'] . 'english/module');
			rmdir($this->dirs['admin'] . 'english');
		}
	}

	public function uninstall() {
		// for the moment, no uninstallation action is needed
	}

	// update the language file specified in the input
	public function saveTranslation($input) {
		$filepath = $this->getFilePath($input['dirKey'], $input['page'], $input['lang']);
		// check that we can write to the file if it exists
		if (file_exists($filepath) && !is_writable($filepath)) {
			return sprintf($this->language->get('error_write_permission'), $filepath);
		}

		$_ = array();
		if (file_exists($filepath)) { include($filepath); } // this should fill $_ with the strings for this file
		$_[$input['key']] = html_entity_decode($input['translation'], ENT_COMPAT, 'UTF-8');

		// create the file content with the updated array of translation strings
		// NOTE - this removes any comments in the file, but I've never really found the comments very helpful anyway
		$fileContents = "<?php\n";
		foreach ($_ as $key => $value) {
			$fileContents .= '$_[\'' . $key . '\'] = \'' . addcslashes($value, "'\\") . "';\n";
		}
		$fileContents .= "?>";

		// check that the directory exists first. If not, create it
		$fileDir = dirname($filepath);
		$dirExists = is_dir($fileDir);
		if (!$dirExists) {
			// attempt to get the appropriate directory permissions by looking at neighboring directories
			$dirPerms = 0777;
			foreach (array_filter(glob(dirname($fileDir) . '/*', GLOB_ONLYDIR)) as $dir) {
				$dirPerms = fileperms($dir);
			}
			$dirExists = mkdir($fileDir, $dirPerms, TRUE);
		}

		// if writing to the file fails, return an error. Othewise return a success indicator
		return $dirExists && (file_put_contents($filepath, $fileContents) !== FALSE) ? array(
			'success' => $_[$input['key']] ) :
			sprintf($this->language->get('error_write_permission'), $filepath);
	}

	// get the full file path based on the file name and language
	protected function getFilePath($dirKey, $file, $lang) {
		if ($file == $this->mainLangFileKey) { $file = $lang; };
		return $this->dirs[$dirKey] . $lang . '/' . $file . '.php';
	}

	// gets arrays of language data from Opencart
	public function langs() {
		if (!empty($this->langs)) { return $this->langs; }
		$this->load->model('localisation/language');
		$this->langs = $this->model_localisation_language->getLanguages();
		foreach ($this->langs as $key => $value) {
			$this->langs[$key]['directory'] = $this->langs[$key]['directory'];
		}

		return $this->langs;
	}

	// extracts the language name from the language arrays
	public function langNames() {
		return !empty($this->langNames) ? $this->langNames :
			($this->langNames = array_map(function ($a) {
			return $a['directory'];
		}, $this->langs()));
	}

	// list the filenames of all files in the language directories
	public function listFiles($dirKey = "catalog") {
		// get the directory names of all site languages
		$langs = $this->langNames();

		// iterate through all subdirectories in the main admin/catalog language directories
		$directory = new RecursiveDirectoryIterator($this->dirs[$dirKey]);
		$iterator = new RecursiveIteratorIterator($directory);
		// search for only PHP files
		$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

		foreach ($regex as $name => $object) {
			// replace backslashes with forward slashes if they exist
			$name = str_replace('\\', '/', $name);
			$fullPath = $name;
			// remove that language path
			$name = str_replace($this->dirs[$dirKey], '', $name);
			// extract the language directory
			$lang = explode('/', $name, 2);
			$name = $lang[1];
			$lang = $lang[0];
			// remove the '.php' extension
			$name = substr($name, 0, strlen($name) - 4);
			// finally add it to the list, setting the key as well to avoid duplicates
			if ($name != $lang) {
				$list[$name] = $name;
			} else { // or if the file is the main language file (english.php, spanish.php, etc), label it as such
				$list[$this->mainLangFileKey] = $this->mainLangFileStr();
			}
		}
		ksort($list); // put in alphabetical order

		return $list;
	}

	// get the name to use for the main language file for each language (english.php, spanish.php, etc)
	public function mainLangFileStr() {
		if (!empty($this->mainLangFileStr)) { return $this->mainLangFileStr; }
		$this->load->language('module/' . $this->modName);

		return $this->mainLangFileStr = h($this->language->get('text_main_lang_file'));
	}

	// load all the texts from the selected files with the selected options
	public function loadTexts(array $options = array()) {
		$opts = array( // option defaults
			'length'				 => 20, // we'll parse files until we've hit this number. Then we'll stop.
			'notTranslated'	 => false, // get only strings that aren't translated if true
			'dirKey'				 => 'catalog', // whether to fetch admin or catalog strings
			'singleFile'		 => false, // the name of a single file to load (or load all files if false)
			'keyFilter'			 => false, // name of the key to filter by (or no filter if false)
			'textFilter'		 => false, // text to filter by (or no filter if false)
			// 'startAfter' => (Start loading texts after this file. ex: account/account)
		);
		// overwrite defaults with given options
		$opts = array_merge($opts, $options);

		$files = $this->listFiles($opts['dirKey']);

		//prd($files); 

		$texts = array();
		$count = 0;
		$startHere = !isset($opts['startAfter']) && !$opts['singleFile']; // to indicate where to start loading files
		while ($count < $opts['length'] && list($page, $pageStr) = each($files)) {
			if (!$startHere) { // skip this file if is (or comes before) the file specifed in $opts['startAfter']
				if (isset($opts['startAfter']) && $page == $opts['startAfter']) {
					$startHere = true;
				}

				if ($page != $opts['singleFile']) { // or skip if this file isn't the specified file to load
					continue;
				}
			}
			$pageStrs = array();

			foreach ($this->langNames() as $lang) {
				$path = $this->dirs[$opts['dirKey']] . $lang . '/' . ($page == $this->mainLangFileKey ? $lang : $page) . '.php';
				if (!file_exists($path)) { continue; }
				// get the language strings (Opencart saves them to the array '$_')
				$_ = array();
				include($path);
				// add the strings to the texts array for the appropriate page and language
				foreach ($_ as $strKey => $strVal) {
					// if filtering by key, check if the strKey contains or matches the filter. If not, skip.
					if ($opts['keyFilter'] && stripos($strKey, $opts['keyFilter']) === FALSE) { continue; }
					$pageStrs[$strKey][$lang] = $strVal;
				}
			}
			// get only non-translated strings if the option is set
			if ($opts['notTranslated']) {
				$pageStrs = $this->notTranslated($pageStrs);
			}
			// filter by text if the option is set
			if ($opts['textFilter'] !== FALSE) {
				$pageStrs = $this->textFilter($pageStrs, $opts['textFilter'], $page);
			}

			if ($pageStrs) {
				$texts[$page] = $pageStrs;
				// count the strings for this page - it's possible that it'll exceed the max count,
				// but that's fine. We'll just stop processing files.
				$count += count($pageStrs);
			}

			$this->lastLoadedFile = $page; // update the last loaded file

			if ($opts['singleFile']) { break; } // break out of the loop if only loading a single file
		}

		return $texts;
	}

	// filter out any strings that are fully translated, leaving only those that lack translations
	protected function notTranslated(array $texts) {
		foreach ($texts as $string => $vals) {
			$translated = true; // assume that the strings are translated until proven otherwise
			foreach ($this->langNames() as $lang) { // check each language
				if (empty($vals[$lang])) {
					$translated = false;
				}
			}
			if ($translated) { unset($texts[$string]); } // remove string if fully translated
		}
		// if all strings translated, return false, otherwise return the array of untranslated strings
		return empty($texts) ? false : $texts;
	}

	// filter out any strings that don't match the given filter (very similar to the notTranslated function)
	protected function textFilter(array $texts, $filter, $page = '') {
		foreach ($texts as $string => $vals) {
			$matches = false; // assume that the strings don't match the filter until proven otherwise
			foreach ($this->langNames() as $lang) { // check each language
				if (isset($vals[$lang])) {
					if (!is_string($vals[$lang])) {
						// log a value for debugging https://github.com/chrisrollins65/cr_translate_mate/issues/2
						$this->log->write('Translate Mate: searched for [' . $filter . '] and found a non-string on page [' . $page . '] in [' . $string . '->' . $lang . ']: ' . print_r($vals[$lang], true));
					} else if (stripos($vals[$lang], $filter) !== FALSE) {
						$matches = true;
					}
				}
			}
			if (!$matches) { unset($texts[$string]); } // remove string if it doesn't match the filter
		}
		// if no strings match, return false, otherwise return the array of the matching strings
		return empty($texts) ? false : $texts;
	}

	// turns the list of file paths into a multidimensional array based on path segments
	public function filesArray($dirKey = "catalog") {
		$list = $this->listFiles($dirKey);
		$filesArray = array();
		foreach ($list as $key => $file) {
			$this->pathToArray($filesArray, explode('/', $file), $key);
		}

		return $filesArray;
	}

	// converts a single path into a multidimensional array
	protected function pathToArray(array &$target, array $parts, $leafValue) {
		// get the next segment of the path
		$e = array_shift($parts);

		if (empty($parts)) { // base case
			$target[$e] = $leafValue;
			return;
		}
		// create a new array for this segment if it doesn't exist
		if (!isset($target[$e]) || !is_array($target[$e])) {
			$target[$e] = array();
		}
		// process the rest of the segments recursively
		$this->pathToArray($target[$e], $parts, $leafValue);
	}

	// generates the basic html structure for the files menu
	public function fileHTMLSelect($dirKey = "catalog") {
		$filesArray = $this->filesArray($dirKey);

		$menu = $this->fileHTMLSelectOpts($filesArray);

		return $menu;
	}

	// recursively creates the html structure for each subarray in the $filesArray
	protected function fileHTMLSelectOpts(array &$filesArray) {
		$menu = '';
		foreach ($filesArray as $key => $file) {
			if (is_array($file)) {
				$menu .= '<optgroup label="' . h($key) . '">' . $this->fileHTMLSelectOpts($file) . '</optgroup>';
			} else {
				$menu .= '<option value="' . h($file) . '">' . h($key) . '</option>';
			}
		}
		return $menu;
	}

	// gets the name of the admin folder (useful if it has been renamed)
	public function adminPath() {
		return $this->adminPath;
	}

	public function getLastLoadedFile() {
		return $this->lastLoadedFile;
	}

}