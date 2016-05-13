<?php
    include_once("dbconfig.php");    

    //get search term
//print_r($_POST);

if(isset($_POST['action']) && $_POST['action'] == 'invoicepayments' ){
	
	$invoice_id = $_POST['invoice_id'];
	$paidamount  = $_POST['paidamount'];
	echo $query = "Update wsalesinvoicesmaster set paidamount = $paidamount WHERE id = $invoice_id ";
	mysql_query($query);
}	
 	

?>
