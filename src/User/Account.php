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
}