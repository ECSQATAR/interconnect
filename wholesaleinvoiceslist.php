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

});

</script> 
 
<div class="container">

<h1>Whole Sales Invoices List </h1>

<form role="form" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<div class="row">

 <div class="col-md-3">

  <label>Wholeseller</label>
 
<select  name="wholeseller_id" required >
<option value="">Select Wholeseller</option>
<?php
  $sql = "SELECT id,accountname FROM accountusers Where accountgroup_id=62";
 $result = mysql_query($sql);
while($row = mysql_fetch_object($result)){
?>
<option value="<?php echo $row->id;?>" <?php if(isset($_GET['wholeseller_id']) && $_GET['wholeseller_id'] == $row->id) echo 'selected=selected';?> ><?php echo $row->accountname;?></option>
<?php  
} 

?> 
</select> 
</div>

  
<div class="col-md-4">
<label>From Date</label> 
<input type="text" id="from_date"   name="from_date"  value="<?php echo $_GET['from_date'];?>" placeholder="Please select from  date" required /> 
</div>

<div class="col-md-5">
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
		   <td>Company Name </td>
		    <td>Invoice No </td>
		    <td>Invoice Date </td>
		    <td>Due Date</td>
			<td>Amount</td>
			<td>Paid Invoice </td>
			<td>&nbsp; </td>
        </tr>
<?php
 $sql = "SELECT * From wsalesinvoicesmaster";
 $result = mysql_query($sql);
 while($rowinv = mysql_fetch_object($result)){
	?>	
	<tr class="border_bottom_payments">
	<td> <?php echo $rowinv->companyname;?></td>			
	<td> <?php echo $rowinv->invoicenumber;?></td>			
	<td> <?php echo $rowinv->invoicecreateddate;?></td>
	<td> <?php echo $rowinv->invoiceduedate;?></td>
	<td> <?php echo $rowinv->invoiceamount;?></td>
	<td> <?php echo $rowinv->paidinvoice;?></td>
	<td>
	<a href="#">Send Pdf</a> <br/>
	 <a href="<?php echo 'generateinvoicepdf.php?id='.$rowinv->id;?>">Generate pdf</a> <br/>
	 <a href="#">Delete Invoice</a>
	 </td>
	
	
	</tr>
<?php
  
	}
?>
 </table>
</div>
</div>
