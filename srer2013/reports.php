<?php
ini_set('display_errors', '1');
require_once('MyPDF.php');
if($_SERVER['PHP_SELF']=='/srer2013/reports.php')
	include('ShowPDF.php'); 
require_once('functions.php'); 
if($_SERVER['PHP_SELF']=='/srer2013/reports.php')
	srer_auth();
else
	initSRER();
$Data=new DB();
SetCurrForm();
if($_SESSION['ACNo']=="")
	$_SESSION['ACNo']="-- Choose --";
if(($_SESSION['PartID']=="") || ($_SESSION['ACNo']!=$_POST['ACNo']))
	$_SESSION['PartID']="-- Choose --";
if (intval($_POST['PartID'])>0)
	$_SESSION['PartID']=intval($_POST['PartID']);
if($_POST['ACNo']!="")
	$_SESSION['ACNo']=$_POST['ACNo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report - SRER 2013 Paschim Medinipur</title>
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
if($_SERVER['PHP_SELF']=='/srer2013/reports.php')
{ 
	require_once('srermenu.php'); 
} 
?>
</div>
<div class="content" style="margin-left:5px;margin-right:5px;">
<h2>Summary Revision of Electoral Roll 2013</h2>
<hr/>
<form name="frmSRER" method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
    <label for="textfield">AC No.:</label>
    <select name="ACNo" onChange="document.frmSRER.submit();">
      <?php
		  $Query="select ACNo,ACNo from SRER_PartMap group by ACNo";
		  $Data->show_sel('ACNo','ACNo',$Query,$_SESSION['ACNo']);
	  ?>
    </select>
	<label for="textfield">Part No.:</label>
    <select name="PartID">
      <?php
		  $Query="Select PartID,CONCAT(PartNo,'-',PartName) as PartName from SRER_PartMap where ACNo='".$_SESSION['ACNo']."' group by PartNo";
		  $Data->show_sel('PartID','PartName',$Query,$_SESSION['PartID']);
	  ?>
    </select>
	<?php //echo $Query; ?>
	<input type="submit" name="FormName" value="Show" />
    <hr /><br />
</form>
<?php 
if(intval($_SESSION['PartID'])>0)
{
	echo "<h3>Form 6</h3>";
	$Query="Select `SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status` from SRER_Form6 Where PartID={$_SESSION['PartID']}";
	ShowSRER($Query);
	echo "<h3>Form 6A</h3>";
	$Query="Select `SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status` from SRER_Form6A Where PartID={$_SESSION['PartID']}";
	ShowSRER($Query);
	echo "<h3>Form 7</h3>";
	$Query="Select `SlNo`, `ReceiptDate`, `ObjectorName`, `PartNo`, `SerialNoInPart`, `DelPersonName`, `ObjectReason`, `Status` from SRER_Form7 Where PartID={$_SESSION['PartID']}";
	ShowSRER($Query);
	echo "<h3>Form 8</h3>";
	$Query="Select `SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status` from SRER_Form8 Where PartID={$_SESSION['PartID']}";
	ShowSRER($Query);
	echo "<h3>Form 8A</h3>";
	$Query="Select `SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status` from SRER_Form8A Where PartID={$_SESSION['PartID']}";
	ShowSRER($Query);
}
if($_SERVER['PHP_SELF']=='/srer2013/reports.php')
{
		$Query="SELECT UserName as `Block Name`,ACNo as `AC Name`,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
		."SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
		."IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
		."FROM SRER_Users U INNER JOIN SRER_PartMap P ON U.PartMapID=P.PartMapID LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6` GROUP BY PartID) F6 "
		."ON (F6.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A` GROUP BY PartID) F6A "
		."ON (F6A.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` GROUP BY PartID) F7 "
		."ON (F7.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8` GROUP BY PartID) F8 "
		."ON (F8.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A` GROUP BY PartID) F8A "
		."ON (F8A.PartID=P.PartID) GROUP BY UserName,ACNo";
		ShowSRER($Query);
		//echo $Query;
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
}
//echo $Query;
?>
<br />
</div>
<div class="pageinfo"><?php pageinfo(); ?></div>
<div class="footer"><?php footerinfo();?></div>
</body>
</html>