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
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }   
              $mensagem = '';
              $idutilizador = $_SESSION['utilizador_id'];
              
                    if($_POST["pass1"] != ''){      //verifica se pass foi introduzida                 
                                                  //envia de volta se palavra-passe antiga introduzida não corresponder
                            $passa = $_POST['passa'];
                            $pass1 = $_POST['pass1'];
                            $passa_safe = Encrypter::encrypt($passa); 
                            $pass1_safe = Encrypter::encrypt($pass1);
                            $pass1_sha1 = sha1($pass1);
                            
                            $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
                            $sql = "SELECT `palavrap` FROM `utilizadores` WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";

                            $query = mysqli_query($link_bd, $sql);
                            if (!$query) {
                                die ("Ocorreu um erro na query.");
                            }else if(mysqli_num_rows($query) > 0) {
                                while ($resultado = mysqli_fetch_assoc($query)) {
                                    if($passa_safe != $resultado["palavrap"]){
                                        die("A palavrapasse antiga que introduziu esta errada.");
                                    }else{ // altera password
                                        $sql = "UPDATE `utilizadores` SET `palavrap` = '".$pass1_safe."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca da palavra-passe");
                                        }else{
                                            $mensagem .= 'Palavra-passe alterada com sucesso. ';
                                                }
                                                
                                        $sql = "UPDATE `utilizadores` SET `palavrap_sha1` = '".$pass1_sha1."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca da palavra-p2");
                                        }
                            }
                            }                          
                    }
                    }
                    if(isset($_FILES["imagem"])){ //verifica se há uma nova imagem inserida
                        $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                            $uploaddir = 'imagens_utilizadores/'; 
                            move_uploaded_file($_FILES['imagem']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $_SESSION["utilizador_id"] . '.jpg');
                            $caminhoimagem = $uploaddir.$_SESSION['utilizador_id'].'.jpg';
                            
                            $sql = "UPDATE `utilizadores` SET `imagem` = '".$caminhoimagem."' WHERE `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                            
                            //$sql = "UPDATE `utilizadores` SET `imagem` = `$caminhoimagem` WHERE `id` = `$idutilizador` LIMIT 1";
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
                                die("ocorreu um erro na mudanca da imagem");
                            }else{
                                $mensagem .= 'Imagem alterada com sucesso. ';
                                //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                               // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                
                                $checkimg1 = 'imagens_utilizadores/'.$idutilizador.'_pequena.jpg';
                                $checkimg2 = 'imagens_utilizadores/'.$idutilizador.'_media.jpg';
                                $checkimg3 = 'imagens_utilizadores/'.$idutilizador.'_lista.jpg';
                                $checkimg4 = 'imagens_utilizadores/'.$idutilizador.'_miniatura.jpg';
                                $checkimg5 = 'imagens_utilizadores/'.$idutilizador.'_perfil.jpg';
                                if (file_exists($checkimg1)) {
                                    unlink($checkimg1);
                                }
                                if(file_exists($checkimg2)) {
                                    unlink($checkimg2);
                                }
                                if(file_exists($checkimg3)) {
                                    unlink($checkimg3);
                                }
                                if(file_exists($checkimg4)) {
                                    unlink($checkimg4);
                                }
                                if(file_exists($checkimg5)) {
                                    unlink($checkimg5);
                                }
                                //--------------
                            }
                        }
                    }
                    
                    //verifica email e descricao antiga para ver se é preciso re-inserir na base de dados
                    $sql = 'SELECT email, descricao FROM utilizadores WHERE id = '.$_SESSION['utilizador_id'].' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("morreu em query a verificar a descricao antiag");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $descricao_ant = $r['descricao'];
                        $email_ant = $r['email'];
                    }
                    
                    if($_POST["descricao"] != '' && isset($descricao_ant) && $_POST["descricao"] != $descricao_ant){
                        $descricao = $_POST["descricao"];
                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                        $sql = "UPDATE `utilizadores` SET `descricao` = '".$descricao."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                        
                        //$sql = "UPDATE utilizadores SET descricao = $descricao WHERE id = $idutilizador LIMIT 1";
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
                                die("ocorreu um erro na mudanca da descricao.");
                            }else{
                                $mensagem .= 'Descrição alterada com sucesso. ';
                            }
                    }
                    if($_POST["email"] != '' && isset($email_ant) && $_POST["email"] != $email_ant){
                        $email = $_POST["email"];
                        $email = mysqli_real_escape_string($link_bd, $email);
                        //Verifica se o novo email ja existe na bd
                        $checksql = "SELECT id FROM utilizadores WHERE email = '$email' LIMIT 1";
                        $checkquery = mysqli_query($link_bd, $checksql);
                        if($checkquery->num_rows == 0){
                            $sql = "UPDATE `utilizadores` SET `email` = '".$email."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";

                            //$sql = "UPDATE utilizadores SET descricao = $descricao WHERE id = $idutilizador LIMIT 1";
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
                                    die("ocorreu um erro na mudanca do email.");
                                }else{
                                    $mensagem .= 'Email alterado com sucesso. ';
                                }
                        }else{
                            $_SESSION['mensagem_erro'] = 'O email que inseriu já existe, escolha outro. ';
                        }
                    }
                    //reencaminha para o index com a mensagem 
                    if($mensagem != ''){
                        $_SESSION['mensagem_sucesso'] = $mensagem;
                    }
                                        //redireciona para o index
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    