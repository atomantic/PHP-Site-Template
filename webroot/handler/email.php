<?php
/**
 * @package Contact
 */
 
// configure
 
// on error
// vsprintf formats the error text into %s
// mode = error
// name = error field name
// rule = failed on rule
// ... all post data passed back for rendering (must be security cleaned on contact page before echoing!)
$pageError = 'contact.php?mode=error&name=%s&rule=%s%s';

// on success
$pageRedirect = 'contact.php?mode=thanks';

// recipient email
$to = 'YOUREMAIL@DOMAIN.com';

// email subject
$subject = 'YOUR SUBJECT';

// email message
// we will use vsprintf to format this string with our arguments supplied in $params
$emailMessage = "name: %s \n\nemail: %s \n\nmessage: %s";

// the anti-spam answer
$answer = 'brains';

/**
 * @var $params is a list of valid $_POST keys and validation rules
 * the 'name' key is the name of the $_POST key, e.g. 'name'=>'Email' maps to $_POST['Email']
 * the 'rules' is a comma delimited list of validation rules
 * currently, this script supports the following validation rules:
 * 'not_empty' -- checks to make sure that the post key is not empty (uses php empty() so it will fail for '',0,false,undefined,null)
 * 'email' -- validates an email address
 * 'is=*' -- tests if the value of the post key is the same as the given string (will convert the post value to lowercase)
 */
$params = array(
	array('name'=>'Name','rules'=>'not_empty'),
	array('name'=>'Email','rules'=>'not_empty,email'),
	array('name'=>'Message','rules'=>'not_empty'),	
	array('name'=>'arg1','rules'=>'is='.$answer)
);

/**
 * validEmail checks for a single valid email
 *
 * supported RFC valid email addresses (test data):
 * a@a.com
 * A_B@A.co.uk
 * a@subdomain.adomain.com
 * abc.123@a.net
 * O'Connor@a.net
 * 12+34-5+1=42@a.org
 * me&mywife@a.co.uk
 * root!@a_b.com
 * _______@a-b.la
 * %&=+.$#!-@a.com
 *
 * Current known unsupported (but are RFC valid):
 * abc+mailbox/department=shipping@example.com
 *  !#$%&'*+-/=?^_`.{|}~@example.com (all of these characters are RFC compliant)
 * "abc@def"@example.com (anything goes inside quotation marks)
 * "Fred \"quota\" Bloggs"@example.com (however, quotes need escaping)
 *
 * @param string $email The supposed email address to validate
 * @param bool $validateDomain (default true): ping the domain for a valid mailserver
 * @return bool valid
 * @author Adam Eivy
 * @version 2.1
 * @codesnippit fa5a06bf-2bce-41a8-a2e0-2f6db7dd22f9
 */
function validEmail($email,$validateDomain=true){
   if(preg_match('/^[\w.%&=+$#!-\']+@[\w.-]+\.[a-zA-Z]{2,4}$/' , $email)) {
      if(!$validateDomain)   return true; // not testing mailserver but regex passed
      // now test mail server on supplied domain
      list($username,$domain)=split('@',$email);
      if(checkdnsrr($domain,'MX')) return true; // domain has mail record
   }
   return false; // either failed to match regex or mailserver check failed
}


// validate
$messageArgs = array();
$errorName = '';
$errorRule = '';
$postBack = '';

for($i=0; $i < count($params); $i++){
	
	$rules = explode(',',$params[$i]['rules']);
	$name = $params[$i]['name'];
	foreach($rules as $rule){
		if(strpos($rule,'is=')!==false){
			$equalsRule = explode('=',$rule);
			if(strtolower($_POST[$name])!=$equalsRule[1]){
				$errorName = $name;
				$errorRule = 'is';
			}
		}else{
			switch($rule){
				case 'not_empty':
					if(empty($_POST[$name])){
						$errorName = $name;
						$errorRule = 'not_empty';
					}
					break;
				case 'email':
					if(!validEmail($_POST[$name],false)){
						$errorName = $name;
						$errorRule = 'email';
					}
					break;
			}
		}
	}
	array_push($messageArgs,$_POST[$name]);
	$postBack .= '&'.$name . '=' . $_POST[$name];
}

if($errorName!=''){
	header("Location: ".vsprintf($pageError,array($errorName,$errorRule,$postBack)));
	exit;
}

// send the mail
$sent = mail($to,$subject,vsprintf($emailMessage,$messageArgs));

// redirect
if($pageRedirect)	header("Location: ".$pageRedirect);
