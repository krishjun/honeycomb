<?php
class SkinController extends Zend_Controller_Action
{
	
	protected $_table;
	
	public function init()
	{
		$this->_table = new Zend_Db_Table('utroy_jscss');
		
	}
	
	public function jsAction()
	{
		
		if($this->getRequest()->isPost() && ($this->_getParam('command') == 'sort'))
		{
			
		  $orders = $this->_getParam('js',$this->_getParam('css'));
		
		  foreach($orders as $order => $id)
		  {
		  	$id = (int)$id;
		  	$row = $this->_table->find($id)->current();
		  	$row->sort_order = $order;
		  	$row->save();
		  	
		  }
		}
		$this->view->scripts = $this->_table->select()->where('is_js = ?',true)
		                                              ->order('sort_order')
		                                              ->query()
		                                              ->fetchAll(Zend_Db::FETCH_OBJ);
		                                              
		$this->view->styles = $this->_table->select()->where('is_css = ?',true)
		                                              ->order('sort_order')
		                                              ->query()
		                                              ->fetchAll(Zend_Db::FETCH_OBJ);                                              
		                                              
	}
	
	public function testAction()
	{
		if($this->_getParam('q') == 2)
		{
			echo '{"page":"1","total":2,"records":"13","rows":[{"id":"13","cell":["13","2007-10-06","Client 3","1000.00","0.00","1000.00",null]},{"id":"12","cell":["12","2007-10-06","Client 2","700.00","140.00","840.00",null]},{"id":"11","cell":["11","2007-10-06","Client 1","600.00","120.00","720.00",null]},{"id":"10","cell":["10","2007-10-06","Client 2","100.00","20.00","120.00",null]},{"id":"9","cell":["9","2007-10-06","Client 1","200.00","40.00","240.00",null]},{"id":"8","cell":["8","2007-10-06","Client 3","200.00","0.00","200.00",null]},{"id":"7","cell":["7","2007-10-05","Client 2","120.00","12.00","134.00",null]},{"id":"6","cell":["6","2007-10-05","Client 1","50.00","10.00","60.00",""]},{"id":"5","cell":["5","2007-10-05","Client 3","100.00","0.00","100.00","no tax at all"]},{"id":"4","cell":["4","2007-10-04","Client 3","150.00","0.00","150.00","no tax"]}],"userdata":{"amount":3220,"tax":342,"total":3564,"name":"Totals:"}}';
			exit;
		}
	}
}