<?php
require_once('head.php');
session_start();
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

$( ".savecomments" ).click(function(){
	 
	
	var sid = jQuery(this).attr("id");
  	var notes_comments = jQuery('#cdata-'+sid).val();
	 
	var	data = {
		invoice_id:sid,
		invoicecomments:notes_comments,
		action:'invoicecomment'
	};
	//alert(data);
	
	 $.post("updateconsumptioncomments.php", data, function(resp){
        alert('Your comment updated.');
		jQuery('#mastercmnt'+sid).text(notes_comments);
		jQuery('#mastercmnt'+sid).show('slow');
		jQuery("#childcmnt"+sid).hide('slow');
    });
	
 });


  $( ".showcomments" ).click(function(){
		
		var masterdivid = jQuery(this).attr("id");
		var k = masterdivid.split('mastercmnt')	;
		//alert(k);
		 
		jQuery('#'+masterdivid).hide('slow');
		jQuery("#childcmnt"+k[1]).show('slow');
		  
 });
  



$( ".savepayments" ).click(function(){
	 
	
	var sid = jQuery(this).attr("id");
  	var paidamount = jQuery('#pdata-'+sid).val();
	 
	var	data = {
		invoice_id:sid,
		paidamount:paidamount,
		action:'invoicepayments'
	};
	//alert(data);
	
	 $.post("updateconsumptionpayments.php", data, function(resp){
        alert('Your payment updated.');
		jQuery('#masterpmnt'+sid).text(paidamount);
		jQuery('#masterpmnt'+sid).show('slow');
		jQuery("#childpmnt"+sid).hide('slow');
    });
	
 });


  $( ".showpayments" ).click(function(){
		
		var masterdivid = jQuery(this).attr("id");
		var k = masterdivid.split('masterpmnt')	;
		//alert(k);
		 
		jQuery('#'+masterdivid).hide('slow');
		jQuery("#childpmnt"+k[1]).show('slow');
		  
 });
	 
});

 
function checkdelte(id){
//alert(id);
 
if (confirm('Are you sure you want to Remove ?')) {
    window.location.href = "wholesalesconsumptionlist.php?action=delete&id="+id;
    // Save it!
} 

}


function lockinvoice(id){
//alert(id);
 
if (confirm('Are you sure you want to Lock the invoice ?')) {
    window.location.href = "wholesalesconsumptionlist.php?action=lock&id="+id;
    // Save it!
} 

}


</script>

<div class="container">

<?php

if(isset($_GET['action']) && $_GET['action']=='delete'){
//print_r($_GET);
	$id = $_GET['id'];
	$sqldelete = "DELETE from wsalesconsumptionmaster where id=$id";
	mysql_query($sqldelete); 
	//header("Location:prefixmasterlist.php");
	//exit(0);
}


if(isset($_GET['action']) && $_GET['action']=='lock'){
//print_r($_GET);
	$id = $_GET['id'];
	$sqldelete = "update wsalesconsumptionmaster set lockedinvoice=1  where id=$id";
	mysql_query($sqldelete); 
	//header("Location:prefixmasterlist.php");
	//exit(0);
}

?>



<form role="form" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<div class="row">
  <div class="col-md-3"> &nbsp; </div>  <a href="index.php"> Back to home Page </a> &nbsp; 
|| <a href="prefixmasterlist.php"> Manage Prefix </a> || <a href="companieslist.php"> Manage Company</a> || <a href="addcompany.php"> Add Company </a>
</div>

<div class="row">
<div class="col-md-3">
 Invoices -> 
</div> 
 <a href="wholesaleinvoiceslist.php"> Manage Invoices. </a>  || &nbsp;&nbsp; <a href="importinvoicesdata.php"> Import invoices data </a>
</div>



<div class="row">
<div class="col-md-3">
Consumption Reports ->
</div>
 <a href="wholesalesconsumptionlist.php"> Manage  Consumption Reports </a> || &nbsp;&nbsp; <a href="importconsumeddata.php"> Import Consumption data </a>
</div>

<div class="row">
&nbsp; <br/>
</div>



<h1> Consumption Reports </h1>

<div class="row">

<div class="col-md-3">

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
  
  
<div class="col-md-3">
<label>From Date</label> 
<input type="text" id="from_date"   name="from_date"  value="<?php echo $_GET['from_date'];?>" placeholder="Please select from  date" required /> 
</div>

<div class="col-md-3">
<label>To Date</label> 
<input type="text" id="to_date" name="to_date"  value="<?php echo $_GET['to_date'];?>" placeholder="Please select to  date" required /> 

<input type="submit" name="Go" value="submit" />
</div>



 
</div>



</form>

<div class="row">
&nbsp;
</div>

<div class="row">
 <table  class="table"  border="0" bgcolor="#dbeefc" cellpadding="5">
		  <tr align="center" class="bg_head_payments white font_18">
		<td>S.No.</td>
		   <td>Company Name </td>
		    <td> GMT </td>
		    <td>Invoice No </td>
		    <td>Invoice Date </td>
		    <td>From Date</td>
		    <td>To Date</td>
			<td>Amount</td>
			<td>Paid Amount </td>
			<td> Comments </td>
			<td width="10%">&nbsp; </td>
        </tr>
<?php
$sumtotalinv = 0;
 $sql = "SELECT * From  wsalesconsumptionmaster ";
 $result = mysql_query($sql);
$sno = 0;
 while($rowinv = mysql_fetch_object($result)){
$sno = $sno+1;

	?>	
	<tr class="border_bottom_payments">
	<td> <?php echo $sno;?></td>

	<td> <?php echo $rowinv->companyname;?></td>
	<td> <?php echo 'GMT+0'; ?>			
	<td> <?php echo $rowinv->invoicenumber;?></td>			
	<td> <?php echo $rowinv->invoicecreateddate;?></td>
	<td> <?php echo $rowinv->invoicefromdate;?></td>
	<td> <?php echo $rowinv->invoicetodate;?></td>
	<td> <?php echo $rowinv->invoiceamount;?>$</td>
	<td>

<span title='Click here to update your payments' class="showpayments" id="<?php echo 'masterpmnt'.$rowinv->id; ?>" > <?php if( $rowinv->paidamount == 0) echo 'Add your payments here.'; else  echo $rowinv->paidamount;?> </span>
<span id="<?php echo 'childpmnt'.$rowinv->id; ?>" style='display:none'> 
<input type='text' value="<?php echo $rowinv->paidamount;?>" class="form-control"   id="pdata-<?php echo $rowinv->id;?>" >    <img src ="make_comment.png"  class="savepayments"  id="<?php echo $rowinv->id;?>"   title="Save Payments" /> 
</span>
</td>
 	

<td>

<span title='Click here to update your comments' class="showcomments" id="<?php echo 'mastercmnt'.$rowinv->id; ?>" > <?php if( strlen(trim($rowinv->invoicecomments)) == 0) echo 'Add your comments here.'; else  echo $rowinv->invoicecomments;?> </span>
<span id="<?php echo 'childcmnt'.$rowinv->id; ?>" style='display:none'> 
<input type='text' value="<?php echo $rowinv->invoicecomments;?>" class="form-control"   id="cdata-<?php echo $rowinv->id;?>" >    <img src ="make_comment.png"  class="savecomments"  id="<?php echo $rowinv->id;?>"   title="Save Comments" /> 
</span>
</td>
 	


	<td>
	 <a href="<?php echo 'generateconsumptionpdf.php?id='.$rowinv->id;?>"> <img src="geneatepdf.png" width="20" height="20"   title="Generater Pdf"/> </a> &nbsp;

<?php if($rowinv->lockedinvoice==0){?>
   &nbsp; &nbsp; <image src="remove.png" width="20" height="20" title="Delete" onclick="checkdelte(<?php echo $rowinv->id;?>)"/>
 <image src="invoice_lock.png" width="20" height="20" title="locke your invoice" onclick="lockinvoice(<?php echo $rowinv->id;?>)"/>  
<?php } ?>

		



	 </td>
	
	
	</tr>
<?php
	$sumpaidAmount = $sumpaidAmount +  $rowinv->paidamount;
	$sumtotalinv = $sumtotalinv +  $rowinv->invoiceamount;
	}

$balanceAmount =  $sumtotalinv - $sumpaidAmount;
?>

<tr>
<td>&nbsp; </td><td>&nbsp; </td> <td>&nbsp; </td> <td>&nbsp; </td><td>&nbsp; </td> <td>&nbsp; </td> 
<td>&nbsp;<b>Total</b> :  </td>  <td> <?php echo $sumtotalinv; ?>$ </td> <td><?php echo $sumpaidAmount; ?>$ </td> 
<td> Balance Amount : <?php echo $balanceAmount; ?>$ </td> <td>&nbsp; </td>  <td>&nbsp;</td>
</tr>

 </table>
</div>
</div>
