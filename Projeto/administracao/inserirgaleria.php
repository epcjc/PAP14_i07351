<?php
include_once '../includes/seguranca.php';
include_once '../WideImage/lib/WideImage.php';
            
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

              
                //insere conteudo e titulo
                $descricao = $_POST['descricao'];
                $descricao = mysqli_real_escape_string($link_bd, $descricao);
                $userid = $_SESSION['administrador_id'];
                
                $sql = "INSERT INTO galeria (id_utilizador, descricao) VALUES ('$userid', '$descricao')";
                $stmt = $link_bd->prepare($sql);
                if ($stmt) {
                //$stmt->bind_param('iss', $varID, $var1, $var2);
                    if ($stmt->execute()) {
                        $success = TRUE;   
                    }else{
                        $success = FALSE;//
                    }
                }
                if($success == FALSE){
                    die("ocorreu um erro na insercao da galeria");
                }else{
                    
                    $registoid = $link_bd->insert_id;
                        }
                
                        
                

              if(!isset($registoid)){
                 $registoid = rand(1300) . 'a' . rand(1300,2600); 
              }
                //verifica se foi adicionada uma imagem
              $foi = 0;//confirmaçao que introduziu uma imagem valida
              if(isset($_FILES['imagem'])){
                  $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                    if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                       $uploaddir = $_SG['caminhoservidor']."galeria/"; 
                       move_uploaded_file($_FILES['imagem']['tmp_name'], $uploaddir . $registoid .'.jpg'); 
                       $caminhoimagem = 'galeria/'. $registoid .'.jpg'; //caminho que irá para a base de dados
                       
                        $novaimagem = WideImage::loadFromFile('../galeria/'.$registoid.'.jpg');
                   // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(960, 473, 'outside');
                        $novaimagem = $novaimagem->crop('center', 'center', 960, 473);
                        // Guarda a imagem
                        $imagemsubs = '../galeria/'.$registoid.'.jpg'; // ex: 5_miniatura, 82_miniatura
                        $novaimagem->saveToFile($imagemsubs, 40); // Coloca a imagem pequena no disco
                       $_SESSION['mensagemadmin'] = 'Imagem adicionada com sucesso. ';
                       $foi = 1;
              //envia-a para a pasta imagens_noticias com o nome $id.jpg (ex: 1.jpg, 8.jpg)
                    }
              }
                 //se não foi adicionada
              if($foi == 0){
                  $_SESSION['mensagemadmin'] = 'A imagem não foi aceite. ';
                  $sql = "DELETE FROM galeria WHERE id=$registoid LIMIT 1";
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
                    }

              }else{
                    //insere caminho da imagem na bd
                $sql = "UPDATE galeria SET imagem = '$caminhoimagem' WHERE id = '$registoid'";
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
                    die("ocorreu um erro na insercao da imagem");
                }
              }
                

                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirgaleria.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    

