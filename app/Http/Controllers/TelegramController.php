<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
use App\AdditionalClasses\CustomValidator;
use Telegram\Bot\Laravel\Facades\Telegram;
use Facade\Ignition\Support\Packagist\Package;

class TelegramController extends TelegramComponentsController
{
    /**
     * Handel all user request in bot
     *
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        try {
            $this->saveRecentMessage($request);

            if (isset($request['channel_post'])) {
                if ($request['channel_post']['chat']['type'] == 'channel') {
                    $this->handleMessages($request);
                }
            }

        } catch (\Exception $e) {
            $this->saveLog('Error-handleRequest: ' . $e->getMessage());
        }
    }

    /**
     * Handle user message requrst
     * @param $request
     */
    public function handleMessages($request)
    {
        try {
            // initialize variable
            $this->chat_id = $request['channel_post']['chat']['id'];
            $this->username = $request['channel_post']['chat']['username'];
            $this->text = $request['channel_post']['text'];

            if ($this->chat_id == '-1001554475157') $this->saveChatToDatabase($this->text);

//            /* send message to user */
//            $message = "سلام ، خوش آمدید🌹";
//            $keyboard = array(
//                ['text' => 'تیکت های من', 'callback_data' => 'ticket_list'],
//                ['text' => 'تیکت جدید', 'callback_data' => 'ticket_new'],
//            );
//            $keyboard = $this->init_keyboard($keyboard);
//            $this->sendMessage($message, $this->chat_id, $keyboard, true);

        } catch (\Exception $e) {
            $this->saveLog('Error-handleMessages: ' . $e->getMessage());
        }
    }
}

