<?php

namespace App\Services;

use App\Services\Keyboards\AppKeyboardList;
use Longman\TelegramBot\Exception\TelegramException;

class InfoBot extends TelegramBot
{
    /**
     * @throws TelegramException
     */
    public static function makeBot(): TelegramBot
    {
        return new static(
            config('telegram.bot_api_key'),
            config('telegram.bot_username'),
            __DIR__.'/Commands',
        );
    }

    public function setBot(): void
    {
        $this->telegram->useGetUpdatesWithoutDatabase();
        $this->telegram->setDownloadPath(storage_path('app'));
        $buttons = AppKeyboardList::getAllButtons();

        foreach ($buttons as $button) {
            $this->addFunctionInCallback(new $button());
        }
    }
}
