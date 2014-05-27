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
                    $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ver essa página';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }
              if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                  $idupl = $_GET['id']; 
              }else{
                  //reencaminha 
                                      //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'Tentou votar num trabalho/projeto que não existe.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
              }
              //verifica se projeto existe
              $sql = 'SELECT nlikes,ndislikes FROM uploads WHERE id = '.$idupl.' LIMIT 1';
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("morreu votar jhdaisj");
              if($query->num_rows == 0){
                  //reencaminha
                                    //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'Tentou votar num trabalho/projeto que não existe.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
              }
              $r=mysqli_fetch_assoc($query);
              $nlikes_ant = $r['nlikes'];
              $ndislikes_ant = $r['ndislikes'];
              if($nlikes_ant == NULL || $nlikes_ant == ''){
                  $nlikes_ant = 0;
              }
              if($ndislikes_ant == NULL || $ndislikes_ant == ''){
                  $ndislikes_ant = 0;
              }
              //-------------
              //verifica se ja votou neste projeto
              $votoant = '';
              $sql = 'SELECT voto FROM votacoes WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_upload = '.$idupl.' LIMIT 1';
              $query = mysqli_query($link_bd, $sql);
              if(!$query) die("morreu votar nafshfas");
              if($query->num_rows > 0){
                  $r = mysqli_fetch_assoc($query);
                  $votoant = $r['voto'];
              }
              
              
              $v = '';
              if(isset($_GET['v'])){
                  switch($_GET['v']){
                      case 's': $v='s'; break;
                      case 'n': $v='n'; break;
                      case 'r': $v='r'; break;
                  }
              }
              if($v == ''){
                  //reencaminha
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'trabalho.php?id='.$idupl;
                    header ("location: http://$host$uri/$extra");
              }
              
              $idusr = $_SESSION['utilizador_id'];
              
              if($v == 's' && $votoant == ''){
                  //vota sim e aumenta 1 valor de nvotacoes do utilizador e aumenta 1 nlikes no upload
                        $sql = "INSERT INTO votacoes (id_utilizador,id_upload,voto) VALUES ('$idusr','$idupl','$v')";
                        $stmt = $link_bd->prepare($sql);
                        if ($stmt) {
                            if ($stmt->execute()) {
                                $success = TRUE;   //or something like that
                             }else{
                                 $success = FALSE;//
                             }
                        }
                        if($success == FALSE){
                            die('houve um erro na query [' . $link_bd->error . ']');
                        }
                            
                            $sql = 'SELECT nvotacoes FROM utilizadores WHERE id='.$idusr.' LIMIT 1';
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query) die("morreu nvotacoes mais um");
                            $r = mysqli_fetch_assoc($query);
                            $nvotacoes = $r['nvotacoes']+1;
                        
                            $sql = "UPDATE `utilizadores` SET `nvotacoes` = '".$nvotacoes."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca das nvotacoes.");
                                }
                               
                            $nlikes = $nlikes_ant + 1;    
                            $sql = "UPDATE `uploads` SET `nlikes` = '".$nlikes."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca dos nlikes.");
                                }
                                
                                
                           
              }else if($v == 'n' && $votoant == ''){
                  //vota nao e aumenta 1 valor de nvotos do utilizador e aumenta 1 ndislikes no upload
                        $sql = "INSERT INTO votacoes (id_utilizador,id_upload,voto) VALUES ('$idusr','$idupl','$v')";
                        $stmt = $link_bd->prepare($sql);
                        if ($stmt) {
                            if ($stmt->execute()) {
                                $success = TRUE;   //or something like that
                             }else{
                                 $success = FALSE;//
                             }
                        }
                        if($success == FALSE){
                            die('houve um erro na query [' . $link_bd->error . ']');
                        }
                            
                            $sql = 'SELECT nvotacoes FROM utilizadores WHERE id='.$idusr.' LIMIT 1';
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query) die("morreu nvotacoes mais um 2");
                            $r = mysqli_fetch_assoc($query);
                            $nvotacoes = $r['nvotacoes']+1;
                        
                            $sql = "UPDATE `utilizadores` SET `nvotacoes` = '".$nvotacoes."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca das nvotacoes 2.");
                                }
                                
                                $ndislikes = $ndislikes_ant + 1;    
                            $sql = "UPDATE `uploads` SET `ndislikes` = '".$ndislikes."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca dos ndislikes.");
                                }
                                
                                
              }else if($v == 'r' && $votoant != ''){
                  //remove voto e remove 1 valor de nvotos do utilizador e remove 1 ndislike ou 1 nlike do uload
                            $sql = "DELETE FROM votacoes WHERE id_upload=$idupl AND id_utilizador=$idusr";
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
                                die("ocorreu um erro ao apagar o voto do utilziador");
                            }
                  
                            $sql = 'SELECT nvotacoes FROM utilizadores WHERE id='.$idusr.' LIMIT 1';
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query) die("morreu nvotacoes menos um");
                            $r = mysqli_fetch_assoc($query);
                            if($r['nvotacoes'] > 0){
                                $nvotacoes = $r['nvotacoes']-1;
                            }else{
                                $nvotacoes = 0;
                            }
                            $sql = "UPDATE `utilizadores` SET `nvotacoes` = '".$nvotacoes."' WHERE ".$cS." `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca das nvotacoes 3.");
                                }
                            
                                if($nlikes_ant > 0){
                                    $nlikes = $nlikes_ant - 1;  
                                }else{
                                    $nlikes = 0;
                                }
                                if($ndislikes_ant > 0){
                                    $ndislikes = $ndislikes_ant - 1;
                                }else{
                                    $ndislikes = 0;
                                }
                            
                            if($votoant == "s"){
                                $sql = "UPDATE `uploads` SET `nlikes` = '".$nlikes."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                            }else{
                                $sql = "UPDATE `uploads` SET `ndislikes` = '".$ndislikes."' WHERE ".$cS." `id` = '".$idupl."' LIMIT 1";
                            }   
                            
                            $stmt = $link_bd->prepare($sql);
                                $success = FALSE;
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $success = TRUE;   //or something like that
                                    }else{
                                        $success = FALSE;//
                                    }
                                }
                                if($success == FALSE){
                                    die("ocorreu um erro na mudanca dos nlikes ou ndislikes.");
                                }
                                
                                
              }
              
              
              
              
              
              
                                        //redireciona
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'trabalho.php?id='.$idupl;
                    header ("location: http://$host$uri/$extra");
                    