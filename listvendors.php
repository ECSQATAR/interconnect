<?php
require_once('xmlrpc.inc');
$msg = new xmlrpcmsg('listVendors', $params);
	$cli = new xmlrpc_client('https://38.130.112.22/xmlapi/xmlapi');
	$cli->setSSLVerifyPeer(false);
	$cli->setSSLVerifyHost(false);
	//$cli->setCredentials('VC','jaihind999', CURLAUTH_DIGEST);
	$cli->setCredentials('Irf','greatlife911', CURLAUTH_DIGEST);  

	$r = $cli->send($msg, 20);       /* 20 seconds timeout */

	 
	if ($r->faultCode()) {
		$error = $r->faultString();
		return array();
	}
	
function object_to_array($obj) {
    if(is_object($obj)) $obj = (array) $obj;
    if(is_array($obj)) {
        $new = array();
        foreach($obj as $key => $val) {
            $new[$key] = object_to_array($val);
        }
    }
    else $new = $obj;
    return $new;       
}
	
	$r =  object_to_array($r);
 // echo "<pre>"; print_r($r['val']['me']['struct']['list']['me']['array']); echo "</pre>";
	
		$objectResult = $r['val']['me']['struct']['list']['me']['array'];
	
	 
 
 
$vendorList = array();

    for($k=0;$k<sizeof($objectResult);$k++){

$vendorList[$k]['i_vendor'] = $objectResult[$k]['me']['struct']['i_vendor']['me']['int'];
$vendorList[$k]['i_contact'] = $objectResult[$k]['me']['struct']['i_vendor']['me']['int'];
$vendorList[$k]['name']	  = $objectResult[$k]['me']['struct']['name']['me']['string'];
$vendorList[$k]['web_login'] = $objectResult[$k]['me']['struct']['web_login']['me']['string'];
$vendorList[$k]['balance'] = round($objectResult[$k]['me']['struct']['balance']['me']['double'],2);

 

	}
//echo "<pre>";print_r($vendorList);echo "</pre>";
?>
<h2>Vendors List</h2>
<table width="100%">
<tr>
<td>name</td>
<td>i_vendor</td>
<td>i_contact</td>
<td>web_login</td>
<td>Balance</td>
</tr>

<?php
for($m=0;$m<sizeof($vendorList);$m++){
?>
<tr>
<td><?php echo $vendorList[$m]['name'];?></td>
<td><?php echo $vendorList[$m]['i_vendor'];?></td>
<td><?php echo $vendorList[$m]['i_contact'];?></td>
<td><?php echo $vendorList[$m]['web_login'];?></td>
<td><?php echo $vendorList[$m]['balance'];?></td>
</tr>
<?php
}
?>
</table>