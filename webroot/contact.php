<?php 
$pageName = 'contact';
$pageTitle = 'Contact';

include('../includes/header.php');
?>
<script type="text/javascript">
<!--
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
	 * @param string email The supposed email address to validate
	 * @return bool valid
	 * @author Adam Eivy
	 * @version 2.1
	 * @codesnippit bcd71ab9-dc05-45af-9855-abb57c0cf0ab
	 */
	function validEmail(email){
	   var re = /^[\w.%&=+$#!-']+@[\w.-]+\.[a-zA-Z]{2,4}$/;
	   return re.test(email);
	}
	$(document).ready(function(){
		$('#Name').focus();
		$('#formContact').submit(function(){
			if($('#Name').val()==''){
				alert("Please enter your name.");
				return false;
			}else if(!validEmail($('#Email').val())){
				alert("Invalid e-mail address! please re-enter.");
				return false;
			}else if($('#Message').val()==''){
				alert("Please type a message.");
				return false;
			}else if($('#arg1').val()==''){
				alert("Please answer the question :)");
				return false;
			}
			return true;
		});
	});
//-->
</script>

<h1>Contact</h1>
<?php
if(isset($_GET['mode'])){
	switch($_GET['mode']){
		case 'thanks':
			echo '<p>Thanks for the contact. I will get back to you shortly.</p>';
			break;
		case 'error':
			$msg = 'There is a problem with your information. Please try again.';
			showForm($msg);
			break;
		default:
			showForm();
			break;
	}
}else{
	showForm();
}
function showForm($msg=false){
	if(!empty($msg)){
		echo '<div class="error">'.$msg.'</div>';
	}
	?>
    <form method="post" id="formContact" action="/handler/email.php">
		<p>
        	<label>Name:</label><br />
			<input name="Name" id="Name" type="text" value="<?=htmlentities(strip_tags($_GET['Name']))?>" size="25" maxlength="25" />
        </p>
        <p>
            <label>E-Mail Address:</label><br />
            <input name="Email" id="Email" type="text" value="<?=htmlentities(strip_tags($_GET['Email']))?>" size="25" />
        </p>
        <p>
            <label>Message:</label><br />
            <textarea name="Message" id="Message" rows="5" cols="40"><?=htmlentities(strip_tags($_GET['Message']))?></textarea>
        </p>
        <p>
            <label>Robot Check: What do zombies eat?</label><br />
            <input name="arg1" id="arg1" type="text" value="" size="25" /><br />
            <input type="submit" name="send" value="Send &gt;&gt;" />
        </p>
	</form>
	<?
	}
?>
<?php include('../includes/footer.php'); ?>