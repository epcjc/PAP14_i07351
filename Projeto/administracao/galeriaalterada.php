<?php
include_once '../includes/seguranca.php';
include_once '../WideImage/lib/WideImage.php';
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
if($_SESSION['administrador_rank'] != 2){
    //envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode acessar esta página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}

if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idregisto = $_GET['id'];
}else{
                //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A imagem da galeria que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirgaleria.php';
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
                          $sql = "SELECT imagem FROM galeria WHERE id=$idregisto";
    
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("ocorreu um erro na query da imagem em alterargaleria.php");
                        }
                        if($query->num_rows == 0){
                                //envia-o de volta para o gerirnoticias.php
                            $_SESSION['mensagemadmin'] = "A imagem da galeria que tentou alterar não existe.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirgaleria.php';
                            header ("location: http://$host$uri/$extra");  
                        }
                       

//altera dados
                     
                        $mensagem = '';
                        if(isset($_FILES['imagem'])){
                            //faz update da imagem
                            $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                            if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                                    $uploaddir = '../galeria/'; 
                                    move_uploaded_file($_FILES['imagem']['tmp_name'], $uploaddir . $idregisto . '.jpg');
                                    $caminhoimagem = 'galeria/'.$idregisto.'.jpg';
                                    $novaimagem = WideImage::loadFromFile('../galeria/'.$idregisto.'.jpg');
                   // Redimensiona a imagem
                                    $novaimagem = $novaimagem->resize(960, 473, 'outside');
                                    $novaimagem = $novaimagem->crop('center', 'center', 960, 473);
                                    // Guarda a imagem
                                    $imagemsubs = '../galeria/'.$idregisto.'.jpg'; // ex: 5_miniatura, 82_miniatura
                                    $novaimagem->saveToFile($imagemsubs, 40); // Coloca a imagem pequena no disco
                                    $sql = "UPDATE `galeria` SET `imagem` = '".$caminhoimagem."' WHERE `id` = '".$idregisto."' LIMIT 1";

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
                                        die("ocorreu um erro na mudanca da imagem da galeria");
                                    }else{
                                        $mensagem .= 'Imagem alterada com sucesso. ';
                                        //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                                       // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                        //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';

                                        $checkimg1 = '../galeria/'.$idregisto.'_pequena.jpg';
                                        $checkimg2 = '../galeria/'.$idregisto.'_miniatura.jpg';
                                        if (file_exists($checkimg1)) {
                                            unlink($checkimg1);
                                        }
                                        if(file_exists($checkimg2)) {
                                            unlink($checkimg2);
                                        }
                                        //--------------
                            }
                        }
                            
                            
                        }
                        //verifica as informacoes da noticia na base de dados, para comparar e verificar se é preciso inserir na base de dados

                        $sql = 'SELECT descricao FROM galeria WHERE id = '.$idregisto.' LIMIT 1';
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("falhou a query em galeriaalterada");
                        }
                        while($r = mysqli_fetch_assoc($query)){
                            $descricao_ant = $r['descricao'];
                           }
                        
                           //-------------------------------------
                        if(isset($_POST['descricao']) && $_POST['descricao'] != '' && $_POST['descricao'] != NULL && $_POST['descricao'] != $descricao_ant){
                                                       //Faz update do descricao
                                           $descricao = $_POST['descricao'];
                                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                                        $sql = "UPDATE `galeria` SET `descricao` = '".$descricao."' WHERE `id` = '".$idregisto."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do conteudo da descricao na galeria");
                                        }else{
                                            $mensagem .= 'Descrição alterada com sucesso. ';
                                                } 
                        }

                        

if($mensagem != ''){
    $_SESSION['mensagemadmin'] = $mensagem;
}
                            //redireciona para gerirnoticias.php
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'gerirgaleria.php';
                    header ("location: http://$host$uri/$extra");

