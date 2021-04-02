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

class Access {
    public static function verifySecret(array $data){
        $account = $data["account"];
        $token =  $data["secret"];

        $query = "SELECT 1 FROM provider WHERE provider_id = $account AND provider_secret = '$token'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0] ?? false;
    }
}