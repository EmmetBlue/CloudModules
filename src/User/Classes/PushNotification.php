<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\User\Classes;

use EmmetBlue\Core\Builder\BuilderFactory as Builder;
use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Factory\DatabaseQueryFactory as DBQueryFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Exception\SQLException;
use EmmetBlue\Core\Session\Session;
use EmmetBlue\Core\Logger\DatabaseLog;
use EmmetBlue\Core\Logger\ErrorLog;
use EmmetBlue\Core\Constant;
use Minishlink\WebPush\WebPush;


class PushNotification {
	public static function subscribe(int $user, array $data){
		$pKey = $data["keys"]["p256dh"];
		$aToken = $data["keys"]["auth"];
		$endpoint = $data["endpoint"];
		$status = (int) $data["status"];

		$query = "INSERT INTO user_push_subscriptions (user_id, endpoint, public_key, auth_token) VALUES ($user, '$endpoint', '$pKey', '$aToken')";
		try {
			$result = DBConnectionFactory::getConnection()->exec($query);	
		}
		catch (\Exception $e){
			$query = "UPDATE user_push_subscriptions SET endpoint = '$endpoint' WHERE user_id = $user AND (public_key = '$pKey' OR auth_token = '$aToken');".
					 "UPDATE user_push_subscriptions SET public_key = '$pKey' WHERE user_id = $user AND (endpoint = '$endpoint' OR auth_token = '$aToken');".
					 "UPDATE user_push_subscriptions SET auth_token = '$aToken' WHERE user_id = $user AND (public_key = '$pKey' OR endpoint = '$endpoint'";

			$result = DBConnectionFactory::getConnection()->exec($query);
		}

		if ($result){
			$query = "UPDATE user_push_subscriptions SET subscription_status = $status WHERE user_id = $user AND public_key = '$pKey' AND auth_token = '$aToken' AND endpoint = '$endpoint'";
			$result = DBConnectionFactory::getConnection()->exec($query);

			if ($result == 0){
				$result = 1;
			}

			return $result;
		}

		return false;
    }

    public static function sendNotification(int $user, array $data){
    	$auth = [
    		"VAPID"=>[
    			"subject"=>"https://cloud.emmetblue.ng",
    			"publicKey"=>"BJmy_J74pOv1peZA3LIEK1R9sHGb3ULNofbw8sIrqQob8uPqXtTj9TvcO716Sqke5wRfddWlVIxytuscYaDUmbk",
    			"privateKey"=>"TxxEWbqRAZq9x8OCic6ziJofxz3ipLTmbDN4mWmPuNI"
    		]
    	];

    	$webPush = new WebPush($auth);

    	$query = "SELECT * FROM user_push_subscriptions WHERE user_id = $user AND subscription_status = 1";
    	$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

    	foreach ($result as $subscription){
    		$webPush->sendNotification(
    			$subscription["endpoint"],
    			json_encode($data),
    			$subscription["public_key"],
    			$subscription["auth_token"]
    		);
    	};

    	$webPush->flush();

    	return;
    }

    public static function sendNotificationByProfile(int $profile, array $data){
    	$query = "SELECT user_id FROM user_profile_providers WHERE profile_id = $profile";
    	$result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC)[0];

    	return self::sendNotification((int) $result["user_id"], $data);
    }
}