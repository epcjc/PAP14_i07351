
<?php
include_once '../includes/seguranca.php';
include_once '../includes/funcoes.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$home = file_get_contents('HTML/mainadmin.html');

//verifica se há mensagem a apresentar
$msg = '';
if(isset($_SESSION['mensagemadmin'])){
    if($_SESSION['mensagemadmin'] != ''){
        //apresenta mensagem 
        $msg = $_SESSION['mensagemadmin'];
        $home = str_replace('()-mensagemadmin-()', $msg, $home);
        $_SESSION['mensagemadmin'] = '';
    }else{
        //não apresenta nenhuma mensagem
        $home = str_replace('()-mensagemadmin-()', '', $home);
        
    }
}else{
    //não apresenta nenhuma mensagem
     $home = str_replace('()-mensagemadmin-()', '', $home);
}

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
    $idmsg = $_GET['id'];
}else{
    //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A mensagem que tentou acessar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirmensagens.php';
        header ("location: http://$host$uri/$extra");  
    }

    
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
    
                          //Verifica se o esta msg ja foi respondida
    $sql = "SELECT respondida, titulo, conteudo, id_utilizador FROM mensagens_administracao WHERE id=$idmsg LIMIT 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("morreu em aaaaaaaaaaaaa acadcalkdcadlc 7");
    }
    if($query->num_rows == 0){
        $_SESSION['mensagemadmin'] = "A mensagem que tentou acessar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirmensagens.php';
        header ("location: http://$host$uri/$extra"); 
    }
    $r = mysqli_fetch_assoc($query);
    
    if($r['respondida'] == 1 && $_SESSION['administrador_rank'] < 2){
        $_SESSION['mensagemadmin'] = "Essa mensagem já foi respondida";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirmensagens.php';
        header ("location: http://$host$uri/$extra");
    }
    
    
    $titulo = htmlspecialchars($r['titulo']);
    $conteudo = htmlspecialchars($r['conteudo']);
    $id_utilizador = htmlspecialchars($r['id_utilizador']);
    $sql = 'SELECT username FROM utilizadores WHERE id = '.$id_utilizador.' LIMIT 1';
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("morreu em aaaaaaaaaaaaa hdfhdf6745lc 2");
    }
    if($query->num_rows == 0){
        $username = 'Utilizador removido';//f
    }else{
        $r = mysqli_fetch_assoc($query);
        $username = htmlspecialchars($r['username']);
    }
    //Verifica se foi adicionada resposta, se sim, guarda resposta na bd mensagens_administracao, envia uma mensagem para o utilizador, e reencaminha para gerirmensagens
    //-----------------------------------------------------------
    //-----------------------------------------------------------
   if(isset($_POST['resposta']) && $_POST['resposta'] != '' && $_POST['resposta'] != NULL){
       $resposta = mysqli_real_escape_string($link_bd, $_POST['resposta']);
       $resposta_msg = '<font size="+1">Resposta enviada por: '.htmlspecialchars($_SESSION['administrador_username']).'</font><br><br><br><h5><font size="+1">Mensagem:</font><br> <font color="#787878">'.$titulo.'</font></h5>'.$conteudo.'<br><br><h5><font size="+1">Resposta:</font></h5> '.$resposta;
       $resposta_bd = $resposta.' | Resposta enviada por: '.htmlspecialchars($_SESSION['administrador_username']);
       if($username != 'Utilizador removido'){
           $titulomsg = 'Administração Digiart - Resposta';
           mensagemservidor($id_utilizador, $titulomsg, $resposta_msg);
       }
       //insere resposta na tabela mensagens_administracao
       $sql = "UPDATE `mensagens_administracao` SET `resposta` = '".$resposta_bd."', `respondida` = 1 WHERE `id` = '".$idmsg."' LIMIT 1";
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
            die("ocorreu um erro na insercao da resposta no registo");
        }else{
            $_SESSION['mensagemadmin'] .= 'A resposta foi enviada com sucesso. ';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'gerirmensagens.php';
            header ("location: http://$host$uri/$extra");
                } 
   }
    //  //-----------------------------------------------------------
    //-----------------------------------------------------------


    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/respondermensagem.tpl');


//insere 

    $tplconteudo = str_replace('()-idmsg-()', $idmsg, $tplconteudo);
    $tplconteudo = str_replace('()-titulo-()', $titulo,$tplconteudo);
    $tplconteudo = str_replace('()-conteudo-()', $conteudo,$tplconteudo);
    $tplconteudo = str_replace('()-username-()', $username,$tplconteudo);

    

//apresenta menus e conteudo(form)
 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
