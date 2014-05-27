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
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }        
                  
                 //-----------    

              //envia de volta se ja existir um projeto com o mesmo nomeoriginal
          //verifica se existe projeto com o mesmo nome
                    $nomeoriginal = $_FILES['projeto']['name'];
                    $nomeoriginal = mysqli_real_escape_string($link_bd, $nomeoriginal);
                    $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
                    $sql = "SELECT `titulo` FROM `uploads` WHERE ".$cS." `id_utilizador` = '".$_SESSION['utilizador_id']."' AND nomeoriginal = '".$nomeoriginal."' LIMIT 1";

                    $query = mysqli_query($link_bd, $sql);
                    if (!$query) {
                        die ("Ocorreu um erro na query.");
                    }else if(mysqli_num_rows($query) > 0) {
                          echo '<br/><div align="center">Ja existe um projeto com o nome '.$nomeoriginal.' enviado por si. Por favor, altere o nome do ficheiro antes de o enviar, ou apague o anterior.<br /><br /></div>';
                            echo '<div align="center"><a href="javascript:history.go(-1)">Voltar atras</a></div>';
                            exit;
                        
                    }
                        
                    

                    
                    
                    //------------------------------------------
              //------------
                    
                    $uploaddir = './upload/'.$_SESSION['utilizador_id'] ;                   //cria diretorio para user se não existir
                    if(!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true);  
                    }
                    $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/' ;
                    
                    //------------------
                    $uploadfile = $uploaddir . $nomeoriginal;
                    
                    $size = $_FILES['projeto']['size'];
                    $type = $_FILES['projeto']['type'];
                    
          
                    
                    //---------cria pasta do projeto/imagens na pasta do utilizador, e move o projeto para la
                    
                    $uploaddir = './upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal;                   //cria diretorio para projeto, se já existir retorna falso
                    $caminhoprojeto = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/'.$nomeoriginal;
                    $uploaddir_imagem1 = './upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem1';  
                    $uploaddir_imagem2 = './upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem2';  
                    $uploaddir_imagem3 = './upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem3';  
                    $uploaddir_imagem4 = './upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem4';  
                    if(!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true); 
                        mkdir($uploaddir_imagem1, 0777, true);
                        mkdir($uploaddir_imagem2, 0777, true);
                        mkdir($uploaddir_imagem3, 0777, true);
                        mkdir($uploaddir_imagem4, 0777, true);
                    }else{
                        die("ja existe um projeto com esse nome, por favor altere");
                    }
                    
                    //move projeto 
                    $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/'; 
                    $foi = move_uploaded_file($_FILES['projeto']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $nomeoriginal);
                    if($foi != true){
                         die ("Ocorreu um erro ao mover o arquivo");

                    }
                   
                    //acrescenta 1 ao campo ncomentarios na tabela utilizadores
                    //----------------------------------------------
                    $sql = 'SELECT nuploads FROM utilizadores WHERE id = '.$_SESSION['utilizador_id'].' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $ant_nupl = $r['nuploads'];
                    }
                    $nupl = $ant_nupl + 1;
                    $ins = "UPDATE `utilizadores` SET `nuploads` = '".$nupl."' WHERE `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }
                    //------------------------------------------------
                    //------------------------------------------------
                    
                    //verifica se foram adicionadas imagens e move-as--------
                    
                    if(isset($_FILES['imagem1'])){
                        $tipo = $_FILES['imagem1']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                           $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem1/'; 
                           move_uploaded_file($_FILES['imagem1']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $_FILES['imagem1']['name']); 
                           $caminhoimagem1 = 'upload/'.$_SESSION['utilizador_id'].'/'. $nomeoriginal . '/imagem1/' . $_FILES['imagem1']['name']; //guarda caminho para a bd
                        }else{
                           $caminhoimagem1 = NULL; 
                        }
                        
                    }else{
                        $caminhoimagem1 = NULL;
                        
                    }
                    if(isset($_FILES['imagem2'])){
                        $tipo = $_FILES['imagem2']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                           $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem2/'; 
                           move_uploaded_file($_FILES['imagem2']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $_FILES['imagem2']['name']);
                           $caminhoimagem2 = 'upload/'.$_SESSION['utilizador_id'].'/'. $nomeoriginal . '/imagem2/' . $_FILES['imagem2']['name']; //guarda caminho para a bd
                        }else{
                           $caminhoimagem2 = NULL; 
                        }
                        
                    }else{
                        $caminhoimagem2 = NULL;
                    }
                    if(isset($_FILES['imagem3'])){
                        $tipo = $_FILES['imagem3']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                           $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem3/'; 
                           move_uploaded_file($_FILES['imagem3']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $_FILES['imagem3']['name']); 
                           $caminhoimagem3 = 'upload/'.$_SESSION['utilizador_id'].'/'. $nomeoriginal . '/imagem3/' . $_FILES['imagem3']['name']; //guarda caminho para a bd
                        }else{
                           $caminhoimagem3 = NULL; 
                        }
                        
                    }else{
                        $caminhoimagem3 = NULL;
                        
                    }
                    if(isset($_FILES['imagem4'])){
                        $tipo = $_FILES['imagem4']['type'];                       //verifica se é imagem
                        if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                           $uploaddir = 'upload/'.$_SESSION['utilizador_id'].'/'.$nomeoriginal.'/imagem4/'; 
                           move_uploaded_file($_FILES['imagem4']['tmp_name'], $_SG['caminhoservidor'] . $uploaddir . $_FILES['imagem4']['name']); 
                           $caminhoimagem4 = 'upload/'.$_SESSION['utilizador_id'].'/'. $nomeoriginal . '/imagem4/' . $_FILES['imagem4']['name']; //guarda caminho para a bd
                        }else{
                           $caminhoimagem4 = NULL; 
                        }
                        
                    }else{
                        $caminhoimagem4 = NULL;
                        
                    }
                    //-----------------------------------------------------------------
                    
                    //---------insere na base de dados
                    
                        $titulo = $_POST['titulo'];
                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                        $descricao = $_POST['descricao'];
                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                        if(isset($_POST['preco']) && $_POST['preco'] != ''){
                            $preco = $_POST['preco'];    
                        }else{
                            $preco = 0;
                        }
                        
                        $preco = mysqli_real_escape_string($link_bd, $preco);
                        $categoria = $_POST['categoria'];
                        $userid = $_SESSION['utilizador_id'];
                        $sql = "INSERT INTO uploads (titulo,caminho,imagem1,imagem2,imagem3,imagem4,descricao,id_utilizador,preco,size,type,categoria,nomeoriginal) VALUES ('$titulo','$caminhoprojeto','$caminhoimagem1','$caminhoimagem2','$caminhoimagem3','$caminhoimagem4','$descricao','$userid','$preco','$size','$type','$categoria','$nomeoriginal')";
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
                        }else{
                            $id_upload = $link_bd->insert_id;
                        }
                        
                        //----------------------------------------------------------------------------------------------------------
                        //verifica se o upload tem preço, se sim, envia-o para a bd uploads_protegidos, e apaga o ficheiro do disco
                        //--------------------------------------------------------------------------------------------------------
                        $ficheiro = 'upload/'.$userid.'/'.$nomeoriginal.'/'.$nomeoriginal;
                        if($preco > 0 && file_exists($ficheiro)){
                            
                            
                            //Abre o ficheiro para leitura
                            $abrir = fopen($ficheiro, "r"); 
                            //Lê toda a imagem
                            $ler = fread($abrir, filesize($ficheiro)); 
                            // altera os caracters ' " para serem aceites na query
                            $ler = addslashes($ler);
                            
                            //insere na bd
                            $sql = "INSERT INTO uploads_protegidos (id_upload, ficheiro) VALUES ('$id_upload','$ler')";
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
                        }
                        //-----------------------------------------------------------------------------------------------------------
                        //----------------------------------------------------------------------------------------------------------
                        
                        
                        
                        //---------------------------------------------------------------------
                  //redireciona-o para o index
                        $msg = "O seu projeto/trabalho foi enviado com sucesso.";
                        $_SESSION['mensagem_sucesso'] = $msg;
                        //redireciona para o index
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header ("location: http://$host$uri/$extra");