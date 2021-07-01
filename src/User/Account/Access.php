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

class Access {
    protected static function log(int $account, array $data){
        $ip = $data["access_ip"];
        $client = $data["access_client"];

        $token = $data["temp_token"];

        $query = "INSERT INTO user_account_access_log (account_id, access_ip, access_client) VALUES ($account, '$ip', '$client');";
        $query .= "INSERT INTO user_account_access (account_id, access_token) VALUES ($account, '$token');";

        if (DBConnectionFactory::getConnection()->exec($query)){
            $query = "UPDATE user_account SET lock_status = 1 WHERE account_id = $account;";
            return DBConnectionFactory::getConnection()->exec($query);
        }

        return false;
    }

    public static function authenticate(array $data){
        $username = $data["username"] ?? null;
        $password = $data["password"] ?? null;

        if (is_null($username) || is_null($password)){
            return false;
        }

        $query = "SELECT * FROM user_account WHERE username = '$username' LIMIT 1";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        if (isset($result[0])){
            $account = $result[0];

            return password_verify($password, $account["password"]); 
        }

        return false;
    }

    public static function getUserId(int $user){
        $query = "SELECT a.*, b.account_id FROM user_id a INNER JOIN user_account b ON a.user_id = b.user_id WHERE a.user_id = $user";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0] ?? [];
    }

    public static function getUserIdFromUsername(array $data){
        $username = $data["username"];

        $query = "SELECT a.*, b.account_id FROM user_id a INNER JOIN user_account b ON a.user_id = b.user_id WHERE b.username = '$username'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0] ?? [];
    }

    public static function getUserIdFromEmail(array $data){
        $username = $data["email"];

        $query = "SELECT a.*, b.account_id FROM user_id a INNER JOIN user_account b ON a.user_id = b.user_id WHERE a.email_address = '$username'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0] ?? [];
    }

    public static function getUserIdFromPhone(array $data){
        $username = $data["email"];

        $query = "SELECT a.*, b.account_id FROM user_id a INNER JOIN user_account b ON a.user_id = b.user_id WHERE a.phone_number = '$username'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0] ?? [];
    }

    public static function closeAccountSessions(int $account){
        $query = "SELECT lock_status FROM user_account WHERE account_id = $account";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        if (isset($result[0]) && $result[0]["lock_status"] == 1){
            $query = "DELETE FROM user_account_access WHERE account_id = $account; UPDATE user_account SET lock_status = 0 WHERE account_id = $account";

            return DBConnectionFactory::getConnection()->exec($query);
        }
    }

    public static function newSession(array $data){
        $username = $data["username"] ?? null;
        $password = $data["password"] ?? null;
        $ip = $data["access"]["ip"] ?? null;
        $client = $data["access"]["client"] ?? null;

        if (!(is_null($username) || is_null($password)) && self::authenticate(["username"=>$username,"password"=>$password])){
            $token = bin2hex(random_bytes(16));
            $user = self::getUserIdFromUsername(["username"=>$username]);

            self::closeAccountSessions((int) $user["account_id"]);

            $logData = [
                "access_ip"=>$ip,
                "access_client"=>$client,
                "temp_token"=>$token
            ];

            if (self::log((int) $user["account_id"], $logData)){
                $res = \EmmetBlue\Plugins\Notifications\Email\Users::sendDemoEmail("samueladeshina73@gmail.com");
                return [
                    "status"=>true,
                    "user"=>$user,
                    "token"=>$token,
                    "res"=>$res
                ];
            }
        }

        return [
            "status"=>false
        ];
    }

    public static function verifyToken(array $data){
        $account = $data["account"];
        $token =  $data["token"];

        $query = "SELECT 1 FROM user_account_access WHERE account_id = $account AND access_token = '$token'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0] ?? false;
    }
}