<?php

namespace App\Http\Controllers;

use App\Services\InfoBot;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class TelegramWebHookController extends Controller
{
    /**
     * @throws Throwable
     */
    public function webhook()
    {
        try {
            $telegram = new Telegram(
                config('telegram.bot_api_key'),
                config('telegram.bot_username'));
            $telegram->useGetUpdatesWithoutDatabase();
            $data = $telegram->handleGetUpdates();
            if (isset($data['message'])) {
                // Обрабатываем входящее сообщение
                $message = $data['message'];

                // Получаем ID чата и текст сообщения
                $chatId = $message['chat']['id'];
                $text = $message['text'];

                // Выполняем необходимые действия в зависимости от текста сообщения
                if ($text === '/start') {
                    // Запросить загрузку файла
                    Request::sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Пожалуйста, загрузите PDF файл.',
                    ]);
                } elseif (isset($message['document'])) {
                    $document = $message['document'];
                }
            }
        } catch (TelegramException $e) {
            report_app($e);
        }
    }

    public function setWebhook()
    {
        $bot = InfoBot::makeBot();
        $response = $bot->setWebHook(route('webhook.telegram'));
        dd($response);
    }
}
