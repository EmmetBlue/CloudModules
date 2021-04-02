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

class LabFollowup {
	public static function register(array $data)
    {
        $profile = $data["profile"];
        $investigationCode = $data["labNumber"];
        $investigation = $data["investigation"];
        $requestedBy = $data["requestedBy"];
        $staffId = $data["staffId"];
        $dateRequested = $data["dateRequested"];

        $query = "INSERT INTO user_profile_lab_followups (profile_id, provider_investigation_code, investigation, investigation_requested_by, investigation_date_requested, provider_member_id) VALUES ($profile, '$investigationCode', '$investigation', '$requestedBy', '$dateRequested', '$staffId')";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $query;
    }

    public static function publish(array $data)
    {
        $profile = $data["profile"];
        $investigationCode = $data["labNumber"];

        $query = "UPDATE user_profile_lab_followups SET investigation_publish_status = 1, investigation_date_published = CURRENT_TIMESTAMP WHERE profile_id = $profile AND provider_investigation_code = '$investigationCode'";

        $result = DBConnectionFactory::getConnection()->exec($query);

        return $query;
    }
}