<?php
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
<?php
//require_once('xmlrpc.inc');
require_once('head.php');
session_start();
 


  
 

//require_once('header_logged_in.php'); ?>
  
 <div class="container">

<h1>Invoice History </h1>


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
		   <td width="160">customerName </td>
		    <td width="160">Prefix </td>
		   <td width="160">customerName </td>
		    <td width="160">country </td>
		    <td width="160">Description </td>
		    <td width="100">price_per_1_min</td
			<td width="100">price_per_n_min</td>
			<td width="100">Charged Amount</td>
            <td width="200">from Date </td>
            <td width="100">to Date</td>
        </tr>
<?php

	
	if( isset($_GET['wholeseller_id']) && isset($_GET['from_date'])  && isset($_GET['to_date'])  ){
			$wholeseller_id = $_GET['wholeseller_id'];
			$fromDate = $_GET['from_date'];
			$todate = $_GET['to_date'];
			$sql = "SELECT * from wholesaleinvoicebasedata where account_id = $wholeseller_id AND  date(fromdate) = $fromDate adn date(todate) = $todate  ";
	} else {
			$sql = "SELECT * from wholesaleinvoicebasedata ";
	}
	 
 
 $result = mysql_query($sql);
while($row = mysql_fetch_object($result)){
	//print_r($row);
	 ?>	
	<tr class="border_bottom_payments">
  	<td> <?php echo $row->CustomerName;?></td>		
	<td> <?php echo $row->prefix;?></td>			
	<td> <?php echo $row->CustomerName;?></td>			
	<td> <?php echo $row->country;?></td>
	<td> <?php echo $row->Description;?></td>
	<td> <?php echo $row->price_per_1_min;?></td>
	<td> <?php echo $row->price_per_n_min;?></td>
	<td> <?php echo $row->Charged_Amount;?></td>
	<td> <?php echo $row->fromdate;?></td>
	<td> <?php echo $row->todate;?></td>
	<td> <input type="submit" value="Generate Payment request" name="generatepaymentrequest" /> </td> 
	</tr>
<?php
	}
?>
 <tr> <td colspan='6'> <input type="submit" value="Generate Payment request" name="generatepaymentrequest" /> </td> </tr>
 

 </table>
</div>
</div>
