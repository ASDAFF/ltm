<?php
// Adjust to your timezone
date_default_timezone_set('Europe/Moscow');

// Report all PHP errors
error_reporting(-1);

// Using Autoload all classes are loaded on-demand
require_once $_SERVER["DOCUMENT_ROOT"].'/ApnsPHP/Autoload.php';

// Instantiate a new ApnsPHP_Push object
$push = new ApnsPHP_Push(
	ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
    $_SERVER["DOCUMENT_ROOT"].'/ApnsPHP/apns_LTM_dev.pem'
);

// Set the Provider Certificate passphrase
$push->setProviderCertificatePassphrase('1');

// Set the Root Certificate Autority to verify the Apple remote peer
//$push->setRootCertificationAuthority($_SERVER["DOCUMENT_ROOT"].'/ApnsPHP/apns_LTM_prod.pem');
//$push->setRootCertificationAuthority($_SERVER["DOCUMENT_ROOT"].'/1/apns_LTM_dev.pem');

// Connect to the Apple Push Notification Service
$push->connect();

// Instantiate a new Message with a single recipient
$message = new ApnsPHP_Message('a8bec13680561d5fbe56eb494cae285011bbd875c1ad1a43b7cdcfaea1ea2487');

// Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
// over a ApnsPHP_Message object retrieved with the getErrors() message.
$message->setCustomIdentifier("Message-Badge-3");

// Set badge icon to "3"
$message->setBadge(3);

// Set a simple welcome text
$message->setText('Вас оповещает администратор!');

// Play the default sound
$message->setSound();

// Set a custom property
//$message->setCustomProperty('acme2', array('bang', 'whiz'));

// Set another custom property
//$message->setCustomProperty('acme3', array('bing', 'bong'));

// Set the expiry value to 30 seconds
$message->setExpiry(30);

// Add the message to the message queue
$push->add($message);

// Send all messages in the message queue
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();

// Examine the error message container
$aErrorQueue = $push->getErrors();
if (!empty($aErrorQueue)) {
	var_dump($aErrorQueue);
}
