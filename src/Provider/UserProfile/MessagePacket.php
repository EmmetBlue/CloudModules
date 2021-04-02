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

class MessagePacket {
	public static function newPacket(array $data)
    {
    	$profileId = $data["profileId"] ?? null;
        $subject = $data["subject"] ?? null;
        $message = $data["message"] ?? null;
        $provider = $data["providerId"] ?? null;
        $staffId = $data["staffId"] ?? null;

    	if (is_null($profileId) || is_null($subject) || is_null($provider)){
    		throw new \Exception("Invalid data provided");
    	}

    	$query = "INSERT INTO user_profile_message_packets (profile_id, packet_provider_id, packet_subject, packet_content, provider_member_id) VALUES ($profileId, $provider, '$subject', '$message', '$staffId')";
        $result = DBConnectionFactory::getConnection()->exec($query);

        \EmmetBlue\Plugins\User\Classes\PushNotification::sendNotificationByProfile((int) $profileId, [
            "title"=>$subject,
            "message"=>$message
        ]);

        return $result;
    }
}