<?php
include_once '../includes/seguranca.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../WideImage/lib/WideImage.php';

$home = file_get_contents('HTML/mainadmin.html');

//verifica se há mensagem a apresentar
$msg = '';
if(isset($_SESSION['mensagemadmin'])){
    if($_SESSION['mensagemadmin'] != ''){
        //apresenta mensagem 
        $msg = $_SESSION['mensagemadmin'];
        $home = str_replace('()-mensagemadmin-()', $msg, $home);
        $_SESSION['mensagemadmin'] = '';
    }else{
        //não apresenta nenhuma mensagem
        $home = str_replace('()-mensagemadmin-()', '', $home);
        
    }
}else{
    //não apresenta nenhuma mensagem
     $home = str_replace('()-mensagemadmin-()', '', $home);
}

if(!isset($_SESSION['administrador_id']) || !isset($_SESSION['administrador_username']) || !isset($_SESSION['administrador_password'])){ //verifica se um utilizador com permissao efetuou o login
//Envia de volta para admin.php se nao estiver logado
//envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "É necessário fazer o login para ver essa página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}
//apresenta menus e conteudo(form de nova noticia)
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/novanoticia.tpl'); 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
