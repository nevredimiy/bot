<?php

require 'vendor/autoload.php';

use Telegram\Bot\Api;

$bot = new Api('5279038391:AAG6qViQ29IKLA9EZ9XpRBFO-80QdFJyp5s'); // замени токен

$update = $bot->getWebhookUpdate();
$message = $update->getMessage();

if (!$message) {
    exit;
}

$text = mb_strtolower($message->getText());
$keywords = ['оплата', 'важно', 'срочно', 'долг'];

foreach ($keywords as $word) {
    if (strpos($text, $word) !== false) {
        $user = $message->getFrom();
        $username = $user->getUsername() ?? 'без username';
        $name = trim($user->getFirstName() . ' ' . $user->getLastName());
        $date = date('Y-m-d H:i:s');
        $log = "[{$date}] {$name} (@{$username}): {$message->getText()}\n";

        file_put_contents('keywords_log.txt', $log, FILE_APPEND);
        break;
    }
}
