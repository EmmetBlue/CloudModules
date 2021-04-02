<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider\Classes;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;

class Member {
    public static function create(array $data){
        $memberId = $data["memberId"];
        $memberName = $data["memberName"];
        $memberPhoto = $data["memberPhoto"];
        $memberDesc = $data["memberDesc"];
        $providerId = $data["providerId"];

        $query = "INSERT INTO provider_member_data(provider_id, provider_member_id, member_name, member_photo, member_description) VALUES ($providerId, $memberId, '$memberName', '$memberPhoto', '$memberDesc')";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $result;
    }

    public static function getPhoto(int $providerId, array $data){
        $memberId = $data["memberId"];

        $query = "SELECT member_photo FROM provider_member_data WHERE provider_id = $providerId AND provider_member_id = '$memberId'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $result = $result[0] ?? $result;

        return $result;
    }

    public static function view(int $providerId, array $data){
        $memberId = $data["memberId"];

        $query = "SELECT member_name, member_description, member_id FROM provider_member_data WHERE provider_id = $providerId AND provider_member_id = '$memberId'";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $result = $result[0] ?? $result;

        return $result;
    }
}