<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail
{
	function send($from, $to, $subject, $text)
	{ 
		require_once('swiftmailer/lib/swift_required.php'); 

		//Create the Transport 
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl") 
		->setUsername('radiohand34@gmail.com')
		->setPassword('34radiohand');

		$mailer = Swift_Mailer::newInstance($transport); 

		//Create a message
		$message = Swift_Message::newInstance($subject) 
		->setFrom(array($from => $from)) 
		->setTo($to) ->setBody($text); 

		//Send the message 
		$result = $mailer->send($message);
	}
}