<?php
include_once '../includes/seguranca.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../WideImage/lib/WideImage.php';

$home = file_get_contents('HTML/mainadmin.html');

//verifica se há mensagem a apresentar


if(!isset($_SESSION['administrador_id']) || !isset($_SESSION['administrador_username']) || !isset($_SESSION['administrador_password'])){ //verifica se um utilizador com permissao efetuou o login
//Envia de volta para admin.php se nao estiver logado
//envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "É necessário fazer o login para ver essa página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}
if($_SESSION['administrador_rank'] < 2){
        $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode ver essa página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}

if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idutilizador = $_GET['id'];
}else{
                //envia-o de volta para o gerirutilizadores.php
        $_SESSION['mensagemadmin'] = "O utilizador que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirutilizadores.php';
        header ("location: http://$host$uri/$extra");  
}
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
                      
                      
                      
                     //Verifica se o utilizador existe na bd
                          $sql = "SELECT username, pnome, unome, pais, descricao, email, permissao FROM utilizadores WHERE id=$idutilizador";
    
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("ocorreu um erro na query da imagem em alterarutilizador.php");
                        }
                        if($query->num_rows == 0){
                                //envia-o de volta para o gerirutilizadores.php
                            $_SESSION['mensagemadmin'] = "O utilizador que tentou alterar não existe.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirutilizadores.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }
                        $r = mysqli_fetch_assoc($query);
                        $username_ant = $r['username'];
                        $pnome_ant = $r['pnome'];
                        $unome_ant = $r['unome'];
                        $pais_ant = $r['pais'];
                        $descricao_ant = $r['descricao'];
                        $permissao_ant = $r['permissao'];
                        $email_ant = $r['email'];
                        if($permissao_ant == NULL || $permissao_ant == 0 || $permissao_ant == ''){
                            $permissao_ant = 'utilizador';
                        }else if($permissao_ant>=2 && $_SESSION['administrador_id'] != $idutilizador){
                            $_SESSION['mensagemadmin'] = "Não pode alterar um administrador geral.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirutilizadores.php';
                            header ("location: http://$host$uri/$extra");
                            exit;
                        }else{                            
                            $permissao_ant = 'administrador';
                        }
                        



//altera dados
                     
                        $mensagem = '';
                        
                        if(isset($_FILES['imagem'])){
                            //faz update da imagem
                            $tipo = $_FILES['imagem']['type'];                       //verifica se é imagem
                            if($tipo == "image/gif" || $tipo == "image/jpg" || $tipo == "image/jpeg" || $tipo == "image/png"){
                                    $uploaddir = '../imagens_utilizadores/'; 
                                    move_uploaded_file($_FILES['imagem']['tmp_name'], $uploaddir . $idutilizador . '.jpg');
                                    $caminhoimagem = 'imagens_utilizadores/'.$idutilizador.'.jpg';

                                    $sql = "UPDATE `utilizadores` SET `imagem` = '".$caminhoimagem."' WHERE `id` = '".$idutilizador."' LIMIT 1";

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
                                        die("ocorreu um erro na mudanca da imagem do utilizador");
                                    }else{
                                        $mensagem .= 'Imagem do utilizador nº'.$idutilizador.' alterada com sucesso. ';
                                        //verifica se utilizador tem as imagens adaptadas(ex: 65_pequena, 34_media) e apaga-as para que possam ser criadas novas com a nova imagem
                                       // $checkimg1 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_pequena.jpg';
                                        //$checkimg2 = '/imagens_utilizadores/'.$_SESSION['utilizador_id'].'_media.jpg';

                                        $checkimg1 = '../imagens_utilizadores/'.$idutilizador.'_pequena.jpg';
                                        $checkimg2 = '../imagens_utilizadores/'.$idutilizador.'_miniatura.jpg';
                                        $checkimg3 = '../imagens_utilizadores/'.$idutilizador.'_lista.jpg';
                                        $checkimg4 = '../imagens_utilizadores/'.$idutilizador.'_media.jpg';
                                        $checkimg5 = '../imagens_utilizadores/'.$idutilizador.'_perfil.jpg';
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
                        //username
                        if(isset($_POST['username']) && $_POST['username'] != '' && $_POST['username'] != NULL && $_POST['username'] != $username_ant && strlen($_POST['username']) >= 4){
                                                       //Faz update do titulo
                                        $username = $_POST['username'];
                                        $username = mysqli_real_escape_string($link_bd, $username);
                                        //verifica se já existe esse username na base de dados
                                        $sqlcheck = "SELECT id FROM utilizadores WHERE username = '$username'";
                                        $querycheck = mysqli_query($link_bd, $sqlcheck);
                                        if($querycheck->num_rows == 0){
                                            $sql = "UPDATE `utilizadores` SET `username` = '".$username."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                                die("ocorreu um erro na mudanca do username");
                                            }else{
                                                $mensagem .= 'Username do utilizador alterado com sucesso. ';
                                                    } 
                                        }else{
                                            $mensagem .= 'O Username que escolheu já existe, escolha outro.';
                                        }
                        }
                        //pnome e unome
                        if(isset($_POST['pnome']) && $_POST['pnome'] != '' && $_POST['pnome'] != NULL && $_POST['pnome'] != $pnome_ant){
                                                       //Faz update do titulo
                                        $pnome = $_POST['pnome'];
                                        $pnome = mysqli_real_escape_string($link_bd, $pnome);
                                        $sql = "UPDATE `utilizadores` SET `pnome` = '".$pnome."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do primeiro nome");
                                        }else{
                                            $mensagem .= 'Primeiro nome do utilizador alterado com sucesso. ';
                                                } 
                        }
                        if(isset($_POST['unome']) && $_POST['unome'] != '' && $_POST['unome'] != NULL && $_POST['unome'] != $unome_ant){
                                                       //Faz update do titulo
                                        $unome = $_POST['unome'];
                                        $unome = mysqli_real_escape_string($link_bd, $unome);
                                        $sql = "UPDATE `utilizadores` SET `unome` = '".$unome."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                        }else{
                                            $mensagem .= 'Último nome do utilizador alterado com sucesso. ';
                                                } 
                        }
                        //email e descricao
                        if(isset($_POST['email']) && $_POST['email'] != '' && $_POST['email'] != NULL && $_POST['email'] != $email_ant){
                                                       //Faz update do titulo
                                        $email = $_POST['email'];
                                        $email = mysqli_real_escape_string($link_bd, $email);
                                        //verifica se já existe esse username na base de dados
                                        $sqlcheck = "SELECT id FROM utilizadores WHERE email = '$email'";
                                        $querycheck = mysqli_query($link_bd, $sqlcheck);
                                        if($querycheck->num_rows == 0){
                                            $sql = "UPDATE `utilizadores` SET `email` = '".$email."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                                die("ocorreu um erro na mudanca do email");
                                            }else{
                                                $mensagem .= 'Email do utilizador alterado com sucesso. ';
                                                    } 
                                        }else{
                                            $mensagem .= 'O Email que escolheu já existe, escolha outro. ';
                                        }
                        }
                        if(isset($_POST['descricao']) && $_POST['descricao'] != $descricao_ant){
                                                       //Faz update do titulo
                                        $descricao = $_POST['descricao'];
                                        $descricao = mysqli_real_escape_string($link_bd, $descricao);
                                        $sql = "UPDATE `utilizadores` SET `descricao` = '".$descricao."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do username");
                                        }else{
                                            $mensagem .= 'Descrição do utilizador alterada com sucesso. ';
                                                } 
                        }
                        //pais e permissao
                        if($_POST['pais'] != $pais_ant){
                                                       //Faz update do titulo
                                        $pais = $_POST['pais'];
                                        $pais = mysqli_real_escape_string($link_bd, $pais);
                                        $sql = "UPDATE `utilizadores` SET `pais` = '".$pais."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do pais");
                                        }else{
                                            $mensagem .= 'País do utilizador alterado com sucesso. ';
                                                } 
                        }
                        if($_POST['permissoes'] != $permissao_ant){
                                                       //Faz update do titulo
                                        $permissoes = $_POST['permissoes'];
                                        $permissoes = mysqli_real_escape_string($link_bd, $permissoes);
                                        $constnull = NULL;
                                        $const1 = 1;
                                        if($permissoes == 'utilizador'){
                                            $sql = "UPDATE `utilizadores` SET `permissao` = '".$constnull."' WHERE `id` = '".$idutilizador."' LIMIT 1";
                                        }else if($permissoes == 'administrador'){
                                            $sql = "UPDATE `utilizadores` SET `permissao` = '".$const1."' WHERE `id` = '".$idutilizador."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca das permissões do utilizador");
                                        }else{
                                            $mensagem .= 'Permissões do utilizador alteradas com sucesso. ';
                                                } 
                        }


                        

if($mensagem != ''){
    $_SESSION['mensagemadmin'] = $mensagem;
}
                            //redireciona para gerirutilizadores.php
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'gerirutilizadores.php';
                    header ("location: http://$host$uri/$extra");



