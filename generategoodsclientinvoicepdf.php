<?php
include_once('smtp.php');
include_once("dbconfig.php");
require_once('tcpdf_config_alt.php');
require_once("tcpdf.php");
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbName);
 
 


function sec2hms($secs) {
    $secs = round($secs);
    $secs = abs($secs);
    $hours = floor($secs / 3600) . ':';
    if ($hours == '0:') $hours = '';
    $minutes = substr('00' . floor(($secs / 60) % 60), -2) . ':';
    $seconds = substr('00' . $secs % 60, -2);
return ltrim($hours . $minutes . $seconds, '0');
}

function addDurationAsSeconds( $timeStamp ) {
        $timeSections = explode( ':', $timeStamp );
        $seconds =  
                   ( $timeSections[0] * 60 )        //Minutes to Seconds
                 +  ( $timeSections[1]  );           //Seconds to Seconds
 
        return $seconds;
}



$prefixmasterList = array();
$sqlp = "SELECT * FROM prefixmaster  ";
$resultp = mysql_query($sqlp);
while($rowp = mysql_fetch_object($resultp)){
 $prefixmasterList[$rowp->prefix] = $rowp->description;
}

$currentid = $_GET['id'];

$sqlinv = "SELECT * FROM  ws_goodservice_clientinvoice_master where id=$currentid";
$recinv = mysql_query($sqlinv);
$rowinv = mysql_fetch_object($recinv);
$invmasterId = $rowinv->id;


$invoiceName = $rowinv->invoicenumber;
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
$pdf->SetFont('times', '', 12, '', 'false');
$pdf->SetFontSize(12);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
  
  
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

 // add a page
$pdf->AddPage();

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
 
  <table border="0" cellpadding="2" cellspacing="2">
 
<tr>
<td>
From: <br>
ECS-NET FZE<br>
Ajman Free Zone <br>
United Arab Emirates<br>
Tel #: +971506466878<br>
ceo@ecs-net.net<br>
</td>
 
<td  style="font-size:200%;color:#ff0000;"> INVOICE </td>
<td width="190px" style="text-align:right"> <img alt="CompanyLogo" src="logouploads/ECS-Logo.png" width="350px" height="250px" /> </td>
</tr>
</table>

<table>
<tr>
<td>To.</td>
<td width="35%">&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td colspan="3">'.$rowinv->companyname.' </td>
<td>Inv#.</td>
<td style="text-align:right">'.$rowinv->invoicenumber.' </td>
<td>&nbsp;</td>
</tr>


<tr>
<td>&nbsp;</td> 
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>Create Date</td>
<td style="text-align:right">'.date("d/m/Y",strtotime($rowinv->invoicecreateddate)).' </td> 
<td>&nbsp;</td>
</tr>

<tr> 
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>Due Date  </td>
<td style="text-align:right">'.date("d/m/Y",strtotime($rowinv->invoiceduedate)).' </td>
<td>&nbsp;</td>
</tr>

</table>
 

<p style="text-align:center;">
This invoice is for the period of '. date('d/m/Y',strtotime($rowinv->invoicefromdate)).' to '.date('d/m/Y',strtotime($rowinv->invoicetodate)).'. 
</p>


<table>

  <tr style="background-color:#0695AD;color:#FFFFFF;text-align:center;">
		    
		    <td style="border-bottom:solid 5px #ff0000;height:20px;width:20%;">Item Code</td>
		    <td style="border-bottom:solid 5px #ff0000;" width="35%">Description</td>
		    <td style="border-bottom:solid 5px #ff0000;">Quantity</td>
			<td style="border-bottom:solid 5px #ff0000;">Price</td>
		    <td style="border-bottom:solid 5px #ff0000;">Amount</td>
			<td border="0" style="border-right:1px solid #0695AD;background-color:#FFFFFF;">&nbsp;</td>
			

 </tr> 
   ';


 	 $htmlsub='';
	
 $sqlchild = "SELECT * from ws_goodservice_clientinvoice_child WHERE invmasterid = $invmasterId";
 	 
 $getTotalTime = 0; 
 $totalchargedamount=0;
 $totalbiledduration =0;
 $resultChild = mysql_query($sqlchild);
 $rk=0;
while($rowChild = mysql_fetch_object($resultChild)){
	//print_r($row);
	 if ($rk%2==0)
		$rdt = '<tr style="background-color:#FFFFFF;">';
	 else
		$rdt = '<tr style="background-color:#CCFFFF;">';
	$rk = $rk + 1 ;
 $htmlsubtxt = $rdt.'
    <td style="text-align:center">'.$rowChild->prefix.'</td>			
	<td>'.$rowChild->Description.'</td>
	<td style="text-align:right">'.$rowChild->quantity.'</td>
	<td style="text-align:right">'.$rowChild->price_per_1_min.'</td>
	<td style="text-align:right">'.$rowChild->Charged_Amount.'</td>  
	<td style="background-color:#FFFFFF;border-right:1px solid #FFFFFF;text-align:left;"> $</td>  
	</tr>';
	 $htmlsub =  $htmlsub.$htmlsubtxt;  

	// $getTotalTime +=  addDurationAsSeconds($rowChild->Duration_min);
	 //	 $totalchargedamount = $totalchargedamount + $rowChild->Charged_Amount;
	  
	  $fromDate = date("d-m-Y",strtotime($rowChild->fromdate));
	   $toDate = date("d-m-Y",strtotime($rowChild->todate));
	  	 
		 
	}
	
	
	
	 


$html = $html.$htmlsub;

$html = $html. '
<tr>
	
	<td style="border-bottom:2px solid #00CCCC;" width="35%">&nbsp;</td>
	<td style="border-bottom:2px solid #00CCCC;"> &nbsp;</td>	 
	<td style="border-bottom:2px solid #00CCCC;">&nbsp;</td>	 
	<td style="border-bottom:2px solid #00CCCC;">&nbsp;</td>
	
	<td>&nbsp; </td>
 </tr>
 
 </table>

 
 <table>
 <tr>
	<td width="35%">&nbsp;</td>
	<td>&nbsp;</td>	 
	<td> &nbsp; </td>
	<td style="text-align:right">  Total : </td>
	<td><span style="text-align:right">'.round($rowinv->invoiceamount,2).'</span> </td>
	<td> <span>$ </span>  </td>
 </tr>
 
 <tr>
	<td width="35%">&nbsp;</td>
	<td> &nbsp; </td>
	<td> &nbsp; </td>
	<td style="text-align:right"> Outstanding : </td>
	<td  style="text-align:right">0.00</td>
	<td> <span>$ </span>  </td>
 </tr>
 
 <tr>
	<td width="35%">&nbsp;</td>
	<td>&nbsp;</td>	 
	<td>&nbsp;</td>	 
	<td style="text-align:right"> Grand Total : </td>
	<td style="text-align:right">'.round($rowinv->invoicesubtotal,2).'</td>
	<td> <span>$ </span>  </td>
 </tr>
 
 </table>

  

 <p style="text-align:center;border-bottom:2px solid #00CCCC;">Notes</p>
   
 
 <table Style="background-color:#FFFFFF;">
 
 
 <tr>
 <td> '.$rowinv->invoicebilleddesc.' </td>
 </tr>
 
 <tr>
 <td> '.$rowinv->invoicedisputeemail.' </td>
 </tr>
 <Tr><td>All invoices are billed at Dubai (UAE) local time GMT+4. </td> </tr>
 <tr><td>Payments can be done through Bank </td> </tr>
 </table>
 


<table  style="width:60%" border="1">
<tr> <td> Bank Name  </td> <td style="background-color:#CCFFFF"> Mashreq Bank PSC  </td> </tr>
<tr> <td> SWIFT code (BIC code)</td> <td style="background-color:#CCFFFF"> BOMLAEAD </td> </tr>
<tr> <td> Mashreq Bank ID  </td> <td style="background-color:#CCFFFF"> 33 </td> </tr>
<tr> <td> Routing Code   </td>  <td style="background-color:#CCFFFF"> 203320101 </td> </tr>
<tr> <td> Account Title </td>  <td style="background-color:#CCFFFF">  SAQIB ASIF ALI ALI </td></tr>
<tr> <td> Account Number </td> <td style="background-color:#CCFFFF"> 010797932038 </td> </tr>
<tr> <td> Account IBAN   </td> <td style="background-color:#CCFFFF"> AE410330000010797932038 </td> </tr>
</table>


 
<p style="text-align:center;border-top:2px solid #00CCCC;"><h5>ECS-NET FZE: Ajman Free Zone, United Arab Emirates, Tel #: +971506466878, Email: ceo@ecs-net.net </h5> </p>
 

 
';

 //echo $html;

 
 
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
$pdfpath = $_SERVER['DOCUMENT_ROOT']."interconnect/goodservicepdf/".$rowinv->pdffilename;
$toEmail='snmurty99@gmail.com'; 
//sendEMail($toEmail,$pdfpath);
$pdf->Output($pdfpath, 'F');
$pdf->Output($rowinv->pdffilename, 'I');
//============================================================+
// END OF FILE
//============================================================+
?>
