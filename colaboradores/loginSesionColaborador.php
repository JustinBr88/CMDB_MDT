<?php
if (!isset($_SESSION['colaborador_logeado']) || $_SESSION['colaborador_logeado'] !== true) {
    header('Location: /colaboradores/LoginColaborador.php');
    exit;
}
?>