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
                  $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ver esta página.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                            exit;


              }
              $t = '';
              if(isset($_GET['t'])){
                  switch($_GET['t']){
                      case 'up': $t = 'up'; break;
                      case 'ut': $t = 'ut'; break;
                  }
              }
              if($t == ''){
                    $_SESSION['mensagem_erro'] = 'Parametro inválido foi inserido ao inserir favorito.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                            exit;

              }
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idr = $_GET['id'];
              }else{
                  $_SESSION['mensagem_erro'] = ' Foi inserido um parametro inválido ao inserir favorito.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                            exit;
              }
              
              if(isset($_GET['alterar']) && $_GET['alterar'] == 'sim'){
                  //altera favorito
                  
                  //verifica se o trabalho ou utilizador $idr existe
                        if($t=='up'){
                            $sql = 'SELECT datahora FROM uploads WHERE id = '.$idr.' LIMIT 1';
                        }else{
                            $sql = 'SELECT datahora FROM utilizadores WHERE id = '.$idr.' LIMIT 1';
                        }
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query) die("erro num asod9193");
                        if($query->num_rows == 0){
                            if($t == 'up'){
                                 $_SESSION['mensagemfavoritos'] = 'O trabalho/projeto favorito que tentou alterar já não existe. ';
                                 $extra = 'gerirfavoritos.php';
                            }else{
                                 $_SESSION['mensagemfavoritos'] = 'O utilizador favorito que tentou alterar já não existe. ';
                                 $extra = 'gerirfavoritos.php?ver=ut';
                            }
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                  
                  
                        //Verifica se ja foi adicionado a base de dados este idr
                        if($t=='up'){
                            $sql = 'SELECT id, nota FROM favoritos_uploads WHERE id_upload = '.$idr.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
                        }else{
                            $sql = 'SELECT id, nota FROM favoritos_utilizadores WHERE id_favorito = '.$idr.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
                        }
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query) die("nao deu query ajsdao");
                        if($query->num_rows == 0){
                            $_SESSION['mensagemfavoritos'] = ' Ainda não adicionou esse trabalho ou utilizador aos favoritos.';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirfavoritos.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                        
                                                
                        
                        
                        $r = mysqli_fetch_assoc($query);
                        $nota_ant = $r['nota'];
                        $idfav = $r['id'];
                        if(isset($_POST['nota']) && $_POST['nota'] != $nota_ant){
                            //altera
                              $nota = mysqli_real_escape_string($link_bd, $_POST['nota']);
                              if($t == 'up'){
                                  $ins = "UPDATE `favoritos_uploads` SET `nota` = '".$nota."' WHERE `id` = '".$idfav."' LIMIT 1";
                              }else{
                                  $ins = "UPDATE `favoritos_utilizadores` SET `nota` = '".$nota."' WHERE `id` = '".$idfav."' LIMIT 1";
                              }
                              
                                $stmt = $link_bd->prepare($ins);
                                if ($stmt) {
                                        //$stmt->bind_param('iss', $varID, $var1, $var2);

                                            $stmt->execute();
                                }else{
                                    die("nao foi possivel a inscricao de do novo favorito");
                                }
                                $_SESSION['mensagemfavoritos'] = 'Favorito alterado com sucesso. ';
                        }
                        
                        
                        
                        
              }else{
                  //insere favorito
                        //verifica se o trabalho ou utilizador $idr existe
                        if($t=='up'){
                            $sql = 'SELECT datahora FROM uploads WHERE id = '.$idr.' LIMIT 1';
                        }else{
                            $sql = 'SELECT datahora FROM utilizadores WHERE id = '.$idr.' LIMIT 1';
                        }
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query) die("erro num asod9193");
                        if($query->num_rows == 0){
                            if($t == 'up'){
                                 $_SESSION['mensagemfavoritos'] = 'O trabalho/projeto que tentou adicionar não existe. ';
                                 $extra = 'gerirfavoritos.php';
                            }else{
                                 $_SESSION['mensagemfavoritos'] = 'O utilizador que tentou adicionar não existe. ';
                                 $extra = 'gerirfavoritos.php?ver=ut';
                            }
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }

                        //Verifica se ja foi adicionado a base de dados este idr
                        if($t=='up'){
                            $sql = 'SELECT id FROM favoritos_uploads WHERE id_upload = '.$idr.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
                        }else{
                            $sql = 'SELECT id FROM favoritos_utilizadores WHERE id_favorito = '.$idr.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
                        }
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query) die("nao deu query ajsdao");
                        if($query->num_rows > 0){
                            $_SESSION['mensagemfavoritos'] = ' Este favorito já se encontra adicionado.';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirfavoritos.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                        //verifica se o favorito nao é o proprio utilizador
                        if($t == 'ut' && $_SESSION['utilizador_id'] == $idr){
                            $_SESSION['mensagemfavoritos'] = ' Não é possível autoadicionar-se aos favoritos.';
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirfavoritos.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                       //Adiciona aos favoritos
                        $idutilizador = $_SESSION['utilizador_id'];
                        if($_POST['nota'] != '' && $_POST['nota'] != NULL){
                              $nota = mysqli_real_escape_string($link_bd, $_POST['nota']);
                              if($t == 'up'){
                                  $sql = "INSERT INTO favoritos_uploads (id_utilizador,id_upload,nota) VALUES ('$idutilizador','$idr','$nota')";
                              }else{
                                  $sql = "INSERT INTO favoritos_utilizadores (id_utilizador,id_favorito,nota) VALUES ('$idutilizador','$idr','$nota')";
                              }
                        }else{
                              if($t == 'up'){
                                  $sql = "INSERT INTO favoritos_uploads (id_utilizador,id_upload) VALUES ('$idutilizador','$idr')";
                              }else{
                                  $sql = "INSERT INTO favoritos_utilizadores (id_utilizador,id_favorito) VALUES ('$idutilizador','$idr')";
                              }
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
                        $_SESSION['mensagemfavoritos'] = 'Favorito adicionado com sucesso. ';
                        
              }
              
              
                        if($t=='up'){
                            $extra = 'gerirfavoritos.php';
                        }else{
                            $extra = 'gerirfavoritos.php?ver=ut';
                        }
                        //redireciona para o index
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            header ("location: http://$host$uri/$extra");