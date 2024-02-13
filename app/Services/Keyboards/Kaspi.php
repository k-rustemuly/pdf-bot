<?php

namespace App\Services\Keyboards;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class Kaspi extends TelegramButton
{
    protected string $buttonKey = 'kaspi';

    protected string $buttonText = 'Kaspi.kz';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $message = $query->getMessage();
        $accountInfo = $message->getChat();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        $data = [
            'chat_id' => $chat_id,
            'text'    => 'xmmmm'
        ];
        if($message->getType() == 'document') {
            $doc = call_user_func('get' . $message->getType(), $message);
            ($message->getType() === 'document') && $doc = $doc[0];
            $file_id = $doc->getFileId();
            $file = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk() && Request::downloadFile($file->getResult())) {
                $data['text'] = $message->getType() . ' file is located at: ' . $this->telegram->getDownloadPath() . '/' . $file->getResult()->getFilePath();
            }
            return Request::sendMessage($data);

        }
        return Request::sendMessage([
            'chat_id' => $accountInfo->getId(),
            'text'    => 'PDF-ті жүктеңіз'
        ]);
    }
}
