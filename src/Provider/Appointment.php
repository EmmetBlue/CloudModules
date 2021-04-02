<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class Appointment {
	public static function create(array $data){
        return UserProfile\Appointment::create($data);
    }

	public static function delete(array $data){
        return UserProfile\Appointment::delete($data);
    }
}