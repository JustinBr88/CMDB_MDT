<?php
session_start();
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: Login.php');
    exit;
}
?>