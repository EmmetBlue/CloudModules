<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */
namespace EmmetBlue\Plugins\Provider;

class Receipt {
	public static function upload(array $data){
        return UserProfile\Receipt::newReceipt($data);
    }
}