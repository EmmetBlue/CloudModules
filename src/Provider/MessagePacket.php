<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class MessagePacket {
    public static function newPacket(array $data){
        return UserProfile\MessagePacket::newPacket($data);
    }
}