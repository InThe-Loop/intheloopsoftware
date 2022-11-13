<?php

namespace app\Controllers;

require "./vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

/**
 * PHP email class
 */
class MailController {
    
    /**
     * The email object
     */
    protected $mail;

    /**
     * Class constructor
     */
    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Create an email instance;
        // passing `true` enables exceptions
        $this->mail = new PHPMailer(true);
        // Server settings
        $this->mail->isSMTP();
        // Enable verbose debug output
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // Set the SMTP server to send through
        $this->mail->Host = $_ENV['SMTP_HOST'];
        // Enable SMTP authentication
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['SMTP_USER'];
        $this->mail->Password = $_ENV['SMTP_PASS'];
        // Enable implicit TLS encryption
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $this->mail->Port = 587;
    }

    /**
     * Sends the email
     * 
     * @param array $email_details
     *  The email contents
     * @return mixed
     *  Returns a false or an array
     */
    public function sendEmail(array $email_details, $file_name = null) {
        try {
            $this->mail->setFrom($_ENV['CONTACT_FROM'], $_ENV['CONTACT_NAME']);
            $this->mail->addAddress(strtolower($email_details['email']), ucwords($email_details['name']));
            $this->mail->addReplyTo($_ENV['CONTACT_FROM'], $_ENV['CONTACT_NAME']);
            $this->mail->addBCC($_ENV['CONTACT_FROM'], $_ENV['CONTACT_NAME']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $email_details['subject'];
            if ($file_name) {$this->mail->AddAttachment($file_name);}
            $this->mail->Body = $this->createHtml([
                'name' => $email_details['name'],
                'phone' => $email_details['phone'],
                'body' => $email_details['message'],
            ]);
            
            $result = $this->mail->send();
            if ($result) {
                $response = [
                    'code' => 200,
                    'status' => 'OK',
                    'text' => "Your message was sent successfully. We will be in touch soon. Thank you!",
                ];
                return $response;
            }
            return [
                'code' => 500,
                'status' => false,
                'text' => "Server error. Please try again later.",
            ];
        }
        catch (Exception $e) {
            $response = [
                'code' => 201,
                'status' => 'Error',
                'text' => "Mailer error: " . $this->mail->ErrorInfo,
            ];
            return $response;
        }
    }

    /**
     * Creates an html email
     * 
     * @param array $substitutes
     *  Substitution values
     * @return string $html
     *  The html markup
     */
    public function createHtml(array $substitutes): string {
        $html = '<html><body><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tbody><tr><td align="center">';
        $html .= '<table align="center" cellpadding="0" cellspacing="0"><tbody><tr><td>';
        $html .= '<img src="' . $this->url() . '/public/img/in_the_loop.png" alt="InTheLoop Logo" height="100" />';
        $html .= '</td></tr></tbody></table>';
        $html .= '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #ccc; padding: 10px;">';
        $html .= '<tbody><tr><td align="left">';
        
        $html .= '<h1>Hi ' . $substitutes['name'] . '</h1>
            <p>This is an acknowledgement that we have received your message containing the details below.</p>
            <p>
                Your telephone number:<br />
                <div style="background-color: #ccc; padding: 10px;">' . $substitutes['phone'] . '</div>
            </p>
            <p>
                Your message:<br />
                <div style="background-color: #ccc; padding: 10px;">' . $substitutes['body'] . '</div>
            </p>
            <p>
                We are busy attending to this message and we will be in touch as soon as possible.
                You may reply to this email, if you wish to do a follow up.
            </p>
            <p>
                Thank you and regards,<br />
                <strong>The InTheLoop Online Team</strong>
            </p>';
        
        $html .= '<center>All rights reserved ' . date("Y") . '<br />InTheLoop Software Solutions &copy;</center>';
        $html .= '</td></tr></tbody></table></td></tr></tbody></table></body></html>';

        return $html;
    }

    public function url() {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $link = "https://" . $_SERVER['HTTP_HOST'];
        }
        else {
            $link = "http://" . $_SERVER['HTTP_HOST'];
        }
        return $link;
    }
}
