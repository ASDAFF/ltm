<?
// Adjust to your timezone
date_default_timezone_set('Europe/Moscow');

// Report all PHP errors
error_reporting(-1);

$android_key = 'AIzaSyBcQfzmB-lvHFZIeIMleT0cdgwljKp7iIw';


if (!empty($_GET["device_ids"])) {
    //foreach ($_GET["device_ids"] as $device) {
        send_notification($_GET["device_ids"],array("message" => json_encode(array("type" => "ABUSE", "message" => "No-shown at the appointment with ".$_GET["user_name"], "from_user_id" => $_GET["user_id"]))),$android_key);
    //}
}

/**
 * Sending Push Notification
 */
function send_notification($registatoin_ids, $message, $key) {
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
    /*
    echo "<pre>";
    print_r($result);
    echo "</pre>";
*/
    // Close connection
    curl_close($ch);
}
?>