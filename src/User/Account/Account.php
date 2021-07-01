<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User\Account;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;
use Samshal\Rando\Rando as Rando;

class Account {
	public static function create(int $user, array $data)
    {
    	$username = $data["username"] ?? null;
    	$password = $data["password"] ?? null;

    	if (is_null($username) || is_null($password)){
    		throw new \Exception("Invalid data provided");
    	}

        $password = password_hash($password, PASSWORD_DEFAULT);

    	$query = "INSERT INTO user_account (user_id, username, password) VALUES ($user, '$username', '$password')";
        try {
            $result = DBConnectionFactory::getConnection()->exec($query);           
        }
        catch(\PDOException $e){
            $result = false;
        }

    	return $result;
    }

    public static function activate(int $account){
        $query = "UPDATE user_account SET verify_status = 1 WHERE account_id = $account";

        return DBConnectionFactory::getConnection()->exec($query);
    }

    protected static function toggleLockStatus(int $account, bool $lock){
        $lock = (int)$lock;
        $query = "UPDATE user_account SET lock_status = $lock WHERE account_id = $account";

        return DBConnectionFactory::getConnection()->exec($query);
    }

    public static function setLockStatus(int $account, array $data){
        return self::toggleLockStatus($account, (bool) $data["status"]);
    }

    public static function viewAccount(int $account){
        $query = "SELECT a.account_id, b.* from user_account a INNER JOIN user_id b ON a.user_id = b.user_id WHERE a.account_id = $account";

        return DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function generateVerificationToken(int $user){
        $token = substr(str_shuffle(MD5(microtime())), 0, 7); //Rando::text(["length"=>7]);

        $query = "SELECT * FROM user_verification_token WHERE user_id=$user";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) > 0){
            $query = "UPDATE user_verification_token SET token='$token', token_guesses=0 WHERE user_id = $user";
        }
        else {
            $query = "INSERT INTO user_verification_token (user_id, token, token_guesses) VALUES ($user, '$token', 0) ";
        }

        $result = DBConnectionFactory::getConnection()->exec($query);

        return ["token"=>$token, "user"=>$user];
    }

    private static function confirmVerificationToken($user, $token){
        $query = "SELECT token, token_guesses FROM user_verification_token WHERE user_id = $user";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) > 0){
            $guesses = $result[0]["token_guesses"];
            if ($guesses < 3 && $token == $result[0]["token"]){
                $query = "UPDATE user_verification_token SET token_guesses=4 WHERE user_id = $user";
                DBConnectionFactory::getConnection()->exec($query);

                return true;
            }
            else {
                $guess = $guesses+1;
                $query = "UPDATE user_verification_token SET token_guesses=$guess WHERE user_id = $user";
                DBConnectionFactory::getConnection()->exec($query);
            }
        }

        return false;
    }

    public static function updatePassword(array $data){
        $user = $data["user"];
        $token = $data["token"];
        $password = $data["password"];

        if (self::confirmVerificationToken($user, $token)){
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE user_account SET password = '$password' WHERE user_id = $user";
            $result = DBConnectionFactory::getConnection()->exec($query);

            $query = "SELECT username FROM user_account WHERE user_id = $user";
            $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

            return ["status"=>true, "account"=>$result[0]];
        }

        return ["status"=>false, "reason"=>"Invalid token"];
    }
}