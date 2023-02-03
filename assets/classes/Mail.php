<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    protected $sql;
    protected $mails;
    protected $cc;
    protected $mailtext;
    protected $attachement;
    protected $subject;
    protected $mailAlttext;

    protected $mailhost;
    protected $mailuser;
    protected $mailpassword;
    protected $mailport;

    public $MailI = 0;
    public $ccI = 0;
    public $i = 0;
    public $error;

    function __construct($sql)
    {
        $this->sql = $sql;
        $this->mailhost = $_ENV['MAIL_HOST'];
        $this->mailuser = $_ENV['MAIL_USER'];
        $this->mailpassword = $_ENV['MAIL_PASSWORD'];
        $this->mailport = $_ENV['MAIL_PORT'];
    }

    function setMailAdres(string $mail, string $name = null)
    {
        $this->mails[$this->MailI]['email'] = $mail;
        if (!empty($name)) {
            $this->mails[$this->MailI]['name'] = $name;
        }

        $this->MailI++;
    }

    function setCCAdres(string $mail)
    {
        $this->cc[$this->ccI] = $mail;
        $this->ccI++;
    }

    function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    function setMail(string $file, array $changeables)
    {
        if (file_exists("./assets/mails/$file")) {
            echo "YES";
            $this->mailtext = file_get_contents('./assets/mails/'.$file);
            foreach ($changeables as $key => $value) {
                $capkey = strtoupper($key);

                $this->mailtext = str_replace("{{" . $capkey . "}}", $value, $this->mailtext);
            }
        } else {
            $this->error[$this->i]['type'] = 'NOMAILFILE';
            $this->error[$this->i]['message'] = 'Mail file not found, please try again!';
            $this->i++;
        }
    }

    function setAltMail(string $text)
    {
        $this->mailAlttext = $text;
    }

    function sendMail()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->mailhost;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->mailuser;                     //SMTP username
            $mail->Password   = $this->mailpassword;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $this->mailport;

            $mail->setFrom('noreply@zerdardian.com', 'Zerdardian');
            foreach ($this->mails as $email) {
                if(!empty($email['name'])) {
                    $mail->addAddress($email['email'], $email['name']);
                } else {
                    $mail->addAddress($email['email']);
                }
            }
            if (!empty($this->cc)) {
                foreach ($this->cc as $email) {
                    $mail->addCC($email);
                }
            }

            if (!empty($this->attachement)) {
                $mail->addAttachment($this->attachement);
            }

            $mail->isHTML(true);
            $mail->Subject =        $this->subject;
            $mail->Body =           $this->mailtext;
            $mail->AltBody =        $this->mailAlttext;
            print_r($mail);

            if ($mail->send()) {
                return 'Mail verzonden';
            } else {
                return 'Mail niet verzonden';
            }
        } catch (Exception $e) {
            $this->error[$this->i]['type'] = 'ERRNOMAIL';
            $this->error[$this->i]['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $this->i++;
        }
    }
}
