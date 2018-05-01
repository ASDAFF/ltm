<?
// Adjust to your timezone
date_default_timezone_set('Europe/Moscow');

// Report all PHP errors
error_reporting(-1);

$android_key = 'dOYiUr_5VPE:APA91bGGy7uaaZ4t9IwdbFK1nXu-YnGDEvdJQmvWvDoGoeupGJQRs4gC7DWrRHZPXNIt_DyihMZD6YAivY12wDy0DVGQhn7AfYrn9zywOSktIQCrKSUePHKPuOqLzY5s9EIOTRuzEue9';

if (!empty($_GET["push_tokens"])) {
// Using Autoload all classes are loaded on-demand
    require_once $_SERVER["DOCUMENT_ROOT"] . '/ApnsPHP/Autoload.php';

// Instantiate a new ApnsPHP_Push object
    $push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
        $_SERVER["DOCUMENT_ROOT"] . '/ApnsPHP/apns_LTM_dev.pem'
    );

// Set the Provider Certificate passphrase
    $push->setProviderCertificatePassphrase('1');

// Set the Root Certificate Autority to verify the Apple remote peer
//$push->setRootCertificationAuthority($_SERVER["DOCUMENT_ROOT"].'/ApnsPHP/apns_LTM_prod.pem');
//$push->setRootCertificationAuthority($_SERVER["DOCUMENT_ROOT"].'/1/apns_LTM_dev.pem');

// Connect to the Apple Push Notification Service
    $push->connect();

foreach ($_GET["push_tokens"] as $token) {
    if (!substr_count($token,":") OR substr_count($token,":")==0 ) {
        $message = new ApnsPHP_Message($token);
        $message->setCustomIdentifier("ABUSE");
        $message->setBadge(1);
        $message->setText('No-shown at the appointment with ' . $_GET["user_name"]);
        $message->setSound();
        $message->setCustomProperty('data', array("type" => "ABUSE", "message" => "No-shown at the appointment with " . $_GET["user_name"], "from_user_id" => $_GET["user_id"]));
        $message->setExpiry(30);
        $push->add($message);
        //echo "<br>".$token;
    }
}

// Send all messages in the message queue
    $push->send();

// Disconnect from the Apple Push Notification Service
    $push->disconnect();

// Examine the error message container
    $aErrorQueue = $push->getErrors();
    if (empty($aErrorQueue)) {
        var_dump($aErrorQueue);
    }
}


?>