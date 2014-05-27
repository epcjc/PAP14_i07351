<?php

include_once 'includes/seguranca.php';

include_once 'securimage/securimage.php';
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
  echo '<br/><div align="center">O codigo de seguranca inserido esta errado.<br /><br /></div>';
  echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e tente novamente.</div>';
  exit;
}



            
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
               //envia de volta se nao estiver logado       
              if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
                  
                    //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = "É necessário efetuar o login para ver essa página.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }        
                  
                 //-----------    
              $t = '';
              if(isset($_GET['t'])){
                  switch($_GET['t']){
                      case 'up': $t = 'up'; break;
                      case 'ut': $t = 'ut'; break;
                  }
              }
              if($t == ''){
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro invalido nesta denuncia.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $id = $_GET['id'];
              }else{
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro invalido nesta denuncia.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              if($_POST['titulo'] == '' || $_POST['conteudo'] == ''){
                    $_SESSION['mensagem_erro'] = "É necessário um titulo e um conteudo.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              $titulo = mysqli_real_escape_string($link_bd, $_POST['titulo']);
              $conteudo = mysqli_real_escape_string($link_bd, $_POST['conteudo']);
              
//Verifica se existe na bd
              
              if($t == 'ut'){
                  $sql = 'SELECT username FROM utilizadores WHERE id = '.$id.' LIMIT 1';
              }else{
                  $sql = 'SELECT titulo FROM uploads WHERE id = '.$id.' LIMIT 1';
              }
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu para verificar se existe na bd fsfs");
              if($query->num_rows == 0){
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro que não existe na base de dados.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              $r = mysqli_fetch_assoc($query);
              
              
//acrescenta report na bd
             
              $iduser = $_SESSION['utilizador_id'];
              if($t == 'ut'){
                    $sql = "INSERT INTO reports_utilizadores (id_utilizador,id_reportado,titulo,conteudo) VALUES ('$iduser','$id','$titulo','$conteudo')";
              }else{
                    $sql = "INSERT INTO reports_uploads (id_utilizador,id_upload,titulo,conteudo) VALUES ('$iduser','$id','$titulo','$conteudo')";
              }
                        $stmt = $link_bd->prepare($sql);
                        if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                            if ($stmt->execute()) {
                                $success = TRUE;   //or something like that
                             }else{
                                 $success = FALSE;//
                             }
                        }

                        if($success == FALSE){
                        die('houve um erro na query [' . $link_bd->error . ']');
                        }
              
//acrescenta 1 nreports 
                    if($t == 'ut'){
                        $sql = 'SELECT nreports FROM utilizadores WHERE id = '.$id.' LIMIT 1';
                    }else{
                        $sql = 'SELECT nreports FROM uploads WHERE id = '.$id.' LIMIT 1';
                    }
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $nreports = $r['nreports']+1;
                    }
                    if($t == 'ut'){
                        $ins = "UPDATE `utilizadores` SET `nreports` = '".$nreports."' WHERE `id` = '".$id."' LIMIT 1";
                    }else{
                        $ins = "UPDATE `uploads` SET `nreports` = '".$nreports."' WHERE `id` = '".$id."' LIMIT 1";
                    }
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }
              
              
                   
                  
                        
                        //---------------------------------------------------------------------
                    $_SESSION['mensagem_sucesso'] = 'A sua denúncia foi enviada com sucesso.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                      if($t == 'ut'){
                          $extra = 'perfil.php?id='.$id;
                      }else{
                          $extra = 'trabalho.php?id='.$id;
                      }
                    header ("location: http://$host$uri/$extra");

