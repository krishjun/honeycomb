<?php
abstract  class Honeycomb_View_Helper_JqPlugin extends  ZendX_JQuery_View_Helper_UiWidget
{
  protected $_attribs; //html attribs
  protected $_options;	 //plugin specifix options will get encoded in json later
  protected $_id;
  protected $_pluginName;
  protected $_customOptions;
  protected $_config;
  
  const LIBRARY_PATH = "libs";

  
  protected  function initSetup($id,array $pluginOptions = null,array $htmlAttribs)
  {
  	$this->_attribs = $htmlAttribs;  	
  	$this->_customOptions = $pluginOptions;
  	$this->_id = $id;
  	
  	
  }
  
  
  protected function loadPlugin($pluginName)
  {
    $this->_pluginName = $pluginName;
  	$defaultOptions = array();
  	if(!Zend_Registry::isRegistered('jqPlugins'))
  	{
  		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/jqPlugins.ini',$pluginName);
  		$this->_config = $config; //taking confing in plugin context 
  		$this->_options = $this->_config->options->toArray();
  	}
  	
  	if(is_array($this->_customOptions))
  	$this->_options = array_merge($this->_options,$this->_customOptions);
  	
  	if($this->_config->stylesheets) $this->loadstyleSheets($this->_config->stylesheets);
  	if($this->_config->scripts) $this->loadScripts($this->_config->scripts);
  	
  	$jq = sprintf('%s("#%s").%s(%s);',ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),$this->_id,$pluginName,Zend_Json::encode($this->_options));
  	$this->jquery->addOnLoad($jq);
  
  }
  
  
  protected  function loadStylesheets($stylsheets)
  {
  	
  	foreach($stylsheets as $sheet )
  	{
  		$this->jquery->addStylesheet($this->view->baseUrl(self::LIBRARY_PATH . '/'. $this->_pluginName . '/' . $sheet));
  		
  	}
  }
  
  protected function loadScripts($scripts)
  {
  	foreach ($scripts as $script)
  	{
  		$this->jquery->addJavascriptFile($this->view->baseUrl(self::LIBRARY_PATH . '/'. $this->_pluginName . '/' .$script));
  	}
  }
  
  public function setOption($name,$value)
  {
  	$this->_options[$name] = $value;
  }
  
  
  /**
   * enables jquery , enable ui yourself if needed
   * @see ZendX_JQuery_View_Helper_UiWidget::setView()
   */   
  
  public function setView(Zend_View_Interface $view)
    {
        parent::setView($view);
        $this->jquery = $this->view->jQuery();
        $this->jquery->enable();
        return $this;
    }
  
  
  /**
   * 	
   * Returns the minimum html required by the specific plugin return null if 
   * your plugin needs nothing
   */
  
  abstract  protected function loadHtml($text= null);
  
 
}