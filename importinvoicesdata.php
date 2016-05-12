<?php
session_start();
require_once('head.php');
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
    jQuery(document).ready(function () {

     $( "#from_date").datepicker({
      showOn: "button",
      buttonImage: "images/calendar.gif",
      buttonImageOnly: true,
      buttonText: "Select assign date",
      dateFormat: "yy-m-d"

    });
		
    $( "#to_date" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar.gif",
      buttonImageOnly: true,
      buttonText: "Select assign date",
      dateFormat: "yy-m-d"

    });

});

</script> 
<?php
//require_once('xmlrpc.inc');

  
 

//require_once('header_logged_in.php');
 ?>
  
 <div class="container">

<h1>Import invoice History </h1>


<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<div class="row">

 
<div class="form-group">
  <label>Company Name</label>
 
<select  name="company_id" required >
<option value="">Select Company</option>
<?php
  $sql = "SELECT id,nameofcompany FROM company";
 $result = mysql_query($sql);
while($row = mysql_fetch_object($result)){
?>
<option value="<?php echo $row->id;?>" <?php if(isset($_GET['company_id']) && $_GET['company_id'] == $row->id) echo 'selected=selected';?> > 
<?php echo $row->nameofcompany;?></option>
<?php  
} 

?> 
</select> 
</div>
  
  

<div class="form-group">
<label>From Date</label> 
<input type="text" id="from_date"   name="from_date"  value="<?php echo $_POST['from_date'];?>" placeholder="Please select from  date"  required  /> 
</div>


<div class="form-group">
<label>To Date</label> 
<input type="text" id="to_date" name="to_date"  value="<?php echo $_POST['to_date'];?>" placeholder="Please select to  date" required  /> 
</div>

 
 
</div>

<div class="form-group">
<textarea name="invdata" class="form-control" rows='15' required> </textarea>
</div>

<div class="form-group">
	<button type="submit" name="submit" value="submit" class="btn btn-info">Submit</button>
 </div>
 

</form>

<div class="row">
&nbsp;
</div>


<?php
 //print_r($_POST);
// print_r($_FILES);
if(isset($_POST['submit'])){
	
	
	if( isset($_POST['from_date']) && strlen($_POST['from_date'])>0 )
	{	
		$fromDate = $_POST['from_date'];
		$Condition  = $Condition." AND date(fromdate) = '$fromDate' ";
	}

	if( isset($_POST['to_date']) && strlen($_POST['to_date'])>0 )
	{	
		$todate = $_POST['to_date'];
		$Condition  = $Condition." AND date(todate) = '$todate' ";
	}

 		
	$company_id = $_POST['company_id'];	
	$invData = ltrim($_POST['invdata']);
	$invData = preg_replace("/\n/", "|", $invData);
	//echo "<pre>";print_r($invData);echo "</pre>";
	$invChildData = explode("|",$invData);
	//echo "<pre>";print_r($invChildData);echo "</pre>";
	
	
	for($k=0;$k<=count($invChildData);$k++){
	
		$explodeInvData = preg_split('/[\s]+/', $invChildData[$k]);
	
//	echo "<pre>";print_r($explodeInvData); echo "</pre>"; 


		 $customerName = $explodeInvData[0] .  $explodeInvData[1];
		 $prefix  =  $explodeInvData[2];
		 $country = $explodeInvData[3]; 
		 $Description = $explodeInvData[4];
		 $price_per_1_min =  $explodeInvData[5];
		 $price_per_n_min =   $explodeInvData[6];
		 $numberofCalls =  $explodeInvData[7];
		 $Duration_min =  $explodeInvData[8];
		 $BilledDuration_min =  $explodeInvData[9];
		 $Charged_Amount =  $explodeInvData[10];

//print_r($data->sheets[0]['cells'][$i]);
		 $account_id=$company_id;
		$_SESSION['company_id'] = $company_id;
		$sql = "INSERT INTO  tempwsaleinvoicedata(customerName,	company_id,`account_id`, `prefix`, `country`, `Description`, `price_per_1_min`, `price_per_n_min`, `numberofCalls`, `Duration_min`, `BilledDuration_min`, `Charged_Amount`,fromDate,toDate) 
		  VALUES ('$customerName',$company_id,$account_id, '$prefix', '$country', '$Description', '$price_per_1_min','$price_per_n_min','$numberofCalls', '$Duration_min', '$BilledDuration_min', '$Charged_Amount','$fromDate','$todate')";
	 
	
	//echo "<br>".$sql;

	mysql_query($sql);

 

}

	//unlink('uploads/invoices.xls');
	header("Location: showimportinvoicesdata.php");	exit(0);
}



	
?>


</div>
</div>
