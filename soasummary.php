<?php
require_once('head.php');



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

 

session_start();
echo $sql = "SELECT * From wsalesinvoicesmaster  $condition";
$result = mysql_query($sql);
$sno = 0;
$invoiceList=array();
while($rowinv = mysql_fetch_object($result)){
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoicenumber'] = $rowinv->invoicenumber;
	$invoiceList[$rowinv->invoicecreateddate]['a']['description'] = $rowinv->invoicefromdate.'-'.$rowinv->invoicetodate;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoiceamount'] = $rowinv->invoiceamount;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoiceTotalminutes'] = $rowinv->invoiceTotalminutes;


    //$invoiceList[$rowinv->invoicecreateddate]['paiddate'] = .$rowinv->paiddate;
	//$invoiceList[$rowinv->invoicecreateddate]['paidamount'] = .$rowinv->paidamount;
}

echo $sql = "SELECT * From wsalesconsumptionmaster  $condition";
$result = mysql_query($sql);
$sno = 0;

while($rowinv = mysql_fetch_object($result)){
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoicenumber'] = $rowinv->invoicenumber;
	$invoiceList[$rowinv->invoicecreateddate]['b']['description'] = $rowinv->invoicefromdate.'-'.$rowinv->invoicetodate;
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoiceamount'] = $rowinv->invoiceamount;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoiceTotalminutes'] = $rowinv->invoiceTotalminutes;

    //$invoiceList[$rowinv->invoicecreateddate]['paiddate'] = .$rowinv->paiddate;
	//$invoiceList[$rowinv->invoicecreateddate]['paidamount'] = .$rowinv->paidamount;
}

//echo "<pre>"; print_r($invoiceList); echo "</pre>";

 
?>


<?php
include_once("headermenu.php");
?>
<h1> SOA Summary </h1>

<form role="form" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
 
<div class="container">
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
<input type="text" id="from_date"   name="from_date"  value="<?php echo $_GET['from_date'];?>" placeholder="Please select from  date" /> 
</div>

<div class="col-md-3">
<label>To Date</label> 
<input type="text" id="to_date" name="to_date"  value="<?php echo $_GET['to_date'];?>" placeholder="Please select to  date" /> 

<input type="submit" name="Go" value="submit" />
</div>

</form>


<table border=1 width="100%">
<tr style="text-align:center">
<td> &nbsp; </td> <td colspan="4"> ECS (Our company) </td> <td colspan="4"> SyncSound </td>
</tr>
<tr>
<td>Date</td>
<td>Description/Notes </td>
<td>Minutes Consumed</td>
<td>Invoiced Amount</td>
<td>Paid By COMPANY</td>
<td>Description/Notes </td>
<td>Minutes Consumed</td>
<td>Invoiced Amount</td>
<td>Paid By COMPANY</td>
</tr>
<?php
foreach ($invoiceList as $key => $rowinv) {
?>
<tr>
<td> <?php echo  $key;?></td>
<td> <?php echo  $invoiceList[$key]['a']['description'];?></td>
<td> <?php echo  $invoiceList[$key]['a']['invoiceTotalminutes'];?></td>	
<td> <?php echo  $invoiceList[$key]['a']['invoiceamount'];?></td>	
<td>&nbsp;</td>
<td> <?php echo  $invoiceList[$key]['b']['description'];?></td>
<td> <?php echo  $invoiceList[$key]['a']['invoiceTotalminutes'];?></td>	
<td> <?php echo  $invoiceList[$key]['b']['invoiceamount'];?></td>	
</tr>
<?php
}
?>
</table>
</div>
</div>
