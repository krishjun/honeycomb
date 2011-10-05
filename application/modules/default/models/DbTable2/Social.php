<?php
class Default_Model_DbTable_Social extends Zend_Db_Table
{
	protected $_name = 'social';
	protected $_primary = 'social_id';
	
	const TWITTER = "twitter";
	const FACEBOOK = "facebook";
	
	public function saveToken($tokenObj,$socialType,$userId)
	{
		$row = $this->createRow();
		$row->social_type = $socialType;
		$row->access_token = serialize($tokenObj);
		$row->create_time = time();
		$row->is_active = true;
		$row->user_id = $userId;
		return $row->save();
	}
	
	public function getToken($userId,$socialType)
	{
		$token = $this->select()->from($this,'access_token')
		                        ->where('user_id = ?',$userId)
		                        ->where('social_type = ?',$socialType)
		                        ->query()
		                        ->fetch();
		 if(!$token) return false;
		 $token = unserialize($token);
		 return $token; 
		                        
	}
}