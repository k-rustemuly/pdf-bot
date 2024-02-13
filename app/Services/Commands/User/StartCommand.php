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
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        if(!empty($text))
        {
            return Request::sendMessage([
                'chat_id'       => $chat_id,
                'text'          => $text,
            ]);
        }

        return $this->send('Сегодня для тебя 🤌 :', $this->getMessage()->getChat()->getId(), StartKeyboard::make()->getKeyboard());
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
