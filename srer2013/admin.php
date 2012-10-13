<?php 
require_once('MyPDF.php'); 
require_once('functions.php'); 
srer_auth();
if($_SESSION['UserName']!="Admin")
	exit();
$Data=new DB();
SetCurrForm();	
if (intval($_POST['PartID'])>0)
	$_SESSION['PartID']=intval($_POST['PartID']);
if($_POST['ACNo']!="")
	$_SESSION['ACNo']=$_POST['ACNo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin Report - SRER 2013 Paschim Medinipur</title>
<meta name="robots" content="noarchive,noodp">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="all" title="CSS By Abu Salam Parvez Alam" >
<!--
@import url("../css/Style.css");
-->
</style>
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<script>
$(function() {
	$( ".datepick" ).datepicker({ 
								dateFormat: 'yy-mm-dd',
								showOtherMonths: true,
								selectOtherMonths: true,
								showButtonPanel: true,
								showAnim: "slideDown"
								});
	$( "#Dept" ).autocomplete({
			source: "query.php",
			minLength: 3,
			select: function( event, ui ) {
				$('#Dept').val(ui.item.value);
			}
		});
});
</script>
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
<?php 
if($_SESSION['UserName']!="") 
{ 
	require_once('srermenu.php'); 
} 
?>
</div>
<div class="content" style="margin-left:5px;margin-right:5px;">
<h2>Summary Revision of Electoral Roll 2013</h2>
<?php 
echo "<h3>Users Activity</h3>";
$Query="Select `UserName`,`LoginCount`,DATE_FORMAT(`LastLoginTime`+37800,'%d-%m-%Y %H:%i:%s') as LastLoginTime from SRER_Users order by LastLoginTime desc";
ShowSRER($Query);
//echo $Query;
?>
<br />
</div>
<div class="pageinfo"><?php pageinfo(); ?></div>
<div class="footer"><?php footerinfo();?></div>
</body>
</html>