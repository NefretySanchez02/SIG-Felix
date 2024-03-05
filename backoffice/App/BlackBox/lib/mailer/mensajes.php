<?php
echo "cargado emailer";
function sendEmail($mailp, $asunto, $mensaje)
{
	//PHP Mailer
	require_once(dirname(__FILE__) . "/tools/phpmailer/PHPMailerAutoload.php");


	$toName = "Clovis Mau"; //Nombre de quien recibe
	$toAddress = $mailp; //Correo de quien recibe
	$email = "notificadorpmo@esenttia.com"; //Correo de quien envía
	$toName2 = "Notificaciones PMO Esenttia"; //Nombre de quien envía


	/*if (!strlen($error)) {
if(get_magic_quotes_gpc()) {
	$message = stripslashes($message);
}*/

	$email_subject = $asunto;

	$email_body = $mensaje;

	$objmail = new PHPMailer();

	//Usar esta linea si tu quieres usar la funcion mail de PHP
	$objmail->IsMail();


	$objmail->From = $email;
	$objmail->FromName = $toName2;
	$objmail->AddAddress($toAddress, $toName);
	$objmail->AddReplyTo($email, $toName2);
	$objmail->Subject = $email_subject;
	$objmail->MsgHTML($email_body);
	if (!$objmail->Send()) {
		$error = "Error al enviar el mensaje : " . $objmail->ErrorInfo;
		echo $error;
	}
	//}

	//Result
	if ($error != "") { } else { }
}



//Verifica la dirección de correo electrónico
function isEmail($value)
{
	return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $value);
}
