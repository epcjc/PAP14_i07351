<?php
include_once '../includes/seguranca.php';
include_once '../includes/funcoes.php';
            
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
                    exit;
                }
               //apaga user
                if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] != 0){
                    $idupl = $_GET['id'];
                    
                    //verifica se existe
                    $sql = 'SELECT caminho, id_utilizador, nomeoriginal, preco FROM uploads WHERE id ='.$idupl.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("jasda apagar upl");
                    if($query->num_rows == 0){
                                //envia-o de volta para o admin.php
                        $_SESSION['mensagemadmin'] = 'Esse upload não existe, ou houve um erro com o parametro inserido no url.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'admin.php';
                        header ("location: http://$host$uri/$extra");
                        exit;
                    }
                    $r = mysqli_fetch_assoc($query);
                    $caminhoupl = $r['caminho'];
                    $idusr = $r['id_utilizador'];
                    $nomeorgupl = $r['nomeoriginal'];
                    $preco = $r['preco'];
                    
                    if(file_exists('../'.$caminhoupl)){
                        unlink('../'.$caminhoupl);
                    }
                    if(is_dir('../upload/'.$idusr.'/'.$nomeorgupl)){
                        apagardir('../upload/'.$idusr.'/'.$nomeorgupl);
                    }
                      //--------------------------------------------------------
                    
                    $sql = "DELETE FROM uploads WHERE id=$idupl LIMIT 1";
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
                    }else{
                        $_SESSION['mensagemadmin'] = 'Upload apagado com sucesso. ';
                         }
                   
                    
                
                
                  //apaga upload_protegido
                    if($preco > 0){
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
                    }
              
              //subtrai 1 valor no campo nuploads deste utilizador
                    $sqlcheck = 'SELECT nuploads FROM utilizadores WHERE id = '.$idusr;
                    $querycheck = mysqli_query($link_bd, $sqlcheck);
                    if(!$querycheck) die(" epa nao deu em subtrai 1 valor apagarprojeto");
                    $r = mysqli_fetch_assoc($querycheck);
                    if($r['nuploads'] > 0){
                        $novo_nuploads = $r['nuploads'] - 1;
                    }else{
                        $novo_nuploads = 0;
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
                        die("ocorreu um erro na mudanca dos nuploads");
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
                    
                    
               }
                
               
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'geriruploads.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    
