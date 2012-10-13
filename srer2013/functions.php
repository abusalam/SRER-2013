<?php
function initSRER()
{
	session_start();
	$sess_id=md5(microtime());
	
	$_SESSION['Debug']=$_SESSION['Debug']."InInitPage(".$_SESSION['SRER_TOKEN']."=".$_COOKIE['SRER_TOKEN'].")";
	setcookie("SRER_TOKEN",$sess_id,(time()+(LifeTime*60)));
	$_SESSION['SRER_TOKEN']=$sess_id;
	$_SESSION['LifeTime']=time();
	$t=(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"");
	$reg=new DB();				
	$reg->do_ins_query("INSERT INTO visitors(ip,vpage,uagent,referrer) values"		
			."('".$_SERVER['REMOTE_ADDR']."','".htmlspecialchars($_SERVER['PHP_SELF'])."','".$_SERVER['HTTP_USER_AGENT']
			."','<".$t.">');");
	if(isset($_REQUEST['show_src']))
	{
		if($_REQUEST['show_src']=="me")
		show_source(substr($_SERVER['PHP_SELF'],1,strlen($_SERVER['PHP_SELF'])));
	}	
	return;
}

function SetCurrForm()
{
	Switch($_POST['FormName'])
	{
	case 'Form 6':
		$_SESSION['TableName']="SRER_Form6";
		$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
		break;
	case 'Form 6A':
		$_SESSION['TableName']="SRER_Form6A";
		$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
		break;
	case 'Form 7':
		$_SESSION['TableName']="SRER_Form7";
		$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `ObjectorName`, `PartNo`, `SerialNoInPart`, `DelPersonName`, `ObjectReason`, `Status` ";
		break;
	case 'Form 8':
		$_SESSION['TableName']="SRER_Form8";
		$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
		break;
	case 'Form 8A':
		$_SESSION['TableName']="SRER_Form8A";
		$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
		break;
	}
	if($_POST['FormName']!="")
		$_SESSION['FormName']=$_POST['FormName'];
}
function CheckSessSRER()
{
	$_SESSION['Debug']=$_SESSION['Debug']."CheckSessSRER";
    if((!isset($_SESSION['UserName'])) && (!isset($_SESSION['PartMapID'])))
	{
		return "Browsing";
	}
	if(isset($_REQUEST['LogOut']))
    {
        return "LogOut";
    }
    else if($_SESSION['LifeTime']<(time()-(LifeTime*60)))
    {
        return "TimeOut(".$_SESSION['LifeTime']."-".(time()-(LifeTime*60)).")";
    }
    else if($_SESSION['SRER_TOKEN']!=$_COOKIE['SRER_TOKEN'])
    {
        $_SESSION['Debug']="(".$_SESSION['SRER_TOKEN']."=".$_COOKIE['SRER_TOKEN'].")";
		return "INVALID SESSION (".$_SESSION['SRER_TOKEN']."=".$_COOKIE['SRER_TOKEN'].")";
    }
    else
    {                                        
		return "Valid";
    }
}
function srer_auth()
{
	session_start();
	$_SESSION['Debug']=$_SESSION['Debug']."InSRER_AUTH";
    $SessRet=CheckSessSRER();
	$reg=new DB();
	$reg->do_max_query("Select 1");
	if($_REQUEST['NoAuth'])
		initSRER();
	else
	{
		if($SessRet!="Valid")
        {
			$reg->do_ins_query("INSERT INTO SRER_logs (`SessionID`,`IP`,`Referrer`,`UserAgent`,`UserID`,`URL`,`Action`,`Method`,`URI`) values"
                    ."('".$_SESSION['ID']."','".$_SERVER['REMOTE_ADDR']."','".mysql_real_escape_string($t)."','".$_SERVER['HTTP_USER_AGENT']
                    ."','".$_SESSION['LoggedOfficerID']."','".mysql_real_escape_string($_SERVER['PHP_SELF'])."','".$SessRet.": ("
                    .$_SERVER['SCRIPT_NAME'].")','".mysql_real_escape_string($_SERVER['REQUEST_METHOD'])."','".mysql_real_escape_string($_SERVER['REQUEST_URI'])."');");    
			session_unset();
			session_destroy();
			session_start();
			$_SESSION=array();
			$_SESSION['Debug']=$_SESSION['Debug'].$SessRet."SRER_TOKEN-!Valid";
			header("Location: index.php");
			exit;
        }
        else
        {
			$_SESSION['Debug']=$_SESSION['Debug']."SRER_TOKEN-IsValid";
			$sess_id=md5(microtime());
			setcookie("SRER_TOKEN",$sess_id,(time()+(LifeTime*60)));
			$_SESSION['SRER_TOKEN']=$sess_id;
			$_SESSION['LifeTime']=time();
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
            echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >';
            $t=(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"");  
            $reg->do_ins_query("INSERT INTO visitors(ip,vpage,uagent,referrer) values"		
                    ."('".$_SERVER['REMOTE_ADDR']."','".htmlspecialchars($_SERVER['PHP_SELF'])."','".$_SERVER['HTTP_USER_AGENT']
                    ."','<".$t.">');");
            $reg->do_ins_query("INSERT INTO SRER_logs (`SessionID`,`IP`,`Referrer`,`UserAgent`,`UserID`,`URL`,`Action`,`Method`,`URI`) values"		
                    ."('".$_SESSION['ID']."','".$_SERVER['REMOTE_ADDR']."','".mysql_real_escape_string($t)."','".$_SERVER['HTTP_USER_AGENT']
                    ."','".$_SESSION['LoggedOfficerID']."','".mysql_real_escape_string($_SERVER['PHP_SELF'])."','Process (".$_SERVER['SCRIPT_NAME'].")','"
                    .mysql_real_escape_string($_SERVER['REQUEST_METHOD'])."','".mysql_real_escape_string($_SERVER['REQUEST_URI'])."');");
        }
	}
	if(isset($_REQUEST['show_src']))
	{
		if($_REQUEST['show_src']=="me")
		show_source(substr($_SERVER['PHP_SELF'],1,strlen($_SERVER['PHP_SELF'])));
	}	
	return;	
}
function EditForm($QueryString)
{ 
	$RowBreak=8;
	$Data=new DB();
	$TotalRows=$Data->do_sel_query($QueryString);
	// Printing results in HTML 
	echo '<form name="frmData" method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF'])
		.'"><table rules="all" frame="box" width="100%" cellpadding="5" cellspacing="1">';
	//Update Table Data
	$col=1;
	$TotalCols=mysql_num_fields($Data->result);
	if($_POST['AddNew']=="New Rows")
	{
		$i=0;
		$AddNewDB=new DB();
		$MaxSlNo=$AddNewDB->do_max_query("Select max(SlNo)+1 from ".$_SESSION['TableName']." Where PartID=".$_SESSION['PartID']);
		if($MaxSlNo==0)
			$MaxSlNo=1;
		while($i<intval($_POST['txtInsRows']))
		{
			$Query="Insert Into ".$_SESSION['TableName']."(`SlNo`,`PartID`) values({$MaxSlNo},{$_SESSION['PartID']});";
			$AddNewDB->do_ins_query($Query);
			$i++;
			$MaxSlNo++;
			//echo $Query."<br />";
		}
		$AddNewDB->do_close();
		unset($AddNewDB);
	}
	else
	{
		if(isset($_POST[mysql_field_name($Data->result,$col)]))
		{
			$DBUpdt=new DB();
			while ($col<$TotalCols)
			{
				$row=0;
				//echo $row.",".$col."--".mysql_field_name($Data->result,$col)."--".mysql_field_table($Data->result,$col)
				//	.$_POST[mysql_field_name($Data->result,$col)][$row];
				while($row<count($_POST[mysql_field_name($Data->result,$col)]))
				{
					$Query="Update ".mysql_field_table($Data->result,$col)
						." Set ".mysql_field_name($Data->result,$col)."='".mysql_real_escape_string($_POST[mysql_field_name($Data->result,$col)][$row])."'"
						." Where ".mysql_field_name($Data->result,0)."=".mysql_real_escape_string($_POST[mysql_field_name($Data->result,0)][$row])." AND PartID=".$_SESSION['PartID']." LIMIT 1;";
					//echo $Query."<br />";
					$DBUpdt->do_ins_query($Query);
					$row++;
				}
				$col++;
			}
			//echo $Query."<br />";
			$DBUpdt->do_close();
			unset($DBUpdt);
		}
	}
	$EditRows=$TotalRows-9;		
	if(intval($_SESSION['PartID'])>0)
		$EditRows=(intval($_POST['SlFrom'])>0)?(intval($_POST['SlFrom'])-1):$EditRows;
	$QueryString=$QueryString." LIMIT ".(($EditRows>0)?$EditRows:0).",10";
	$Data->do_sel_query($QueryString);
	//Print Collumn Names
	$i=0;
	$Fields=new DB();
	$PartName=$Fields->do_max_query("Select CONCAT(PartNo,'-',PartName) as PartName from SRER_PartMap where PartID=".$_SESSION['PartID']);
	echo "<h3>Part[{$PartName}] \"{$_SESSION['FormName']}\"</h3>Total Records: {$TotalRows}";
	echo '<tr><td colspan="'.$TotalCols.'" style="background-color:#F4A460;"></td></tr><tr>';
	
	while ($i<$TotalCols)
	{
		echo '<th>'.$Fields->do_max_query("Select Description from SRER_FieldNames where FieldName='".mysql_field_name($Data->result,$i)."'").'</th>';
		$i++;
		if (($i%$RowBreak)==0 && $i>1)
				echo '</tr><tr>';
	}
	$Fields->do_close();
	unset($Fields);
	echo '</tr><tr><td colspan="'.$TotalCols.'" style="background-color:#F4A460;"></td></tr>';
	//Print Rows
	$odd="";
	$RecCount=0;
	while ($line = mysql_fetch_array($Data->result, MYSQL_ASSOC)) 
	{   
		$RecCount++;
		$odd=$odd==""?"odd":"";
		echo '<tr class="'.$odd.'">';
		$i=0;
		foreach ($line as $col_value)
		{  
			if (($i%$RowBreak)==0 && $i>1)
				echo '</tr><tr>';
			echo '<td>';
			if($i==0)
			{
				$allow='readonly';
				echo '<input type="checkbox" name="RowSelected[]" value="'.htmlspecialchars($col_value).'"/>&nbsp;&nbsp;'
					.'<!--a href="?Delete='.htmlspecialchars($col_value).'"><img border="0" height="16" width="16" '
					.'title="Delete" alt="Delete" src="./Images/b_drop.png"/></a-->&nbsp;&nbsp;';
			}
			else
				$allow='';
			echo '<input '.$allow.' type="text"';
				//size="'.((mysql_field_len($Data->result,$i)>40)?40:mysql_field_len($Data->result,$i)).'"
			echo ' name="'.mysql_field_name($Data->result,$i).'[]" value="'.htmlspecialchars($col_value).'" /> </td>';     
			$i++;
		}   
		echo '</tr><tr><td colspan="'.$TotalCols.'" style="background-color:#F4A460;"></td></tr>'; 
	} 
	echo '<tr><td colspan="'.$TotalCols.'" style="text-align:right;">'
		.'<label for="txtInsRows">Insert:</label>'
		.'<input type="text" name="txtInsRows" size="3" value="'.(isset($_POST['txtInsRows'])?htmlspecialchars($_POST['txtInsRows']):"1").'"/>'
		.'<input type="submit" name="AddNew" value="New Rows" />';
	echo '&nbsp;&nbsp;&nbsp;<input style="width:80px;" type="submit" value="Save" /></td></tr></table></form>'; 
}

function ShowSRER($QueryString)
{ 
	// Connecting, selecting database 
	$Data=new DB();
	$TotalRows=$Data->do_sel_query($QueryString);  
	$TotalCols=mysql_num_fields($Data->result);
	// Printing results in HTML 
	echo '<table rules="all" frame="box" width="100%" cellpadding="5" cellspacing="1">'; 
	$i=0;
	$Fields=new DB();
	$PartName=$Fields->do_max_query("Select CONCAT(PartNo,'-',PartName) as PartName from SRER_PartMap where PartID=".$_SESSION['PartID']);
	echo "Total Records: {$TotalRows}<br />";
	while ($i<$TotalCols)
	{
		echo '<th style="text-align:center;">'.$Fields->do_max_query("Select Description from SRER_FieldNames where FieldName='".mysql_field_name($Data->result,$i)."'").'</th>';
		$i++;
	}
	$Fields->do_close();
	unset($Fields);
	$i=0;
	while ($line = mysql_fetch_array($Data->result, MYSQL_ASSOC)) 
	{   
		echo "\t<tr>\n";   
		foreach ($line as $col_value)
			echo "\t\t<td>".$col_value."</td>\n";
		//$strdt=date("F j, Y, g:i:s a",$ntime); 
		//echo "\t\t<td>$strdt</td>\n";   
		echo "\t</tr>\n"; 
		$i++;
	} 
	echo "</table>\n"; 
	// Free resultset 
	$Data->do_close();
	unset($Data);
	return ($i);
}
?>