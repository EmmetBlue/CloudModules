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

class Profile {
    public static function addData(int $profile, array $data){
        $values = [];
        foreach ($data as $key => $value) {
            if (!is_array($value)){
                $values[] = "($profile, '$key', '$value')";   
            }
        }

        $query = "INSERT INTO user_profile_data (profile_id, item_key, item_value) VALUES ".implode(", ", $values);

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $result;
    }

    public static function getProfileImage(int $profile){
        $query = "SELECT item_value FROM user_profile_data WHERE profile_id = $profile AND item_key = 'patientpicture'";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0]["item_value"] ?? $result;
    }

    public static function viewProfile(int $profile){
        $query = "SELECT a.*, b.provider_alias, b.provider_status, c.profile_alias 
                    FROM user_profile_providers a 
                    INNER JOIN provider b ON a.provider_id = b.provider_id
                    INNER JOIN user_profile_details c ON c.profile_id = a.profile_id
                    WHERE a.profile_id = $profile";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $result = $result[0] ?? $result;

        return $result;
    }

    public static function viewFullProfile(int $profile){
        $query = "SELECT item_key, item_value from user_profile_data WHERE item_key IN ('categoryname', 'patienttypename', 'patientuuid', 'date_of_birth') AND profile_id = $profile";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $data = [];

        foreach ($result as $key=>$value){
            $data[$value["item_key"]] = $value["item_value"];
        }

        $_profile = self::viewProfile($profile);
        $_profile["data"] = $data;

        return $_profile;
    }
}