<?php
session_start();
if(isset($_SESSION['utilizador_id'])){
    unset($_SESSION['utilizador_id']);
}
if(isset($_SESSION['utilizador_username'])){
    unset($_SESSION['utilizador_username']);
}
if(isset($_SESSION['utilizador_login'])){
    unset($_SESSION['utilizador_login']);
}
if(isset($_SESSION['utilizador_password'])){
    unset($_SESSION['utilizador_password']);
}


//envia-o de volta para o index
$host = $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'index.php';
header ("location: http://$host$uri/$extra");

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
