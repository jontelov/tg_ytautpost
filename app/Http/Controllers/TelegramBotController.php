<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\TelegramBot;

/**
 * The Telegram Bot  Class
 *
 * @author John Muiruri  <jontedev@gmail.com>
 *
 */
class TelegramBotController extends Controller
{
    /**
     * Get Updates(Messages from users or input) via Long polling
     * Cant work if a webhook is already setup
     */
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        return response()->json($activity);
    }

    /**
     * Run Commands (eg. /start, /help)
     */
    public function runCommands()
    {
        $update = Telegram::commandsHandler(false, ['timeout' => 0]);
        return response()->json(['status' => 'success']);
    }

    /**
     * Get Webhook Updates
     */
    public function tgWebhook()
    {
        //$response = $update = Telegram::commandsHandler(true);
        Telegram::commandsHandler(true);
        // Telegram::getWebhookUpdates();
        // $update = new  Update;

        $this->processUpdates();

        return response()->json(['status' => 'success']);
    }

    /**
     * Set Webhook
     */
    public function runWebhook()
    {
        $url = env('APP_URL') . '/' . env('TELEGRAM_BOT_TOKEN') . '/webhook';
        $updates = Telegram::setWebhook(['url' => $url]);
        return response()->json($updates);
    }

    /**
     * Get Webhook Info
     */
    public function getWebhook()
    {
        $response = Telegram::getWebhookInfo();
        return response()->json($response);
    }

    /**
     * Remove Webhook
     */
    public function removeWebhook()
    {
        $response = Telegram::removeWebhook();
        return response()->json($response);
    }

    /**
     * Process Updates
     */
    public function processUpdates()
    {
        $this->previousCommand();

        sleep(1);
        $this->saveUpdates();

        return true;
    }

    /**
     * Save Updates
     */
    public function saveUpdates()
    {
        //Get Json Update
        $data = Telegram::getWebhookUpdates();

        //Pluck Values
        $update_id = $data->update_id;
        $user_id = $data->message->from->id;
        $username = $data->message->from->username;
        $chat_id = $data->message->chat->id;
        $chat_type = $data->message->chat->type;
        $message_id = $data->message->message_id;
        $message = $data->message->text;
        $entities = $data->message->entities;
        $object  = $entities->toArray();
        $entityArray = $object['0'];
        $message_type = $entityArray['type'];
        // Log::debug($message_type);

        // Store messages in db

        TelegramBot::create(
            [
                'update_id' => $update_id,
                'user_id' => $user_id,
                'username' => $username,
                'chat_id' => $chat_id,
                'chat_type' => $chat_type,
                'message_id' => $message_id,
                'message' => $message,
                'message_type' => $message_type
            ]
        );

        return true;
    }
    /**
     * Gets Previous Command
     */
    public function previousCommand()
    {
        $data = Telegram::getWebhookUpdates();

        $user_id = $data->message->from->id;
        $chat_id = $data->message->chat->id;
        $message = $data->message->text;

        $entities = $data->message->entities;
        $object  = $entities->toArray();
        $entityArray = $object['0'];
        $message_type = $entityArray['type'];

        $command = TelegramBot::select('message')
        ->where([
            ['user_id', '=', $user_id],
            ['chat_id', '=', $chat_id],
            ['message_type', '=', 'bot_command'],
        ])->first();
        Log::debug($command);
    }

}
