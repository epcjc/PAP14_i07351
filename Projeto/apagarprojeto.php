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
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idupl = $_GET['id'];
              }else{
                    $_SESSION['mensagem_erro'] = 'Esse projeto/trabalho não existe.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              //Verifica se existe e se foi carregado por este utilizador
              $sql = 'SELECT id_utilizador, caminho, nomeoriginal FROM uploads WHERE id='.$idupl;
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("morreu query apagar upload da");
              $r= mysqli_fetch_assoc($query);
              if($query->num_rows == 0){
                    $_SESSION['mensagem_erro'] = 'Esse projeto/trabalho não existe.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }else if($r['id_utilizador'] != $_SESSION['utilizador_id']){
                    $_SESSION['mensagem_erro'] = 'Não pode apagar esse projeto.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              $caminhoupl = $r['caminho'];
              $nomeorgupl = $r['nomeoriginal'];
              $idusr = $r['id_utilizador'];
              //apaga pasta do upload 
              if(file_exists($caminhoupl)){
                  unlink($caminhoupl);
              }
              if(is_dir('upload/'.$idusr.'/'.$nomeorgupl)){
                  apagardir('upload/'.$idusr.'/'.$nomeorgupl);
              }
              //apaga upload
                    $sql = "DELETE FROM uploads WHERE id = $idupl";
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
                        die("ocorreu um erro ao apagar o upload");
                    }
                    
                    //apaga upload_protegido
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
              
              //subtrai 1 valor no campo nuploads deste utilizador
                    $sqlcheck = 'SELECT nuploads FROM utilizadores WHERE id = '.$idusr;
                    $querycheck = mysqli_query($link_bd, $sqlcheck);
                    if(!$querycheck) die(" epa nao deu em subtrai 1 valor apagarprojeto");
                    $r = mysqli_fetch_assoc($querycheck);
                    if($r['nuploads'] > 0){
                        $novo_nuploads = $r['nuploads'] - 1;
                    }else{
                        $novo_nuploads = $r['nuploads'];
                    }
                     $sql = "UPDATE `utilizadores` SET `nuploads` = '".$novo_nuploads."' WHERE `id` = '".$idusr."' LIMIT 1";
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
                        die("ocorreu um erro na mudanca do ultimo nome");
                    }
                    
                    
              //apaga comentarios feitos neste upload
                    $sql = "DELETE FROM comentarios_uploads WHERE id_upload=$idupl";
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
                        die("ocorreu um erro ao apagar os comentarios do upload");
                    }
              
              //apaga votos neste upload
                    $sql = "DELETE FROM votacoes WHERE id_upload=$idupl";
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
                        die("ocorreu um erro ao apagar os votos do upload");
                    }
              
              //apaga favoritos deste upload
                    $sql = "DELETE FROM favoritos_uploads WHERE id_upload=$idupl";
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
                        die("ocorreu um erro ao apagar os favoritos do upload");
                    }
              
              //apaga reports deste upload
                    $sql = "DELETE FROM reports_uploads WHERE id_upload=$idupl";
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
                        die("ocorreu um erro ao apagar os reports do upload");
                    }
                        
                        //---------------------------------------------------------------------
                  //redireciona-o para o index

                        //redireciona para o index
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'gerirprojetos.php';
                        header ("location: http://$host$uri/$extra");