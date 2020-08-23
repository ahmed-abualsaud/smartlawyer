<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class HelperController extends Controller
{
    // store files
    public static function storeFile($file,$path){
        $name = rand(10,10000)+time();
        $fileName = $name.'.'.$file->getClientOriginalExtension();
        $file->storeAs($path,$fileName);
        $fullPath = $path.'/'.$fileName;
        return $fullPath;
    }

    // send firebase notifications
    public static function sendFireBaseNotification($title , $body, $deviceToken){

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder( $title );
        $notificationBuilder->setBody( $body )->setSound('default');
//        $notificationBuilder->setBadge($badgeAmount);
        //$notificationBuilder->setClickAction($type);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder = new PayloadDataBuilder();

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $deviceToken;
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        return $downstreamResponse->numberSuccess() == 1 ? true : false;

    }
}
