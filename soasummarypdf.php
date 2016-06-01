<?php
include_once('smtp.php');
include_once("dbconfig.php");
require_once('tcpdf_config_alt.php');
require_once("tcpdf.php");
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbName);

$companyList = array();
  $sql = "SELECT id,nameofcompany FROM company";
 $result = mysql_query($sql);
while($row = mysql_fetch_object($result)){
	$companyList[$row->id] = $row->nameofcompany;
}

$condition = " Where 1 = 1   ";
if (isset($_GET['Go'])){
if (strlen($_GET['company_id'])>0 && isset($_GET['company_id'])){
	$company_id = $_GET['company_id'];
	$condition = $condition." AND company_id = $company_id";
	$linkurl  = $linkurl."&company_id=$company_id"; 
	}


	if (strlen(trim($_GET['from_date']))>0 && isset($_GET['from_date'])){
	 $from_date = $_GET['from_date'];
	$condition = $condition." AND DATE(invoicecreateddate)>='$from_date' ";

 
	$linkurl  = $linkurl."&reseller_id=$reseller_id"; 

	}

	if (strlen(trim($_GET['to_date']))>0 && isset($_GET['to_date'])){

		$to_date = $_GET['to_date'];
		$condition = $condition." AND DATE(invoicecreateddate)<='$to_date' ";


		$to_date = $_GET['to_date'];
	}
	
	if (strlen(trim($_GET['sortfield']))>0 && isset($_GET['sortfield'])){

		$sortfield = $_GET['sortfield'];
		$sortByData =  " order by $sortfield ";
	}


}

 

$sql = "SELECT * From wsalesconsumptionmaster";
$result = mysql_query($sql);
$sno = 0;
$consumptionList = array();
while($rowinv = mysql_fetch_object($result)){
	$consumptionList[$rowinv->invoicecreateddate]['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$consumptionList[$rowinv->invoicecreateddate]['invoicenumber'] = $rowinv->invoicenumber;
	$consumptionList[$rowinv->invoicecreateddate]['description'] = substr($rowinv->invoicefromdate, 0, 10).'-'.substr($rowinv->invoicetodate, 0, 10);
	$consumptionList[$rowinv->invoicecreateddate]['invoiceamount'] = $rowinv->invoiceamount;
	$consumptionList[$rowinv->invoicecreateddate]['invoiceTotalminutes'] = $rowinv->invoiceTotalminutes;
	$consumptionList[$rowinv->invoicecreateddate]['paiddate'] = $rowinv->paiddate;
	$consumptionList[$rowinv->invoicecreateddate]['paidamount'] = $rowinv->paidamount;
}

  $sql = "SELECT * From wsalesinvoicesmaster ";
$result = mysql_query($sql);
$sno = 0;
$invoiceList=array();
while($rowinv = mysql_fetch_object($result)){
	$invoiceList[$rowinv->invoicecreateddate]['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$invoiceList[$rowinv->invoicecreateddate]['invoicenumber'] = $rowinv->invoicenumber;
	$invoiceList[$rowinv->invoicecreateddate]['description'] = substr($rowinv->invoicefromdate, 0, 10).'-'.substr($rowinv->invoicetodate, 0, 10);
	$invoiceList[$rowinv->invoicecreateddate]['invoiceamount'] = $rowinv->invoiceamount;
	$invoiceList[$rowinv->invoicecreateddate]['invoiceTotalminutes'] = $rowinv->invoiceTotalminutes;
    $invoiceList[$rowinv->invoicecreateddate]['paiddate'] = $rowinv->paiddate;
	$invoiceList[$rowinv->invoicecreateddate]['paidamount'] = $rowinv->paidamount;
}

//echo "<pre>"; print_r($invoiceList); echo "</pre>";

 
?>


  
 

<?php 
$ak = '';
$bk = '';

if(sizeof($consumptionList)==0)
$ak = 'display:none';
 

if(sizeof($invoiceList)==0)
$bk = 'display:none';
 

$invoiceName = '12334';
//define ('PDF_HEADER_LOGO', 'logoVoip.png');
define ('PDF_HEADER_LOGO', 'ECS-Logo.png');


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);

if(isset($_GET['lastInserId']))
	$lastInserId = $_GET['lastInserId'];
else
 	$lastInserId = 1;

//$pdf->SetAuthor('ECS-NET');
//$pdf->SetTitle('ECS-NET FZE');
//$pdf->SetSubject('ECS-NET FZE INTER CONNECT');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


$Header_logo = 'ECS-Logo.png';

// set default header data
//$pdf->SetHeaderData($Header_logo, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
 
 
// ---------------------------------------------------------

// set font
$pdf->SetFont('courier', '', 12, '', 'false');
$pdf->SetFontSize(12);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
  
  
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

 // add a page
$pdf->AddPage('L');

$sqlold = "SELECT * FROM company where id=1";
$oldrec = mysql_query($sqlold);
$rowold = mysql_fetch_object($oldrec);

 $Condition='';
 
 
$currentDate = date("Ymd");
$createdDate = date("d/m/Y");
$dueDate =  date('d/m/Y', strtotime($createdDate. ' + 3 day'));
$invNo = $currentDate;

$totalchargedamount = 0;
$totalbiledduration=0;
	  
  $html = '
 
  
<table>

<tr>

<td  style="'.$ak.'>">

 

<table>
<tr>
<td style="text-align:center" colspan="7" >
ECS (Our company)  
</td>
</tr>
 
<tr style="text-align:center">
<td>Date &nbsp;</td>
<td>Invoice Period </td>
<td>Minutes</td>
<td>Amount</td>
<td>Paid Date</td>
<td>Paid</td>
<td>Balance</td>
</tr>'; 
  
 $mycompanytotalinvAmount = 0;
$mycompanytotalPaidAmount = 0;

foreach ($consumptionList as $key => $rowinv) {
 
$html = $html . '<tr>
<td>'.$key.'</td>
<td style="text-align:center">'.$consumptionList[$key]["description"].'</td>
<td>'.$consumptionList[$key]["invoiceTotalminutes"].'</td>	
<td style="text-align:right">'.$consumptionList[$key]["invoiceamount"].'</td>';
if( $consumptionList[$key]["paidamount"]>0) 
$html = $html . '<td>'.$consumptionList[$key]["paiddate"].'</td>';
if( $consumptionList[$key]['paidamount']>0)
	$html = $html . '<td style="text-align:right">'.$consumptionList[$key]["paidamount"].'</td>';

	$mycompanytotalinvAmount = $mycompanytotalinvAmount +  $consumptionList[$key]['invoiceamount'];
	$mycompanytotalPaidAmount = $mycompanytotalPaidAmount +  $consumptionList[$key]['paidamount'];
	$mycompanyBalanceAmount =  $mycompanytotalinvAmount  -  $mycompanytotalPaidAmount;

	$html = $html . '<td  style="text-align:right">'.$mycompanyBalanceAmount.'</td></tr>';

}

$html = $html . '<tr><td> Totals : </td>  <td> &nbsp; </td> <td> &nbsp; </td> <td  style="text-align:right">'.$mycompanytotalinvAmount.'</td> 
<td> &nbsp; </td> <td  style="text-align:right">'.$mycompanytotalPaidAmount.'</td></tr>

<tr>
<td style="text-align:center" colspan="7">
Balance :  '.$mycompanytotalinvAmount.' </td> </tr>

</table>

  
</td>
 
 
 
<td  style="'.$bk.'">



 

<table>

<tr>
<td style="text-align:center" colspan="7">';
$company_id = 71;
$html = $html.$companyList[$company_id].';
</td>
</tr>

<tr style="text-align:center">
<td>Date &nbsp;</td>
<td>Invoice Period </td>
<td>Minutes</td>
<td>Amount</td>
<td>Paid Date</td>
<td>Paid</td>
<td>Balance</td>
</tr>';


$othercompanytotalinvAmount = 0;
$othercompanytotalPaidAmount  = 0;
$rowbalance = 0;

foreach ($invoiceList as $key => $rowinv) {

$html = $html.'<tr>
<td>'.$key.'</td>
<td style="text-align:center"> '.$invoiceList[$key]["description"].'</td>
<td> '.$invoiceList[$key]["invoiceTotalminutes"].'</td>	
<td style="text-align:right"> '. $invoiceList[$key]["invoiceamount"].'</td>';
 if( $invoiceList[$key]['paidamount']>0)	
$html = $html.'<td>'.$invoiceList[$key]["paiddate"].'</td>	
<td style="text-align:right"> ';
if( $invoiceList[$key]['paidamount']>0)
$html = $html.$invoiceList[$key]["paidamount"].'</td>';

 
$othercompanytotalinvAmount = $othercompanytotalinvAmount +  $invoiceList[$key]['invoiceamount'];
$othercompanytotalPaidAmount = $othercompanytotalPaidAmount +  $invoiceList[$key]['paidamount'];
$rowbalance =   $othercompanytotalinvAmount -  $othercompanytotalPaidAmount;
$html = $html.'<td  style="text-align:right">'.$rowbalance.'</td></tr>';
}


$othercompanyBalanceAmount =  $othercompanytotalinvAmount  -  $othercompanytotalPaidAmount;

$html = $html.'<tr>
<td> Totals :&nbsp; </td>  <td> &nbsp; </td> <td> &nbsp; </td> <td style="text-align:right">'.$othercompanytotalinvAmount.'</td>
 <td> &nbsp; </td> <td style="text-align:right">'.$othercompanytotalPaidAmount.'</td>
</tr>
 
 <tr >
<td  style="text-align:center" colspan="7">
 Balance : '.$othercompanyBalanceAmoun.' &nbsp;
 </td>
 </tr>
 
</table>

 </td>

</tr>

</table>


';
  

  
// ---------------------------------------------------------
 $Header_logo = '1157692848Untitled-1-04.png';
// set default header data
$pdf->SetHeaderData($Header_logo, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderTemplateAutoreset(true);
$pdf->SetAutoPageBreak(false, 0);
//$img_file = 'invbg.jpg';
//$pdf->Image($img_file, 0, 0, 1000, 1000, '', '', '', false, 1000, '', false, false, 0);

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// Print some HTML Cells
   
// reset pointer to the last page
$pdf->lastPage();
 
 
// ---------------------------------------------------------
ob_clean();
ob_start();
//Close and output PDF document
$pdfpath = $_SERVER['DOCUMENT_ROOT']."interconnect/invoicepdfs/soasummary.pdf";
$toEmail='snmurty99@gmail.com'; 
//sendEMail($toEmail,$pdfpath);
$pdf->Output($pdfpath, 'F');
$pdf->Output('soasummary.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>
