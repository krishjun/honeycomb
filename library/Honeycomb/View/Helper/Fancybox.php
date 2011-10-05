<?php
class Honeycomb_View_Helper_Fancybox extends  Honeycomb_View_Helper_JqPlugin
{
	
	
	public function fancybox($id,$text,$url,$pluginOptions = null)
	{
		if(func_num_args() == 0) return $this;
		
		$htmlAttribs = array();
		$htmlAttribs['href'] = $url;
		$htmlAttribs['class'] = 'iframe';
		$htmlAttribs['id'] = $id;
		
		$this->initSetup($id, $pluginOptions, $htmlAttribs);
		$this->loadPlugin(__FUNCTION__);
		return $this->loadHtml($text);
	}


/* (non-PHPdoc)
	 * @see Honeycomb_View_Helper_JqPlugin::loadHtml()
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