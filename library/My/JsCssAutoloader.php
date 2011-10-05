<?php
class JsCssAutoloader
{
	protected $_path; //path to the js, css files e.g public folder
	protected $_baseUrl; 
	protected $_table;
	protected $_jsCounter;
	protected $_cssCounter;
	protected $_db;
	
	public function __construct($path,$baseUrl,$refresh = false)
	{
		
		$this->_table = new Zend_Db_Table('utroy_jscss');
		$this->_path = dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . $path;
		$this->_baseUrl = $baseUrl;
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		
		if($refresh) $this->loadInDatabase(); //deletes everthing in database and build from start
	    $this->loadInView();
	}
	
	protected function loadInView()
	{
		//here we will check in db exist on disk . if not delte the entry from db
		$headScripts = new Zend_View_Helper_HeadScript();
		$headStyles = new Zend_View_Helper_HeadLink();
		
		$select = $this->_table->select()->order('is_js')->order('sort_order');
		$allFiles = $this->_table->fetchAll($select);
	
		foreach($allFiles as $file)
		{
			if($this->notExistOnDisk($file->url))
			{
				$file->delete();
				continue;
			}
			
			if($file->is_css)
			 $headStyles->appendStylesheet($this->_baseUrl . $file->url);
			 else 
			 $headScripts->appendFile($this->_baseUrl . '/' .  $file->url);
		}
	}
	
	protected function notExistOnDisk($url)
	{
		$fileUri = dirname($_SERVER['SCRIPT_FILENAME'])  . '/' . $url;
		return !file_exists($fileUri); 
	}
	public function loadInDatabase()
	{
		//$this->_table->delete('jscss_id > 0'); //remove all rows
		
		$dirItr = new RecursiveDirectoryIterator($this->_path);
		$dirItr = new RecursiveIteratorIterator($dirItr, RecursiveIteratorIterator::CHILD_FIRST);
		
		foreach($dirItr as $file)
		{
			/* @var $file SplFileInfo */
			if(!$file->isFile()) continue;
			if($this->isJavascript($file->getFilename())) $this->loadJavascript($file);
			if($this->isCss($file->getFilename())) $this->loadCss($file);
			
		}
	}
	
	
	public function isJavascript($pathname)
	{
		$ext = $this->getExt($pathname);
		return $ext == 'js';
	}
	
	public function isCss($pathname)
	{
		$ext = $this->getExt($pathname);
		return $ext == 'css';
	}
	
	public function getExt($pathname)
	{
		$pathinfo = pathinfo($pathname);
		return $pathinfo['extension'];
	}
	
	public function loadJavascript(SplFileInfo $file)
	{
		$maxSortOrder = (int) $this->_table->select()->from($this->_table,'sort_order')->where('is_js = ?',true)->order('sort_order DESC')->limit(1)->query()->fetchColumn();
		$maxSortOrder++;
		
		$sha1 = sha1_file($file->getPathname());
		$url = $this->pathToUrl($file->getPathname());
		$select = $this->_table->select()->where('url = ?',$url)->limit(1);
		$oldJs = $this->_table->fetchRow($select);
		
		if($oldJs)
		{
			if($oldJs->sha1 != $sha1) 
			{
			    $maxSortOrder = $oldJs->sort_order;
			$js = $oldJs; //delete the row if file has changed
			}
			   else { 
			    return; //file already exist and not changed
			   }
		}else {
				$js = $this->_table->createRow();
		}
		
		
	
		$js->url = $url;
		$js->is_css = false;
		$js->is_js = true;
		$js->sha1 = $sha1;
		$js->sort_order = $maxSortOrder;
		return $js->save();
		
	}
	
	public function loadCss(SplFileInfo $file)
	{
		$maxSortOrder = (int) $this->_table->select()->from($this->_table,'sort_order')->where('is_css = ?',true)->order('sort_order DESC')->limit(1)->query()->fetchColumn();
		$maxSortOrder++;
		
		$sha1 = sha1_file($file->getPathname());
		$url = $this->pathToUrl($file->getPathname());
		$select = $this->_table->select()->where('url = ?',$url)->limit(1);
		$oldJs = $this->_table->fetchRow($select);
		
		if($oldJs)
		{
			if($oldJs->sha1 != $sha1) 
			{
			    $maxSortOrder = $oldJs->sort_order;
			$js = $oldJs; //delete the row if file has changed
			}
			   else { 
			    return; //file already exist and not changed
			   }
		}else {
				$js = $this->_table->createRow();
		}
		
		
	
		$js->url = $url;
		$js->is_css = true;
		$js->is_js = false;
		$js->sha1 = $sha1;
		$js->sort_order = $maxSortOrder;
		return $js->save();
	}
	
	protected function pathToUrl($path)
	{
		
		$path = str_replace('\\', '/', $path);
		$len = strlen($this->_baseUrl);
        $pos = strpos($path, $this->_baseUrl);

        return substr($path,$pos + $len + 1); //added 1 to remove the backslash
	}
}