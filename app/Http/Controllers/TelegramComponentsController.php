<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use App\AdditionalClasses\Date;
use App\AdditionalClasses\CustomValidator;
use Telegram\Bot\Laravel\Facades\Telegram;
use Facade\Ignition\Support\Packagist\Package;

class TelegramComponentsController extends Controller
{
    protected $user;
    protected $text;
    protected $chat_id;
    protected $instance;
    protected $username;
    protected $telegram;

    /**
     * TelegramController constructor.
     */
    public function __construct()
    {
        $this->telegram = new Api(config('telegram.bots')['mybot']['token']);
    }

    /**
     * Save chat information in database
     *
     * @param string|null $text
     * @return \App\Telegram
     */
    public function saveChatToDatabase(string $text = null)
    {
        $instance = new \App\Telegram();
        $instance->text = $text;
        $instance->file = null;
        $instance->save();
        return $instance;
    }

    /**
     * Save last user request in file
     * @param $request
     */
    public function saveRecentMessage($request)
    {
        try {
            $file = 'telegram.json';

            if (isset($request['message'])) {
                file_put_contents($file, json_encode(['message' => $request['message']]));
            } elseif (isset($request['callback_query'])) {
                file_put_contents($file, json_encode(['callback_query' => $request['callback_query']]));
            } else {
                file_put_contents($file, $request);
            }

        } catch (\Exception $e) {
            $this->saveLog('Error-setHistory: ' . $e->getMessage());
        }
    }

    /**
     * Save log in file
     *
     * @param $text
     * @param string $filename
     */
    public function saveLog($text, string $filename = 'telegram.log')
    {
        try {
            file_put_contents($filename, (is_array($text) || is_object($text)) ? json_encode($text) : $text);

        } catch (\Exception $e) {
            file_put_contents($filename, 'Error-setLog: ' . $e->getMessage());
        }
    }

    /**
     * Send message to chat
     *
     * @param string $message
     * @param string $chat_id
     * @param array $parameters
     * @param bool $parse_html
     */
    protected function sendMessage(string $message, string $chat_id, array $parameters = [], bool $parse_html = false)
    {
        try {
            $data = [
                'chat_id' => $chat_id,
                'text' => $message,
            ];
            if ($parameters) $data = array_merge($data, $parameters);

            if ($parse_html) $data['parse_mode'] = 'HTML';
            $this->telegram->sendMessage($data);

        } catch (\Exception $e) {
            $this->saveLog('Error-sendMessage: ' . $e->getMessage());
        }
    }

    /**
     * Initialization keyboard
     *
     * @param array $keyboard
     * @param string $keyboard_type 'inline_keyboard'|'keyboard'
     * @param bool $resize_keyboard
     * @param bool $one_time_keyboard
     * @return array|null
     */
    public function init_keyboard(array $keyboard, string $keyboard_type = 'inline_keyboard', bool $resize_keyboard = false, bool $one_time_keyboard = false)
    {
        if (!$keyboard) return $keyboard;

        $keyboard = [
            $keyboard_type => [$keyboard],
            'resize_keyboard' => $resize_keyboard,
            'one_time_keyboard' => $one_time_keyboard,
        ];
        $encodedKeyboard = json_encode($keyboard);
        return array('reply_markup' => $encodedKeyboard);
    }

    /**
     * fetch bot information
     *
     * @return string|\Telegram\Bot\Objects\User
     */
    public function getMe()
    {
        try {
            $response = $this->telegram->getMe();
            return $response;

        } catch (\Exception $e) {
            $this->saveLog('Error-getMe: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}

