<?php
require_once('head.php');
session_start();
$sql = "SELECT * From wsalesinvoicesmaster";
$result = mysql_query($sql);
$sno = 0;
$invoiceList=array();
while($rowinv = mysql_fetch_object($result)){
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoicenumber'] = $rowinv->invoicenumber;
	$invoiceList[$rowinv->invoicecreateddate]['a']['description'] = $rowinv->invoicefromdate.'-'.$rowinv->invoicetodate;
	$invoiceList[$rowinv->invoicecreateddate]['a']['invoiceamount'] = $rowinv->invoiceamount;
    //$invoiceList[$rowinv->invoicecreateddate]['paiddate'] = .$rowinv->paiddate;
	//$invoiceList[$rowinv->invoicecreateddate]['paidamount'] = .$rowinv->paidamount;
}

$sql = "SELECT * From wsalesconsumptionmaster";
$result = mysql_query($sql);
$sno = 0;

while($rowinv = mysql_fetch_object($result)){
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoicecreateddate'] = $rowinv->invoicecreateddate;
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoicenumber'] = $rowinv->invoicenumber;
	$invoiceList[$rowinv->invoicecreateddate]['b']['description'] = $rowinv->invoicefromdate.'-'.$rowinv->invoicetodate;
	$invoiceList[$rowinv->invoicecreateddate]['b']['invoiceamount'] = $rowinv->invoiceamount;
    //$invoiceList[$rowinv->invoicecreateddate]['paiddate'] = .$rowinv->paiddate;
	//$invoiceList[$rowinv->invoicecreateddate]['paidamount'] = .$rowinv->paidamount;
}

//echo "<pre>"; print_r($invoiceList); echo "</pre>";

 
?>
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
<td>&nbsp;</td>
<td> <?php echo  $invoiceList[$key]['a']['invoiceamount'];?></td>	
<td>&nbsp;</td>
<td> <?php echo  $invoiceList[$key]['b']['description'];?></td>
<td>&nbsp;</td>
<td> <?php echo  $invoiceList[$key]['b']['invoiceamount'];?></td>	
</tr>
<?php
}
?>
</table>

