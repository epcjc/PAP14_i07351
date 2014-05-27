
<?php
include_once '../includes/seguranca.php';
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
    $idpag = $_GET['id'];
}else{
    //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A página que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirnoticias.php';
        header ("location: http://$host$uri/$extra");  
    }

    
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
    
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
    


    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/alterarpagina.tpl');


//insere id da noticia e imagem

    $tplconteudo = str_replace('()-idpag-()', $idpag, $tplconteudo);
    $sql = "SELECT nome, titulo, conteudo FROM paginas WHERE id=$idpag";
    
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("ocorreu um erro na query em alterarpagina.php");
    }
    if($query->num_rows == 0){
            //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A página que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirpaginas.php';
        header ("location: http://$host$uri/$extra");  
    }
    
        while($result = mysqli_fetch_assoc($query)){
            $nome = $result['nome'];
            $titulo = $result['titulo'];
            $conteudo = $result['conteudo'];
        }
        
        $tplconteudo = str_replace('()-nomepag-()', $nome,$tplconteudo);
        $tplconteudo = str_replace('()-snome-()', $nome,$tplconteudo);
        $tplconteudo = str_replace('()-stitulo-()', $titulo,$tplconteudo);
        $tplconteudo = str_replace('()-sconteudo-()', $conteudo,$tplconteudo);
        //if($imagem == 'imagens_noticias/imagem.jpg' || $imagem == NULL || $imagem == ''){
            
       // }
    

//apresenta menus e conteudo(form de alterAR noticia)
 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
