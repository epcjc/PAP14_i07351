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
              

              
                //insere conteudo e titulo
                $titulo = $_POST['titulo'];
                $titulo = mysqli_real_escape_string($link_bd, $titulo);
                $conteudo = $_POST['conteudo'];
                $conteudo = mysqli_real_escape_string($link_bd, $conteudo);
                $userid = $_SESSION['administrador_id'];
                
                $sql = "INSERT INTO noticias (id_utilizador, titulo, conteudo) VALUES ('$userid', '$titulo', '$conteudo')";
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
                    die("ocorreu um erro na insercao da noticia");
                }else{
                    $_SESSION['mensagemadmin'] = 'Noticia adicionada com sucesso. ';
                    $noticiaid = $link_bd->insert_id;
                        }
                
                        
                

              if(!isset($noticiaid)){
                 $noticiaid = rand(1300) . 'a' . rand(1300,2600); 
              }
                //verifica se foi adicionada uma imagem
              $foi = 0;//confirmaçao que introduziu uma imagem valida
              if(isset($_FILES['imagem'])){
                  $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                    if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                       $uploaddir = $_SG['caminhoservidor']."imagens_noticias/"; 
                       move_uploaded_file($_FILES['imagem']['tmp_name'], $uploaddir . $noticiaid .'.jpg'); 
                       $caminhoimagem = 'imagens_noticias/'. $noticiaid .'.jpg'; //caminho que irá para a base de dados 
                       $foi = 1;
              //envia-a para a pasta imagens_noticias com o nome $id.jpg (ex: 1.jpg, 8.jpg)
                    }
              }
                 //se não foi adicionada, é associada uma imagem default para noticias sem imagens
              if($foi == 0){
                  $caminhoimagem = 'imagens_noticias/imagem.jpg';
              }
                  //insere caminho da imagem na bd
                $sql = "UPDATE noticias SET imagem = '$caminhoimagem' WHERE id_utilizador = '$userid' AND id = '$noticiaid'";
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

                
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirnoticias.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    
