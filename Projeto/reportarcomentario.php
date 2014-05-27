<?php

include_once 'includes/seguranca.php';



            
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
                      case 'n': $t = 'n'; break;
                      case 'u': $t = 'u'; break;
                  }
              }
              if($t == ''){
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro invalido ao denunciar comentario.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $id = $_GET['id'];
              }else{
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro invalido ao denunciar comentario.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              
//Verifica se existe na bd
              
              if($t == 'n'){
                  $sql = 'SELECT id_noticia FROM comentarios_noticias WHERE id = '.$id.' LIMIT 1';
              }else{
                  $sql = 'SELECT id_upload FROM comentarios_uploads WHERE id = '.$id.' LIMIT 1';
              }
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu para verificar se existe na bd comentario");
              if($query->num_rows == 0){
                    $_SESSION['mensagem_erro'] = "Foi inserido um parametro invalido ao denunciar comentario.";
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              $r = mysqli_fetch_assoc($query);
              
//verifica se utilizador ja reportou esse comentario
              
              if($t == 'n'){
                  $idnoticia = $r['id_noticia'];
                  $sql = 'SELECT id FROM reports_cnoticias WHERE id_comentario = '.$id.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
              }else{
                  $idupload = $r['id_upload'];
                  $sql = 'SELECT id FROM reports_cuploads WHERE id_comentario = '.$id.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
              }
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu para verificar se ja reportou comeny");
              if($query->num_rows > 0){
                    //ja reportou
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                      if($t == 'n'){
                          $extra = 'noticia.php?id='.$idnoticia;
                      }else{
                          $extra = 'upload.php?id='.$idupload;
                      }
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              
//acrescenta report na bd
             
              $iduser = $_SESSION['utilizador_id'];
              if($t == 'n'){
                    $sql = "INSERT INTO reports_cnoticias (id_utilizador,id_comentario,id_noticia) VALUES ('$iduser','$id','$idnoticia')";
              }else{
                    $sql = "INSERT INTO reports_cuploads (id_utilizador,id_comentario,id_upload) VALUES ('$iduser','$id','$idupload')";
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
              
//acrescenta 1 nreports no comentario
                    if($t == 'n'){
                        $sql = 'SELECT nreports FROM comentarios_noticias WHERE id = '.$id.' LIMIT 1';
                    }else{
                        $sql = 'SELECT nreports FROM comentarios_uploads WHERE id = '.$id.' LIMIT 1';
                    }
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $nreports = $r['nreports']+1;
                    }
                    if($t == 'n'){
                        $ins = "UPDATE `comentarios_noticias` SET `nreports` = '".$nreports."' WHERE `id` = '".$id."' LIMIT 1";
                    }else{
                        $ins = "UPDATE `comentarios_uploads` SET `nreports` = '".$nreports."' WHERE `id` = '".$id."' LIMIT 1";
                    }
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }
              
              
                   
                  
                        
                        //---------------------------------------------------------------------

                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                      if($t == 'n'){
                          $extra = 'noticia.php?id='.$idnoticia;
                      }else{
                          $extra = 'trabalho.php?id='.$idupload;
                      }
                    header ("location: http://$host$uri/$extra");