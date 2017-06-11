<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User\Profile;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;

class Provider {
	public static function newLink(int $user, array $data)
    {
    	$provider = $data["provider"] ?? null;
        $alias = $data["alias"] ?? null;

    	if (is_null($provider)){
    		throw new \Exception("Invalid data provided");
    	}

    	$query = "INSERT INTO user_profile_providers (user_id, provider_id) VALUES ($user, $provider)";
        try {
            $result = DBConnectionFactory::getConnection()->exec($query);

            if ($result){
                $id = DBConnectionFactory::getConnection()->query("SELECT provider_id FROM provider WHERE provider_alias = '$provider'")->fetchAll(\PDO::FETCH_ASSOC)[0]["provider_id"];
                $query = "INSERT INTO user_profile_details (profile_id, profile_alias) VALUES ($id, '$alias')";

                $result = DBConnectionFactory::getConnection()->exec($query);
            }           
        }
        catch(\PDOException $e){
            $result = false;
        }

    	return $result;
    }

    public static function viewLinks(int $user){
        $query = "SELECT a.*, b.provider_alias, b.provider_status, c.profile_alias 
                    FROM user_profile_providers a 
                    INNER JOIN provider b ON a.provider_id = b.provider_id
                    INNER JOIN user_profile_details c ON a.user_id = c.user_id
                    WHERE a.user_id = $user";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}