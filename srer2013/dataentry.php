<?php
ini_set('display_errors', '1');
require_once('../library.php');
require_once('functions.php');
srer_auth();
$Data = new DB();
SetCurrForm();
if ($_SESSION['ACNo'] == "")
  $_SESSION['ACNo'] = "-- Choose --";
if ($_SESSION['PartID'] == "")
  $_SESSION['PartID'] = "-- Choose --";
if (intval(Getval($_POST, 'PartID')) > 0)
  $_SESSION['PartID'] = intval($_POST['PartID']);
if (Getval($_POST, 'ACNo') != "")
  $_SESSION['ACNo'] = $_POST['ACNo'];
if (intval(Getval($_REQUEST, 'ID')) > 0)
  $_SESSION['PartMapID'] = intval($_REQUEST['ID']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Data Entry - SRER 2013 Paschim Medinipur</title>
    <meta name="robots" content="noarchive,noodp">
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

      <style type="text/css" media="all" title="CSS By Abu Salam Parvez Alam" >
        <!--
        @import url("css/Style.css");
        -->
      </style>
      <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
      <script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
      <script>
        $(function() {
          $(".datepick").datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            showButtonPanel: true,
            showAnim: "slideDown"
          });
          $("#Dept").autocomplete({
            source: "query.php",
            minLength: 3,
            select: function(event, ui) {
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
      <form name="frmSRER" method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="textfield">AC No.:</label>
        <select name="ACNo" onChange="document.frmSRER.submit();">
          <?php
          $Query = "select ACNo,ACNo from SRER_PartMap Where PartMapID={$_SESSION['PartMapID']} group by ACNo";
          $Data->show_sel('ACNo', 'ACNo', $Query, $_SESSION['ACNo']);
          ?>
        </select>
        <label for="textfield">Part No.:</label>
        <select name="PartID">
          <?php
          $Query = "Select PartID,CONCAT(PartNo,'-',PartName) as PartName from SRER_PartMap where ACNo='" . $_SESSION['ACNo'] . "' and PartMapID=" . $_SESSION['PartMapID'] . " group by PartNo";
          $RowCount = $Data->show_sel('PartID', 'PartName', $Query, $_SESSION['PartID']);
          ?>
        </select>
        <input type="submit" name="CmdSubmit" value="Refresh" />
        <?php //echo $Query; ?>
        <br /><hr />
        <?php
        if ((intval($_SESSION['PartID']) > 0) && (Getval($_SESSION, 'TableName') != "")) {
          $RowCount = $Data->do_max_query("Select count(*) from {$_SESSION['TableName']} Where PartID={$_SESSION['PartID']}");
          $RowCount = $RowCount - 9;
          if ($RowCount < 1)
            $RowCount = 1;
        }
        if (intval($_SESSION['PartID']) > 0) {
          ?>
          <label for="SlFrom">From Serial No.:</label>
          <input type="text" name="SlFrom" size="3" value="<?php echo (Getval($_POST, 'ShowBlank') == "1") ? '0' : $RowCount; ?>"/>
          <input type="submit" name="FormName" value="Form 6" />
          <input type="submit" name="FormName" value="Form 6A" />
          <input type="submit" name="FormName" value="Form 7" />
          <input type="submit" name="FormName" value="Form 8" />
          <input type="submit" name="FormName" value="Form 8A" />
          <input type="checkbox" name="ShowBlank" value="1" <?php if (Getval($_POST, 'ShowBlank')) echo "Checked" ?>/>
          <label for="ShowBlank">Show Blank Records</label>
          <input type="checkbox" name="ShowBlankCount" value="1"/>
          <label for="ShowBlank">Show Blank Records Count</label>
          <hr /><br />
          <?php
          $PartName = GetPartName();
          echo "<h3>Selected Part[{$PartName}] " . Getval($_SESSION, 'FormName') . "</h3>";
        }
        ?>
      </form>
      <?php
      if (Getval($_SESSION, 'TableName') != "") {
        if ($_POST['ShowBlank'] == "1") {
          $FieldNames = explode(',', $_SESSION['Fields']);
          $CondBlank = " AND (";
          for ($i = 1; $i < count($FieldNames); $i++) {
            $CondBlank = $CondBlank . $FieldNames[$i] . "='' OR " . $FieldNames[$i] . " IS NULL) AND (";
          }
          $CondBlank = $CondBlank . "1 )";
        }
        $Query = "Select {$_SESSION['Fields']} from {$_SESSION['TableName']} Where PartID={$_SESSION['PartID']}";
        $Query = $Query . $CondBlank;
        EditForm($Query);
        if ($_POST['ShowBlankCount'] == "1") {
          //echo $Query;
          $Query = "SELECT ACNo as `AC Name`,PartNo,PartName,SUM(CountF6) as CountF6,SUM(CountF6A) as CountF6A,SUM(CountF7) as CountF7,"
                  . "SUM(CountF8) as CountF8,SUM(CountF8A) as CountF8A,(IFNULL(SUM(CountF6),0)+IFNULL(SUM(CountF6A),0)+IFNULL(SUM(CountF7),0)+"
                  . "IFNULL(SUM(CountF8),0)+IFNULL(SUM(CountF8A),0)) as Total "
                  . "FROM SRER_Users U INNER JOIN SRER_PartMap P ON U.PartMapID=P.PartMapID AND U.PartMapID={$_SESSION['PartMapID']} LEFT JOIN "
                  . "(SELECT PartID,Count(*) as CountF6 FROM `SRER_Form6` where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F6 "
                  . "ON (F6.PartID=P.PartID) LEFT JOIN "
                  . "(SELECT PartID,Count(*) as CountF6A FROM `SRER_Form6A` where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F6A "
                  . "ON (F6A.PartID=P.PartID) LEFT JOIN "
                  . "(SELECT PartID,Count(*) as CountF7 FROM `SRER_Form7` Where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`ObjectorName`='' OR `ObjectorName` IS NULL) AND (`PartNo`='' OR `PartNo` IS NULL) AND (`SerialNoInPart`='' OR `SerialNoInPart` IS NULL) AND (`DelPersonName`='' OR `DelPersonName` IS NULL) AND (`ObjectReason`='' OR `ObjectReason` IS NULL) AND (`Status` ='' OR `Status` IS NULL)) GROUP BY PartID) F7 "
                  . "ON (F7.PartID=P.PartID) LEFT JOIN "
                  . "(SELECT PartID,Count(*) as CountF8 FROM `SRER_Form8` where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F8 "
                  . "ON (F8.PartID=P.PartID) LEFT JOIN "
                  . "(SELECT PartID,Count(*) as CountF8A FROM `SRER_Form8A` where ((`ReceiptDate`='' OR `ReceiptDate` IS NULL) AND (`AppName`='' OR `AppName` IS NULL) AND (`RelationshipName`='' OR `RelationshipName` IS NULL) AND (`Relationship`='' OR `Relationship` IS NULL) AND (`Status`='' OR `Status` IS NULL)) GROUP BY PartID) F8A "
                  . "ON (F8A.PartID=P.PartID) GROUP BY ACNo,PartNo,PartName";
          ShowSRER($Query);
          //echo $Query;
          $Query = "Select SUM(CountF6) as TotalF6,SUM(CountF6A) as TotalF6A,SUM(CountF7) as TotalF7,SUM(CountF8) as TotalF8,SUM(CountF8A) as TotalF8A"
                  . ",SUM(Total) as Total FROM ({$Query}) as T";
          ShowSRER($Query);
        }
      }
      ?>
      <br />
    </div>
    <div class="pageinfo"><?php pageinfo(); ?></div>
    <div class="footer"><?php footerinfo(); ?></div>
  </body>
</html>
