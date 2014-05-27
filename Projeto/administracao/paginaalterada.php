<?php
include_once '../includes/seguranca.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idpag = $_GET['id'];
}else{
                //envia-o de volta para o gerirpaginas.php
        $_SESSION['mensagemadmin'] = "A página que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirpaginas.php';
        header ("location: http://$host$uri/$extra");  
}
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
                      
                     //Verifica se noticia existe na bd
                        $sql = "SELECT nome, titulo, conteudo FROM paginas WHERE id=$idpag";
    
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query){
                            die("ocorreu um erro na query do nome em alterarpagina.php");
                        }
                        if($query->num_rows == 0){
                                //envia-o de volta para o gerirpaginas.php
                            $_SESSION['mensagemadmin'] = "A página que tentou alterar não existe.";
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'gerirpaginas.php';
                            header ("location: http://$host$uri/$extra");  
                        }
                        $r = mysqli_fetch_assoc($query);
                        $nome_ant = $r['nome'];
                        $titulo_ant = $r['titulo'];
                        $conteudo_ant = $r['conteudo'];
                        
                                              
                                                //Verifica se o administrador logado pode alterar esta pagina
    $sql = "SELECT id_utilizador FROM paginas WHERE id=$idpag";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("morreu em aaaaaaaaaaaaa alterarpagina");
    }
    while($r = mysqli_fetch_assoc($query)){
        $iduser = $r['id_utilizador'];
    }
    if($iduser != $_SESSION['administrador_id'] && $_SESSION['administrador_rank'] < 2){
        $_SESSION['mensagemadmin'] = "Não pode alterar esta página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
    }
        
                      //--------------------------------------------------------
                        
                      //altera dados
                     
                        $mensagem = '';

                        
                        
                       if(isset($_POST['nome']) && $_POST['nome'] != '' && $_POST['nome'] != NULL && $_POST['nome'] != $nome_ant){
                                                       //Faz update do titulo
                                        $nome = $_POST['nome'];
                                        $nome = mysqli_real_escape_string($link_bd, $nome);
                                        $sql = "UPDATE `paginas` SET `nome` = '".$nome."' WHERE `id` = '".$idpag."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do nome");
                                        }else{
                                            $mensagem .= 'Nome da página alterado com sucesso. ';
                                                } 
                        }
                        
                        if(isset($_POST['titulo']) && $_POST['titulo'] != '' && $_POST['titulo'] != NULL && $_POST['titulo'] != $titulo_ant){
                                                       //Faz update do titulo
                                        $titulo = $_POST['titulo'];
                                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                                        $sql = "UPDATE `paginas` SET `titulo` = '".$titulo."' WHERE `id` = '".$idpag."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do titulo");
                                        }else{
                                            $mensagem .= 'Título da Página alterado com sucesso. ';
                                                } 
                        }
                        if(isset($_POST['conteudo']) && $_POST['conteudo'] != '' && $_POST['conteudo'] != NULL && $_POST['conteudo'] != $conteudo_ant){
                                                       //Faz update do descricao
                                        $conteudo = $_POST['conteudo'];
                                        $conteudo = mysqli_real_escape_string($link_bd, $conteudo);
                                        $sql = "UPDATE `paginas` SET `conteudo` = '".$conteudo."' WHERE `id` = '".$idpag."' LIMIT 1";
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
                                            die("ocorreu um erro na mudanca do conteudo da noticia");
                                        }else{
                                            $mensagem .= 'Conteúdo da página alterado com sucesso. ';
                                                } 
                        }

                        

if($mensagem != ''){
    $_SESSION['mensagemadmin'] = $mensagem;
}
                            //redireciona para gerirpaginas.php
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'gerirpaginas.php';
                    header ("location: http://$host$uri/$extra");

