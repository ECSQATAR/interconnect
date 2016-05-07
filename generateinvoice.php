<?php
session_start(true);
require_once('head.php');
include_once('smtp.php');
include_once("dbconfig.php");
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbName);

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<?php 

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


$sqlnew = "SELECT * FROM company where id=61";
$newrec = mysql_query($sqlnew);
$rownewcompany = mysql_fetch_object($newrec);
$invoiceName = trim($rownewcompany->nameofcompany.date("d-m-Y").'.pdf');
$company_id = $rownewcompany->id;
//define ('PDF_HEADER_LOGO', 'logoVoip.png');



if(isset($_GET['lastInserId']))
	$lastInserId = $_GET['lastInserId'];
else
 	$lastInserId = 1;


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
$invoicenumber = $invNo; 

$companyname = $rownewcompany->nameofcompany;

$totalchargedamount = 0;
$totalbiledduration=0;
?>
 
<div class="container">  

<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
<table class="table">
<tr>
<td width="25%">
From: <br>
ECS-NET FZE<br>
Ajman Free Zone <br>
United Arab Emirates<br>
Tel #: +971506466878<br>
ceo@ecs-net.net<br>
</td>
 
<td width="50%" style="font-size:200%;color:#ff0000;text-align:center;"> INVOICE </td>
<td width="25%"> <img alt="CompanyLogo" src="logouploads/ECS-Logo.png" width="250" heigth="200"/> </td>
</tr>

<tr>
<td>To.</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td><?php echo $companyname;?> </td>
<td>&nbsp;</td>
<td>Inv.#<?php echo $invNo;?></td>
</tr>


<tr> <td> &nbsp; </td> <td>&nbsp;</td> <td> Create Date <?php echo $createdDate;?> </td> </tr>
<tr> <td> &nbsp; </td> <td>&nbsp;</td> <td> Due Date  <?php echo $dueDate;?></td> </tr>



</table>

<p> &nbsp; </p>
 
 


<table border="1" cellpadding="2" cellspacing="2" class="table">

  <tr style="background-color:#000000;color:#FFFFFF;">
		    
		    <td>Prefix</td>
		    <td>Description </td>
		    <td>Quantity</td>
			<td>Price</td>
		    <td>Charged Amount</td>
			<td border="0" cellpadding="2" cellspacing="2" style="border-right:1px solid #FFFFFF;background-color:#FFFFFF;">&nbsp;</td>

 </tr>

<?php
	
while($row = mysql_fetch_object($resultinvoice)){
	//echo "<pre>";print_r($row);
	 
 ?>
	<tr>
	<td><?php echo $row->prefix;?></td>			
	<td><?php echo $prefixmasterList[$row->prefix];?></td>
	<td><?php echo $row->Duration_min;?></td>
	<td><?php echo $row->price_per_1_min;?></td>
	<td style="text-align:right"><?php echo round($row->Charged_Amount,2);?> </td>  
	<td style="border-right:1px solid #FFFFFF;color:red;text-align:left;"> USD</td>  
	</tr>
	<?php

		$getTotalTime +=  addDurationAsSeconds($row->Duration_min);
	 	$totalchargedamount = $totalchargedamount + $row->Charged_Amount;
		$fromDate = date("d-m-Y",strtotime($row->fromdate));
	    $toDate = date("d-m-Y",strtotime($row->todate));
	}
	
	$seconds  = gmdate($getTotalTime);
	$totalbiledduration = sec2hms($seconds);


?>


 <tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="3" style="text-align:right">Total Minutes : <?php echo $totalbiledduration;?></td>
 <td colspan="2" style="text-align:right">Total : <?php echo round($totalchargedamount,2);?></td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>

<tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="5" style="text-align:right"> Outstanding : 0.00 </td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>

<tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="5" style="text-align:right"> Subtotal : <?php echo round($totalchargedamount,2);?></td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>



</table>


 
 
 <hr>

 
 
<p style="color:#ff0000;">Note: No dispute will be entertained after 72 hours of the invoice date. </p>
 
 <div style="border-top: solid; border-bottom:solid;">
<p>This invoice is for the period of <?php echo $fromDate;?> 00:00:00 to <?php echo $toDate;?> 23:59:59. </p>
<p>All invoices are billed at Dubai (UAE) local time GMT+4. </p>
<p>In case of any dispute please send email to accounts@ecs-net.net </p>
<p> !!!!!!!!!!!!!Thank you for your business!!!!!!!!!!!!!! </p>
</div>

<br>

<center> <input type="submit" name="conform"  class="btn btn-info" value="conform invoice" /> </center>

<br>
<br>

</form>
</div>
<?php
print_r($_POST);
if (isset($_POST['conform'])){
 $invoiceduedate =  date('Y-m-d',strtotime($dueDate));	
 $invoicecreateddate = date('Y-m-d',strtotime($createdDate));
 $invoicefromdate  = date('Y-m-d',strtotime($fromDate));
 $invoicetodate  = date('Y-m-d',strtotime($toDate));


	$invoiceoutstanding = 0;
 	$sqlinv = "INSERT INTO `wsalesinvoicesmaster` (`company_id`, `companyname`, `invoicenumber`, `invoicecreateddate`, `invoiceduedate`, `invoiceTotalminutes`, `invoiceamount`, `invoiceoutstanding`, `invoicesubtotal`, `invoicefromdate`, `invoicetodate`, `paidinvoice`, `pdffilename`) 
VALUES($company_id, '$companyname', '$invoicenumber', '$invoicecreateddate', '$invoiceduedate', '$totalbiledduration', $totalchargedamount, $invoiceoutstanding, $totalchargedamount, '$invoicefromdate', '$invoicetodate', 0, '$pdffilename')";
    mysql_query($sqlinv);

	
sleep(5);
header('Location: wholesaleinvoiceslist.php');
exit(0); 
}
?>
