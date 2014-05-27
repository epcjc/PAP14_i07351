<?php

include_once 'includes/seguranca.php';
include_once 'includes/funcoes.php';


            
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
                    $_SESSION['mensagem_erro'] = 'Tem que efetuar o login primeiro, para ver essa página.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }        
                  
                 //-----------   
              $reenc = ''; //reencaminhar
              $idmsg = '';
              $t = '';
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idmsg = $_GET['id'];
              }
              if(isset($_GET['apagar'])){
                    switch($_GET['apagar']){
                        case 'recebidas': $t = 'r'; break;
                        case 'enviadas': $t = 'e'; break;
                    }
              }
              if($t == '' && $idmsg == ''){
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'mensagens.php';
                    header ("location: http://$host$uri/$extra");
              }else if($idmsg != ''){
                  $t = 'i';
              }
              if($t == ''){
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'mensagens.php';
                    header ("location: http://$host$uri/$extra");
              }
              //verifica se a mensagem existe e se pode apaga-la
              if($t == 'i'){
                      $tipo = '';
                      $sql = "SELECT * FROM mensagens WHERE id=$idmsg LIMIT 1";
                      $query = mysqli_query($link_bd, $sql);
                      if(!$query) die("nao deu a querry da mensagem existe");
                      if($query->num_rows==0){
                        $_SESSION['mensagem_erro'] = 'A mensagem que tentou apagar não existe';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header ("location: http://$host$uri/$extra");
                      }
                      $r = mysqli_fetch_assoc($query);
                      if($r['id_utilizadorE'] != $_SESSION['utilizador_id'] && $r['id_utilizadorR'] != $_SESSION['utilizador_id']){
                            $_SESSION['mensagem_erro'] = 'Não pode apagar essa mensagem';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'index.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                      }
                      if($r['id_utilizadorE'] == $_SESSION['utilizador_id']){
                          $tipo = 'enviada';
                          //apaga mensagem enviada
                          if($r['apagou_utilizadorR'] == 1 || $r['id_utilizadorR'] == 0 || $r['id_utilizadorR'] == $r['id_utilizadorE']){
                              //se o outro utilizador apagou, apaga completamente
                              if($r['id_utilizadorR'] == $r['id_utilizadorE']){
                                $reenc = 'r';
                              }else{
                                $reenc = 'e';  
                              }
                                
                                $sql = "DELETE FROM mensagens WHERE id = $idmsg LIMIT 1";
                                $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                //$stmt->bind_param('iss', $varID, $var1, $var2);
                                    if ($stmt->execute()) {
                                        $success = TRUE;   
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro ao apagar a msg1");
                                }
                          }else{
                              //se o outro utilizador nao apagou, apenas altera o campo apagou_utilizadorX para 1
                                $sql = "UPDATE `mensagens` SET `apagou_utilizadorE` = 1 WHERE `id` = '".$idmsg."' LIMIT 1";
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
                                    die("ocorreu um erro na mudanca do apagouuti1");
                                }

                          }
                      }else{
                          $tipo = 'recebida';
                          //apaga mensagem recebida
                          if($r['apagou_utilizadorE'] == 1 || $r['id_utilizadorE'] == 0 || $r['id_utilizadorR'] == $r['id_utilizadorE']){
                              //se o outro utilizador apagou, apaga completamente
                                $reenc = 'r';
                                $sql = "DELETE FROM mensagens WHERE id = $idmsg LIMIT 1";
                                $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                //$stmt->bind_param('iss', $varID, $var1, $var2);
                                    if ($stmt->execute()) {
                                        $success = TRUE;   
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro ao apagar a msg2");
                                }
                          }else{
                              //se o outro utilizador nao apagou, apenas altera o campo apagou_utilizadorX para 1
                                $sql = "UPDATE `mensagens` SET `apagou_utilizadorR` = 1 WHERE `id` = '".$idmsg."' LIMIT 1";
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
                                    die("ocorreu um erro na mudanca do apagouuti2");
                                }
                          }
                      }
              }else if($t == 'e'){
                  //conta total de mensagens enviadas
                  $sql = 'SELECT id, id_utilizadorR, apagou_utilizadorR FROM mensagens WHERE id_utilizadorE = '.$_SESSION['utilizador_id'];
                  $query = mysqli_query($link_bd, $sql);
                  if(!$query){
                      die("morreu ao contar as mensagens env");
                  }
                  if($query->num_rows > 0){
                      $i = 0;
                      while ($i < $query->num_rows){
                        $query->data_seek($i);
                        $datarow = $query->fetch_array();
                        if($datarow['id_utilizadorR'] == 0 || $datarow['apagou_utilizadorR'] == 1){
                            //apaga mensagem
                                $sql = 'DELETE FROM mensagens WHERE id = '.$datarow['id'].' LIMIT 1';
                                $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                //$stmt->bind_param('iss', $varID, $var1, $var2);
                                    if ($stmt->execute()) {
                                        $success = TRUE;   
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro ao apagar as msgs enviadas");
                                }
                            
                        }else{
                            //altera apagou_utilizadorX para 1
                                $sql = "UPDATE `mensagens` SET `apagou_utilizadorE` = 1 WHERE `id` = '".$datarow['id']."' LIMIT 1";
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
                                    die("ocorreu um erro na mudanca das emnsagens enviadas");
                                }
                        }
                        
                        $i++;
                      }
                  }

                  
              }else if($t == 'r'){
                  //conta total de mensagens recebidas
                  $sql = 'SELECT id, id_utilizadorE, apagou_utilizadorE FROM mensagens WHERE id_utilizadorR = '.$_SESSION['utilizador_id'];
                  $query = mysqli_query($link_bd, $sql);
                  if(!$query){
                      die("morreu ao contar as mensagens REC");
                  }
                  if($query->num_rows > 0){
                      $i = 0;
                      while ($i < $query->num_rows){
                        $query->data_seek($i);
                        $datarow = $query->fetch_array();
                        if($datarow['id_utilizadorE'] == 0 || $datarow['apagou_utilizadorE'] == 1){
                            //apaga mensagem
                                $sql = 'DELETE FROM mensagens WHERE id = '.$datarow['id'].' LIMIT 1';
                                $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                //$stmt->bind_param('iss', $varID, $var1, $var2);
                                    if ($stmt->execute()) {
                                        $success = TRUE;   
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro ao apagar as msgs RECEBIDAS");
                                }
                            
                        }else{
                            //altera apagou_utilizadorX para 1
                                $sql = "UPDATE `mensagens` SET `apagou_utilizadorR` = 1 WHERE `id` = '".$datarow['id']."' LIMIT 1";
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
                                    die("ocorreu um erro na mudanca das emnsagens RECEBIDAS");
                                }
                        }
                        
                        $i++;
                      }
                  }
                  
              }
              
              //faz contagem das nmensagens do utilizador para definir em nmensagens
              $sql = 'SELECT count(*) FROM mensagens WHERE id_utilizadorR = '.$_SESSION['utilizador_id'].' AND porler = 1 AND apagou_utilizadorR = 0';
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu contagem nmensagens");
              $r= mysqli_fetch_assoc($query);
              $nmensagens_novo = $r['count(*)'];
              $sql = "UPDATE `utilizadores` SET `nmensagens` = '".$nmensagens_novo."' WHERE `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
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
                    die("ocorreu um erro na contagem das msgens para definir final fix");
                }
              
              //-------------------------------------------------------------------
                        
                        //---------------------------------------------------------------------
                  //redireciona-o para o index
                  if($reenc == 'e'){    
                        $_SESSION['mensagem_mensagens'] = 'A mensagem foi apagada.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php?ver=enviadas';
                        header ("location: http://$host$uri/$extra");
                  }elseif($reenc == 'r'){
                        $_SESSION['mensagem_mensagens'] = 'A mensagem foi apagada.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php';
                        header ("location: http://$host$uri/$extra");
                  }elseif($t == 'i'){
                      $_SESSION['mensagem_mensagens'] = 'A mensagem foi apagada.';
                      if($tipo == 'enviada'){
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php?ver=enviadas';
                        header ("location: http://$host$uri/$extra");
                      }else{
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php';
                        header ("location: http://$host$uri/$extra"); 
                      }
                  }elseif($t == 'r'){
                      $_SESSION['mensagem_mensagens'] = 'As mensagens da caixa de entrada foram apagadas.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php';
                        header ("location: http://$host$uri/$extra");
                  }else if($t == 'e'){
                      $_SESSION['mensagem_mensagens'] = 'As mensagens da caixa de saída foram apagadas.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'mensagens.php?ver=enviadas';
                        header ("location: http://$host$uri/$extra");
                  }
