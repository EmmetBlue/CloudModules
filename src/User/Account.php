<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

class Account {
	public static function activate(int $account){
        return Account\Account::activate($account);
    }
    
    public static function setLockStatus(int $account, array $data){
        return Account\Account::setLockStatus($account, $data);
    }

    public static function login(array $data){
    	return Account\Access::newSession($data);
    }

    public static function closeSession(int $account){
    	return Account\Access::closeAccountSessions($account);
    }

    public static function verifyToken(array $data){
    	return Account\Access::verifyToken($data);
    }
}