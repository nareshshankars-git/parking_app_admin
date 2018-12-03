<?php
session_start();
unset($_SESSION["user_id_admin"]);  // unset Session
session_destroy();                  // destroy all session
header("Location: index.php");  	// Redirection to index page
?>