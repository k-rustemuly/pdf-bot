<?php

namespace App\Services\Keyboards;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class Halyk extends TelegramButton
{
    protected string $buttonKey = 'halyk';

    protected string $buttonText = 'Выписка с Halyk Bank';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $accountInfo = $query->getMessage()->getChat();

        return Request::sendMessage([
            'chat_id' => $accountInfo->getId(),
            'text'    => 'PDF-ті жүктеңіз'
        ]);
    }
}
