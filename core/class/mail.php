<?php
 
##### Mail functions #####
global $domain;
$domain="whatsapi.com";
function sendResetPasswordEmail($email, $link)
{
	global $domain;
    $message = "You have requested a reset password on http://www.$domain/,
 
Please click the link below to reset your password.
 
$link
 
 
Regards
$domain Administration
";
 
    if (sendMail($email, "Your request for reset password.", $message, "no-reply@$domain"))
    {
        return true;
    } else
    {
        return false;
    }
 
 
}
 
function sendMail($to, $subject, $message, $from)
{
 
	global $domain;
	$headers = "From: $from\r\n";
	$headers .= "Reply-To: $from\r\n";
	$headers .= "Return-Path: $from\r\n";
	$headers .= "Organization: $domain\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: PHP". phpversion() ."\r\n" ; 
    if (mail($to, $subject, $message, $headers))
    {
        return true;
    } else
    {
        return false;
    }
    return false;
}
 
function sendActivationEmail($email, $link)
{
    global $domain;
    $message = "
Thank you for registering on http://www.$domain/,
  
Please click the link below to activate your account.
 
$link
 
Regards
$domain Administration
";
 
    if (sendMail($email, "Please activate your account.", $message, "no-reply@$domain"))
    {
        return true;
    } else
    {
        return false;
    }
}
?>