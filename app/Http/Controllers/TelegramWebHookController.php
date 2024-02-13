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
            $bot = InfoBot::makeBot();
            $bot->handle();
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
