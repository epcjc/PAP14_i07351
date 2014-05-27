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
                $t = '';
                if(isset($_GET['t'])){
                    switch ($_GET['t']){
                        case 'up': $t = 'up'; break;
                        case 'ut': $t = 'ut'; break;
                    }
                }
                
                if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] != 0 && $t != ''){
                    $idrep = $_GET['id'];
                    
                    //verifica se existe
                    if($t == 'up'){
                        $sql = 'SELECT id_upload FROM reports_uploads WHERE id ='.$idrep.' LIMIT 1';
                    }else{
                        $sql = 'SELECT id_reportado FROM reports_utilizadores WHERE id ='.$idrep.' LIMIT 1';
                    }
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("jasda apagar upl");
                    if($query->num_rows == 0){
                                //envia-o de volta para o admin.php
                        $_SESSION['mensagemadmin'] = 'Esse report não existe, ou houve um erro com o parametro inserido no url.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'admin.php';
                        header ("location: http://$host$uri/$extra");
                        exit;
                    }
                    $r = mysqli_fetch_assoc($query);
                    if($t == 'up'){
                        $id_reportado = $r['id_upload'];
                        $sql = "DELETE FROM reports_uploads WHERE id=$idrep";
                    }else{
                        $id_reportado = $r['id_reportado'];
                        $sql = "DELETE FROM reports_utilizadores WHERE id=$idrep";
                    }
                    
                     
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
                        die("ocorreu um erro ao apagar o report");
                    }else{
                        $_SESSION['mensagemadmin'] = 'A denúncia foi apagada com sucesso. ';
                    }
                    
                    //diminui um nreports no upload
                    if($t == 'up'){
                        $sqlcheck = 'SELECT nreports FROM uploads WHERE id = '.$id_reportado.' LIMIT 1';
                    }else{
                        $sqlcheck = 'SELECT nreports FROM utilizadores WHERE id = '.$id_reportado.' LIMIT 1';
                    }
                    $querycheck = mysqli_query($link_bd, $sqlcheck);
                    if(!$querycheck) die(" epa nao deu em subtrai 1 valor apagarreport");
                    $r = mysqli_fetch_assoc($querycheck);
                    if($r['nreports'] > 0){
                        $novo_nreports = $r['nreports'] - 1;
                    }else{
                        $novo_nreports = 0;
                    }
                    if($t == 'up'){
                        $sql = "UPDATE `uploads` SET `nreports` = '".$novo_nreports."' WHERE `id` = '".$id_reportado."' LIMIT 1";
                    }else{
                        $sql = "UPDATE `utilizadores` SET `nreports` = '".$novo_nreports."' WHERE `id` = '".$id_reportado."' LIMIT 1";
                    }
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
                        die("ocorreu um erro na mudanca dos nreports");
                    }
                    
                }                
                
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                if($t == 'up'){
                    $extra = 'reportsupload.php?id='.$id_reportado;
                }else{
                    $extra = 'reportsutilizador.php?id='.$id_reportado;
                }
                header ("location: http://$host$uri/$extra");