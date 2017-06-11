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

    public static function getProviderByAlias(array $resource){
        return Account\Account::getProviderByAlias($resource);
    }
    
    public static function setStatus(int $provider, array $data){
        return Account\Account::setStatus($provider, $data);
    }
}