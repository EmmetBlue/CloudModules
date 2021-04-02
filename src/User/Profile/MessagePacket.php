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

class MessagePacket {
    public static function viewByUser(int $user){
        $query = "SELECT a.*, b.user_id, c.profile_alias FROM user_profile_message_packets a
                INNER JOIN user_profile_providers b ON a.profile_id = b.profile_id
                INNER JOIN user_profile_details c On a.profile_id = c.profile_id
                WHERE b.user_id = $user ORDER BY a.packet_arrive_date DESC";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function viewByProfile(int $profile){
        $query = "SELECT a.*, c.profile_alias FROM user_profile_message_packets a
                INNER JOIN user_profile_details c On a.profile_id = c.profile_id
                WHERE a.profile_id = $profile ORDER BY a.packet_arrive_date DESC";
        $result = DBConnectionFactory::getConnection()->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;        
    }
}