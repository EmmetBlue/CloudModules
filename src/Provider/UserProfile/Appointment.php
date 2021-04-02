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

class Appointment {
	public static function create(array $data)
    {
        $profile = $data["profile"];
        $appointmentId = $data["appointmentId"];
        $provider = $data["provider"];
        $appointmentReason = $data["appointmentReason"];
        $appointmentDate = $data["appointmentDate"];
        $staffId = $data["staffId"];    

        $appointmentDate = date('Y-m-d', strtotime(str_replace('-', '/', $appointmentDate)));

        $query = "INSERT INTO user_profile_provider_appointments (provider_appointment_id, profile_id, provider_id, provider_member_id, appointment_date, appointment_reason) VALUES ('$appointmentId', $profile, $provider, '$staffId', '$appointmentDate', '$appointmentReason')";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $result;
    }

    public static function delete(array $data){
        $providerId = $data["provider"];
        $appointment = $data["appointmentId"];

        $query = "DELETE FROM user_profile_provider_appointments WHERE provider_appointment_id = '$appointment' AND provider_id = '$providerId'";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $result;
    }
}