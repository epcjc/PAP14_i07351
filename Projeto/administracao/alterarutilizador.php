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
if($_SESSION['administrador_rank'] != 2){
    //envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode acessar esta página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idutilizador = $_GET['id'];
}else{
    //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "O utilizador que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirutilizadores.php';
        header ("location: http://$host$uri/$extra");  
    }
    
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }

        
                      //--------------------------------------------------------
                      
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/alterarutilizador.tpl');

//-------------------------------------------------------
    $sql = "SELECT imagem, username, pnome, unome, email, pais, descricao, permissao FROM utilizadores WHERE id = $idutilizador";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("morreu alterar utilizado");
    if($query->num_rows == 0){
        $_SESSION['mensagemadmin'] = "O utilizador que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirutilizadores.php';
        header ("location: http://$host$uri/$extra"); 
        exit;
    }
    $r = mysqli_fetch_assoc($query);
    
    //faz substituicao no tplconteudo
    //idutilizador e idutilizador2
    $tplconteudo = str_replace('()-idutilizador-()',$idutilizador,$tplconteudo);
    $tplconteudo = str_replace('()-idutilizador2-()',$idutilizador,$tplconteudo);
    //imagem
    if($r['imagem'] == 'imagens_utilizadores/imagem.jpg' || $r['imagem'] == NULL || $r['imagem'] == ''){
        $imagemfinal = '../imagens_utilizadores/imagem_media.jpg';
    }else if(file_exists('../imagens_utilizadores/'.$idutilizador.'_media.jpg')){
        $imagemfinal = '../imagens_utilizadores/'.$idutilizador.'_media.jpg';
    }else if(file_exists('../'.$r['imagem'])){
                    $nimagem = WideImage::loadFromFile('../'.$r['imagem']);
                   // Redimensiona a imagem
                    $nimagem = $nimagem->resize(300, 225);
                        // Guarda a imagem
                    $imagemfinal = '../imagens_utilizadores/'.$idutilizador.'_media.jpg'; // ex: 5_pequena, 82_pequena
                    $nimagem->saveToFile($imagemfinal, 40); // Coloca a imagem media no disco
    }else{
        $imagemfinal = '../imagens_utilizadores/imagem_media.jpg';
    }
    if($imagemfinal != '../imagens_utilizadores/imagem_media.jpg'){
        $tplconteudo = str_replace('()-imagem-()','<a href="../imagens_utilizadores/'.$idutilizador.'.jpg"><img src="'.$imagemfinal.'"></a>',$tplconteudo);
    }else{
        $tplconteudo = str_replace('()-imagem-()','<a href="../imagens_utilizadores/imagem.jpg"><img src="'.$imagemfinal.'"></a>',$tplconteudo);  
    }
    //username
    $tplconteudo = str_replace('()-username-()',$r['username'],$tplconteudo);
    //nome email descricao
    $tplconteudo = str_replace('()-pnome-()',$r['pnome'],$tplconteudo);
    $tplconteudo = str_replace('()-unome-()',$r['unome'],$tplconteudo);
    $tplconteudo = str_replace('()-email-()',$r['email'],$tplconteudo);
    $tplconteudo = str_replace('()-descricao-()',$r['descricao'],$tplconteudo);
    //País e permissão
    
    $tplconteudo = str_replace('<option value="'.$r['pais'].'">','<option value="'.$r['pais'].'" selected>',$tplconteudo);
    if($r['permissao'] == NULL || $r['permissao'] == 0 || $r['permissao'] == '' || $r['permissao'] > 2){
        $tplconteudo = str_replace('<option value="utilizador">','<option value="utilizador" selected>',$tplconteudo);
    }else{
        $tplconteudo = str_replace('<option value="administrador">','<option value="administrador" selected>',$tplconteudo);
    }
    
    
    

//apresenta menus e conteudo(form de alterAR noticia)
 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
