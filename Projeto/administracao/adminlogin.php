<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../includes/seguranca.php';
include_once '../securimage/securimage.php';
        if($_SESSION['a_tentativaslogin'] >= 3){
            $securimage = new Securimage();
            if ($securimage->check($_POST['captcha_code']) == false) {
              echo '<br/><div align="center">O codigo de seguranca inserido esta errado.<br /><br /></div>';
              echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e tente novamente.</div>';
              exit;
            }else{
               $_SESSION['a_tentativaslogin'] = 0; 
            }
        }

$foi = 0; //confirmaçao que foi encontrado na base de dados
//verifica se foram colocados os 2 campos de login:
if($_POST["administrador"] == '' || $_POST["palavrap"] == ''){
    //envia de volta para o admin.php
    $_SESSION['a_tentativaslogin']++;
    $_SESSION['mensagemadmin'] = "Administrador e/ou palavra-passe incorretos. ";
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'admin.php';
    header ("location: http://$host$uri/$extra");
}else{
    //verifica se existe na base de dados e tem permissao
    
        // conectar bd
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
    
    $user = $_POST["administrador"];
    $user =  mysqli_real_escape_string($link_bd, $user);
    $pass = $_POST["palavrap"];
    
    $pass_safe = Encrypter::encrypt($pass);
    $userf = addslashes($user);
    $pass_safef= addslashes($pass_safe);

                      
            $const0 = 0;          
            $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';                     
            $sql = "SELECT `id`, `username`, `permissao` FROM `".$_SG['tabela']."` WHERE ".$cS." `username` = '".$userf."' AND ".$cS." `palavrap` = '".$pass_safef."' AND `permissao` > '".$const0."' LIMIT 1";
            $query = mysqli_query($link_bd, $sql);
            if (!$query) {
                die ("Não foi possível executar a consulta ($sql) no banco de dados: " . mysqli_error());
            }else if(mysqli_num_rows($query) == 0) {
                    //envia de volta para o admin.php
                    $_SESSION['a_tentativaslogin']++;
                    $_SESSION['mensagemadmin'] = "Administrador e/ou palavra-passe incorretos. ";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'admin.php';
                    header ("location: http://$host$uri/$extra");

            }else{
                while ($resultado = mysqli_fetch_assoc($query)) {
                    $admin_id = $resultado["id"];
                    $admin_username = $resultado["username"];
                    $rank_admin = $resultado['permissao'];
                    $foi = 1;
                }
            }
        }
if($foi == 1){
    $_SESSION['administrador_id'] = $admin_id;
    $_SESSION['administrador_username'] = $admin_username;
    $_SESSION['administrador_password'] = $pass_safe;
    if($rank_admin > 1){
        $_SESSION['administrador_rank'] = 2;
    }else{
        $_SESSION['administrador_rank'] = 1;
    }
    //define estes dados como inicio de sessão e envia-o para admin.php
    $_SESSION['mensagemadmin'] = 'Bem-vindo, '.$_SESSION['administrador_username'];
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'admin.php';
    header ("location: http://$host$uri/$extra");
}