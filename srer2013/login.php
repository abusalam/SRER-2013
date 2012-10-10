<?php 
if (!ini_get('display_errors')) 
{
    //ini_set('display_errors', '1');
}
require_once( '../library.php');
require_once('functions.php'); 
initSRER();
	$Data=new DB();
	$ID=isset($_SESSION['ID'])?$_SESSION['ID']:"";
	$_SESSION['ID']=session_id();
    if(!isset($_SESSION['LifeTime']))
		$_SESSION['LifeTime']=time();
    $action=CheckSessSRER();
	$LogC=0;
	if (($_POST['UserID']!="") && ($_POST['UserPass']!=""))
	{
		$QueryLogin="Select PartMapID,UserName from `SRER_Users` where `UserID`='".$_POST['UserID']."' AND MD5(concat(`UserPass`,MD5('".$_POST['LoginToken']."')))='".$_POST['UserPass']."'";
		$rows=$Data->do_sel_query($QueryLogin);					
		if($rows>0 )
		{
			session_regenerate_id();
			$Row=$Data->get_row();
			$_SESSION['UserName']=$Row['UserName'];
			$_SESSION['PartMapID']=$Row['PartMapID'];
			$_SESSION['ID']=session_id();
			$_SESSION['FingerPrint']=md5($_SERVER['REMOTE_ADDR']
								.$_SERVER['HTTP_USER_AGENT']
								."KeyLeft");
			$_SESSION['REFERER1']="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$action="JustLoggedIn";
			$Data->do_ins_query("Update SRER_Users Set LoginCount=LoginCount+1 where `UserID`='".$_POST['UserID']."' AND MD5(concat(`UserPass`,MD5('".$_POST['LoginToken']."')))='".$_POST['UserPass']."'");
			$Data->do_ins_query("INSERT INTO SRER_logs (`SessionID`,`IP`,`Referrer`,`UserAgent`,`UserID`,`URL`,`Action`,`Method`,`URI`) values"		
				."('".$_SESSION['ID']."','".$_SERVER['REMOTE_ADDR']."','".mysql_real_escape_string($_SERVER['HTTP_REFERER'])."','".$_SERVER['HTTP_USER_AGENT']
				."','".$_SESSION['UserName']."','".mysql_real_escape_string($_SERVER['PHP_SELF'])."','Login: Success','"
				.mysql_real_escape_string($_SERVER['REQUEST_METHOD'])."','".mysql_real_escape_string($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'])."');");
		}	
		else
		{
			$action="NoAccess";
			$Data->do_ins_query("INSERT INTO SRER_logs (`SessionID`,`IP`,`Referrer`,`UserAgent`,`UserID`,`URL`,`Action`,`Method`,`URI`) values"		
				."('".$_SESSION['ID']."','".$_SERVER['REMOTE_ADDR']."','".mysql_real_escape_string($_SERVER['HTTP_REFERER'])."','".$_SERVER['HTTP_USER_AGENT']
				."','".$_POST['UserID']."','".mysql_real_escape_string($_SERVER['PHP_SELF'])."','Login: Failed','"
				.mysql_real_escape_string($_SERVER['REQUEST_METHOD'])."','".mysql_real_escape_string($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'])."');");
		}
		$Query="Select MD5(concat(`Passwd`,MD5('".$_POST['LoginToken']."'))) from SRER_Users where UserID='".$_POST['UserID']."'";
		$UserPass=$Data->do_max_query($Query);
		
	}
	$_SESSION['Token']=md5($_SERVER['REMOTE_ADDR'].$ID.time());
?>
<head>
<meta name="robots" content="noarchive">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login - SRER 2013 Paschim Medinipur</title>
<style type="text/css" media="all" title="CSS By Abu Salam Parvez Alam" >
<!--
@import url("../css/Style.css");
-->
</style>
<?php
include "../js/php.js";
?>
</head>
<body>
<div class="TopPanel">
  <div class="LeftPanelSide"></div>
  <div class="RightPanelSide"></div>
  <h1>Summary Revision of Electoral Roll 2013</h1>
</div>
<div class="Header">
</div>
<div class="MenuBar">
<?php if($_SESSION['UserName']!="") 
	{ 
require_once('srermenu.php'); 
  } ?>
</div>
<div class="content" style="margin-left:5px;margin-right:5px;">
<?php 
//echo $action."Captcha: ".$_SESSION['Captcha'];
switch($action)
{
	case "LogOut":
		echo "<h2 align=\"center\">Thank You! You Have Successfully Logged Out!</h2>";
		break;
	case "JustLoggedIn":
		echo "<h2 align=\"center\">Welcome ".$_SESSION['UserName']." You Have Successfully Logged In!</h2>";
	break;
	case "Valid":
		echo "<h2 align=\"center\">You are already Logged In!</h2>";
	break;
	case "NoAccess":
		echo "<h2 align=\"center\">Sorry! Access Denied!</h2>";
	break;
	default:
		echo "<h2>Summary Revision of Electoral Roll 2013</h2>";
		break;
}
if(($action!="JustLoggedIn") && ($action!="Valid"))
{
?> 
<form name="frmLogin" method="post" action="<?php $_SERVER['PHP_SELF']?>">
	<?php //echo "USERID: ".$_POST['UserID']."<br/>".$_POST['UserPass']."<br />".$UserPass.$QueryLogin.$action; ?>
    <label for="UserID">User ID:</label><br />
	<input type="text" id="UserID" name="UserID" value="" autocomplete="off"/>
<br />
<label for="UserPass">Password:</label><br />
<input type="password" id="UserPass" name="UserPass" value="" autocomplete="off"/><br />
<input type="hidden" name="LoginToken" value="<?php echo $_SESSION['Token'];?>" />
<input style="width:80px;" type="submit" value="Login" onClick="document.getElementById('UserPass').value=MD5(MD5(document.getElementById('UserPass').value)+'<?php echo md5($_SESSION['Token']);?>');"/>
</form>
<p><b>Note:</b>Contact System Manager on (9647182926) for User ID and Password.</p>
<?php 
}
?>
</div>
<div class="pageinfo">
  <?php pageinfo(); ?>
</div>
<div class="footer">
  <?php footerinfo();?>
</div>
<?php 
//print_r($_SESSION);
?>
</body>
</html>
