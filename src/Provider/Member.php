<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class Member {
	public static function newMember(array $data){
        return Classes\Member::create($data);
    }

	public static function getPhoto(int $resourceId, array $data){
        return Classes\Member::getPhoto($resourceId, $data);
    }

	public static function viewMember(int $resourceId, array $data){
        return Classes\Member::view($resourceId, $data);
    }
}