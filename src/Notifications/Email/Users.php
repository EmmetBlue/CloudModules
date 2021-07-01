<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */

namespace EmmetBlue\Plugins\Notifications\Email;

use EmmetBlue\Core\Factory\MailerFactory as MailerFactory;
use EmmetBlue\Core\Constant;

class Users {

    private static function getConfigs(){
        $smtpConfigJson = file_get_contents(Constant::getGlobals()["config-dir"]["smtp-config"]);

        $smtpConfig = json_decode($smtpConfigJson);
        $currentPath = dirname(__FILE__);

        return [
            "path"=>$currentPath,
            "config"=>$smtpConfig
        ];
    }

	public static function sendProviderRegistrationWelcomeEmail(int $user, string $email = ""){
        $verToken = \EmmetBlue\Plugins\User\Account\Account::generateVerificationToken($user);
        $token = $verToken["token"];

        $configs = self::getConfigs();

        $currentPath = $configs["path"];
        $emailBody = file_get_contents($currentPath."/Users/provider-registration-welcome-email.body.html");
        $emailBody = str_replace("{{token}}", $token, $emailBody);

        $emailSubject = file_get_contents($currentPath."/Users/provider-registration-welcome-email.subject.html");

        $sender = [
            "address"=>$configs["config"]->user,
            "name"=>$configs["config"]->name,
            "replyTo"=>$configs["config"]->user
        ];

        $recipients = [
            ["address"=>$email]
        ];

        $message = [
            "subject"=>$emailSubject,
            "body"=>$emailBody,
            "alt"=>"",
            "isHtml"=>true
        ];

        $mailObj = new MailerFactory($sender, $recipients, $message);

        return $mailObj->send();
    }

    public static function sendDemoEmail(string $email = ""){
        $configs = self::getConfigs();

        $currentPath = $configs["path"];

        $emailBody = file_get_contents($currentPath."/Users/demo.body.html");
        $emailSubject = file_get_contents($currentPath."/Users/demo.subject.html");

        $sender = [
            "address"=>$configs["config"]->user,
            "name"=>$configs["config"]->name,
            "replyTo"=>$configs["config"]->user
        ];

        $recipients = [
            ["address"=>$email]
        ];

        $message = [
            "subject"=>$emailSubject,
            "body"=>$emailBody,
            "alt"=>"",
            "isHtml"=>true
        ];

        $mailObj = new MailerFactory($sender, $recipients, $message);

        return $mailObj->send();
    }
}