<?php
session_start();

# Required File Includes
if (file_exists("../../../dbconnect.php")){
	include("../../../dbconnect.php");
} else {
	include("../../../init.php");
}

include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

$gatewaymodule = "fortumomobile";
$gatewayVariables = getGatewayVariables($gatewaymodule);

$fortumoIPaddress = array("54.72.6.27",
                          "54.72.6.17",
                          "54.72.6.23",
                          "79.125.125.1",
                          "79.125.5.95",
                          "79.125.5.205"					  
						  );

if (!in_array($_SERVER['REMOTE_ADDR'], $fortumoIPaddress)){
	header("HTTP/1.0 403 Forbidden");
	die("Error: Access denied!");
}

if (!empty($_GET['sig']) && !check_signature($_GET, $gatewayVariables['secretKey'])) {
		file_put_contents("server_response.txt", $_GET['message']);
		$invoiceid = $_GET['message'];
		$amount = $_GET['price'];
		$fee = 0;
		$transid = $_GET['message_id'];

	 if(preg_match("/OK/i", $_GET['status']) || (preg_match("/MO/i", $_GET['billing_type']) && preg_match("/pending/i", $_GET['status']))) {
		$status = "Completed";
  }
}

 function check_signature($params_array, $secret) {
    ksort($params_array);
 
    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);
 
    return ($params_array['sig'] == $signature);
  } 

$invoiceid = checkCbInvoiceID($invoiceid,$gatewayVariables["name"]); # Checks invoice ID is a valid invoice number or ends processing

checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

if ($status=="Completed") {
    # Successful
    addInvoicePayment($invoiceid,$transid,$amount,$fee,$gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
	logTransaction($GATEWAY["name"],$responseCapture,"Successful"); # Save to Gateway Log: name, data array, status
	echo "Your payment of ".$amount." for invoice# ".$invoiceid." is received. Thank you!";
} else {
	# Unsuccessful
    logTransaction($GATEWAY["name"],$responseCapture,"Unsuccessful"); # Save to Gateway Log: name, data array, status
	echo "Error: Unable to process payment!";
 }

session_destroy();
?>