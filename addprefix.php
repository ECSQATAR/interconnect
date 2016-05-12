<?php
include_once("head.php"); 
//include_once("logincheck.php");
 

if(isset($_GET['action']) && $_GET['action']=='delete'){
//print_r($_GET);
	$id = $_GET['id'];
	$sqldelete = "DELETE from prefixmaster where id=$id";
	mysql_query($sqldelete); 
	header("Location:prefixmasterlist.php");
	exit(0);
}	
?>
<link href="css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="js/bootstrap-toggle.min.js"></script>
 
<script type="text/javascript" src="jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.timepicker.css" />
 
  
<div class="container">
 
       <form method="post"  role="form"   action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" >
 
  <div class="panel-group">
 
    <div class="panel panel-primary">
      <div class="panel-heading">Prefix information  </div>
      <div class="panel-body">

	  
	  <div class="row">
	 
	  <label>Company Name</label>
	 
	<select  name="company_id"  class="form-control" required >
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
 

		<div class="row">
		

      		     <label>     Prefix  </label>   
					<input name="prefix"  class="form-control"   type="text"   placeholder="Enter prefix"  required> 
	      </div>
			<div class="row">
			 <label>     Description  </label>   
				<input name="description"  class="form-control"   type="text"   placeholder="Enter description"  required> 
			</div>
			
			<div class="row">
				<button type="submit" name="submit" class="btn btn-primary">Save</button>
			</div>				
			  
	 </div> </div> <!-- panel close -->
 </div>

 </form>


<?php
 
   

function savedb(){
/// echo "<pre>";	print_r($_POST);
	 $company_id = $_POST['company_id'];
	 $prefix  = mysql_escape_string( $_POST['prefix']);
	 $description  = mysql_escape_string($_POST['description']);
	
      $sqlupdt =  "INSERT INTO prefixmaster (company_id,prefix,description) VALUES ($company_id,$prefix, '$description')";

		 mysql_query($sqlupdt); 
		sleep(5);
	 	mysql_close();
	 	 header("Location:prefixmasterlist.php");
	 	exit(0);
	
  
}
if(isset($_POST['submit']))
	savedb();

?>


</div>

<body>
<html>