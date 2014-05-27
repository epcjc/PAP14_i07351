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
              
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idutilizadorb = $_GET['id'];
              }else{
                  //envia d volta
                    $_SESSION['mensagem_erro'] = 'Foi adicionado um parametro inválido ao bloquear.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              //verifica se pode bloquear
              if($idutilizadorb == $_SESSION['utilizador_id']){
                    $_SESSION['mensagem_erro'] = 'Não são permitidos auto-bloqueios.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }else{
                  $idutilizador = $_SESSION['utilizador_id'];
              }
              
              
              //verifica se ja há um bloqueio com estes 2 utilizadores
              $sql = 'SELECT id FROM bloqueios WHERE id_utilizador = '.$idutilizador.' AND id_bloqueado = '.$idutilizadorb.' LIMIT 1';
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu verificar bloqueio");
              if($query->num_rows > 0){
                  $acao = 'd';//desbloquear
                  $r = mysqli_fetch_assoc($query);
                  $idbloqueio = $r['id'];
              }else{
                  $acao = 'b'; //bloquear
              }
              //verifica se o utilizador bloqueado existe
              if($acao == 'b'){
                  $sql = 'SELECT datahora FROM utilizadores WHERE id = '.$idutilizadorb.' LIMIT 1';
                  $query = mysqli_query($link_bd, $sql);
                  if(!$query) die("nao deu verificar se bloqueado existe");
                  if($query->num_rows == 0){
                    $_SESSION['mensagem_erro'] = 'O utilizador que tentou bloquear não existe.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
                  }
              }
              
              if($acao == 'b'){
                  //insere bloqueio
                  $sql = "INSERT INTO bloqueios (id_utilizador,id_bloqueado) VALUES ('$idutilizador','$idutilizadorb')";
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
                        
                        $msg = "O utilizador foi bloqueado.";
                        
              }else{
                  //apaga bloqueio
                  $sql = "DELETE FROM bloqueios WHERE id = $idbloqueio LIMIT 1";
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
                                    die("ocorreu um erro ao apagar o bloqueio");
                                }
                                
                        $msg = "O utilizador foi desbloqueado.";

              }
              
                        if(isset($msg)){
                            $_SESSION['mensagem_mensagens'] = $msg;
                        }
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'utilizadoresbloqueados.php';
                        header ("location: http://$host$uri/$extra");
                                   


