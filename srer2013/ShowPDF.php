<?php
if (intval($_POST['PartID'])>0)
	$_SESSION['PartID']=intval($_POST['PartID']);
Switch($_POST['FormName'])
{
case 'Form 6':
	$_SESSION['TableName']="SRER_Form6";
	$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
	$ColWidths=array(
		array("1","2","3","4","5","6"),
		array(20,25,80,80,35,47)
	);
	ShowPDF($ColWidths,"Form 6");
	break;
case 'Form 6A':
	$_SESSION['TableName']="SRER_Form6A";
	$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
	$ColWidths=array(
		array("1","2","3","4","5","6"),
		array(20,25,80,80,35,47)
	);
	ShowPDF($ColWidths,"Form 6A");
	break;
case 'Form 7':
	$_SESSION['TableName']="SRER_Form7";
	$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `ObjectorName`, `PartNo`, `SerialNoInPart`, `DelPersonName`, `ObjectReason`, `Status` ";
	$ColWidths=array(
		array("1","2","3","4","5","6","7","8"),
		array(17,25,80,20,20,80,20,25)
	);
	ShowPDF($ColWidths,"Form 7");
	break;
case 'Form 8':
	$_SESSION['TableName']="SRER_Form8";
	$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
	$ColWidths=array(
		array("1","2","3","4","5","6"),
		array(20,25,80,80,35,47)
	);
	ShowPDF($ColWidths,"Form 8");
	break;
case 'Form 8A':
	$_SESSION['TableName']="SRER_Form8A";
	$_SESSION['Fields']="`SlNo`, `ReceiptDate`, `AppName`, `RelationshipName`, `Relationship`, `Status`";
	$ColWidths=array(
		array("1","2","3","4","5","6"),
		array(20,25,80,80,35,47)
	);
	ShowPDF($ColWidths,"Form 8A");
	break;
}
function ShowPDF($ColWidths,$SRERForm)
{
	$pdf=new PDF();
	$pdf->cols=$ColWidths;
	$ColHead=& $pdf->cols[0];
	$Data=new DB();
	$Fields=new DB();
	$_SESSION['PartName']=$Fields->do_max_query("Select CONCAT(ACNo,' [',PartNo,']-',PartName) as PartName from SRER_PartMap where PartID=".$_SESSION['PartID']);
	$i=0;
	$Query="Select {$_SESSION['Fields']} from {$_SESSION['TableName']} Where PartID={$_SESSION['PartID']}";
	$Data->do_sel_query($Query);
	$TotalCols=mysql_num_fields($Data->result);
	while ($i<$TotalCols)
	{
		$ColHead[$i]=$Fields->do_max_query("Select Description from SRER_FieldNames where FieldName='".mysql_field_name($Data->result,$i)."'");
		$i++;
	}
	$Fields->do_close();
	unset($Fields);
	unset($ColHead);
	$pdf->SetTitle($SRERForm);
	$pdf->AddPage(); 
	//$Query="Select '1','2','3','4','5','6','7','8' from visitors limit 200";
	//$pdf->Write(0,"PartID=[".$_SESSION['PartID']."-".$_POST['PartID']."]");
	$pdf->Details($Query,0);
	$pdf->Output($SRERForm.".pdf","D");
	unset($pdf);
}
?>