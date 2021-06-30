<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;

class Registration {
	public static function newRegistration(array $data)
    {
    	$username = $data["username"] ?? null;
    	$password = $data["password"] ?? null;
    	$alias = $data["alias"] ?? null;
    	$mail = $data["email"] ?? null;
        
    	if (is_null($username) || is_null($password) || is_null($mail) || is_null($alias)){
    		throw new \Exception("Invalid data provided");
    	}

    	$query = "INSERT INTO user_id (email_address, user_alias) VALUES ('$mail', '$alias')";
    	try {
            $result = DBConnectionFactory::getConnection()->exec($query);           
        }
        catch(\PDOException $e){
            $result = false;
        }

    	if ($result){
    		$id = DBConnectionFactory::getConnection()->query("SELECT user_id FROM user_id where email_address = '$mail'")->fetchAll(\PDO::FETCH_ASSOC)[0]["user_id"];

    		$result = Account\Account::create((int) $id, ["username"=>$username, "password"=>$password]);
    		if ($result){
    			return $result;
    		}
    		else {
    			self::dropUser((int) $id);
    			throw new \Exception("Unable to complete registration");
    		}
    	}
    	else {
    		throw new \Exception("Unable to complete registration");
    	}
    }

    public static function dropUser(int $user){
    	$query = "DELETE FROM user_id WHERE user_id = $user";

    	return DBConnectionFactory::getConnection()->exec($query);
    }
}