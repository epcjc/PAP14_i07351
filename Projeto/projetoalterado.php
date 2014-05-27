<?php

include_once 'includes/seguranca.php';
include_once 'includes/funcoes.php';
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
                    $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ver essa página.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }   
              $mensagem = '';
              $idutilizador = $_SESSION['utilizador_id'];
              
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idupl = $_GET['id'];
              }else{
                                      //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'O projeto que tentou alterar não existe';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              
                //verifica se utilizador pode alterar este projeto
              $sql = "SELECT id_utilizador, nomeoriginal, descricao, categoria, preco, titulo FROM uploads WHERE id = $idupl";
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("nao deu projetoalterado 1");
              $r = mysqli_fetch_assoc($query);
              if ($query->num_rows == 0){
                  //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'Esse projeto não existe';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              if ($r['id_utilizador'] != $idutilizador){
                  //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'Não pode alterar esse projeto';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              $nomeorg = $r['nomeoriginal'];
              $tituloant = $r['titulo'];
              $descricaoant = $r['descricao'];
              $categoriaant = $r['categoria'];
              $precoant = $r['preco'];
              

                    if(isset($_FILES["imagem1"])){ //verifica se há uma nova imagem inserida
                        $tipo = $_FILES['imagem1']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                            $uploaddir = 'upload/'.$idutilizador.'/'.$nomeorg.'/imagem1'; 
                            apagarconteudodir($uploaddir);
                            
                            move_uploaded_file($_FILES['imagem1']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir .'/'. $_FILES['imagem1']['name']);
                            $caminhoimagem1 = $uploaddir.'/'.$_FILES['imagem1']['name'];
                            
                            $sql = "UPDATE `uploads` SET `imagem1` = '".$caminhoimagem1."' WHERE `id` = '".$idupl."' LIMIT 1";
                           
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
                                die("ocorreu um erro na mudanca da imagem1");
                            }else{
                                $mensagem .= 'Imagem nº1 alterada com sucesso. ';
                                //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                               // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                //--------------
                            }
                        }
                    }
                    
                    if(isset($_FILES["imagem2"])){ //verifica se há uma nova imagem inserida
                        $tipo = $_FILES['imagem2']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                            $uploaddir = 'upload/'.$idutilizador.'/'.$nomeorg.'/imagem2'; 
                            apagarconteudodir($uploaddir);
                            
                            move_uploaded_file($_FILES['imagem2']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir .'/'. $_FILES['imagem2']['name']);
                            $caminhoimagem2 = $uploaddir.'/'.$_FILES['imagem2']['name'];
                            
                            $sql = "UPDATE `uploads` SET `imagem2` = '".$caminhoimagem2."' WHERE `id` = '".$idupl."' LIMIT 1";
                           
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
                                die("ocorreu um erro na mudanca da imagem2");
                            }else{
                                $mensagem .= 'Imagem nº2 alterada com sucesso. ';
                                //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                               // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                //--------------
                            }
                        }
                    }
                    
                   if(isset($_FILES["imagem3"])){ //verifica se há uma nova imagem inserida
                        $tipo = $_FILES['imagem3']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                            $uploaddir = 'upload/'.$idutilizador.'/'.$nomeorg.'/imagem3'; 
                            apagarconteudodir($uploaddir);
                            
                            move_uploaded_file($_FILES['imagem3']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir .'/'. $_FILES['imagem3']['name']);
                            $caminhoimagem3 = $uploaddir.'/'.$_FILES['imagem3']['name'];
                            
                            $sql = "UPDATE `uploads` SET `imagem3` = '".$caminhoimagem3."' WHERE `id` = '".$idupl."' LIMIT 1";
                           
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
                                die("ocorreu um erro na mudanca da imagem3");
                            }else{
                                $mensagem .= 'Imagem nº3 alterada com sucesso. ';
                                //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                               // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                //--------------
                            }
                        }
                    }
                    
                    if(isset($_FILES["imagem4"])){ //verifica se há uma nova imagem inserida
                        $tipo = $_FILES['imagem4']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                            $uploaddir = 'upload/'.$idutilizador.'/'.$nomeorg.'/imagem4'; 
                            apagarconteudodir($uploaddir);
                            
                            move_uploaded_file($_FILES['imagem4']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir .'/'. $_FILES['imagem4']['name']);
                            $caminhoimagem4 = $uploaddir.'/'.$_FILES['imagem4']['name'];
                            
                            $sql = "UPDATE `uploads` SET `imagem4` = '".$caminhoimagem4."' WHERE `id` = '".$idupl."' LIMIT 1";
                           
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
                                die("ocorreu um erro na mudanca da imagem4");
                            }else{
                                $mensagem .= 'Imagem nº4 alterada com sucesso. ';
                                //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                               // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';
                                //--------------
                            }
                        }
                    }
                    
                    if($_POST["titulo"] != '' && isset($tituloant) && $_POST["titulo"] != $tituloant){
                        $titulo = $_POST["titulo"];
                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                        $sql = "UPDATE `uploads` SET `titulo` = '".$titulo."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                        
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
                                die("ocorreu um erro na mudanca do titulo.");
                            }else{
                                $mensagem .= 'Título alterado com sucesso. ';
                            }
                    }
                    
                    if($_POST["descricao"] != '' && isset($descricaoant) && $_POST["descricao"] != $descricaoant){
                        $descricao = $_POST["descricao"];
                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                        $sql = "UPDATE `uploads` SET `descricao` = '".$descricao."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                        
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
                    
                    if($_POST['categoria'] != '' && isset($categoriaant) && $_POST['categoria'] != $categoriaant){
                        $categoria = $_POST["categoria"];
                        $categoria = mysqli_real_escape_string($link_bd, $categoria);
                        $sql = "UPDATE `uploads` SET `categoria` = '".$categoria."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                        
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
                                die("ocorreu um erro na mudanca da categoria.");
                            }else{
                                $mensagem .= 'Categoria alterada com sucesso. ';
                            }
                        
                        
                    }
                    
                    if(isset($precoant) && $_POST['preco'] != $precoant){
                        if($_POST['preco'] != ''){
                            $preco = $_POST["preco"];
                            $preco = mysqli_real_escape_string($link_bd, $preco);
                        }else{
                            $preco = 0;                            
                        }
                        $sql = "UPDATE `uploads` SET `preco` = '".$preco."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                        
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
                                die("ocorreu um erro na mudanca do preco.");
                            }else{
                                $mensagem .= 'Preço alterado com sucesso. ';
                            }
                  }       
                            
//----------------------------------------------
                        //verifica se é necessário mudar o upload para uploads_protegidos ou viceversa, dependendo do preço antigo e preco novo
                        //------------------------------------------------------------------
                            if(isset($preco) && $precoant > 0 && $preco == 0){
                                //Desprotege - apaga registo da tabela uploads_protegidos e move o ficheiro para a pasta de upload acessivel
                                $sql = 'SELECT ficheiro FROM uploads_protegidos WHERE id_upload = '.$idupl.' LIMIT 1';
                                $query = mysqli_query($link_bd, $sql);
                                if(!$query) die("nao deeu proteger ou desproteger");
                                $r = mysqli_fetch_assoc($query);
                                $ficheiro = $r['ficheiro'];
                           //     $ficheiro = base64_decode($ficheiro);
                                
                                //coloca ficheiro no disco
                                $destino = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeorg.'/'.$nomeorg;
                                
                               /* $handle = fopen($destino, 'w') or die("can't open file");
                                fclose($handle);*/
                                
                                file_put_contents($destino, $ficheiro);
                                
                                //apaga registo da bd
                                $sql = "DELETE FROM uploads_protegidos WHERE id_upload = $idupl";
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
                                    die("ocorreu um erro ao apagar a protecao do upload");
                                }
                                
                            }else if(isset($preco) && $precoant == 0 && $preco > 0){
                                //Protege - adiciona registo na tabela uploads_proteg com o ficheiro e apaga-o da pasta de upload acessivel
                                 $ficheiro = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeorg.'/'.$nomeorg;
                                 if(file_exists($ficheiro)){
                                     //Abre o ficheiro para leitura
                                        $abrir = fopen($ficheiro, "r"); 
                                        //Lê toda a imagem
                                        $ler = fread($abrir, filesize($ficheiro)); 
                                        // altera os caracters ' " para serem aceites na query
                                        $ler = addslashes($ler);

                                        //insere na bd
                                        $sql = "INSERT INTO uploads_protegidos (id_upload, ficheiro) VALUES ('$idupl','$ler')";
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

                                        //apaga o ficheiro que foi protegido do disco
                                        unlink($ficheiro);
                                 }else{
                                     die("houve um erro e nao foi detetado o ficheiro no disco");
                                 }
                                

                         }
                         //------------------------------------------------------------------
                            //--------------------------------------------------------------------
                        
                    
                    
                    
                    
                    
                    
                    
                    //reencaminha para o index com a mensagem 
                    if($mensagem != ''){
                        $_SESSION['mensagemprojetos'] = $mensagem;
                    }
                                        //redireciona para o index
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'gerirprojetos.php';
                    header ("location: http://$host$uri/$extra");
                    