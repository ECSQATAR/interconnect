<?php
require_once('xmlrpc.inc');
mysql_connect('localhost', 'root','5510331');
mysql_select_db('sippydatabase');

$sql = "SELECT id,api_access,api_password FROM sippyreseller where id=44 ";
$result = mysql_query($sql);
$resellersdata = array();
while($row = mysql_fetch_object($result)){
$resellersdata[$row->id] = $row;
}
//echo "<pre>";print_r($resellersdata);echo "</pre>";

$accountusers = array();
 $query = "SELECT id,reseller_id FROM accountusers";
 $resultacc =  mysql_query($query);
 while ($rowacc = mysql_fetch_assoc($resultacc)){
	//print_r($rowacc);
	  $childAccObject = array();
	  
	  $childAccObject['id'] = $rowacc['id'];
	  $childAccObject['reseller_id'] = $rowacc['reseller_id'];
	  $childAccObject['api_access'] = $resellersdata[$rowacc['reseller_id']]->api_access;
	  $childAccObject['api_password'] = $resellersdata[$rowacc['reseller_id']]->api_password;
	   
	 $accountusers[$rowacc['id']] = $childAccObject; 
 }

 //echo "<pre>";print_r($accountusers);echo "</pre>";
	 
  foreach($accountusers as $account){
/************************/
	
$i_account =  $account['id'];
$api_access = $account['api_access'];
$api_password = $account['api_password'];

$params = array(new xmlrpcval(array("i_account"   => new xmlrpcval($i_account, "int"),
									 "i_media_relay_type" => new xmlrpcval("2","int"),
									),'struct'));
																			
$msg = new xmlrpcmsg('updateAccount', $params);
$cli = new xmlrpc_client('https://38.130.112.22/xmlapi/xmlapi');
$cli->setSSLVerifyPeer(false);
$cli->setSSLVerifyHost(false);
$cli->setCredentials($api_access,$api_password, CURLAUTH_DIGEST);
//$cli->setCredentials('Irf','greatlife911', CURLAUTH_DIGEST);
$r = $cli->send($msg, 20);       
sleep(5);
echo "<pre>"; print_r($r);echo "</pre>"; 
if ($r->faultCode()) {
	$error = $r->faultString();
	return array();
}

/**********************/

}
?>
