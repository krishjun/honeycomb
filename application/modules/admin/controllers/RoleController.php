<?php
class Admin_RoleController extends Admin_Library_Controller_Action
{
    public function init()
    {
        
    }
    
    
    /**
     * 
     * @acl 
     * @name Home Page
     */
    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        echo "Go to grid action instead ";
    }
    
    public function gridAction()
    {
        if($this->getRequest()->isXmlHttpRequest())
        {
           // die('{"page":"2","total":2,"records":"13","rows":[{"id":"3","invdate":"2007-10-02","name":"Client 2","amount":"300.00","tax":"60.00","total":"360.00","note":"note invoice 3 & and amp test"},{"id":"2","invdate":"2007-10-03","name":"Client 1","amount":"200.00","tax":"40.00","total":"240.00","note":"note 2"},{"id":"1","invdate":"2007-10-01","name":"Client 1","amount":"100.00","tax":"20.00","total":"120.00","note":"note 1"}]}');
          $limit = (int) $this->_getParam('rows');
          $page = (int) $this->_getParam('page');
          $sortBy = $this->_getParam('sidx');
          $sortType = $this->_getParam('sord'); //DESC or ASC
          $count = (int) $this->rolesTb->count();
          
          if($count > 0 )
          {
              $total_pages = ceil($count/$limit);
          } else {
              $total_pages = 0;
          }
                     
          if($page > $total_pages) $page = $total_pages;
          
          $start = $limit * $page - $limit;
          
          if($start < 0)$start = 0;
          
          $rows = $this->rolesTb->listData($page,$limit,$sortBy,$sortType);
          $response = new stdClass();
          
          $response->page = $page;
          $response->total = $total_pages;
          $response->records = $count;
          
          $response->rows = $rows;
          $this->_helper->json($response);
         
          
        
        }
    }
    
    public function editAction()
    {
        $operation = $this->_getParam('oper');
        $output = null;
        switch ($operation)
        {
            case 'add':
              
                $this->acl->addRole($this->_getParam('name'));
                
                break;
                
            case 'edit':
                $row = $this->rolesTb->find($this->_getParam('id'))->current();
                $row->name = $this->_getParam('name');
                $row->save();
                
                break;
                
            case 'del':
         
               $this->acl->removeRole($this->_getParam('id'));

               break;
                
           default:
                throw new Exception('Invalid Grid Operation : ' . $operation);       
        }
        
        $this->_helper->json($output);
        
        //$row = $this->rolesTb->find($this->_getParam(''))
    }
    
    
}