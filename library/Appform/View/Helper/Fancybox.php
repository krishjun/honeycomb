<?php

/**
 * 
 * This is concreate implementation of Jquery "Fancybox" plugin 
 * as a Zend View Helper
 * 
 * @author Uhaish Gupta
 * @email uhaish@gmail.com
 * @version 1.0
 *
 */

class Appform_View_Helper_Fancybox extends  Appform_View_Helper_JqPlugin
{
	
    /**
     * 
     * This function takes options which get passed to fancybox plugin in javascript
     * 
     * @param String $id of html element on which fancy jquery plugin will get called
     * @param String $text label shown to the user to click on
     * @param String $url of page which gets loaded inside fancy box
     * @param array $pluginOptions custom fancybox plugins options.
     * @return html for fancybox .
     */
	
	public function fancybox($id,$text,$url,$pluginOptions = null,$customhtmlAttribs = null)
	{
		if(func_num_args() == 0) return $this;
		
		$htmlAttribs = array();
		$htmlAttribs['href'] = $url;
		$htmlAttribs['class'] = 'iframe';
		$htmlAttribs['id'] = $id;
	
		
		if(is_array($customhtmlAttribs)) 	$htmlAttribs = array_merge($htmlAttribs,$customhtmlAttribs);
		
		$this->initSetup($id, $pluginOptions, $htmlAttribs);
		$this->loadPlugin(__FUNCTION__);
		return $this->loadHtml($text) . $this->jquery;
	}


    /** 
     * Builds the html for the fancybox
	 * @see Honeycomb_View_Helper_JqPlugin::loadHtml()
	 * @param String $text label of button which opens fancybox on clicked by user
	 * @return String html 
	 */

  protected function loadHtml($text = NULL) {

  	   $html = '<a'
                  . $this->_htmlAttribs($this->_attribs)
                  . '>'.PHP_EOL
                  . $text
                  . '</a>'.PHP_EOL;
        return $html;               
		
	}
	
	



	
	

	

	
	
}