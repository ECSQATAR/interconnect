<?php
include_once("head.php"); 
//include_once("logincheck.php");
include_once("dbconfig.php");
include_once("headermenu.php");

if(!isset($_GET['items_per_page']))
$_GET['items_per_page'] = 25;
$reseller_id = $_SESSION['reseller_id'];
$usedvoucher = array('0'=>'No','1'=>'Yes');
?>
<div class="container">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
   $(function() {
    $( "#assigned_date" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar.gif",
      buttonImageOnly: true,
      buttonText: "Select assign date",
      dateFormat: "yy-m-d"

    });
	 

  });
  </script>
   
  
<h1> Manage Companies information </h1>

<script>
function checkdelte(id){
//alert(id);
 
if (confirm('Are you sure you want to Remove ?')) {
    window.location.href = "editcompany.php?action=delete&id="+id;
    // Save it!
} 

}
</script>

<form role="form" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="row">
<?php
 if(isset($_SESSION['SuccessMsg'])){
?>
<div class="alert alert-success">
  <strong><?php echo $_SESSION['SuccessMsg']; ?> !</strong> 
</div>
<?php 
unset($_SESSION['SuccessMsg']);
}
?>
</div>
 

 



 

<div class="row"> &nbsp;</div>

<div class="row">
<div class="table-responsive">
   
<?php  
 $_PHP_SELF = $_SERVER['PHP_SELF'];
 
//echo "<pre>"; print_r($_GET); 


$sqlRedeemed= "SELECT id,description FROM accountusers   ORDER BY createddate ";  
$rs_resultRedeemed = mysql_query ($sqlRedeemed);  


$RedeemedusersList = array();
$sql = "SELECT id,description  from accountusers";
$result = mysql_query($sql);
while($row = mysql_fetch_object($result)){
$RedeemedusersList[$row->id] = $row->description;
}


$condition = array();
$condition = " WHERE  1=1 ";
$linkurl = "";

if (isset($_GET['Go'])){
 

	$linkurl  = $linkurl."&Go=submit"; 



	 

	if (strlen(trim($_GET['book_value']))>0 && isset($_GET['book_value'])){
	$book_value = $_GET['book_value'];
	$condition = $condition." AND book_value = $book_value";
	$linkurl  = $linkurl."&book_value=$book_value"; 
	}	

 

	if (strlen(trim($_GET['assigned_date']))>0 && isset($_GET['assigned_date'])){
	$assigned_date = $_GET['assigned_date'];
	$condition = $condition." AND DATE(assigned_date)='$assigned_date' ";
	$linkurl  = $linkurl."&assigned_date=". urlencode($assigned_date);
	}

	if (strlen($_GET['items_per_page'])>0 && isset($_GET['items_per_page'])){
	 $items_per_page = $_GET['items_per_page'];
	 $linkurl  = $linkurl."&items_per_page=".$items_per_page;
	}


	if (strlen($_GET['voucher_status'])>0 && isset($_GET['voucher_status']) ){
		$voucher_status = $_GET['voucher_status'];
		$condition = $condition." AND voucher_status = $voucher_status";
		$linkurl  = $linkurl."&voucher_status=".$voucher_status; 
	}


	if (strlen(trim($_GET['voucher_id']))>0 && isset($_GET['voucher_id'])){
	$voucher_id = $_GET['voucher_id'];
	$condition = $condition." AND voucher_id = $voucher_id ";
	$linkurl  = $linkurl."&&voucher_id=$voucher_id";
	
 	}
 

//echo $linkurl;

}

 if(isset($_GET['items_per_page']) && $_GET['items_per_page']>0 )
	$limit = $_GET['items_per_page']; 
else
	$limit = 25; 


if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  
  
$sql = "SELECT * FROM company $condition ORDER BY createddate DESC LIMIT $start_from, $limit ";  
$rs_result = mysql_query ($sql);  
?>  
<table class="table table-bordered table-striped" border="1">  
<thead>  
<tr>   
<th>S.No</th>
<th>Company Name</th>
<th>Country</th>
<th>Primary contact person</th>
<th>Telephone</th>
<th>Mobile</th>		
<th>Email</th>		
<th>Skype</th>		
<th> &nbsp;</th>
<th> &nbsp; </th>
</tr>   
<thead>  
<tbody>  
<?php  
$k=1;
while ($row = mysql_fetch_object($rs_result)) {  
?>  
<tr>
<td><?php echo $k;?></td>
<td><?php echo $row->nameofcompany;?></td>
<td><?php echo $row->country;?></td>
<td><?php echo $row->primarycontact;?></td>
<td><?php echo $row->telephone;?></td>
<td><?php echo $row->mobile;?></td> 
<td><?php echo $row->email;?></td> 
<td><?php echo $row->skype;?></td> 
<td> <a href="<?php echo 'editcompany.php?action=edit&id='.$row->id;?>"> Edit </a> </td>
 
 <td style="text-align:center">  &nbsp; &nbsp; <image src="remove.png" width="20" height="20" title="Delete" onclick="checkdelte(<?php echo $row->id;?>)"/> </td>






</tr>
 
<?php
$k = $k + 1;
}
?>  
</tbody>  
</table> 

<div class="row">


<div class="col-md-6">

<?php  
  $sql = "SELECT COUNT(id) FROM company $condition ";  
$rs_result = mysql_query($sql);  
$row = mysql_fetch_row($rs_result);  
$total_records = $row[0];  
$total_pages = ceil($total_records / $limit);  
$pagLink = "<nav>  <ul class='pagination'>";  
for ($i=1; $i<=$total_pages; $i++) {  
		if($i==$_GET['page'])
		$scls='active';
		else
		$scls='disabled';
           
			 
		 	 $newlinkurl  = $_PHP_SELF."?page=$i".$linkurl;
  
		 	
			
			 
			 $pagLink .= " <li class='".$scls."'> <a href='".$newlinkurl."'>".$i."</a> </li>";
			 
};  
echo $pagLink . "</ul> </nav>";  

?> 
 
</div>


<div class="col-md-2">
<label>Total Records </label> : <?php echo $total_records; ?> 
</div>
 

<div class="col-md-2">
<label>Items Per Page</label>
<select   name="items_per_page">
<option value="">Select Items per Page</option>
<option value="25" <?php if(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 25) echo 'selected=selected';?> >25</option>
<option value="100" <?php if(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 100) echo 'selected=selected';?> >100</option>
<option value="200" <?php if(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 200) echo 'selected=selected';?> >200</option>
<option value="500" <?php if(isset($_GET['items_per_page']) && $_GET['items_per_page'] == 500) echo 'selected=selected';?> >500</option>
</select> 
</div>

<div class="col-md-2">
<input type="submit" name="Go" value="submit" />
</div>


</div>
</div>
</div> 
</form>
<?php
mysql_close();
?>                    
<body>