<?php
include_once('smtp.php');
include_once("dbconfig.php");
require_once('tcpdf_config_alt.php');
require_once("tcpdf.php");
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbName);
 

function sendEMail($toEmail,$pdfpath) {

    $body = " 

<table>
<tr><td> Welcome to ESC-NET FZE </td></tr>
<tr><td> Please keep this email for your records. Your information is as follows:</td> </tr>
<tr><td> Please find the PDF File as attached here </td></tr>
<tr><td> &nbsp; </td> </tr>
<tr><td> &nbsp; </td> </tr>
<tr><td> Please feel free to contact us in case of any assiatnce, we are available on skype id 'mob-voip' </td> </tr>
<tr><td> Whatsapp # +16473602360  </td> </tr>
<tr><td> &nbsp;  </td> </tr>
<tr><td> Thank you for Business! </td> </tr>
<tr><td> &nbsp; </td> </tr>
<tr><td>   ESC-NET FZE  Team. </td> </tr>
</table>";
 
#$bcc = "mail@mob-voip.net";
$to = $toEmail;
$subject = 'Inter Connect Form Confirmation PDF';
		$from ='info@mob-voip.net'; 
	        $headers  = "From: " . $from . "\r\n";
		$headers .= "Reply-To: ". $from . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

 
	externalmail($to,$subject,$body,$pdfpath);



	 



}


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


$sql = "SELECT * FROM company where id=1";
$oldrec = mysql_query($sql);
$rowold = mysql_fetch_object($oldrec);


$sqlnew = "SELECT * FROM company where id=62";
$newrec = mysql_query($sqlnew);
$rownewcompany = mysql_fetch_object($newrec);
$invoiceName = trim($rownewcompany->nameofcompany.date("d-m-Y").'.pdf');
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
 $sql = "SELECT * from tempwsaleinvoicedata WHERE 1 = 1  ";
 	 
 $getTotalTime = 0; 
 $totalchargedamount=0;
 $totalbiledduration =0;
 $resultinvoice = mysql_query($sql);
 
$currentDate = date("Ymd");
$createdDate = date("d/m/Y");
$dueDate =  date('d/m/Y', strtotime($createdDate. ' + 3 day'));
$invNo = $currentDate;

$totalchargedamount = 0;
$totalbiledduration=0;
	  
  $html = '
 
   
<table>
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
<td width="150px"> <img alt="CompanyLogo" src="logouploads/ECS-Logo.png" width="100" heigth="100"/> </td>
</tr>

<tr>
<td>To.</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>Sync Sound </td>
<td>&nbsp;</td>
<td> Inv. #'.$invNo.' </td>
</tr>


<tr> <td> &nbsp; </td> <td>&nbsp;</td> <td> Create Date '.$createdDate.' </td> </tr>
<tr> <td> &nbsp; </td> <td>&nbsp;</td> <td> Due Date '.$dueDate.' </td> </tr>



</table>

<p> <br> </p>
<table border="1" >

  <tr style="background-color:#000000;color:#FFFFFF;">
		    
		    <td>Prefix</td>
		    <td>Description </td>
		    <td>Quantity</td>
			<td>Price</td>
		    <td>Charged Amount</td>

 </tr>';

 	 $htmlsub='';
	
while($row = mysql_fetch_object($resultinvoice)){
	//print_r($row);
	 
 $htmlsubtxt = '	 
	<tr>
	<td>'.$row->prefix.'</td>			
	<td>'.$prefixmasterList[$row->prefix].'</td>
	<td>'.$row->Duration_min.'.</td>
	<td>'.$row->price_per_1_min.'</td>
	<td>'.$row->Charged_Amount.'  </td>  
	<td><span style="color:red">USD </span> </td>  
	</tr>';
	 $htmlsub =  $htmlsub.$htmlsubtxt;  

	 $getTotalTime +=  addDurationAsSeconds($row->Duration_min);
	 	 $totalchargedamount = $totalchargedamount + $row->Charged_Amount;
	  
	  $fromDate = date("d-m-Y",$row->fromdate);
	   $toDate = date("d-m-Y",$row->todate);
	  	 
		 
	}
	
	$seconds  = gmdate($getTotalTime);
	$totalbiledduration = sec2hms($seconds);


$html = $html.$htmlsub;

$html = $html. '
 <tr> <td colspan="5"> Total Minutes: '.$totalbiledduration.' &nbsp; charged Amount :'.$totalchargedamount.'</td> </tr>

 <hr>
 
 </table>
 
<p> <br> </p>
 
 <p style="">  &nbsp; &nbsp; &nbsp; &nbsp; Total Minutes: '.$totalbiledduration.'  </span>
 <span style="text-align:right">Total : '.$totalchargedamount.' <span style="color:red">USD </span>  </span>
 </p>
 <p style="text-align:right"> Outstanding 0.00 <span style="color:red">USD </span>  </p>
 <p style="text-align:right"> Subtotal '.$totalchargedamount.' <span style="color:red">USD </span>  </p>
 
 <p style="color:#ff0000;">Note: No dispute will be entertained after 72 hours of the invoice date. </p>
 
 <hr>

<p> This invoice is for the period of '.$fromDate.' 00:00:00 to '.$toDate.'23:59:59. </p>
<p> All invoices are billed at Dubai (UAE) local time GMT+4. </p>
<p> In case of any dispute please send email to accounts@ecs-net.net </p>
<p> !!!!!!!!!!!!!Thank you for your business!!!!!!!!!!!!!! </p>

 
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
$pdfpath = $_SERVER['DOCUMENT_ROOT']."interconnect/invoicepdfs/$invoiceName";
$toEmail='snmurty99@gmail.com'; 
//sendEMail($toEmail,$pdfpath);
$pdf->Output($pdfpath, 'F');
$pdf->Output('output.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>
