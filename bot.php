<?php

require 'vendor/autoload.php';

use Telegram\Bot\Api;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$token = $_ENV['TELEGRAM_BOT_TOKEN'];
$bot = new Api($token);

$update = $bot->getWebhookUpdate();
$message = $update->getMessage();

if (!$message) {
    exit;
}

$text = mb_strtolower($message->getText());
$keywords = ['taxi', 'pay', 'оплата', 'важно', 'срочно', 'долг'];

foreach ($keywords as $word) {
    if (strpos($text, $word) !== false) {
        $user = $message->getFrom();
        $chat = $message->getChat();

        // 🔹 Информация о пользователе
        $username = $user->getUsername() ?? 'без username';
        $name = trim($user->getFirstName() . ' ' . $user->getLastName());

        // 🔹 Информация о чате
        $chatId = $chat->getId();
        $chatTitle = $chat->getTitle() ?? $chat->getFirstName() ?? 'неизвестный чат';

        $date = date('Y-m-d H:i:s');

        // 🔹 Лог
        $log = "[{$date}] [Chat: {$chatTitle} | ID: {$chatId}] {$name} (@{$username}): {$message->getText()}\n";

        file_put_contents('keywords_log.txt', $log, FILE_APPEND | LOCK_EX);
        break;
    }
}
