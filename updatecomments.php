<?php
    include_once("dbconfig.php");    

    //get search term
//print_r($_POST);

if(isset($_POST['action']) && $_POST['action'] == 'invoicecomment' ){
	
	$invoice_id = $_POST['invoice_id'];
	$invoicecomments  = $_POST['invoicecomments'];
	$query = "Update wsalesinvoicesmaster set invoicecomments = '$invoicecomments' WHERE id = $invoice_id ";
	mysql_query($query);
}	
 	

?>
