<?php

namespace App\Services\Keyboards;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;

class StartKeyboard extends TelegramKeyboard
{
    public function buildKeyboard(string $value = ''): Keyboard
    {
        return new InlineKeyboard(
            [$this->inlineButton(new Kaspi())],
            [$this->inlineButton(new Halyk())],
        );
    }
}
