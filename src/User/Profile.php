<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class Profile {
	public static function linkProvider(int $user, array $data){
        return Profile\Provider::newLink($user, $data);
    }

    public static function viewProviders(int $user){
        return Profile\Provider::viewLinks($user);
    }
}