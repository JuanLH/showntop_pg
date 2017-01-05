<?php
class Utilities{
	public static function getConnection(){
		include_once "DbPDO.php";
		$db = new DbPDO("pgsql", "127.8.218.130", "5432", "adminjiusmji", "gj1BUDwx-sZR", "showntop");
    //$db = new DbPDO("pgsql", "127.0.0.1", "5432", "postgres", "JuanLH@21", "showntop");
		return $db;
	}
        
        public static function sendEmail($toAddress){
            $db = Utilities::getConnection();
            $userData = $db->thereAreUser($toAddress);
            
            if(!$userData===false){
                /**
                * This example shows settings to use when sending via Google's Gmail servers.
                */

               //SMTP needs accurate times, and the PHP time zone MUST be set
               //This should be done in your php.ini, but this is how to do it if you don't have access to that
               date_default_timezone_set('Etc/UTC');

               require 'PHPMailer/PHPMailerAutoload.php';

               //Create a new PHPMailer instance
               $mail = new PHPMailer;

               //Tell PHPMailer to use SMTP
               $mail->isSMTP();

               //Enable SMTP debugging
               // 0 = off (for production use)
               // 1 = client messages
               // 2 = client and server messages
               $mail->SMTPDebug = 0;

               //Ask for HTML-friendly debug output
               $mail->Debugoutput = 'html';

               //Set the hostname of the mail server
               $mail->Host = 'smtp.gmail.com';
               // use
               // $mail->Host = gethostbyname('smtp.gmail.com');
               // if your network does not support SMTP over IPv6

               //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
               $mail->Port = 587;

               //Set the encryption system to use - ssl (deprecated) or tls
               $mail->SMTPSecure = 'tls';

               //Whether to use SMTP authentication
               $mail->SMTPAuth = true;

               //Username to use for SMTP authentication - use full email address for gmail
               $mail->Username = "juanlhiciano.social@gmail.com";

               //Password to use for SMTP authentication
               $mail->Password = "JuanLHiciano@21";

               //Set who the message is to be sent from
               $mail->setFrom('juanlhiciano.social@gmail.com', 'ShownTop');

               //Set an alternative reply-to address
               //$mail->addReplyTo('juanlhicianoworks@gmail.com', 'First Last');
               
               
               //Link for set password
                $link = "http://showntop-tomcatws.rhcloud.com//index.php?section=recuperarClave&user=".$userData[0]['nombre']."&securitycode=".$userData[0]['detalle']."";
               //Set who the message is to be sent to
               $mail->addAddress($toAddress, $userData[0]['nombre']);

               //Set the subject line
               $mail->Subject = 'Recuperar de clave - ShownTop';

               //Read an HTML message body from an external file, convert referenced images to embedded,
               //convert HTML into a basic plain-text alternative body
               $content = '<h2>Saludos</h2><h1>'.$userData[0]['nombre'].'</h1><br><b>Para establecer una nueva clave haga click en el siguiente enlace</b>'
                       . '<br>'.$link.'';
               //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
               $mail->msgHTML($content);
               //Replace the plain text body with one created manually
               $mail->AltBody = 'This is a plain-text message body';

               //Attach an image file
               //$mail->addAttachment('images/phpmailer_mini.png');

               //send the message, check for errors
               if (!$mail->send()) {
                   echo "Mailer Error: " . $mail->ErrorInfo;
               } else {
                  // echo  "Message sent!";
                   return "Le Hemos Enviado un Correo (Verifique por favor)";
               }
                
                /*require_once "Mail.php";
                $from = 'juanlhiciano.social@gmail.com';
                $to = $toAddress;
                $subject = 'Recuperacion De Clave - Valoracion Online ';
                $body = "http://showntop.lo/index.php?section=recuperarClave&user=".$userData[0]['nombre']."&securitycode=".$userData[0]['detalle']."";
                var_dump($body);exit();
                $headers = array(
                    'From' => $from,
                    'To' => $to,
                    'Subject' => $subject
                );

                $smtp = Mail::factory('smtp', array(
                        'host' => 'ssl://smtp.gmail.com',
                        'port' => '465',
                        'auth' => true,
                        'username' => 'juanlhiciano.social@gmail.com',
                        'password' => 'JuanLHiciano@21'
                    ));

                $mail = $smtp->send($to, $headers, $body);

                if (PEAR::isError($mail)) {
                    echo('<p>' . $mail->getMessage() . '</p>');
                } else {
                    echo('<p>Message successfully sent!</p>');
                }*/
            }
            else{
                return "No hay usuarios con este correo";
            }
        }

        
        public static function sendGmailExample(){
            /**
            * This example shows settings to use when sending via Google's Gmail servers.
            */

           //SMTP needs accurate times, and the PHP time zone MUST be set
           //This should be done in your php.ini, but this is how to do it if you don't have access to that
           date_default_timezone_set('Etc/UTC');

           require 'PHPMailer/PHPMailerAutoload.php';

           //Create a new PHPMailer instance
           $mail = new PHPMailer;

           //Tell PHPMailer to use SMTP
           $mail->isSMTP();

           //Enable SMTP debugging
           // 0 = off (for production use)
           // 1 = client messages
           // 2 = client and server messages
           $mail->SMTPDebug = 2;

           //Ask for HTML-friendly debug output
           $mail->Debugoutput = 'html';

           //Set the hostname of the mail server
           $mail->Host = 'smtp.gmail.com';
           // use
           // $mail->Host = gethostbyname('smtp.gmail.com');
           // if your network does not support SMTP over IPv6

           //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
           $mail->Port = 587;

           //Set the encryption system to use - ssl (deprecated) or tls
           $mail->SMTPSecure = 'tls';

           //Whether to use SMTP authentication
           $mail->SMTPAuth = true;

           //Username to use for SMTP authentication - use full email address for gmail
           $mail->Username = "juanlhiciano.social@gmail.com";

           //Password to use for SMTP authentication
           $mail->Password = "JuanLHiciano@21";

           //Set who the message is to be sent from
           $mail->setFrom('juanlhiciano.social@gmail.com', 'Juan Hiciano');

           //Set an alternative reply-to address
           //$mail->addReplyTo('juanlhicianoworks@gmail.com', 'First Last');

           //Set who the message is to be sent to
           $mail->addAddress('juanlhicianoworks@gmail.com', 'Juan Hiciano');

           //Set the subject line
           $mail->Subject = 'PHPMailer GMail SMTP test';

           //Read an HTML message body from an external file, convert referenced images to embedded,
           //convert HTML into a basic plain-text alternative body
           $content = '<b>klk pasa</b> estamos probando <h1>esto</h1>';
           //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
           $mail->msgHTML($content);
           //Replace the plain text body with one created manually
           $mail->AltBody = 'This is a plain-text message body';

           //Attach an image file
           //$mail->addAttachment('images/phpmailer_mini.png');

           //send the message, check for errors
           if (!$mail->send()) {
               echo "Mailer Error: " . $mail->ErrorInfo;
           } else {
               echo "Message sent!";
           }
            
        }

         public static function getWname(){
            return "http://localhost/showntop/public/index.php";
         }
}
?>