<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider\Account;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;

class Account {
	public static function create(int $user, array $data)
    {
    	$alias = $data["alias"] ?? null;

    	if (is_null($alias)){
    		throw new \Exception("Invalid data provided");
    	}

    	$query = "INSERT INTO provider (provider_alias) VALUES ('$alias')";
        try {
            $result = DBConnectionFactory::getConnection()->exec($query);           
        }
        catch(\PDOException $e){
            $result = false;
        }

    	return $result;
    }

    public static function getProvider(int $provider){
        $query = "SELECT * FROM provider WHERE provider_id = $provider";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0] ?? false;
    }

    public static function getProviderByAlias(array $data){
        $provider = $data["alias"];
        $query = "SELECT * FROM provider WHERE provider_alias = '$provider'";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0] ?? false;
    }

    protected static function toggleStatus(int $provider, bool $lock){
        $lock = (int)$lock;
        $query = "UPDATE provider SET provider_status = $lock WHERE provider_id = $account";

        return DBConnectionFactory::getConnection()->exec($query);
    }

    public static function setStatus(int $provider, array $data){
        return self::toggleStatus($provider, (bool) $data["status"]);
    }
}