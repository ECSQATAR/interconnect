<?php
include_once("dbconfig.php");    
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbName);

    //get search term
//print_r($_POST);

if(isset($_POST['action']) && $_POST['action'] == 'invoicepayments' ){
	
	$invoice_id = $_POST['invoice_id'];
	$paidamount  = $_POST['paidamount'];
	$query = "Update ws_goodservice_invoice_master set paidamount = $paidamount WHERE id = $invoice_id ";
	mysql_query($query);
}	
 


if(isset($_POST['action']) && $_POST['action'] == 'invoicepaymentdate' ){
	
	$invoice_id = $_POST['invoice_id'];
	$paiddate  = $_POST['paiddate'];
	$query = "Update ws_goodservice_invoice_master set paiddate = '$paiddate'  WHERE id = $invoice_id ";
	mysql_query($query);
}	
   

?>
