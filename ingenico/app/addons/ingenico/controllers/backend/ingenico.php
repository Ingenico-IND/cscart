<?php
use Tygh\Registry;
use Tygh\Tygh;

defined('BOOTSTRAP') or die('Access denied');

if($mode == "offline")
{
    if (defined('AJAX_REQUEST')) {

        $identifier = $_GET['identifier'];
        $date = $_GET['date'];
        $newDate = date("d-m-Y", strtotime($date));
        $merchantid = $_GET['merchantid'];
        $currency = $_GET['currency'];

        $arr_req = array(
            "merchant" => [
                "identifier" => $merchantid
            ],
            "transaction" => [ "deviceIdentifier" => "S", "currency" => $currency, "identifier" => $identifier, "dateTime" => $newDate, "requestType" => "O"]
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

        $offlineData = json_decode($result, true);
        $status = $offlineData["paymentMethod"]["paymentTransaction"]["statusCode"];
        $message = $offlineData["paymentMethod"]["paymentTransaction"]["errorMessage"];

        $responseHtml = '<table class="table table-bordered table-hover">
                  <tr class="info">
                    <th width="100%" colspan=2 style="background: #000000;color: #ffffff;">Offline Verification Result</th>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Merchant Code</td>
                    <td>'.$offlineData["merchantCode"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Merchant Transaction Identifier</td>
                    <td>'.$offlineData["merchantTransactionIdentifier"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Token Identifier</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["identifier"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Amount</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["amount"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Message</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["errorMessage"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Status Code</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["statusCode"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Status Message</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["statusMessage"].'</td>
                  </tr>
                  <tr>
                    <td style="color: #0388cc;font-weight: 900;">Date & Time</td>
                    <td>'.$offlineData["paymentMethod"]["paymentTransaction"]["dateTime"].'</td>
                  </tr>
              </table>';

        Tygh::$app['ajax']->assign('responseHtml', $responseHtml);
        Tygh::$app['ajax']->assign('message', $message);
        Tygh::$app['ajax']->assign('status', $status);
    }

    $payment_data = db_get_array("SELECT * FROM ?:payment_processors WHERE processor = ?s", "Ingenico");
    $admin_data = db_get_array("SELECT * FROM ?:payments WHERE processor_id = ?s", $payment_data[0]['processor_id']);
	if (!empty($admin_data)) 
    {
        $data['payment'] = unserialize($admin_data[0]['processor_params']);
    }

    Tygh::$app['view']->assign('paymentData', $data['payment']);
}

if($mode == "refund")
{
    if (defined('AJAX_REQUEST')) {

        $token = $_GET['identifier'];
        $amount = $_GET['amount'];
        $date = $_GET['date'];
        $newDate = date("d-m-Y", strtotime($date));
        $merchantid = $_GET['merchantid'];
        $currency = $_GET['currency'];

        $arr_req = array(
            "merchant" => [
                "identifier" => $merchantid
            ],
            "cart" => [ "" => ""
            ],
            "transaction" => [ "deviceIdentifier" => "S", "amount" => $amount, "currency" => $currency, "dateTime" => $newDate, "token" => $token, "requestType" => "R"]
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

        $refundData = json_decode($result, true);
        $status = $refundData["paymentMethod"]["paymentTransaction"]["statusCode"];

        $responseHtml = '<table class="table table-bordered table-hover"><tr class="info"><th width="100%" colspan=2 style="background: #000000;color: #ffffff;">Refund Result</th></tr><tr><td style="color: #0388cc;font-weight: 900;">Merchant Code</td><td>'. $refundData["merchantCode"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Merchant Transaction Reference No</td><td>' . $refundData["merchantTransactionIdentifier"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Ingenico Merchant Transaction ID</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["identifier"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Refund Identifier</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["refundIdentifier"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Amount</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["amount"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Message</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["errorMessage"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Status Code</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["statusCode"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Status Message</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["statusMessage"] .'</td></tr><tr><td style="color: #0388cc;font-weight: 900;">Date Time</td><td>' . $refundData["paymentMethod"]["paymentTransaction"]["dateTime"] .'</td></tr></table>';

        if($status == "0400")
        {
            $order_data = db_get_array("SELECT * FROM ?:order_ingenico WHERE tpsl_identifier = ?i", $token);
            
            if(!empty($order_data))
            {
                $remaining_amt = $order_data[0]['total'] - $amount;

                $updatedata = array (
                    'total' => $remaining_amt
                );
                $order_id = $order_data[0]['order_id'];

                db_query('UPDATE ?:order_ingenico SET ?u WHERE order_id = ?i', $updatedata, $order_id);
                db_query('UPDATE ?:orders SET ?u WHERE order_id = ?i', $updatedata, $order_id);
                $message = "Refund Successful !!!";
            }else{
                $message = "Refund Successful !!! But having trouble updating database. Check if there is an order with mentioned identifier.";
            }
        }
        else
        {
            $message = "Refund Failed !!! Try after some time.";
            //fn_set_notification('E', __('error'), __('text_fp_cancelled'))
        }

        Tygh::$app['ajax']->assign('responseHtml', $responseHtml);
        Tygh::$app['ajax']->assign('message', $message);
        Tygh::$app['ajax']->assign('status', $status);

    }

    $payment_data = db_get_array("SELECT * FROM ?:payment_processors WHERE processor = ?s", "Ingenico");
    $admin_data = db_get_array("SELECT * FROM ?:payments WHERE processor_id = ?s", $payment_data[0]['processor_id']);
    if (!empty($admin_data)) 
    {
        $data['payment'] = unserialize($admin_data[0]['processor_params']);
    }

    Tygh::$app['view']->assign('paymentData', $data['payment']);
}

if($mode == "reconcile")
{
    $payment_data = db_get_array("SELECT * FROM ?:payment_processors WHERE processor = ?s", "Ingenico");
    $admin_data = db_get_array("SELECT * FROM ?:payments WHERE processor_id = ?s", $payment_data[0]['processor_id']);

    if (!empty($admin_data))
    {
        $adminData = unserialize($admin_data[0]['processor_params']);
    }

    if (defined('AJAX_REQUEST')) {
        $reconcileFromDate = $_GET['reconcileFromDate'];
        $reconcileToDate = $_GET['reconcileToDate'];
        $orderData = db_get_array("SELECT * FROM ?:order_ingenico as oi JOIN ?:orders as o ON o.order_id = oi.order_id WHERE o.status IN ('O','F','D') AND dateAdded BETWEEN '" . $reconcileFromDate. "' AND '" . $reconcileToDate . "'");
        $successFullOrdersIds = [];
        if($orderData != ''){
            foreach ($orderData as $order_data) {
                $order_id = $order_data['order_id'];
                $request_array = array("merchant"=>array("identifier"=>$adminData['merchant_id']),
                    "transaction"=>array(
                        "deviceIdentifier"=>"S",
                        "currency"=>$adminData['currency'],
                        "identifier"=>$order_data['merchant_identifier'],
                        "dateTime"=>$order_data['dateAdded'],
                        "requestType"=>"O"
                ));
                $url = "https://www.paynimo.com/api/paynimoV2.req";
                $options = array(
                'http' => array(
                    'method'  => 'POST',
                    'content' => json_encode($request_array),
                    'header' =>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
                    )
                );
                $context     = stream_context_create($options);
                $response_array = json_decode(file_get_contents($url, false, $context));
                $status_code = $response_array->paymentMethod->paymentTransaction->statusCode; 
                $status_message = $response_array->paymentMethod->paymentTransaction->statusMessage;
                $txn_id = $response_array->paymentMethod->paymentTransaction->identifier;
                if($status_code=='0300'){
                    $updatedata = array (
                        'status' => 'P'
                    );
                    $updatedata1 = array (
                        'status' => 'success'
                    );
                    db_query('UPDATE ?:order_ingenico SET ?u WHERE order_id = ?i', $updatedata1, $order_id);
                    db_query('UPDATE ?:orders SET ?u WHERE order_id = ?i', $updatedata, $order_id);
                    array_push($successFullOrdersIds, $order_id);
                }else if($status_code=="0397" || $status_code=="0399" || $status_code=="0396" || $status_code=="0392"){
                    $updatedata1 = array (
                        'status' => 'success'
                    );
                    db_query('UPDATE ?:order_ingenico SET ?u WHERE order_id = ?i', $updatedata1, $order_id);
                    db_query('UPDATE ?:orders SET ?u WHERE order_id = ?i', $updatedata, $order_id);
                    array_push($successFullOrdersIds, $order_id);
                   
                }else{
                    null;
                }
            }
            if($successFullOrdersIds){
                $updatedIds = "Updated Order Status for Order ID:  " . implode(", ", $successFullOrdersIds);
            }else{
                $updatedIds = "Updated Order Status for Order ID: None";
            }
        }else{
            $updatedIds = "Updated Order Status for Order ID: None";
        }
        Tygh::$app['ajax']->assign('ids', $updatedIds);
    }
    Tygh::$app['view']->assign('paymentData', $adminData);
}

if($mode == "s2s")
{
    $payment_data = db_get_array("SELECT * FROM ?:payment_processors WHERE processor = ?s", "Ingenico");
    $admin_data = db_get_array("SELECT * FROM ?:payments WHERE processor_id = ?s", $payment_data[0]['processor_id']);
    if (!empty($admin_data)) 
    {
        $data['payment'] = unserialize($admin_data[0]['processor_params']);
    }
    $mer_data = $data['payment'];
    if(isset($_GET['action']))
    {
        $response = explode('|', $_GET['msg']);
        $status = $response[0];
    
        //Hash Verification
        $salt = $mer_data['salt'];
        $responseData_1 = explode('|', $_GET['msg']);
        $verificationHash = array_pop($responseData_1);
        $hashableString = join('|', $responseData_1) . "|" . $salt;
        $hashedString = hash('sha512',  $hashableString);
        if($hashedString != $verificationHash)
        {
            exit('Hash Verification Failed');
        }
        if($status == '0300'){            
            echo json_encode($response[3] . "|" . $response[5] . "|1");
            die;
        }
        else {
            echo json_encode($response[3] . "|" . $response[5] . "|0");
            die;
        }
        die;
    }

}

?>