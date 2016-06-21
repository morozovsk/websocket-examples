#!/usr/bin/env php
<?php
//You can call it from their scripts
//It sends the data to a web socket server, which forwards them to all clients

$localsocket = 'tcp://127.0.0.1:8010';
$message = 'test';

$instance = stream_socket_client ($localsocket, $errno, $errstr);//connect to the websocket server

fwrite($instance, json_encode(['message' => $message, 'userId' => 5204])  . "\n");//send a message
//fwrite($instance, json_encode(['message' => $message, 'clientId' => 12])  . "\n");//send a message
//fwrite($instance, json_encode(['message' => $message, 'PHPSESSID' => '4sk3sgqf1lqbjC2litl75db142'])  . "\n");//send a message
