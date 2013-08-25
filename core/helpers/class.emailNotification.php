<?php
/**
 * Created by Miklós Galicz.
 * galicz.miklos@hinora.hu
 * http://hinora.hu
 * Date: 2013.08.16.
 * Time: 9:02
 */

class emailNotification {
    /**
     * @var null|PHPMailer
     */
    private $mailer = null;

    /**
     * @var null|emailNotificationsModel
     */
    private $model = null;

    function __construct() {
        $this->model = new emailNotificationsModel();

        $this->model->checkTable();

        $this->mailer = new PHPMailer();

        if (_SEND_NOTIFICATION) {
            $this->configMailer();
        }

    }

    /**
     * PHPMailer konfiguráció
     */
    private function configMailer() {

        $this->mailer->IsSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Host = _SMTP_HOST;
        $this->mailer->Port = _SMTP_PORT;
        $this->mailer->SMTPDebug = 0;
        $this->mailer->Username = _SMTP_USER;
        $this->mailer->Password = _SMTP_PASS;
        $this->mailer->SetFrom(_EMAIL_FROM_ADDRESS,_EMAIL_FROM_NAME);
        $this->mailer->IsHTML(true);

        /*
         * Ha nincs válasz cím, akkor a feladó adatai kerüljenek a válasz mezőbe
         */
        if (_EMAIL_REPLY_ADDRESS == '' OR _EMAIL_REPLY_NAME == '') {
            $this->mailer->AddReplyTo(_EMAIL_FROM_ADDRESS,_EMAIL_FROM_NAME);
        } else {
            $this->mailer->AddReplyTo(_EMAIL_REPLY_ADDRESS,_EMAIL_REPLY_NAME);
        }

    }

    function send() {

        if (_SEND_NOTIFICATION) {

            try {
                return $this->mailer->Send();
            } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
                return false;
        }


        }

        return true;
    }



    function winningUserNotification() {
        $gameData = $_SESSION['activeGame'];
        $template = $this->model->getEmailTemplate(1);

        $userData = $_SESSION['solverData'];

        $placeHolder['userName'] = $userData['name'];
        $placeHolder['email'] = $userData['email'];
        $placeHolder['quizName'] = $gameData['title_' . $_SESSION['lang']];
        $placeHolder['prize'] = $gameData['prize_' . $_SESSION['lang']];

        $this->mailer->AddAddress($userData['email'],$userData['name']);

        $subject = $template['subject_' . $_SESSION['lang']];
        $content = htmlspecialchars_decode(htmlspecialchars_decode($template['content_' . $_SESSION['lang']]));

        foreach ($placeHolder AS $key=>$val) {
            $subject = str_replace('[' . $key . ']',$val,$subject);
            $content = str_replace('[' . $key . ']',$val,$content);
        }

        $this->mailer->Subject = $subject;
        $this->mailer->Body = $content;
        $this->mailer->AltBody = strip_tags($content);

        return $this->send();
    }

    function adminWinnerNotification() {

    }

}