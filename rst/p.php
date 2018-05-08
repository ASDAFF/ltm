<?
// Adjust to your timezone
date_default_timezone_set('Europe/Moscow');

// Report all PHP errors
error_reporting(-1);


/**
 * Sending Push Notification
 */
function send_notification($registatoin_ids, $message, $key) {
    // include config
    //   include_once './config.php';
    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
    );

    $headers = array(
        'Authorization: key=' . $key . '',
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
    echo $result;
}

send_notification(array('dSv4UvruPFw:APA91bEsNjPvbeQT3wr7vAbKTTyD_nXSfn9ehXQ29dcuiX8xkW-wY0Iq3qWW3Uy3U_wNVqBZ0MjvpaeCjFed1iAaXrP2L4Ow3zg736DiRcA81xTN1iLIackFBARUON8ANy5tkXbixfyW'),array("type" => "ABUSE", "message" => "No-shown at the appointment with ABBERLEY", "from_user_id" => 411),'AIzaSyBcQfzmB-lvHFZIeIMleT0cdgwljKp7iIw');
//send_notification(array('dOYiUr_5VPE:APA91bGGy7uaaZ4t9IwdbFK1nXu-YnGDEvdJQmvWvDoGoeupGJQRs4gC7DWrRHZPXNIt_DyihMZD6YAivY12wDy0DVGQhn7AfYrn9zywOSktIQCrKSUePHKPuOqLzY5s9EIOTRuzEue9'),array("message" => "No-shown at the appointment with ABBERLEY"),'AIzaSyBcQfzmB-lvHFZIeIMleT0cdgwljKp7iIw');

?>