<?php
session_start();
session_unset();
session_destroy();
header("Location: ../register.html"); // Redirects to register.html in the screens folder
exit;
?>