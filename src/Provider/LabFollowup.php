<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class LabFollowup {
	public static function register(array $data){
        return UserProfile\LabFollowup::register($data);
    }

    public static function publish(array $data){
        return UserProfile\LabFollowup::publish($data);
    }
}