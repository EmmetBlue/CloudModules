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

class Appointment {
	public static function getAppointments(int $profile)
    {
        $query = "SELECT a.*, b.provider_alias, c.member_name, c.member_description from user_profile_provider_appointments a 
                    INNER JOIN provider b ON a.provider_id = b.provider_id 
                    INNER JOIN provider_member_data c ON a.provider_member_id = c.provider_member_id
                    WHERE a.profile_id=$profile";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getOpenAppointmentsCount(int $profile){
    	$query = "SELECT COUNT(*) as total from user_profile_provider_appointments a WHERE a.profile_id=$profile";

        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0];
    }
}