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
 <script>
    jQuery(document).ready(function () {
	jQuery("#recalculculate").click(function() {
		$( ".Duration_min" ).each(function( index,value ) {
	//console.log('div' + index + ':' + $(this).attr('id')); 
		var mysDiv = $(this).attr('id');
		var res = mysDiv.split('_'); 
		var myId = res[2];
		var Duration_min = jQuery('Duration_min_'+myId).val();
		var price_per_1_min_ = jQuery('price_per_1_min_'+myId).val();
		var Duration_min = jQuery('Duration_min_'+myId).val();
		
		});

});

	
  });

 </script>
<style>
.Duration_min{

}
</style>
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
function converToMMHH($timeStamp){

	    $timeSections = explode(':',$timeStamp);
		$hours =  $timeSections[0];
		$minutes = $timeSections[1];
		$seconds = $timeSections[2];
	
		$totaLMinutes = ($hours * 60) + $minutes;
	  	$resultTimestamp = $totaLMinutes.':'.$seconds;
		return $resultTimestamp;
	
}


$prefixmasterList = array();
$sqlp = "SELECT * FROM prefixmaster  ";
$resultp = mysql_query($sqlp);
while($rowp = mysql_fetch_object($resultp)){
 $prefixmasterList[$rowp->prefix] = $rowp->description;
}

$company_id = $_SESSION['company_id'];

$sql = "SELECT * FROM company where id=$company_id";
$oldrec = mysql_query($sql);
$rowold = mysql_fetch_object($oldrec);
$companyname = $rowold->nameofcompany;


 
$cdate = date("Y-m-d");
$sqlcurntInvcount = "select count(id) as cnt From wsalesinvoicesmaster WHERE company_id=$company_id AND date(invoicecreateddate) = '$cdate' ";
$recCountData = mysql_query($sqlcurntInvcount);
$rowrecCount = mysql_fetch_object($recCountData);
 $oldRecordsCount = $rowrecCount->cnt;
 //print_r($rowrecCount);
 if( $oldRecordsCount == 0 )
	  $oldRecordsCount = 1;
  else
	  $oldRecordsCount = $oldRecordsCount + 1;
  
$sqlnew = "SELECT * FROM company where id=$company_id";
$newrec = mysql_query($sqlnew);
$rownewcompany = mysql_fetch_object($newrec);
$company_id = $rownewcompany->id;
$companyname = $rownewcompany->nameofcompany;
//define ('PDF_HEADER_LOGO', 'logoVoip.png');



if(isset($_GET['lastInserId']))
	$lastInserId = $_GET['lastInserId'];
else
 	$lastInserId = 1;



 $Condition='';
 $sql = "SELECT * from tempwsaleinvoicedata WHERE 1 = 1  ";
 	 
 $getTotalTime = 0; 
 $totalchargedamount=0;
 $totalbiledduration =0;
 $resultinvoice = mysql_query($sql);
 
$currentDate = date("Ymd");
$createdDate = date("d/m/Y");
$dueDate =  date('d/m/Y', strtotime(' + 3 day'));
$invNo = $currentDate.$oldRecordsCount;
$invoicenumber = $invNo; 


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
<td colspan="2"><input type="text" name="companyname"  class="form-control" value="<?php echo $companyname;?>" />  </td>
 
<td>Inv.# <input type="text" name="invoicenumber" value="<?php echo $invNo;?>" /> </td>
</tr>


<tr>
<td> &nbsp; </td> <td> &nbsp; </td>  <td> Create Date <input type="text" name="createdDate" value="<?php echo $createdDate;?>" /> </td> 
</tr>

<tr> <td colspan='2'>Comments:<textarea name="invoicecomments" class="form-control"> </textarea> </td>  <td> Due Date  <input type="text" name="dueDate" value="<?php echo $dueDate;?>" /> </td> </tr>



</table>

<p> &nbsp; </p>
 
 


<table border="1" cellpadding="2" cellspacing="2" class="table">

  <tr style="background-color:#000000;color:#FFFFFF;">
		    
		    <td>Prefix</td>
		    <td>Description </td>
		    <td>Quantity</td>
		    <td>Price</td>
		    <td>Amount</td>
		<td border="0" cellpadding="2" cellspacing="2" style="border-right:1px solid #FFFFFF;background-color:#FFFFFF;">&nbsp;</td>

 </tr>

<?php
	$totalrecords=0;
while($row = mysql_fetch_object($resultinvoice)){
	//echo "<pre>";print_r($row);
	 
 ?>
	<tr>
	<td><input type="hidden" name="prefix[]" value="<?php echo $row->prefix;?>" /> <?php echo $row->prefix;?></td>			
	<td><input type="hidden" name="description[]" value="<?php echo  $prefixmasterList[$row->prefix];?>" /> <?php echo $prefixmasterList[$row->prefix];?>  </td>
	<td><input type="text" name="Duration_min[]" class="Duration_min" id="Duration_min_<?php echo $row->id; ?>" value="<?php echo $row->Duration_min;?>" />  </td>
	<td>
		<input type="text" name="price_per_1_min[]" id="price_per_1_min_<?php echo $row->id; ?>"  value="<?php echo $row->price_per_1_min;?>" />
		<input type="hidden" name="numberofCalls[]" value="<?php echo $row->numberofCalls;?>" />
	</td>
	<td style="text-align:right"><input type="text" name="Charged_Amount[]"  id="Charged_Amount_<?php echo $row->id; ?>"  value="<?php echo round($row->Charged_Amount,2);?>" /> </td>  
	<td style="border-right:1px solid #FFFFFF;color:red;text-align:left;"> USD</td>  
	</tr>

	

	<?php
		$totalrecords = $totalrecords + 1;
		$getTotalTime +=  addDurationAsSeconds($row->Duration_min);
	 	$totalchargedamount = $totalchargedamount + $row->Charged_Amount;
		$fromDate = date("d-m-Y",strtotime($row->fromdate));
	    $toDate = date("d-m-Y",strtotime($row->todate));
	}
	
 	$seconds  = gmdate($getTotalTime);
	$totalbiledduration = converToMMHH(sec2hms($seconds));
	//$totalbiledduration  =  sec2hms($seconds);
	$outstanding =0;

?>


 <tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="3" style="text-align:right">Total Minutes :
<input tsype="text" name="totalbiledduration" value="<?php echo $totalbiledduration;?>" /> 
</td>
 <td colspan="2" style="text-align:right">Total : 
 <input type="text" name="totalchargedamount" value="<?php echo round($totalchargedamount,2);?>" /> 
   
 </td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>

<tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="5" style="text-align:right"> Outstanding : 
<input type="text" name="outstanding" value="<?php echo round($outstanding,2);?>" /> 
   
 </td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>

<tr style="border-right:1px solid #FFFFFF;text-align:right;">
 <td colspan="5" style="text-align:right"> Subtotal :
<input type="text" name="totalchargedamount" value="<?php echo round($totalchargedamount,2);?>" /> 
</td>
 <td style="border:0px 0px 0px 0px  solid #FFFFFF;color:red;text-align:left;"> USD</td>  
</tr>
 </table>

 <input type="text" name="totalrecords" value="<?php echo $totalrecords;?>" />
<input type="button" value="recalculculate" id="recalculculate"/>

 
 
 <hr>

 
 
<p style="color:#ff0000;">Note: No dispute will be entertained after 72 hours of the invoice date. </p>
 
 <div style="border-top: solid; border-bottom:solid;">
<p>This invoice is for the period of <input type="text" name="fromDate" value="<?php echo $fromDate.' 00:00:00';?>" />  to <input type="text" name="toDate" value="<?php echo $toDate.' 00:00:00';?>" />. </p>
<p><input type="text" class="form-control" name="invoicebilleddesc" value="All invoices are billed at Dubai (UAE) local time GMT+4." /> </p>
<p><input type="text" class="form-control" name="invoicedisputeemail" value="In case of any dispute please send email to accounts@ecs-net.net cc to ceo@ecs-net.net." /> </p>
<p> !!!!!!!!!!!!!Thank you for your business!!!!!!!!!!!!!! </p>
</div>

<br>

<center> <input type="submit" name="conform"  class="btn btn-info" value="conform invoice" /> </center>

<br>
<br>

</form>
</div>
<?php
//print_r($_POST);
//=======
//print_r($_POST);

	$company_id = $_SESSION['company_id'];


if (isset($_POST['conform'])){

	$invoicenumber = $_POST['invoicenumber'];
	$totalbiledduration = $_POST['totalbiledduration'];
	

	$dueDate = $_POST['dueDate'];
	$dueDateObject = explode('/',$dueDate);
	$invoiceduedate =  date('Y-m-d',mktime(0,0,0,$dueDateObject[1],$dueDateObject[0],$dueDateObject[2]));
	
	
	$createdDate = $_POST['createdDate'];
	$createdDateObject = explode('/',$createdDate);
	$invoicecreateddate =  date('Y-m-d',mktime(0,0,0,$createdDateObject[1],$createdDateObject[0],$createdDateObject[2]));
	
	
	$fromDate = $_POST['fromDate'];
	$toDate = $_POST['toDate'];
	//$invoicefromdate  = date('Y-m-d',strtotime($fromDate));
	//$invoicetodate  = date('Y-m-d',strtotime($toDate));
	$invoicefromdate  = $fromDate;
	$invoicetodate  = $toDate;


	$totalchargedamount = $_POST['totalchargedamount'];
	$invoicebilleddesc = $_POST['invoicebilleddesc'];
    	$invoiceoutstanding = $_POST['outstanding'];

	$companyname = $_POST['companyname'];
 

	$invoiceoutstanding = 0;
 
	$invoicedisputeemail = $_POST['invoicedisputeemail'];
		
	$ftotamount = round($totalchargedamount,0);
	$pdffilename = trim($rownewcompany->nameofcompany.'-'.date("d-M-y").'-'.$ftotamount.'.pdf');
	
	 $sqlinv = "INSERT INTO wsalesinvoicesmaster (`company_id`, `companyname`, `invoicenumber`, `invoicecreateddate`, `invoiceduedate`, `invoiceTotalminutes`, `invoiceamount`, `invoiceoutstanding`, `invoicesubtotal`, `invoicefromdate`, `invoicetodate`, `paidamount`, `pdffilename`,invoicebilleddesc,invoicedisputeemail) 
		 VALUES($company_id, '$companyname', '$invoicenumber', '$invoicecreateddate', '$invoiceduedate', '$totalbiledduration', $totalchargedamount, $invoiceoutstanding, $totalchargedamount, '$invoicefromdate', '$invoicetodate', 0, '$pdffilename','$invoicebilleddesc','$invoicedisputeemail')";
     mysql_query($sqlinv);
	$invmasterid =	 mysql_insert_id();
	$prefixData = $_POST['prefix'];
	$descriptionData = $_POST['description'];
	$Duration_minData = $_POST['Duration_min'];
	$price_per_1_minData =$_POST['price_per_1_min'];
	$Charged_AmountData = $_POST['Charged_Amount'];
	$numberofCallsData =  $_POST['numberofCalls'];
 
	 for($k=0;$k<count($prefixData);$k++){
	 
		 $prefix  =  $prefixData[$k];
		 $Description = $descriptionData[$k];
		 $price_per_1_min =  $price_per_1_minData[$k];
		 $Duration_min =  $Duration_minData[$k];
		 $Charged_Amount =  $Charged_AmountData[$k];
		 $BilledDuration_min =  $Duration_minData[$k];
		 $customerName = '';
		 $numberofCalls = $numberofCallsData[$k]; 
		 $fromDate  = date('Y-m-d',strtotime($fromDate));
		 $toDate  = date('Y-m-d',strtotime($toDate));
		 //print_r($data->sheets[0]['cells'][$i]);
		
		$account_id = $company_id;

		
	
		$sqlchd = "INSERT INTO  wsalesinvoiceschild (invmasterid,customerName,company_id,`account_id`, `prefix`,  `Description`, `price_per_1_min`,  `numberofCalls`, `Duration_min`, `BilledDuration_min`, `Charged_Amount`,fromDate,toDate) 
		  VALUES ($invmasterid,'$customerName',$company_id,$account_id, '$prefix', '$Description', '$price_per_1_min','$numberofCalls', '$Duration_min', '$BilledDuration_min', '$Charged_Amount','$fromDate','$todate')";
		mysql_query($sqlchd);
	 
	 }

	$mydelsql = "TRUNCATE TABLE tempwsaleinvoicedata";
	mysql_query($mydelsql);
	
sleep(5);
header('Location: wholesaleinvoiceslist.php');
exit(0); 
}
?>
  
