<?php
include_once '../includes/seguranca.php';
            
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
               //envia de volta se nao estiver logado       
              if(!isset($_SESSION['administrador_id']) || !isset($_SESSION['administrador_username']) || !isset($_SESSION['administrador_password'])){
                  
                    //envia-o de volta para o index
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
               //apaga noticia
                if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                    $idregisto = $_GET['id'];
                    

                    
                    $sql = "DELETE FROM galeria WHERE id=$idregisto LIMIT 1";
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
                        die("ocorreu um erro ao apagar da galeria");
                    }else{
                        $_SESSION['mensagemadmin'] = 'Imagem apagada com sucesso. ';
                         }
                   
                    if(file_exists('../galeria/'.$idregisto.'.jpg')){ //apaga imagens da noticia
                        unlink('../galeria/'.$idregisto.'.jpg');
                    }
                    if(file_exists('../galeria/'.$idregisto.'_pequena.jpg')){
                        unlink('../galeria/'.$idregisto.'_pequena.jpg');
                    }
                    if(file_exists('../galeria/'.$idregisto.'_miniatura.jpg')){
                        unlink('../galeria/'.$idregisto.'_miniatura.jpg');
                    }
                }
                
               
                
                  //envia-o de volta para o gerirnoticias.php
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirgaleria.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    
