

<?php

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

/*print_r($_GET);
$postData = new ArrayObject($_GET,ArrayObject::ARRAY_AS_PROPS);
echo $postData->name;*/

$pattern = '#.*\_([0-9]+)\.[a-z]+$#';
$subject = 'whatever_files_123456.ext';
$matches = array();

preg_match($pattern, $subject,$matches);

//echo $matches[1]; // this is want u want


$parts = explode('_', $subject);
$num = (int)end($parts);
echo $num;exit;

?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js"></script>

<style>
<!--
#view {
	display:none;
}
-->
</style>

<form action="" id="myForm">
<label for="username">Username</label>

<input type="text" name="username"/>

<input type="submit" value="post"/>
</form>


<p id="view"></p>

<<script type="text/javascript">
<!--
$("#myForm").submit(function(){
	console.log(this.action);
$.post(this.action,$(this).serialize(),function(data){
	$(this).hide(2000);
	
	$("#view").show(2000).html(data);
});
return false;
});
//-->
</script>

<?php 


class someclass{
    function foo(){
        $my_array = array('1st value','2nd value');
       foreach ($my_array as $this->key => $this->value){
           $this->bar();
           $this->baz();
        }

     }
    function bar(){
     //do something with $this->key or $this->value
     echo $this->key.'<br />';
    }
    function baz(){
     //do something with $this->key or $this->value
     echo $this->value.'<br />';
    }

 }

 $obj = new someclass();
 $obj->foo();

exit;

$mail = new Zend_Mail();
$mail->setBodyText('This is the text of the mail.');
$mail->setFrom('admin@desijanwar.com', 'Some Sender');
$mail->addTo('uhaish@gmail.com', 'Dear member');
$mail->setSubject('subject');
$mail->send();


$first = array(1,2,3,4,5);
$second = array(4,5,6,7,8);
Zend_Debug::dump(array_diff($first, $second),"first then second"); //common between the two get subtracted from first
Zend_Debug::dump(array_diff($second, $first),"second then first");

exit;

echo strtotime("+55 days");
exit;
$foo = "abcdefg";
//echo $foo{0};
//exit;
foreach((array)$foo as $char) echo ($char);
//die($foo[0]);
exit;

$acl = new Zend_Acl();
$acl->addRole(new Zend_Acl_Role('superadmin'))
    ->addRole(new Zend_Acl_Role('admin'));
 //$acl->allow('superadmin',null,null);
 if($acl->isAllowed('superadmin',null,null))
 {
     die('allowed');
 }else {
     die('not allowed');
 } 


$config = new Zend_Config_Ini('../application/configs/application.ini','development');
Zend_Debug::dump($config->resources->db->toArray());
$db = Zend_Db::factory($config->resources->db);

Zend_Db_Table::setDefaultAdapter($db);
$wasteTb = new Zend_Db_Table('waste');
//$wasteTb->setDefaultAdapter($db);
$wasteTb->update(array('sort_order'=> new Zend_Db_Expr('sort_order + 1')),array('sort_order > 0','sort_order < 3'));
//$db->update('waste',array('order'=>new Zend_Db_Expr('order - 1 '))
//$db->query('UPDATE waste SET order = order - 1 where waste.order > 4');


die('done');

class Foo {
    public $data = 0 ;
    public function &getData()
    {
        return ++$this->data;
    }
}
echo 'rune';
$foo = new Foo();
$refData = &$foo->getData();
$foo->data++;
echo var_dump($refData);
exit;





/*
date_default_timezone_set('Europe/London');


$lastMonth = strtotime('-1 month');
if($lastMonth == 0) echo 'string not correctly formated';
$date = new Zend_Date();
$date->setTimestamp($lastMonth);
echo $date;

$data1 = array('boy','toy','cow','lion');
$data2 = array('boy','pig','tiger','lion');

print_r(array_diff($data1, $data2));*/



$input = "0123456";
echo substr($input, -3); //expected 456
exit;

$dirItr = new RecursiveDirectoryIterator('C:/wamp/www/honeycomb/public/js/',RecursiveIteratorIterator::CHILD_FIRST);
$dirItr = new RecursiveIteratorIterator($dirItr, RecursiveIteratorIterator::CHILD_FIRST);
foreach($dirItr as $file)
{
	echo $file->getFilename();
	//if(!$file->isFile()) continue;
	//echo $file->getFilename();
}
exit;
$info = new SplFileInfo(__FILE__);
echo $info->getFilename();
exit;

$path = 'C:/wamp/www/honeycomb/public/js/lib/jquery.js';
$baseUrl = '/honeycomb/public';
//$url = '/honeycomb/public/js/lib/jquery.js'; 
$len = strlen($baseUrl);

$pos = strpos($path, $baseUrl);

$url = substr($path,$pos + $len + 1);
echo $url;



echo strlen(sha1("uhaish"));
exit;
class Foo {
	
	protected $_name;
	public function __construct($name)
	{
		$this->_name;
	}
	
	public function getName()
	{
		return $this->_name;
	
	}
	
	public function setName($name)
	{
		$this->_name = $name;
	}
	
	
}

//$my = new Foo("jason");
$ref = new Zend_Reflection_File('C:\wamp\www\honeycomb\application\modules\default\controllers\IndexController.php');
$classes = $ref->getClasses();
$class = $classes[0];
$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
$author = $class->getDocblock()->getTag('aclDescription')->getDescription();

foreach($methods as $method)
{
	/*@var $method Zend_Reflection_Method */
	if(!strstr($method->getName(),'Action')) continue;
	
	echo $method->getName();
}



?>