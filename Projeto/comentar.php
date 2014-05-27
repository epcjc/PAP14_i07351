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
                    $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para comentar.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }else{    
              //verifica se os parametros sao validos
              $t = '';//tipo = n=noticia ou u=upload
              $id = 0;
              $userid = $_SESSION['utilizador_id'];
              if(isset($_GET['t'])){
                  switch($_GET['t']){
                      case 'n': $t = 'n'; break;
                      case 'u': $t = 'u'; break;
                  }                 
                  if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                      //Verifica se existe na base de dados
                      if($t == 'n'){
                         $sql = "SELECT id FROM noticias WHERE id = ".$_GET['id']; 
                         $query = mysqli_query($link_bd, $sql);
                         if(!$query){
                             die("comentar erro 1");
                         }
                         if($query->num_rows > 0){
                             while($r = mysqli_fetch_assoc($query)){
                                $id = $r['id'];
                            }
                         }else{
                            $extra = 'noticias.php';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            header ("location: http://$host$uri/$extra");
                         }
                      }else if($t == 'u'){
                         $sql = "SELECT id FROM uploads WHERE id = ".$_GET['id'];
                         $query = mysqli_query($link_bd, $sql);
                         if(!$query){
                             die("comentar erro 2");
                         }
                         if($query->num_rows > 0){
                             while($r = mysqli_fetch_assoc($query)){
                                $id = $r['id'];
                            }
                         }else{
                            $extra = 'downloads.php';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            header ("location: http://$host$uri/$extra"); 
                         }
                      }
                       
                  }
                  
             }
             $comentario = '';
             if(isset($_POST['comment']) && $_POST['comment'] != ''){
                 $comentario = $_POST['comment'];
                 $comentario = mysqli_real_escape_string($link_bd, $comentario);
             }
             //insere comentario na base de dados
       if($comentario == '' && $id != 0 && $t != ''){
                    if($t == 'n'){
                        $extra = 'noticia.php?id='.$id;
                    }else{
                        $extra = 'trabalho.php?id='.$id;
                    }
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    header ("location: http://$host$uri/$extra");
       }else if($id != 0 && $t != ''){      
             if($t == 'n'){                        
                 $sql = "INSERT INTO comentarios_noticias (conteudo,id_utilizador,id_noticia) VALUES ('$comentario','$userid','$id')";
            }else{
                 $sql = "INSERT INTO comentarios_uploads (conteudo,id_utilizador,id_upload) VALUES ('$comentario','$userid','$id')";
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
                        die('houve um erro na query 34fsc [' . $link_bd->error . ']');
                        }
              
             //acrescente um valor em ncomentarios
                    if($t == 'n'){
                        $sql = 'SELECT ncomentarios FROM noticias WHERE id = '.$id.' LIMIT 1';
                    }else{
                        $sql = 'SELECT ncomentarios FROM uploads WHERE id = '.$id.' LIMIT 1';
                    }
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $ant_ncom = $r['ncomentarios'];
                    }
                    $ncom = $ant_ncom + 1;
                    if($t == 'n'){
                      $ins = "UPDATE `noticias` SET `ncomentarios` = '".$ncom."' WHERE `id` = '".$id."' LIMIT 1";
                    }else{
                      $ins = "UPDATE `uploads` SET `ncomentarios` = '".$ncom."' WHERE `id` = '".$id."' LIMIT 1";                        
                    }
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }
                    
                    //adiciona um valor a ncomentarios tabela utilizador
                          
                    $sql = 'SELECT ncomentarios FROM utilizadores WHERE id = '.$_SESSION['utilizador_id'].' LIMIT 1';
                    
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $ant_ncom2 = $r['ncomentarios'];
                    }
                    $ncom2 = $ant_ncom2 + 1;
                    $ins2 = "UPDATE `utilizadores` SET `ncomentarios` = '".$ncom2."' WHERE `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                    $stmt2 = $link_bd->prepare($ins2);
                    if ($stmt2) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt2->execute();
                    }
                    //----------------------------------------------
                    
                     //rencaminha para a pagina anterior
                    if($t == 'n'){
                        $extra = 'noticia.php?id='.$id;
                    }else{
                        $extra = 'trabalho.php?id='.$id;
                    }
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    header ("location: http://$host$uri/$extra");
       }else{
           
                 //Reencaminha de volta
                    $_SESSION['mensagem_erro'] = 'Ocorreu um erro com os parametros ao comentar.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
             
       }
                   
              
              }