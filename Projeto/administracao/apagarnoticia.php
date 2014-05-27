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
               //apaga noticia
                if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                    $idnoticia = $_GET['id'];
                    
                                                                    //Verifica se o administrador logado pode alterar esta noticia
                            $sql = "SELECT id_utilizador FROM noticias WHERE id=$idnoticia";
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query){
                                die("morreu em aaaaaaaaaaaaa apagarnoticia");
                            }
                            while($r = mysqli_fetch_assoc($query)){
                                $iduser = $r['id_utilizador'];
                            }
                            if($iduser != $_SESSION['administrador_id'] && $_SESSION['administrador_rank'] < 2){
                                $_SESSION['mensagemadmin'] = "Não pode apagar esta noticia";
                                $host = $_SERVER['HTTP_HOST'];
                                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                                $extra = 'admin.php';
                                header ("location: http://$host$uri/$extra");
                            }
        
                      //--------------------------------------------------------
                    
                    $sql = "DELETE FROM noticias WHERE id=$idnoticia LIMIT 1";
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
                        die("ocorreu um erro ao apagar da noticia");
                    }else{
                        $_SESSION['mensagemadmin'] = 'Noticia apagada com sucesso. ';
                         }
                   
                    if(file_exists('../imagens_noticias/'.$idnoticia.'.jpg')){ //apaga imagens da noticia
                        unlink('../imagens_noticias/'.$idnoticia.'.jpg');
                    }
                    if(file_exists('../imagens_noticias/'.$idnoticia.'_pequena.jpg')){
                        unlink('../imagens_noticias/'.$idnoticia.'_pequena.jpg');
                    }
                    if(file_exists('../imagens_noticias/'.$idnoticia.'_miniatura.jpg')){
                        unlink('../imagens_noticias/'.$idnoticia.'_miniatura.jpg');
                    }
                    if(file_exists('../imagens_noticias/'.$idnoticia.'_lista.jpg')){
                        unlink('../imagens_noticias/'.$idnoticia.'_lista.jpg');
                    }
                
                //apaga comentarios dessa noticia
                    $sql = "DELETE FROM comentarios_noticias WHERE id_noticia=$idnoticia";
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
                        die("ocorreu um erro ao apagar os coomentarios da noticia");
                    }
                //------------------------------
               }
                
                  //envia-o de volta para o gerirnoticias.php
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirnoticias.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    