<?php

namespace App\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandInterface;
use App\YoutubeVideos;
use App\TelegramBot;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GoogleApiClientController;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class RegionCommand.
 * List and set User Country/Region for Trending videos
 * 
 * @author John Muiruri <jontedev@gmail.com>
 */
class RegionCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'region';

    /**
     * @var string Command Description
     */
    protected $description = "List Countries/Regions and set my Country or Region";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $googleClient = new GoogleApiClientController;

        //Send Message
        $this->replyWithMessage(['text' => 'Choose your preffered country below']); // Trending Videos from Youtube:
        sleep(1);

        $regionData =  $googleClient->getRegions();

        // if ($type === 'supergroup') {

        //     sleep(3);
        //     // Reply with the Videos List
        // } else {

        // logger($regionData);

        if ($regionData['status'] === true) {

            // Reply with the Videos List
            $no = 0;

            $regions = $regionData['regions'];
            foreach ($regions as $region) {

                // logger($region['name']);
                $id = $region['region'];


                //data to be retrieved in callback_query
                $callbackData =   'setregion-'.$id;

                $name = $region['name'];
                $keyboardButtons[] = array([
                    'text' => $name,
                    'callback_data' => $callbackData
                ]);
                $no++;
            }

            // $inlineKeyboard = [

            //     [
            //         $keyboardButtons
            //     ]

            // ];
            logger($keyboardButtons);

            $reply_markup = Keyboard::make([
                'inline_keyboard' => $keyboardButtons
            ]);

            $this->replyWithMessage([
                'text' => 'Here is the list of Available Regions/Countries: ',
                'reply_markup' => $reply_markup
            ]);
            // } else {

            //     //user auth tokens has expired or user has not given app access
            //     $this->replyWithMessage(['text' => 'Ooops, There was an error trying to access the videos, reply with /auth to grant us access to your Youtube Videos']);
            // }
            // Trigger another command dynamically from within this command
            // $this->triggerCommand('subscribe');
        }
    }
}
