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
        // $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        if($message->getType() == 'document') {
            $data = [
                'chat_id'      => $chat_id,
            ];
            $doc = call_user_func('get' . $message->getType(), $message);
            ($message->getType() === 'document') && $doc = $doc[0];
            $file_id = $doc->getFileId();
            $file = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk() && Request::downloadFile($file->getResult())) {
                $data['text'] = $message->getType() . ' file is located at: ' . $this->telegram->getDownloadPath() . '/' . $file->getResult()->getFilePath();
            } else {
                $data['text'] = 'Failed to download.';
            }
            return Request::sendMessage($data);
        }
        // if(!empty($text))
        // {
        //     return Request::sendMessage([
        //         'chat_id'       => $chat_id,
        //         'text'          => $text,
        //     ]);
        // }

        return $this->send('Сәлеметсіз бе! Бұл бот Kaspi Bank немесе Halyk Bank шотыңызда 3 ай қатарынан 100 аударым болған, болмағанын анықтауға арналған. Жұмысты бастау үшін қай банктегі шотыңызды тексергіңіз келетінін таңдаңыз.', $chat_id, StartKeyboard::make()->getKeyboard());
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
