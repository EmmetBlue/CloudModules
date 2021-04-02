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

class Receipt {
	public static function newReceipt(array $data)
    {
        $profile = $data["profile"];
        $provider = $data["provider"];
        $description = $data["description"];
        $receipt = $data["receipt"];
        $staffId = $data["staffId"];

        $query = "INSERT INTO user_profile_receipts (profile_id, provider_id, provider_member_id, receipt_description, receipt) VALUES ($profile, $provider, '$staffId', '$description', '$receipt')";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $result;
    }
}