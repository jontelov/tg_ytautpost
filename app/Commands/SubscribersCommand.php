<?php

namespace App\Commands;

use App\Subscribers;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Actions;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\TelegramObject;
use Illuminate\Support\Facades\Log;

/**
 * Class SubscribersCommand.
 * Subscribe to this bot
 * 
 * @author John Muiruri <jontedev@gmail.com>
 */
class SubscribersCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'subscribe';

    /**
     * @var string Command Description
     */
    protected $description = "Subscribe to Updates or Notifications from Selecta Autopost";

    /**
     * @inheritdoc
     */
    public function handle()
    {

        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // $user_id = $this->id;
        $user_id = $this->getUpdate();
        // $user_id = [
        //     'name', 'Mywork'
        // ];
        $data = $user_id->message;
        $chat_id = $data->chat->id;
        $username =$data->from->username;
        $firstname =$data->from->firstname;
        Log::debug($data);
        $newUser = Subscribers::where('chat_id', '=', $chat_id)->first();

        if ($newUser === null) {

            //user doesnt exist so create

            Subscribers::create(
                [
                    'chat_id' => $chat_id,
                    'username' => $username,
                    'firstname' => $firstname
                ]
            );



            //Send Message
            $this->replyWithMessage(['text' => 'Great! User:, You have been added to the Selecta Autopost Subscribers List']);
            // } else {
        //     exit;
        // }
        // Trigger another command dynamically from within this command
        // $this->triggerCommand('subscribe');
        }
    }
}
