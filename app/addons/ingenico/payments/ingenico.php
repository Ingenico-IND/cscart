<?php
use Tygh\Registry;

//require_once ('ingenico/IngenicoPayment.php');
// Preventing direct access to the script, because it must be included by the "include" directive.
defined('BOOTSTRAP') or die('Access denied');

// Here are two different contexts for running the script.
if (defined('PAYMENT_NOTIFICATION'))
{
 	if ($mode == 'return') 
 	{        
        if (isset($_POST['msg']) === true)
        {	
        	$ingenicoResponse = explode('|', $_POST['msg']);

        	$orderDate = explode(" ", trim($ingenicoResponse[8]));
        	$newOrderDate = date("Y-m-d", strtotime($orderDate[0]));

        	$merchantOrderId = $_SESSION['merchant_order_id'];
        	$strCurDate = date('d-m-Y');

        	$orderlist = db_get_array('SELECT * FROM ?:order_ingenico WHERE order_id = ?i', $merchantOrderId);

        	if(empty($orderlist)){
	        	$insertdata = array (
				    'order_id' => $merchantOrderId,
				    'user_id' => $_SESSION['auth']['user_id'],
				    'total' => $_SESSION['cart']['total'],
				    'merchant_identifier' => $ingenicoResponse[3],
				    'tpsl_identifier' => $ingenicoResponse[5],
				    'mandate_no' => $ingenicoResponse[13],
				    'response' => serialize($ingenicoResponse),
				    'status' => $ingenicoResponse[1],
				    'dateAdded' => $newOrderDate
				);
				db_query('INSERT INTO ?:order_ingenico ?e', $insertdata);
			}
			else{
				$updatedata = array (
				    'user_id' => $_SESSION['auth']['user_id'],
				    'total' => $_SESSION['cart']['total'],
				    'merchant_identifier' => $ingenicoResponse[3],
				    'tpsl_identifier' => $ingenicoResponse[5],
				    'mandate_no' => $ingenicoResponse[13],
				    'response' => serialize($ingenicoResponse),
				    'status' => $ingenicoResponse[1],
				    'dateAdded' => $newOrderDate
				);
				$order_id = $merchantOrderId;
				db_query('UPDATE ?:order_ingenico SET ?u WHERE order_id = ?i', $updatedata, $order_id);
			}

        	if($ingenicoResponse[0] == '0300')
        	{
        		$arr_req = array(
		            "merchant" => [
		                "identifier" => $_SESSION['cart']['payment_method_data']['processor_params']['merchant_id']
		            ],
		            "transaction" => [ "deviceIdentifier" => "S","currency" => $_SESSION['settings']['secondary_currencyC']['value'],"dateTime" => $strCurDate,"token" => $ingenicoResponse[5],"requestType" => "S"]
		        );
		        $finalJsonReq = json_encode($arr_req);

	        	$apiURL = "https://www.paynimo.com/api/paynimoV2.req";

	        	$curl = curl_init();
	        	curl_setopt($curl, CURLOPT_POST, 1);
	        	curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
	        	curl_setopt($curl, CURLOPT_URL, $apiURL);
	           	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	              	'Content-Type: application/json',
	           	));
	           	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	           	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	           	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	           	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

	           	$result = curl_exec($curl);
	           	if(!$result){die("Connection Failure !! Try after some time.");}
	           	curl_close($curl);

	           	$dualVerifyData = json_decode($result, true);

        		if ($dualVerifyData['paymentMethod']['paymentTransaction']['statusCode'] == '0300') {
        			$pp_response['order_status'] = 'P';
	            	$pp_response['reason_text'] = 'Payment Successful $ Dual-Verified. You can check the payment status using Payment ID on Admin Dashboard.';
        		} else {
        			$pp_response['order_status'] = 'C';
	            	$pp_response['reason_text'] = 'Payment Successful but not yet verified. You can check the payment status using Payment ID on Admin Dashboard.';
        		}
        	}
        	elseif ($ingenicoResponse[0] == '0398')
        	{
	            $pp_response['order_status'] = 'O';
	            $pp_response['reason_text'] = 'Payment is initiated for your order. You can check the payment status using Payment ID on Admin Dashboard.';
        	}
        	else
        	{
	            $pp_response['order_status'] = 'F';
	            $pp_response['reason_text'] = 'Payment Failed. You can check the payment status using Payment ID on Admin Dashboard.';
        	}

            $pp_response['transaction_id'] = $merchantOrderId;
            $pp_response['client_id'] = $ingenicoResponse[5];

            fn_finish_payment($merchantOrderId, $pp_response);

            fn_order_placement_routines('route', $merchantOrderId);
            exit;
        }
        else {         
            fn_set_notification('E', __('error'), __('text_fp_cancelled'));
            fn_order_placement_routines('checkout_redirect');
        } 
    }
} 
else
{
	$_SESSION['merchant_order_id'] = $order_id;

	$url = fn_url("payment_notification.return?payment=ingenico", AREA, 'current');

	$cID = 'c'.$_SESSION['auth']['user_id'];

	$mNo = str_replace( array( '+', '(', ')', '-' ), '', strval($_REQUEST['user_data']['phone']));

	$mdata= [
		'mrctCode' => $_SESSION['cart']['payment_method_data']['processor_params']['merchant_id'],
		'txn_id' => rand(1,100000000),
		'amount' =>  ($_SESSION['cart']['payment_method_data']['processor_params']['test_mode'] == "TEST") ? 1 : $_SESSION['cart']['total'],
		'scheme' => $_SESSION['cart']['payment_method_data']['processor_params']['merchant_scheme_code'],
		'custID' => $_SESSION['auth']['user_id'],
		'mobNo' => $mNo,
		'email' => $_REQUEST['user_data']['email'],
		'name' => $_REQUEST['user_data']['fullname'],
		'currency' => $_SESSION['cart']['payment_method_data']['processor_params']['currency'],
		'SALT' => $_SESSION['cart']['payment_method_data']['processor_params']['salt'],
		'returnUrl' => $url,
		'accNo' => '',
		'debitStartDate' => '',
		'debitEndDate' => '',
		'maxAmount' => '',
		'amountType' => '',
		'frequency' => '',
		'cardNumber' => '',
		'expMonth' => '',
		'expYear' => '',
		'cvvCode' => ''
	];
	
//fn_print_r($mdata);

    $val = $mdata;

    $datastring = $val['mrctCode']."|".$val['txn_id']."|".$val['amount']."|".$val['accNo']."|".$val['custID']."|".$val['mobNo']."|".$val['email']."|".$val['debitStartDate']."|".$val['debitEndDate']."|".$val['maxAmount']."|".$val['amountType']."|".$val['frequency']."|".$val['cardNumber']."|".$val['expMonth']."|".$val['expYear']."|".$val['cvvCode']."|".$val['SALT'];

//fn_print_r($datastring);
	$hashed = hash('sha512',$datastring);

//fn_print_r($hashed);
	$data=array("hash"=>$hashed,"data"=>array($val['mrctCode'],$val['txn_id'],$val['amount'],$val['debitStartDate'],$val['debitEndDate'],$val['maxAmount'],$val['amountType'],$val['frequency'],$val['custID'],$val['mobNo'],$val['email'],$val['accNo'],$val['returnUrl'],$val['name'],$val['scheme'],$val['currency']));

	$data = json_encode($data);
	$paymentModeOrder = str_replace(",", "','", $_SESSION['cart']['payment_method_data']['processor_params']['paymentModeOrder']);

// fn_print_die($_SESSION);
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.paynimo.com/Paynimocheckout/server/lib/checkout.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
                var obj = JSON.parse('<?php echo $data; ?>');

                function handleResponse(res) {
                    if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0300') {
                    	//alert(1);
                    } else if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0398') {
                    	//alert(2);
                    } else {
                    	//alert(3);
                    }
                };
			    var configJson = {
			        'tarCall': false,
			        'features': {
			            'showPGResponseMsg': true,
		                'enableNewWindowFlow': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['enableNewWindowFlow'] == 1){echo true;}else{echo false;} ?>',   //for hybrid applications please disable this by passing false
		                'enableAbortResponse': false,
		                'enableExpressPay': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['enableExpressPay'] == 1){echo true;}else{echo false;} ?>',  //if unique customer identifier is passed then save card functionality for end  end customer
		                'enableInstrumentDeRegistration': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['enableInstrumentDeRegistration'] == 1){echo true;}else{echo false;} ?>',  //if unique customer identifier is passed then option to delete saved card by end customer
		                'enableMerTxnDetails': true,
		                'hideSavedInstruments': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['hideSavedInstruments'] == 1){echo true;}else{echo false;} ?>',
		                'separateCardMode': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['separateCardMode'] == 1){echo true;}else{echo false;} ?>'
		            },
		            'consumerData': {
		                'deviceId': 'WEBSH2',
		                //possible values 'WEBSH1', 'WEBSH2' and 'WEBMD5'
		                'token': obj['hash'],
		                'returnUrl': obj['data'][12],
		                /*'redirectOnClose': 'https://www.tekprocess.co.in/MerchantIntegrationClient/MerchantResponsePage.jsp',*/
		                'responseHandler': handleResponse,
		                'checkoutElement': '<?php if($_SESSION['cart']['payment_method_data']['processor_params']['embedPaymentGatewayOnPage'] == 1){echo "#embedPaymentGatewayOnPage";}else{echo "";} ?>',
		                'paymentMode': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['paymentMode']; ?>',
		                'paymentModeOrder': ['<?php echo$paymentModeOrder; ?>'],
		                'merchantLogoUrl': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['logoURL']; ?>',  //provided merchant logo will be displayed
		                'merchantMsg': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['merchantMessage']; ?>',
    					'disclaimerMsg': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['disclaimerMessage']; ?>',
		                'merchantId': obj['data'][0],
		                'currency': obj['data'][15],
		                'consumerId': obj['data'][8],  //Your unique consumer identifier to register a eMandate/eNACH
		                'consumerMobileNo': obj['data'][9],
		                'consumerEmailId': obj['data'][10],
		                'txnId': obj['data'][1],   //Unique merchant transaction ID
		                'txnType': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['transactionType']; ?>',
		                'items': [{
		                    'itemId': obj['data'][14],
		                    'amount': obj['data'][2],
		                    'comAmt': '0'
		                }],
		                'cartDescription': '}{custname:'+obj['data'][13],
		                'merRefDetails': [
		                    {"name": "Txn. Ref. ID", "value": obj['data'][1]}
		                ],
		                'customStyle': {
		                    'PRIMARY_COLOR_CODE': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['primaryColor']; ?>',   //merchant primary color code
                            'SECONDARY_COLOR_CODE': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['secondaryColor']; ?>',   //provide merchant's suitable color code
                            'BUTTON_COLOR_CODE_1': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['buttonColor1']; ?>',   //merchant's button background color code
                            'BUTTON_COLOR_CODE_2': '<?php echo $_SESSION['cart']['payment_method_data']['processor_params']['buttonColor2']; ?>'   //provide merchant's suitable color code for button text
		                },
		                'accountNo': obj['data'][11],    //Pass this if accountNo is captured at merchant side for eMandate/eNACH
		                //'accountHolderName': 'Name',  //Pass this if accountHolderName is captured at merchant side for ICICI eMandate & eNACH registration this is mandatory field, if not passed from merchant Customer need to enter in Checkout UI.
		                //'ifscCode': 'YESB0000298',        //Pass this if ifscCode is captured at merchant side.
		                //'accountType': 'Saving',  //Required for eNACH registration this is mandatory field
		                'debitStartDate': obj['data'][3],
		                'debitEndDate': obj['data'][4],
		                'maxAmount': obj['data'][5],
		                'amountType': obj['data'][6],
		                'saveInstrument':'<?php if($_SESSION['cart']['payment_method_data']['processor_params']['saveInstrument'] == 1){echo true;}else{echo false;} ?>',
		                'frequency': obj['data'][7]  //  Available options DAIL, WEEK, MNTH, QURT, MIAN, YEAR, BIMN and ADHO
		            }
		        };

		        $.pnCheckout(configJson);
		        if(configJson.features.enableNewWindowFlow){
		            pnCheckoutShared.openNewWindow();
		        }
	        });
</script>
<div id="embedPaymentGatewayOnPage"></div>
<?php
exit;
?>