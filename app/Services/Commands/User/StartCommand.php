<?php

namespace App\Services\Commands\User;

use App\Models\Log;
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

        $chat    = $this->getMessage()->getChat();
        $chat_id = $chat->getId();

        Log::create([
            'chat_id' => $chat_id,
            'username' => $chat->getUsername(),
        ]);

        return Request::sendMessage([
            'chat_id'       => $chat_id,
            'text'          => __('main.start_message'),
            'reply_markup'  => StartKeyboard::make()->getKeyboard()
        ]);
    }

}
