<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 *
 * In this conversation-related context, we must ensure that active conversations get executed correctly.
 */

namespace App\Services\TelegramBots\InfoBot\Commands\System;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use Longman\TelegramBot\ChatAction;

class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return ServerResponse
     */
    public function executeNoDb(): ServerResponse
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'xmmmm'
        ];
        $message_type = $message->getType();

        if($message_type == 'document') {
            Request::sendChatAction([
                'chat_id' => $chat_id,
                'action'  => ChatAction::TYPING,
            ]);
            $download_path = $this->telegram->getDownloadPath();
            if (!is_dir($download_path)) {
                return $this->replyToChat('Download path has not been defined or does not exist.');
            }
            $doc = $message->{'get' . ucfirst($message_type)}();
            $file_id = $doc->getFileId();
            $file    = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk()) {
                $tg_file_path = $file->getFilePath();
                Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text'  => $tg_file_path,
                ]);
                $base_download_uri = str_replace('{API_KEY}', $this->telegram->getApiKey(), '/file/bot{API_KEY}');
                $parser = new Parser();
                $pdf = $parser->parseFile("{$base_download_uri}/{$tg_file_path}");
                $pages = $pdf->getPages();
                $texts = [];
                foreach($pages as $page) {
                    $texts = array_merge($texts, collect($page->getTextArray())->map(function ($item) {
                        return Str::squish($item);
                    })->toArray());
                }
                $filteredArrays = array_filter($texts, function($v, $k) {
                    return $v == 'Пополнение';
                }, ARRAY_FILTER_USE_BOTH);
                $keys = array_keys($filteredArrays);
                $inputs = [
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => [],
                    8 => [],
                    9 => [],
                    10 => [],
                    11 => [],
                    12 => [],
                ];
                foreach($keys as $key) {
                    $date = $texts[$key-2];
                    $month = (int) Str::of($date)->explode('.')[1];
                    $user = $texts[$key+1];
                    $inputs[$month][$user] = 0;
                }
                foreach($inputs as $month => $users)
                {
                    $inputs[$month] = count($users);
                }
                $data['text'] = json_encode($inputs);
            } else {
                $data['text'] = 'Failed to download.';
            }

        }
        return Request::sendMessage($data);

        $conversation = new Conversation(
            $message->getFrom()->getId(),
            $message->getChat()->getId()
        );

        // Fetch conversation command if it exists and execute it.
        if ($conversation->exists() && $command = $conversation->getCommand()) {
            return $this->telegram->executeCommand($command);
        }

        return Request::emptyResponse();
    }
}
