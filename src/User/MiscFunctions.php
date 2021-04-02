<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class MiscFunctions {
	public static function getProviderMemberPhoto(int $resourceId, array $data){
        return \EmmetBlue\Plugins\Provider\Classes\Member::getPhoto($resourceId, $data);
    }

	public static function viewProviderMember(int $resourceId, array $data){
        return \EmmetBlue\Plugins\Provider\Classes\Member::view($resourceId, $data);
    }
}