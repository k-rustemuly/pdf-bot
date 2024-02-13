<?php

namespace App\Services\Keyboards;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class Start extends TelegramButton
{
    protected string $buttonKey = 'start';

    protected string $buttonText = 'Начать';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $accountInfo = $query->getMessage()->getChat();

        return Request::sendMessage([
            'chat_id' => $accountInfo->getId(),
            'text'    => 'Сәлеметсіз бе! Бұл бот Kaspi Bank немесе Halyk Bank шотыңызда 3 ай қатарынан 100 аударым болған, болмағанын анықтауға арналған. Жұмысты бастау үшін қай банктегі шотыңызды тексергіңіз келетінін таңдаңыз. ',
        ]);

    }
}
