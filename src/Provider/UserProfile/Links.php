<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider\UserProfile;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;

class Links {
	public static function newLink(int $user, array $data)
    {
    	$provider = $data["provider"] ?? null;
        $userProviderID = $data["userProviderId"] ?? null;
        $alias = $data["alias"] ?? null;

    	if (is_null($provider) || is_null($userProviderID)){
    		throw new \Exception("Invalid data provided");
    	}

    	$query = "INSERT INTO user_profile_providers (user_id, user_provider_id, provider_id) VALUES ($user, $userProviderID, $provider)";
        
        $result = DBConnectionFactory::getConnection()->exec($query);

        if ($result){
            $id = DBConnectionFactory::getConnection()->query("SELECT profile_id FROM user_profile_providers WHERE provider_id = $provider AND user_id = $user AND user_provider_id = $userProviderID")->fetchAll(\PDO::FETCH_ASSOC)[0]["profile_id"];
            $query = "INSERT INTO user_profile_details (profile_id, profile_alias) VALUES ($id, '$alias')";

            $result = DBConnectionFactory::getConnection()->exec($query);
            if ($result){
                $result = ["profile_id"=>$id];
            }
        }   

    	return $result;
    }

    public static function viewLinks(int $user){
        $query = "SELECT a.*, b.provider_alias, b.provider_status, c.profile_alias 
                    FROM user_profile_providers a 
                    INNER JOIN provider b ON a.provider_id = b.provider_id
                    INNER JOIN user_profile_details c ON c.profile_id = a.profile_id
                    WHERE a.user_id = $user";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}