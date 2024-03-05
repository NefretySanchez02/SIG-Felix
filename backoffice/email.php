<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once './PHPMailer/src/Exception.php';
require_once './PHPMailer/src/PHPMailer.php';

sendUserContactConfirmMail('ralfonsosanchezh@gmail.com');
function sendUserContactConfirmMail($userMail){
  $mail = new PHPMailer(true);

  try {
      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail->IsMail();

      //Recipients
      $mail->setFrom('eventos@sanlazaro.co', 'San Lazaro');
      $mail->addAddress($userMail);     // Add a recipient
      $mail->addReplyTo('eventos@sanlazaro.co');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');


      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'San Lazaro - Gracias por tu interés';
      $mail->Body    = getHTMLMailTemplate();
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      //echo 'Message has been sent';
  } catch (Exception $e) {
      //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}


function getHTMLMailTemplate () {
  return <<<HTML
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>

    <style type="text/css">
  @media screen {
    @font-face {
      font-family: 'Open Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Open Sans'), local('Open-Sans'), url(https://fonts.gstatic.com/s/opensans/v18/mem8YaGs126MiZpBA-UFVZ0bf8pkAg.woff2) format('woff');
    }


    body {
      font-family: "Open Sans", "Lucida Grande", "Lucida Sans Unicode", Tahoma, Sans-Serif;
      margin: 0;
      padding: 0;
    }
  }
  </style>
  </head>
  <body>
    <center>
      <table align="center" border="0" cellpadding="0" cellspacing="0" width="650" style="border-collapse: collapse; font-size: 14px;">
        <tr>
        <td bgcolor="#f6f8fe" align="center" style="padding: 50px 0;">
            <img src="https://www.sanlazaro.co/assets/img/sl-logo.png" alt="San Lazaro" height="90">
        </td>
        </tr>

        <tr>
        <td bgcolor="#f6f8fe" align="justify" style="color: #636363; padding: 0 50px;">
            <h2 style="color: #227ba7;">GRACIAS POR SU INTER&Eacute;S</h2>
            <p>Nuestro equipo est&aacute; trabajando para dar respuesta a su requerimiento en el menor tiempo posible. <br><br>
            Para alg&uacute;n comentario o requerimiento adicional puede escribirnos a <b>eventos@sanlazaro.co</b> o comunicarse a los tel&eacute;fonos <b>+57 (5) 642 4280 / +57 (301)234 3592</b>        
          </p>
          <br>
          <hr width="30" style="background-color: #c87200; height: 5px; border: none; display: inline-block;" >
          <br><br>
          <p>pol&iacutetica de privacidad: Nuestra Pol&iacutetica de Privacidad online certificada le proporciona seguridad al momento de suministrar sus datos. Para consultar &iacutentegramente nuestra Pol&iacutetica de Privacidad, haga click en <a href="http://www.sanlazaro.co/docs/Politicas%20Tratamiento%20de%20Datos%20Personales%20Fenixor%20Rev%20Henry%20160218.pdf" style="color: #227ba7;">este enlace</a>.</p>
          <br>
          <p> <small>Barrio El Espinal, CRA 15 #31 - 110, Frente al Castillo de San Felipe, Cartagena - Colombia</small></p>
          <br>
          S&Iacute;GUENOS: <a href="https://www.instagram.com/sanlazarodistritoartes/"><img src="https://www.sanlazaro.co/assets/img/insta.png" width="20"/></a>
        </td>
        </tr>


        <tr>
        <td bgcolor="#f6f8fe" align="center" style="padding-top: 50px;">
            <img src="https://www.sanlazaro.co/assets/img/color-bar.png" width="100%">
        </td>
        </tr>
        
      </table>
    </center>
    
  </body>
  </html>
HTML;
}