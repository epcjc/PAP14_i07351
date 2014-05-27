<?php
include_once '../includes/seguranca.php';
include_once '../includes/funcoes.php';
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
if($_SESSION['administrador_rank'] < 2){
        $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode ver essa página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}

if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idupl = $_GET['id'];
}else{
                //envia-o de volta para o gerirutilizadores.php
        $_SESSION['mensagemadmin'] = "O upload que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'geriruploads.php';
        header ("location: http://$host$uri/$extra");  
}
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
                      
                      
                      
                     //Verifica se o utilizador existe na bd
                          $sql = "SELECT imagem1,imagem2,imagem3,imagem4,categoria,descricao,titulo,nomeoriginal,id_utilizador FROM uploads WHERE id=$idupl LIMIT 1";
    
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("ocorreu um erro na query da alterarupload.php das");
                        }
                        if($query->num_rows == 0){
                                //envia-o de volta para o gerirutilizadores.php
                            $_SESSION['mensagemadmin'] = "O upload que tentou alterar não existe.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'geriruploads.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                        $r = mysqli_fetch_assoc($query);
                        $imagem_ant = array(1=>htmlspecialchars($r['imagem1']),2=>htmlspecialchars($r['imagem2']),3=>htmlspecialchars($r['imagem3']),4=>htmlspecialchars($r['imagem4']));
                        
                        $categoria_ant = htmlspecialchars($r['categoria']);
                        $descricao_ant = htmlspecialchars($r['descricao']);
                        $titulo_ant = htmlspecialchars($r['titulo']);
                        $nomeorg = htmlspecialchars($r['nomeoriginal']);
                        $idutilizador = $r['id_utilizador'];
                        


//altera dados
                     
                        $mensagem = '';
                        //imagens
                        $i = 1;
                        while ($i < 5){
                            if(isset($_FILES['imagem'.$i])){ //verifica se há uma nova imagem inserida
                                $tipo = $_FILES['imagem'.$i]['type'];                       //verifica se é imagem
                                if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                                    $uploaddir = '../upload/'.$idutilizador.'/'.$nomeorg.'/imagem'.$i; 
                                    apagarconteudodir($uploaddir);
                                    $uploaddir = 'upload/'.$idutilizador.'/'.$nomeorg.'/imagem'.$i; 
                                    move_uploaded_file($_FILES['imagem'.$i]['tmp_name'], $_SG['caminhoservidor'] . $uploaddir .'/'. $_FILES['imagem'.$i]['name']);
                                    $caminhoimagem = $uploaddir.'/'.$_FILES['imagem'.$i]['name'];

                                    $sql = "UPDATE `uploads` SET `imagem".$i."` = '".$caminhoimagem."' WHERE `id` = '".$idupl."' LIMIT 1";

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
                                        die("ocorreu um erro na mudanca da imagem".$i);
                                    }else{
                                        $mensagem .= 'Imagem nº'.$i.' alterada com sucesso. ';
                                        //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                                       // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                        //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                        //--------------
                                    }
                                }
                            }
                            $i++;
                        }
                        
                        
                        //categoria
                        if(isset($_POST['categoria']) && $_POST['categoria'] != '' && $_POST['categoria'] != NULL && $_POST['categoria'] != $categoria_ant){
                                                       
                                        $categoria = $_POST['categoria'];
                                        $categoria = mysqli_real_escape_string($link_bd, $categoria);
                                        
                                        $sql = "UPDATE `uploads` SET `categoria` = '".$categoria."' WHERE `id` = '".$idupl."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca da categoria");
                                        }else{
                                            $mensagem .= 'Categoria do upload alterada com sucesso. ';
                                                } 
                                        
                        }
                                                //titulo
                        if(isset($_POST['titulo']) && $_POST['titulo'] != '' && $_POST['titulo'] != NULL && $_POST['titulo'] != $titulo_ant){
                                                     
                                        $titulo = $_POST['titulo'];
                                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                                        
                                        $sql = "UPDATE `uploads` SET `titulo` = '".$titulo."' WHERE `id` = '".$idupl."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca d0 titulo");
                                        }else{
                                            $mensagem .= 'Título do upload alterado com sucesso. ';
                                                } 
                                        
                        }
                                                //descricao
                        if(isset($_POST['descricao']) && $_POST['descricao'] != '' && $_POST['descricao'] != NULL && $_POST['descricao'] != $descricao_ant){
                                                    
                                        $descricao = $_POST['descricao'];
                                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                                        
                                        $sql = "UPDATE `uploads` SET `descricao` = '".$descricao."' WHERE `id` = '".$idupl."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca da descricao");
                                        }else{
                                            $mensagem .= 'Descrição do upload alterada com sucesso. ';
                                                } 
                                        
                        }


                        

if($mensagem != ''){
    $_SESSION['mensagemadmin'] = $mensagem;
}
                            //redireciona para gerirutilizadores.php
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'geriruploads.php';
                    header ("location: http://$host$uri/$extra");


