<?php
// Adjust to your timezone
date_default_timezone_set('Europe/Moscow');

// Report all PHP errors
error_reporting(-1);





     $certpath = '/home/bitrix/ext_www/app.luxurytravelmart.ru/ApnsPHP/apns_LTM_dev.pem';
     //$certpath = $_SERVER["DOCUMENT_ROOT"].'/ApnsPHP/apns_LTM_prod.pem';

        $ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $certpath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', '1');

        $devices = array('0'=> array("push_token"=>"a8bec13680561d5fbe56eb494cae285011bbd875c1ad1a43b7cdcfaea1ea2487"));

        foreach ($devices as $device) {
            if (!isset($device["push_token"])) {
                continue;
            }
//'ssl://gateway.push.apple.com:2195'
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            stream_set_blocking ($fp, 0);

            if (!$fp)
                return "Failed to connect: $err $errstr" . PHP_EOL;

            $message = 'alert message';
            $data = 'text data of push g';

            $body['aps'] = array(
                'badge' => 1,
                'alert' => $message,
                'data' => isset($data) ? $data : ""
            );

            $payload = json_encode($body);
            $result = NULL;

            $deviceToken = $device["push_token"];
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));

            checkAppleErrorResponse($fp);
        }

usleep(1000);
echo 'Completed';
fclose($fp);


function checkAppleErrorResponse($fp) {

//byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID).
// Should return nothing if OK.

//NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait
// forever when there is no response to be sent.

    $apple_error_response = fread($fp, 600);

    if ($apple_error_response) {

        // unpack the error response (first byte 'command" should always be 8)
        $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

        if ($error_response['status_code'] == '0') {
            $error_response['status_code'] = '0-No errors encountered';

        } else if ($error_response['status_code'] == '1') {
            $error_response['status_code'] = '1-Processing error';

        } else if ($error_response['status_code'] == '2') {
            $error_response['status_code'] = '2-Missing device token';

        } else if ($error_response['status_code'] == '3') {
            $error_response['status_code'] = '3-Missing topic';

        } else if ($error_response['status_code'] == '4') {
            $error_response['status_code'] = '4-Missing payload';

        } else if ($error_response['status_code'] == '5') {
            $error_response['status_code'] = '5-Invalid token size';

        } else if ($error_response['status_code'] == '6') {
            $error_response['status_code'] = '6-Invalid topic size';

        } else if ($error_response['status_code'] == '7') {
            $error_response['status_code'] = '7-Invalid payload size';

        } else if ($error_response['status_code'] == '8') {
            $error_response['status_code'] = '8-Invalid token';

        } else if ($error_response['status_code'] == '255') {
            $error_response['status_code'] = '255-None (unknown)';

        } else {
            $error_response['status_code'] = $error_response['status_code'].'-Not listed';

        }

        echo '<br><b>+ + + + + + ERROR</b> Response Command:<b>' . $error_response['command'] . '</b>&nbsp;&nbsp;&nbsp;Identifier:<b>' . $error_response['identifier'] . '</b>&nbsp;&nbsp;&nbsp;Status:<b>' . $error_response['status_code'] . '</b><br>';

        echo 'Identifier is the rowID (index) in the database that caused the problem, and Apple will disconnect you from server. To continue sending Push Notifications, just start at the next rowID after this Identifier.<br>';

        return true;
    }

    return false;
}

?>
