<?php

require '../ApnsMessageClient.php';
var_dump(ini_get('socket_timeout'));

/* Creates new Message client instance */
$client = new Services_ApnsMessageClient();

/* Set the authorized certificate provided by Apple */
$client->setSslCertificateFilePath(dirname(__FILE__) . '/aps_developer_identity.pem');

/* Defining the password phrase specified in the Provisioning Portal */
$client->setPasswordPhrase('My top secret password!');

/* Setting the default ENV */
$client->setDefaultEnvironment(APNS_SERVICE_ENV_SANDBOX);

/* Creating a new message */
$message = new Services_Apns_Message();
$message->setBody('This is a simple test!');

/* FIRE! */
$result = $client->sendMessage($message);

var_dump($result);