<?php
class TwitterController extends Zend_Controller_Action
{
	protected $_config; //consumer key , consumer secret
	protected $_client; //http client which will talk to twitter server
	protected $_consumer; //our oauth consumer
	protected $_session;
	protected $_token;
	protected $_socialTb;
	

	/**
	 * loads the configuration
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/social.ini','twitter');
		$this->_config = $this->_config->toArray();
		$this->_session = new Zend_Session_Namespace('Honeycomb_Twitter');
		$this->_consumer = new Zend_Oauth_Consumer($this->_config);
		$this->_socialTb = new Default_Model_DbTable_Social();
	
	}
	
	/**
	 * entry point will decide where to go 
	 * Enter description here ...
	 */
	public function indexAction()
	{
           $this->setToken($this->_consumer->getRequestToken());
           $this->_consumer->redirect();
           
	}
	
	public function callBackAction()
	{
		Zend_Debug::dump($this->_config);
		if(!empty($_GET) )
		{
		
			$accessToken = $this->_consumer->getAccessToken($_GET, $this->getToken());
			$this->setToken(null); //since with one request token we can only try once 
			$this->_client = $accessToken->getHttpClient($this->_config);
			$this->postTweet(md5('twitter test'));
			$this->_socialTb->saveToken($accessToken,'twitter',2);
			
			
			
		}else {
			/**
			 * @todo we can redirect the user to index action here
			 */
			die('user tried to access this link directly');
		}
	}
	
	private function postTweet($msg)
	{
		$response = $this->_client->setMethod('POST')
		              ->setUri('http://twitter.com/statuses/update.json')  //respone will be in json
		              ->setParameterPost('status',$msg)
		              ->request();
		 $result =   $response->getBody();
		 $data = Zend_Json::decode($result);
		 
		 if($data->text) echo $data;
		    else  echo $result;
		           
		              
	}
	
	private function setToken($token)
	{
		$this->_session->token = serialize($token);
		$this->_token = $token;
		return $this;
		
	}
	
	private function getToken()
	{
		if(!$this->_token)
		{
		  $this->_token = unserialize($this->_session->token);
		
		}
			return $this->_token;	
	
	}
	
	
	
	
	
	
	
}