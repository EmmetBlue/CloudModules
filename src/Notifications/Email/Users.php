<?php declare(strict_types=1);
/**
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 */

namespace EmmetBlue\Plugins\Notifications\Email;

use EmmetBlue\Core\Factory\MailerFactory as MailerFactory;

class Users {

	public static function sendProviderRegistrationWelcomeEmail(int $user, string $email = ""){
        $verToken = \EmmetBlue\Plugins\User\Account\Account::generateVerificationToken($user);
        $token = $verToken["token"];

        $currentPath = dirname(__FILE__);
        $emailBody = file_get_contents($currentPath."/Users/provider-registration-welcome-email.body.html");
        $emailBody = str_replace("{{token}}", $token, $emailBody);

        $emailSubject = file_get_contents($currentPath."/Users/provider-registration-welcome-email.subject.html");

        $sender = [
            "address"=>"info@emmetblue.ng",
            "name"=>"EmmetBlue",
            "replyTo"=>"info@emmetblue.ng"
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
        $currentPath = dirname(__FILE__);

        $emailBody = file_get_contents($currentPath."/Users/demo.body.html");
        $emailSubject = file_get_contents($currentPath."/Users/demo.subject.html");

        $sender = [
            "address"=>"info@emmetblue.ng",
            "name"=>"EmmetBlue",
            "replyTo"=>"info@emmetblue.ng"
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