<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class MessagePacket {
    public static function viewByUser(int $user){
        return Profile\MessagePacket::viewByUser($user);
    }

    public static function viewByProfile(int $profile){
        return Profile\MessagePacket::viewByProfile($profile);
    }
}