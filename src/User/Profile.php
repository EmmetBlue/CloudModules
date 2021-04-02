<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class Profile {
    public static function viewLinks(int $user){
        return \EmmetBlue\Plugins\Provider\UserProfile\Links::viewLinks($user);
    }

    public static function viewLinksWithProfile(int $user){
        $links = \EmmetBlue\Plugins\Provider\UserProfile\Links::viewLinks($user);

        foreach ($links as $key=>$link){
            $links[$key]["profileInfo"] = self::viewFullProfile( (int) $link["profile_id"])["data"];
        }

        return $links;
    }

    public static function viewProfile(int $profile){
        return Profile\Profile::viewProfile($profile);
    }

    public static function viewFullProfile(int $profile){
        return Profile\Profile::viewFullProfile($profile);
    }

    public static function addData(int $profile, array $data){
        return Profile\Profile::addData($profile, $data);
    }

    public static function getProfileImage(int $profile){
        return Profile\Profile::getProfileImage($profile);
    }

    public static function retrieveLabRequests(int $profile){
        return Profile\Lab::getRequests($profile);
    }

    public static function countPendingLabRequests(int $profile){
        return Profile\Lab::countOpenRequests($profile);
    }

    public static function retrieveAppointments(int $profile){
        return Profile\Appointment::getAppointments($profile);
    }

    public static function countOpenAppointments(int $profile){
        return Profile\Appointment::getOpenAppointmentsCount($profile);
    }

    public static function getReceipts(int $user){
        return Profile\Receipts::getReceipts($user);        
    }
}