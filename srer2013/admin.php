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
<hr />
<form name="frmSRER" method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>">
	<input type="submit" name="FormName" value="User Activity" />
	<input type="submit" name="FormName" value="AC wise Data Entry Status" />
	<input type="submit" name="FormName" value="Block wise Data Entry Status" />
	<input type="submit" name="FormName" value="Block AC wise Data Entry Status" />
	<input type="submit" name="FormName" value="Block AC wise Blank Records" />
    <input type="submit" name="FormName" value="Block AC wise Accepted" />
	<input type="submit" name="FormName" value="Block AC wise Rejected" /><hr /><br />
</form>
<?php
if($_POST['FormName']!="")
	$_SESSION['AdminView']=htmlspecialchars($_POST['FormName']);
echo "<h3>{$_SESSION['AdminView']}</h3>";
Switch($_SESSION['AdminView'])
{
	case 'User Activity':
		$Query="Select `UserName`,`LoginCount`,CONVERT_TZ(`LastLoginTime`,'+00:00','+05:30') as LastLoginTime,"
			."CONVERT_TZ(L.`AccessTime`,'+00:00','+05:30') as LastAccessTime,Li.`IP`,Li.`Action`"
			." from SRER_Users U,(Select UserID,max(`AccessTime`) as AccessTime,max(LogID) as MaxLogID from SRER_logs Group by UserID ) L"
			.", SRER_logs Li"
			." where UserName=L.UserID AND L.MaxLogID=Li.LogID order by LastLoginTime desc";
		ShowSRER($Query);
	break;
	case 'AC wise Data Entry Status':
		$Query="SELECT ACNo,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
		."SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
		."IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
		."FROM SRER_PartMap P LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6` GROUP BY PartID) F6 "
		."ON (F6.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A` GROUP BY PartID) F6A "
		."ON (F6A.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` GROUP BY PartID) F7 "
		."ON (F7.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8` GROUP BY PartID) F8 "
		."ON (F8.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A` GROUP BY PartID) F8A "
		."ON (F8A.PartID=P.PartID) GROUP BY ACNo";
		ShowSRER($Query);
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
		break;
	case 'Block wise Data Entry Status':
		$Query="SELECT UserName,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
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
		."ON (F8A.PartID=P.PartID) GROUP BY UserName";
		ShowSRER($Query);
		//echo $Query;
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
		//echo $Query;
	break;
	case 'Block AC wise Data Entry Status':
		$Query="SELECT UserName,ACNo,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
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
		//echo $Query;
	break;
	case 'Block AC wise Blank Records':
		$Query="SELECT U.PartMapID as UserID,UserName,ACNo,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
		."SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
		."IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
		."FROM SRER_Users U INNER JOIN SRER_PartMap P ON U.PartMapID=P.PartMapID LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6`  where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F6 "
		."ON (F6.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A`  where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F6A "
		."ON (F6A.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` Where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`ObjectorName`='' OR `ObjectorName` IS NULL) AND (`PartNo`='' OR `PartNo` IS NULL) AND (`SerialNoInPart`='' OR `SerialNoInPart` IS NULL) AND (`DelPersonName`='' OR `DelPersonName` IS NULL) AND (`ObjectReason`='' OR `ObjectReason` IS NULL) AND (`Status` ='' OR `Status` IS NULL)) GROUP BY PartID) F7 "
		."ON (F7.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8`  where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F8 "
		."ON (F8.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A`  where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F8A "
		."ON (F8A.PartID=P.PartID) GROUP BY UserName,ACNo,U.PartMapID";
		ShowSRER($Query);
		//echo $Query;
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
		//echo $Query;
	break;
	case 'Block AC wise Accepted':
		$Query="SELECT UserName,ACNo,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
		."SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
		."IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
		."FROM SRER_Users U INNER JOIN SRER_PartMap P ON U.PartMapID=P.PartMapID LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6`  where (LOWER(TRIM(`Status`))='accepted') GROUP BY PartID) F6 "
		."ON (F6.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A`  where (LOWER(TRIM(`Status`))='accepted') GROUP BY PartID) F6A "
		."ON (F6A.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` Where (LOWER(TRIM(`Status`))='accepted') GROUP BY PartID) F7 "
		."ON (F7.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8`  where (LOWER(TRIM(`Status`))='accepted') GROUP BY PartID) F8 "
		."ON (F8.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A`  where (LOWER(TRIM(`Status`))='accepted') GROUP BY PartID) F8A "
		."ON (F8A.PartID=P.PartID) GROUP BY UserName,ACNo";
		ShowSRER($Query);
		//echo $Query;
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
		//echo $Query;
	break;
	case 'Block AC wise Rejected':
		$Query="SELECT UserName,ACNo,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
		."SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
		."IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
		."FROM SRER_Users U INNER JOIN SRER_PartMap P ON U.PartMapID=P.PartMapID LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6`  where (LOWER(TRIM(`Status`))='rejected') GROUP BY PartID) F6 "
		."ON (F6.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A`  where (LOWER(TRIM(`Status`))='rejected') GROUP BY PartID) F6A "
		."ON (F6A.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` Where (LOWER(TRIM(`Status`))='rejected') GROUP BY PartID) F7 "
		."ON (F7.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8`  where (LOWER(TRIM(`Status`))='rejected') GROUP BY PartID) F8 "
		."ON (F8.PartID=P.PartID) LEFT JOIN "
		."(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A`  where (LOWER(TRIM(`Status`))='rejected') GROUP BY PartID) F8A "
		."ON (F8A.PartID=P.PartID) GROUP BY UserName,ACNo";
		ShowSRER($Query);
		//echo $Query;
		$Query="Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
			.",SUM(Total) as Total FROM ({$Query}) as T";
		ShowSRER($Query);
		//echo $Query;
	break;
}
?>
</div>
<div class="pageinfo"><?php pageinfo(); ?></div>
<div class="footer"><?php footerinfo();?></div>
</body>
</html>