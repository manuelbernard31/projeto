<?php
session_start();
session_destroy();
header("Location: cliente.php");
if (isset($_SESSION['email'])) {
    unset($_SESSION['email']);
}

exit();
?>
