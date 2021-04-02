<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class UserProfile {
	public static function link(int $user, array $data){
        return UserProfile\Links::newLink($user, $data);
    }

    public static function viewProviders(int $user){
        return Profile\Provider::viewLinks($user);
    }
}