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

            if (isset($request['message'])) {
                if ($request['message']['chat']['type'] == 'private') {
                    $this->handleMessages($request);
                }

            } elseif (isset($request['callback_query'])) {
                if ($request['callback_query']['message']['chat']['type'] == 'private') {
                    $this->handlecallback($request);
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
            $this->chat_id = $request['message']['chat']['id'];
            $this->username = $request['message']['from']['username'];
            $this->text = (isset($request['message']['text'])) ? $request['message']['text'] : $request['message']['contact']['phone_number'];

            /* send message to user */
            if ($this->text == '/start') {
                if ($this->user) {
                    $this->saveChatToDatabase($this->chat_id, $this->username, $this->user->id ?? null, 'logined', $this->instance);
                    $message = "سلام " . $this->user->name . " عزیز، خوش آمدید🌹";
                    $keyboard = array(
                        ['text' => 'تیکت های من', 'callback_data' => 'ticket_list'],
                        ['text' => 'تیکت جدید', 'callback_data' => 'ticket_new'],
                    );
                }
                else {
                    $url = url('/');
                    $message = "کاربر گرامی سلام\n به ربات  <a href = '$url'> سامانه پشتیبانی هلدینگ پارس پندار نهاد </a> خوش آمدید. 💐";
                    $message .= "\n\n" . " 👈 لطفا برای ارتباط با بخش <b>پشتیبانی</b> وارد حساب کاربری خود شوید و در صورتیکه حساب کاربری ندارید ثبت نام کنید.";

                    $keyboard = array(
                        ['text' => 'ورود', 'callback_data' => 'signin'],
                        ['text' => 'ثبت نام', 'callback_data' => 'signup']
                    );

                }
                $keyboard = $this->init_keyboard($keyboard);
                $this->sendMessage($message, $this->chat_id, $keyboard, true);
            }

            else {
                switch ($this->text) {
                    case 'TEst':
                        $_message = " 📢 پیام شما برای همکاران ما ارسال شد، لطفا منتظر پاسخ همکاران ما باشید. ";
                        break;
                    default :

                        switch ($this->instance->last_action) {
                            case 'signup':
                            case 'signin':
                                $result = $this->userAuthorize($request);
                                $_keyboard = $result['keyboard'];
                                $_keyboard = $this->init_keyboard($_keyboard);
                                $_message = $result['message'];
                                break;
                            default :
                                $_message = " 📢 درخواست ارسال شده اشتباه است. ";
                        }
                }
            }

        } catch (\Exception $e) {
            $this->saveLog('Error-handleMessages: ' . $e->getMessage());
        }
    }

    /**
     * Handle user callback requrst
     * @param $request
     */
    public function handlecallback($request)
    {
        try {
            // initialize variable
            $this->chat_id = $request['callback_query']['message']['chat']['id'];
            $this->username = $request['callback_query']['from']['username'];
            $this->text = $request['callback_query']['data'];

        } catch (\Exception $e) {
            $this->saveLog('Error-handlecallback: ' . $e->getMessage());
        }
    }
}

