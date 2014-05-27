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


if(!isset($_SESSION['administrador_id']) || !isset($_SESSION['administrador_username']) || !isset($_SESSION['administrador_password'])){ //verifica se um utilizador com permissao efetuou o login
//Envia de volta para admin.php se nao estiver logado
//envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "É necessário fazer o login para ver essa página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idnoticia = $_GET['id'];
}else{
                //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A notícia que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirnoticias.php';
        header ("location: http://$host$uri/$extra");  
}
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
                      
                      
                      
                     //Verifica se noticia existe na bd
                          $sql = "SELECT imagem FROM noticias WHERE id=$idnoticia";
    
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("ocorreu um erro na query da imagem em alterarnoticia.php");
                        }
                        if($query->num_rows == 0){
                                //envia-o de volta para o gerirnoticias.php
                            $_SESSION['mensagemadmin'] = "A notícia que tentou alterar não existe.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirnoticias.php';
                            header ("location: http://$host$uri/$extra");  
                        }
                        

                                                                        //Verifica se o administrador logado pode alterar esta noticia
    $sql = "SELECT id_utilizador FROM noticias WHERE id=$idnoticia";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("morreu em aaaaaaaaaaaaa alterarpagina");
    }
    while($r = mysqli_fetch_assoc($query)){
        $iduser = $r['id_utilizador'];
    }
    if($iduser != $_SESSION['administrador_id'] && $_SESSION['administrador_rank'] < 2){
        $_SESSION['mensagemadmin'] = "Não pode alterar esta noticia";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
    }
        
                      //--------------------------------------------------------

//altera dados
                     
                        $mensagem = '';
                        if(isset($_FILES['imagem'])){
                            //faz update da imagem
                            $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                            if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                                    $uploaddir = '../imagens_noticias/'; 
                                    move_uploaded_file($_FILES['imagem']['tmp_name'], $uploaddir . $idnoticia . '.jpg');
                                    $caminhoimagem = 'imagens_noticias/'.$idnoticia.'.jpg';

                                    $sql = "UPDATE `noticias` SET `imagem` = '".$caminhoimagem."' WHERE `id` = '".$idnoticia."' LIMIT 1";

                                    //$sql = "UPDATE `utilizadores` SET `imagem` = `$caminhoimagem` WHERE `id` = `$idutilizador` LIMIT 1";
                                    $stmt = $link_bd->prepare($sql);
                                    $success = FALSE;
                                    if ($stmt) {
                                    //$stmt->bind_param('iss', $varID, $var1, $var2);

                                        if ($stmt->execute()) {
                                            $success = TRUE;   //or something like that
                                        }else{
                                            $success = FALSE;//
                                        }
                                    }


                                    if($success == FALSE){
                                        die("ocorreu um erro na mudanca da imagem da noticia");
                                    }else{
                                        $mensagem .= 'Imagem da notícia alterada com sucesso. ';
                                        //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                                       // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                        //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';

                                        $checkimg1 = '../imagens_noticias/'.$idnoticia.'_pequena.jpg';
                                        $checkimg2 = '../imagens_noticias/'.$idnoticia.'_miniatura.jpg';
                                        $checkimg3 = '../imagens_noticias/'.$idnoticia.'_lista.jpg';
                                        if (file_exists($checkimg1)) {
                                            unlink($checkimg1);
                                        }
                                        if(file_exists($checkimg2)) {
                                            unlink($checkimg2);
                                        }
                                        if(file_exists($checkimg3)) {
                                            unlink($checkimg3);
                                        }
                                        //--------------
                            }
                        }
                            
                            
                        }
                        //verifica as informacoes da noticia na base de dados, para comparar e verificar se é preciso inserir na base de dados

                        $sql = 'SELECT titulo, conteudo FROM noticias WHERE id = '.$idnoticia.' LIMIT 1';
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("falhou a query em noticiaalterada");
                        }
                        while($r = mysqli_fetch_assoc($query)){
                            $titulo_ant = $r['titulo'];
                            $conteudo_ant = $r['conteudo'];
                           }
                           if(!isset($titulo_ant) || !isset($conteudo_ant)){
                            $titulo_ant = 'Nulo';
                            $conteudo_ant = 'Nulo';
                           }
                           //-------------------------------------
                        if(isset($_POST['titulo']) && $_POST['titulo'] != '' && $_POST['titulo'] != NULL && $_POST['titulo'] != $titulo_ant){
                                                       //Faz update do titulo
                                        $titulo = $_POST['titulo'];
                                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                                        $sql = "UPDATE `noticias` SET `titulo` = '".$titulo."' WHERE `id` = '".$idnoticia."' LIMIT 1";
                                        $stmt = $link_bd->prepare($sql);
                                        $success = FALSE;
                                        if ($stmt) {
                                        //$stmt->bind_param('iss', $varID, $var1, $var2);
                                            if ($stmt->execute()) {
                                                $success = TRUE;   //or something like that
                                            }else{
                                                $success = FALSE;//
                                            }
                                        }
                                        if($success == FALSE){
                                            die("ocorreu um erro na mudanca do titulo");
                                        }else{
                                            $mensagem .= 'Título da notícia alterado com sucesso. ';
                                                } 
                        }
                        if(isset($_POST['conteudo']) && $_POST['conteudo'] != '' && $_POST['conteudo'] != NULL && $_POST['conteudo'] != $conteudo_ant){
                                                       //Faz update do descricao
                                           $conteudo = $_POST['conteudo'];
                                        $conteudo = mysqli_real_escape_string($link_bd, $conteudo);
                                        $sql = "UPDATE `noticias` SET `conteudo` = '".$conteudo."' WHERE `id` = '".$idnoticia."' LIMIT 1";
                                        $stmt = $link_bd->prepare($sql);
                                        $success = FALSE;
                                        if ($stmt) {
                                        //$stmt->bind_param('iss', $varID, $var1, $var2);
                                            if ($stmt->execute()) {
                                                $success = TRUE;   //or something like that
                                            }else{
                                                $success = FALSE;//
                                            }
                                        }
                                        if($success == FALSE){
                                            die("ocorreu um erro na mudanca do conteudo da noticia");
                                        }else{
                                            $mensagem .= 'Conteúdo da notícia alterado com sucesso. ';
                                                } 
                        }

                        

if($mensagem != ''){
    $_SESSION['mensagemadmin'] = $mensagem;
}
                            //redireciona para gerirnoticias.php
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'gerirnoticias.php';
                    header ("location: http://$host$uri/$extra");
