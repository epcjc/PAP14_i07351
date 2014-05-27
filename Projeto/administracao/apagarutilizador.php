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
                    $iduser = $_GET['id'];
                    
                    //verifica a permissao do user
                    $sql = 'SELECT permissao FROM utilizadores WHERE id ='.$iduser.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("jasda apagar user");
                    while($r = mysqli_fetch_assoc($query)){
                        $permissao = $r['permissao'];
                    }
                    if($permissao >= 2 || $query->num_rows == 0){
                                //envia-o de volta para o admin.php
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'admin.php';
                        header ("location: http://$host$uri/$extra");
                        exit;
                    }
                      //--------------------------------------------------------
                    
                    $sql = "DELETE FROM utilizadores WHERE id=$iduser LIMIT 1";
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
                        die("ocorreu um erro ao apagar o utilizador");
                    }else{
                        $_SESSION['mensagemadmin'] = 'Utilizador apagado com sucesso. ';
                         }
                   
                    if(file_exists('../imagens_utilizadores/'.$iduser.'.jpg')){ //apaga imagens  do utilizador
                        unlink('../imagens_utilizadores/'.$iduser.'.jpg');
                    }
                    if(file_exists('../imagens_utilizadores/'.$iduser.'_pequena.jpg')){
                        unlink('../imagens_utilizadores/'.$iduser.'_pequena.jpg');
                    }
                    if(file_exists('../imagens_utilizadores/'.$iduser.'_miniatura.jpg')){
                        unlink('../imagens_utilizadores/'.$iduser.'_miniatura.jpg');
                    }
                    if(file_exists('../imagens_utilizadores/'.$iduser.'_lista.jpg')){
                        unlink('../imagens_utilizadores/'.$iduser.'_lista.jpg');
                    }
                    if(file_exists('../imagens_utilizadores/'.$iduser.'_perfil.jpg')){
                        unlink('../imagens_utilizadores/'.$iduser.'_perfil.jpg');
                    }
                    if(file_exists('../imagens_utilizadores/'.$iduser.'_media.jpg')){
                        unlink('../imagens_utilizadores/'.$iduser.'_media.jpg');
                    }
                
                
                  //apaga bloqueios
                    $sql = "DELETE FROM bloqueios WHERE id_utilizador=$iduser";
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
                        die("ocorreu um erro ao apagar os blocks do utilizador1");
                    }
                    
                    $sql = "DELETE FROM bloqueios WHERE id_bloqueado=$iduser";
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
                        die("ocorreu um erro ao apagar os blocks do utilizador2");
                    }
                    
                    //apaga mensagens de administracao
                    
                    $sql = "DELETE FROM reports_utilizadores WHERE id_reportado=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    
                    $sql = "DELETE FROM mensagens_administracao WHERE id_utilizador=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    //apaga favoritos
                    $sql = "DELETE FROM favoritos_uploads WHERE id_utilizador=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    $sql = "DELETE FROM favoritos_utilizadores WHERE id_utilizador=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    $sql = "DELETE FROM favoritos_utilizadores WHERE id_favorito=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    
                    //apaga uploads
                    if(is_dir('upload/'.$iduser)){
                        apagardir('upload/'.$iduser);
                    }
                    $sql = "SELECT caminho FROM uploads WHERE id_utilizador = $iduser";
                    $query = mysqli_query($link_bd, $sql);
                    if (!$query) die("aishdas utilizadores");
                        $i = 0;
                        while($i < $query->num_rows){
                            $query->data_seek($i);
                            $datarow = $query->fetch_array(); 
                            if(file_exists($datarow['caminho'])){
                                unlink($datarow['caminho']);
                            }
                            $i++;
                        }
                    
                    $sql = "DELETE FROM uploads WHERE id_utilizador=$iduser";
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
                        die("ocorreu um erro ao apagar os cre do utilizador4");
                    }
                    //----------------
                    //apaga paginas e noticias se for administrador
                    if($permissao > 0){
                        $sql = "DELETE FROM paginas WHERE id_utilizador=$iduser";
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
                            die("ocorreu um erro ao apagar os cre do utilizador4");
                        }
                        
                        $sql = "SELECT id FROM noticias WHERE id_utilizador = $iduser";
                        $query = mysqli_query($link_bd, $sql);
                        if (!$query) die("aishdas utilizadores");
                        $i = 0;
                        while($i < $query->num_rows){
                            $query->data_seek($i);
                            $datarow = $query->fetch_array(); 
                            if(file_exists('imagens_noticias/'.$datarow['id'].'.jpg')){
                                unlink('imagens_noticias/'.$datarow['id'].'.jpg');
                            }
                            if(file_exists('imagens_noticias/'.$datarow['id'].'_pequena.jpg')){
                                unlink('imagens_noticias/'.$datarow['id'].'_pequena.jpg');
                            }
                            if(file_exists('imagens_noticias/'.$datarow['id'].'_lista.jpg')){
                                unlink('imagens_noticias/'.$datarow['id'].'_lista.jpg');
                            }
                            if(file_exists('imagens_noticias/'.$datarow['id'].'_miniatura.jpg')){
                                unlink('imagens_noticias/'.$datarow['id'].'_miniatura.jpg');
                            }
                            $i++;
                        }
                        
                        
                        $sql = "DELETE FROM noticias WHERE id_utilizador=$iduser";
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
                            die("ocorreu um erro ao apagar os cre do utilizador4");
                        }
                    
                        
                        
                    }
                    
                    
               }
                
               
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirutilizadores.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    