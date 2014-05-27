<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['administrador_id'])){
    unset($_SESSION['administrador_id']);
}
if(isset($_SESSION['administrador_username'])){
    unset($_SESSION['administrador_username']);
}

if(isset($_SESSION['administrador_password'])){
    unset($_SESSION['administrador_password']);
}
if(isset($_SESSION['administrador_rank'])){
    unset($_SESSION['administrador_rank']);
}


//envia-o de volta para o admin.php
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'admin.php';
header ("location: http://$host$uri/$extra");

