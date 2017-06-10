<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class Account {
	public static function newAccount(array $data){
        return Account\Account::create($data);
    }

    public static function getProvider(int $resourceId){
        return Account\Account::getProvider($resourceId);
    }

    public static function getProviderByAlis(array $resource){
        return Account\Account::getProviderByAlis($resource);
    }
}