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
                  $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ver esta página.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }
              
                  //verifica se username existe na bd
                  if($_POST['destinatario'] == '' || $_POST['titulo'] == '' || $_POST['conteudo'] == ''){
                        $_SESSION['mensagem_erro'] = 'Foram inseridos parámetros inválidos ao enviar a mensagem.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header ("location: http://$host$uri/$extra");
                  }else{
                        $destinatario = mysqli_real_escape_string($link_bd, $_POST['destinatario']);
                        $titulo = mysqli_real_escape_string($link_bd, $_POST['titulo']);
                        $conteudo = mysqli_real_escape_string($link_bd, $_POST['conteudo']);
                  }
                  $sql = "SELECT id FROM utilizadores WHERE username = '$destinatario' LIMIT 1";
                  $query = mysqli_query($link_bd, $sql);
                  if(!$query) die("nao deu para verificar username na bd");
                  if($query->num_rows == 0){
                        $_SESSION['mensagem_erro'] = 'O nome de utilizador que inseriu ao enviar a mensagem não existe.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header ("location: http://$host$uri/$extra");
                  }else{
                      $r = mysqli_fetch_assoc($query);
                      $iddestinatario = $r['id'];
                  }
                  
                  //---------------------------------------------
                    //verifica se o destinatario bloqueou o utilizador que envia a mensagem
                  if($iddestinatario != $_SESSION['utilizador_id']){
                      $sql = 'SELECT id FROM bloqueios WHERE id_utilizador = '.$iddestinatario.' AND id_bloqueado = '.$_SESSION['utilizador_id'].' LIMIT 1';
                      $query = mysqli_query($link_bd, $sql);
                      if(!$query) die("nao deu verificar block do destinatario");
                      if($query->num_rows > 0){
                            $_SESSION['mensagem_mensagens'] = '<font color="#600000">Você está bloqueado por este utilizador e não o pode contactar.</font>';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'mensagens.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                      }    
                  }

                 //----------------------------------------------
                 //
//insere mensagem na bd
                  $idqenvia = $_SESSION['utilizador_id'];
                  $sql = "INSERT INTO mensagens (titulo,conteudo,id_utilizadorE,id_utilizadorR) VALUES ('$titulo','$conteudo','$idqenvia','$iddestinatario')";
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
                  
//aumenta um nmensagens no destinatario

                    $sql = 'SELECT nmensagens FROM utilizadores WHERE id = '.$iddestinatario.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $ant_msg = $r['nmensagens'];
                    }
                    $nmsg = $ant_msg + 1;
                    $ins = "UPDATE `utilizadores` SET `nmensagens` = '".$nmsg."' WHERE `id` = '".$iddestinatario."' LIMIT 1";
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }else{
                        die("nao foi possivel a inscricao de nmensagens destinatario");
                    }
                        
                                   
                        //---------------------------------------------------------------------
                  //redireciona-o para o index
                        $msg = "A sua mensagem foi enviada com sucesso.";
                        $_SESSION['mensagem_mensagens'] = $msg;
                        //redireciona para o index
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php?ver=enviadas';
                        header ("location: http://$host$uri/$extra");