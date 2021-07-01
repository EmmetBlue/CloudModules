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
        return UserProfile\Links::viewLinks($user);
    }

    public static function retrieveUserAccountDetails(int $account){
    	return \EmmetBlue\Plugins\User\Account::viewAccount($account);
    }

    public static function retrieveUserAccountDetailsByUsername(array $data){
    	return \EmmetBlue\Plugins\User\Account\Access::getUserIdFromUsername($data);
    }

    public static function retrieveUserAccountDetailsByEmail(array $data){
    	return \EmmetBlue\Plugins\User\Account\Access::getUserIdFromEmail($data);
    }

    public static function retrieveUserAccountDetailsByPhone(array $data){
    	return \EmmetBlue\Plugins\User\Account\Access::getUserIdFromPhone($data);
    }

    public static function uploadData(int $profile, array $data){
        return \EmmetBlue\Plugins\User\Profile::addData($profile, $data);
    }

    public static function registerNewUser(array $data){
        $result = \EmmetBlue\Plugins\User\Registration::newRegistration($data);

        $userDetails = self::retrieveUserAccountDetailsByEmail(["email"=>$data["email"]]);

        $result = \EmmetBlue\Plugins\Notifications\Email\Users::sendProviderRegistrationWelcomeEmail($userDetails["user_id"], $data["email"]);

        $userDetails["emailNotification"] = $result;
        
        return $userDetails;
    }
}