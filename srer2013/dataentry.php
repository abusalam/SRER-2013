<?php 
ini_set('display_errors', '1');
require_once('../library.php');
require_once('functions.php'); 
srer_auth();
$Data=new DB();
SetCurrForm();
if($_SESSION['ACNo']=="")
	$_SESSION['ACNo']="-- Choose --";
if($_SESSION['PartID']=="")
	$_SESSION['PartID']="-- Choose --";
if (intval($_POST['PartID'])>0)
	$_SESSION['PartID']=intval($_POST['PartID']);
if($_POST['ACNo']!="")
	$_SESSION['ACNo']=$_POST['ACNo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Data Entry - SRER 2013 Paschim Medinipur</title>
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
	require_once('srermenu.php'); 
?>
</div>
<div class="content" style="margin-left:5px;margin-right:5px;">
<h2>Summary Revision of Electoral Roll 2013</h2>
<hr/>
<form name="frmSRER" method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
    <label for="textfield">AC No.:</label>
    <select name="ACNo" onChange="document.frmSRER.submit();">
      <?php
		$Query="select ACNo,ACNo from SRER_PartMap Where PartMapID={$_SESSION['PartMapID']} group by ACNo";
		$Data->show_sel('ACNo','ACNo',$Query,$_SESSION['ACNo']);
	  ?>
    </select>
	<label for="textfield">Part No.:</label>
    <select name="PartID">
      <?php
		$Query="Select PartID,CONCAT(PartNo,'-',PartName) as PartName from SRER_PartMap where ACNo='".$_SESSION['ACNo']."' and PartMapID=".$_SESSION['PartMapID']." group by PartNo";
		$Data->show_sel('PartID','PartName',$Query,$_SESSION['PartID']);
	  ?>
    </select>
	<input type="submit" name="CmdSubmit" value="Refresh" />
	<?php //echo $Query; ?>
    <br /><hr />
	<?php
		if((intval($_SESSION['PartID'])>0) && ($_SESSION['TableName']!=""))
		{	
			$RowCount=$Data->do_max_query("Select count(*) from {$_SESSION['TableName']} Where PartID={$_SESSION['PartID']}");
			$RowCount=$RowCount-9;
			if($RowCount<1)
			$RowCount=1;
		}
		if(intval($_SESSION['PartID'])>0)
		{
	?>
    <label for="SlFrom">From Serial No.:</label>
    <input type="text" name="SlFrom" size="3" value="<?php echo $RowCount; ?>"/>
    <input type="submit" name="FormName" value="Form 6" />
	<input type="submit" name="FormName" value="Form 6A" />
	<input type="submit" name="FormName" value="Form 7" />
	<input type="submit" name="FormName" value="Form 8" />
	<input type="submit" name="FormName" value="Form 8A" />
    <hr /><br />
	<?php 
			$PartName=GetPartName();
			echo "<h3>Selected Part[{$PartName}] {$_SESSION['FormName']}</h3>";
		}
	?>
</form>
<?php 
if($_SESSION['TableName']!="")
{
	$Query="Select {$_SESSION['Fields']} from {$_SESSION['TableName']} Where PartID={$_SESSION['PartID']}";
	EditForm($Query);
}
?>
<br />
</div>
<div class="pageinfo"><?php pageinfo(); ?></div>
<div class="footer"><?php footerinfo();?></div>
</body>
</html>
