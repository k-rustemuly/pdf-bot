<?php

namespace App\Services\Commands\User;

use App\Services\Keyboards\StartKeyboard;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Throwable;

/**
 * Start command
 */
class StartCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @return ServerResponse
     * @throws TelegramException
     * @throws Throwable
     */
    public function execute(): ServerResponse
    {

        $message = $this->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();

        return $this->send(__('main.start_message'), $chat_id, StartKeyboard::make()->getKeyboard());
    }

    /**
     * @throws TelegramException
     */
    private function send(string $text, int $chatId, Keyboard $keyboard): ServerResponse
    {
        return Request::sendMessage([
            'chat_id'       => $chatId,
            'text'          => $text,
            'reply_markup'  => $keyboard
        ]);
    }
}
