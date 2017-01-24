<?php

function fortumomobile_config() {
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value" => "Fortumo Mobile Payment"),
     "serviceId" => array("FriendlyName" => "Service ID", "Type" => "text", "Size" => "20", ),
     "secretKey" => array("FriendlyName" => "Secret", "Type" => "text", "Size" => "20", ),
     "sandBox" => array("FriendlyName" => "Test Mode", "Type" => "yesno", "Description" => "Enable sandbox", ),
    );
	return $configarray;
}

function fortumomobile_link($params) {

	# Gateway Specific Variables
	$gatewaymerchantid = $params['merchantid'];
	$gatewayaccesskey = $params['accesskey'];
	$gatewaysecretkey = $params['secretkey'];
	$gatewayclientid = $params['clientid'];
	$gatewayallowedreturnurl = $params['allowedreturnurl'];
	$gatewaytestmode = $params['testmode'];
	$gatewayendpointurl = $params['endpointurl'];

	# Invoice Variables
	$invoiceid = $params['invoiceid'];
	$description = $params["description"];
    $amount = $params['amount']; # Format: ##.##
    $currency = $params['currency']; # Currency Code

	# Client Variables
	$firstname = $params['clientdetails']['firstname'];
	$lastname = $params['clientdetails']['lastname'];
	$email = $params['clientdetails']['email'];
	$address1 = $params['clientdetails']['address1'];
	$address2 = $params['clientdetails']['address2'];
	$city = $params['clientdetails']['city'];
	$state = $params['clientdetails']['state'];
	$postcode = $params['clientdetails']['postcode'];
	$country = $params['clientdetails']['country'];
	$phone = $params['clientdetails']['phonenumber'];

	# System Variables
	$companyname = $params['companyname'];
	$systemurl = $params['systemurl'];
	$currency = $params['currency'];
	
	switch ($country) {
		case 'US':
			echo 'United States';
		break;
		
		case 'SG':
			echo 'Singapore';
		break;
		
		case 'PH':
			$code = '<div style="font-weight: bold; font-family: arial;text-decoration: underline; text-decoration-color: red; color: maroon;">TEXT PAYMENT '.$invoiceid.' SEND TO 2910</div>';
		break;
		
		default:
			echo 'Invalid Country!';
		break;
	}
	
  return $code;
}




	try {
		$gatewayVariables = getGatewayVariables('fortumomobile');
		$varPresent = 1;	
	} catch (\Exception $e) {
	
	}

?>
