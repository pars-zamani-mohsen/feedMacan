<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

            if (isset($request['channel_post']['photo'])) {
                $this->text = (isset($request['channel_post']['caption']) && $request['channel_post']['caption']) ? $request['channel_post']['caption'] : 'تصویر';
                $fileid = $request['channel_post']['photo'][0]['file_id'];
                $file_type = 'photo';

            } elseif (isset($request['channel_post']['video'])) {
                $this->text = (isset($request['channel_post']['caption']) && $request['channel_post']['caption']) ? $request['channel_post']['caption'] : 'ویدئو';
                $fileid = $request['channel_post']['video']['file_id'];
                $file_type = 'video';

            } elseif (isset($request['channel_post']['voice'])) {
                $this->text = (isset($request['channel_post']['caption']) && $request['channel_post']['caption']) ? $request['channel_post']['caption'] : 'صدا';
                $fileid = $request['channel_post']['voice']['file_id'];
                $file_type = 'audio';

            } elseif (isset($request['channel_post']['audio'])) {
                $this->text = (isset($request['channel_post']['caption']) && $request['channel_post']['caption']) ? $request['channel_post']['caption'] : 'صدا';
                $fileid = $request['channel_post']['audio']['file_id'];
                $file_type = 'audio';

            } elseif (isset($request['channel_post']['document'])) {
                $this->text = (isset($request['channel_post']['caption']) && $request['channel_post']['caption']) ? $request['channel_post']['caption'] : 'فایل';
                $fileid = $request['channel_post']['document']['file_id'];
                $file_type = $request['channel_post']['document']['mime_type'];

            } else {
                $this->text = $request['channel_post']['text'];
            }

//            $fileid = "AgACAgQAAx0CXKdolQADL2GnORBhpK-EpzzMwrqDXH-hNa_1AALMuDEbd7UwUbDy_cDsE_dWAQADAgADcwADIgQ";
            if (isset($fileid) && $fileid) {
                $filename = $this->getFilePath($fileid);
            }

            if ($this->chat_id == '-1001554475157' && $this->text) $this->saveChatToDatabase($this->text, $filename ?? null, $file_type ?? null);

        } catch (\Exception $e) {
            $this->saveLog('Error-handleMessages: ' . $e->getMessage());
        }
    }
}

