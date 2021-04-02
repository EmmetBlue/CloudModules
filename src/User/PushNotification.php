<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class PushNotification {
	public static function subscribe(int $user, array $data){
        return Classes\PushNotification::subscribe($user, $data);
    }

	public static function sendNotification(int $user, array $data){
        return Classes\PushNotification::sendNotification($user, $data);
    }
}