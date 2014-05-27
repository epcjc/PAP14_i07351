<?php
include_once '../includes/seguranca.php';
include_once '../WideImage/lib/WideImage.php';

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
//Apresenta form de login se nao estiver logado
    $home = str_replace('()-menuadmin-()', '', $home);
    if($_SESSION['a_tentativaslogin'] >= 3){
        $tpl_conteudo = file_get_contents('TPL/adminlogin_seguro.tpl');
    }else{
        $tpl_conteudo = file_get_contents('TPL/adminlogin.tpl');
    }
    $home = str_replace('()-conteudo-()', $tpl_conteudo, $home);
}else{
//apresenta menus e conteudo
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/admin.tpl'); 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    //-------------------------
    //DEFINICAO DAS ESTATISTICAS
    //-------------------------
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
    $mes = (int)date('m');
    $ultimomes = (int)date('m', strtotime('-1 month'));
    
    if($ultimomes == 12){
        $ano = (int)date("Y");
        $ano_ultimomes = $ano - 1;
        $anoanterior = $ano - 1;
    }else{
        $ano = (int)date("Y");
        $ano_ultimomes = $ano;
        $anoanterior = $ano - 1;
    }
    
    //utilizadores
    //----------------------------------
    $sql = 'SELECT count(*) FROM utilizadores';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro jashd123kjfa41");
    $r = mysqli_fetch_assoc($query);
    $totalutilizadores = $r['count(*)'];
    
    if($mes < 10){
        $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$ano-0$mes%'";
    }else{
        $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$ano-$mes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro dfgr653j");
    $r = mysqli_fetch_assoc($query);
    $totalutilizadoresmes = $r['count(*)'];    
    
    if($ultimomes < 10){
        $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$ano_ultimomes-0$ultimomes%'";
    }else{
        $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$ano_ultimomes-$ultimomes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalutilizadoresmes_p = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$ano%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalutilizadoresano = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM utilizadores WHERE datahora LIKE '$anoanterior%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalutilizadoresano_p = $r['count(*)'];
    
    $tplconteudo = str_replace('()-totalutilizadores-()',$totalutilizadores,$tplconteudo);
    $tplconteudo = str_replace('()-totalutilizadoresmes-()',$totalutilizadoresmes,$tplconteudo);
    $tplconteudo = str_replace('()-totalutilizadoresmes_p-()',$totalutilizadoresmes_p,$tplconteudo);
    $tplconteudo = str_replace('()-totalutilizadoresano-()',$totalutilizadoresano,$tplconteudo);
    $tplconteudo = str_replace('()-totalutilizadoresano_p-()',$totalutilizadoresano_p,$tplconteudo);
    //-------------------------
    //
    //uploads
    //----------------------------------
    $sql = 'SELECT count(*) FROM uploads';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro jashd123kjfa41");
    $r = mysqli_fetch_assoc($query);
    $totaluploads = $r['count(*)'];
    
    if($mes < 10){
        $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$ano-0$mes%'";
    }else{
        $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$ano-$mes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro dfgr653j");
    $r = mysqli_fetch_assoc($query);
    $totaluploadsmes = $r['count(*)'];    
    
    if($ultimomes < 10){
        $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$ano_ultimomes-0$ultimomes%'";
    }else{
        $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$ano_ultimomes-$ultimomes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totaluploadsmes_p = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$ano%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totaluploadsano = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM uploads WHERE datahora LIKE '$anoanterior%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totaluploadsano_p = $r['count(*)'];
    
    $tplconteudo = str_replace('()-totaluploads-()',$totaluploads,$tplconteudo);
    $tplconteudo = str_replace('()-totaluploadsmes-()',$totaluploadsmes,$tplconteudo);
    $tplconteudo = str_replace('()-totaluploadsmes_p-()',$totaluploadsmes_p,$tplconteudo);
    $tplconteudo = str_replace('()-totaluploadsano-()',$totaluploadsano,$tplconteudo);
    $tplconteudo = str_replace('()-totaluploadsano_p-()',$totaluploadsano_p,$tplconteudo);
    //-------------------------
    //
    //noticias
    //----------------------------------
    $sql = 'SELECT count(*) FROM noticias';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro jashd123kjfa41");
    $r = mysqli_fetch_assoc($query);
    $totalnoticias = $r['count(*)'];
    
    if($mes < 10){
        $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$ano-0$mes%'";
    }else{
        $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$ano-$mes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro dfgr653j");
    $r = mysqli_fetch_assoc($query);
    $totalnoticiasmes = $r['count(*)'];    
    
    if($ultimomes < 10){
        $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$ano_ultimomes-0$ultimomes%'";
    }else{
        $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$ano_ultimomes-$ultimomes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalnoticiasmes_p = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$ano%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalnoticiasano = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM noticias WHERE datahora LIKE '$anoanterior%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalnoticiasano_p = $r['count(*)'];
    
    $tplconteudo = str_replace('()-totalnoticias-()',$totalnoticias,$tplconteudo);
    $tplconteudo = str_replace('()-totalnoticiasmes-()',$totalnoticiasmes,$tplconteudo);
    $tplconteudo = str_replace('()-totalnoticiasmes_p-()',$totalnoticiasmes_p,$tplconteudo);
    $tplconteudo = str_replace('()-totalnoticiasano-()',$totalnoticiasano,$tplconteudo);
    $tplconteudo = str_replace('()-totalnoticiasano_p-()',$totalnoticiasano_p,$tplconteudo);
    //-------------------------
    //
    //paginas
    //----------------------------------
    $sql = 'SELECT count(*) FROM paginas';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro jashd123kjfa41");
    $r = mysqli_fetch_assoc($query);
    $totalpaginas = $r['count(*)'];
    
    if($mes < 10){
        $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$ano-0$mes%'";
    }else{
        $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$ano-$mes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro dfgr653j");
    $r = mysqli_fetch_assoc($query);
    $totalpaginasmes = $r['count(*)'];    
    
    if($ultimomes < 10){
        $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$ano_ultimomes-0$ultimomes%'";
    }else{
        $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$ano_ultimomes-$ultimomes%'";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalpaginasmes_p = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$ano%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalpaginasano = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM paginas WHERE datahora LIKE '$anoanterior%'";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalpaginasano_p = $r['count(*)'];
    
    $tplconteudo = str_replace('()-totalpaginas-()',$totalpaginas,$tplconteudo);
    $tplconteudo = str_replace('()-totalpaginasmes-()',$totalpaginasmes,$tplconteudo);
    $tplconteudo = str_replace('()-totalpaginasmes_p-()',$totalpaginasmes_p,$tplconteudo);
    $tplconteudo = str_replace('()-totalpaginasano-()',$totalpaginasano,$tplconteudo);
    $tplconteudo = str_replace('()-totalpaginasano_p-()',$totalpaginasano_p,$tplconteudo);
    //-------------------------
    //
    //compras
    //----------------------------------
    $sql = 'SELECT count(*) FROM compras WHERE confirmacaoC = 1 AND confirmacaoV = 1';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro jashd123kjfa41");
    $r = mysqli_fetch_assoc($query);
    $totalcompras = $r['count(*)'];
    
    if($mes < 10){
        $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$ano-0$mes%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    }else{
        $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$ano-$mes%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro dfgr653j");
    $r = mysqli_fetch_assoc($query);
    $totalcomprasmes = $r['count(*)'];    
    
    if($ultimomes < 10){
        $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$ano_ultimomes-0$ultimomes%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    }else{
        $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$ano_ultimomes-$ultimomes%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalcomprasmes_p = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$ano%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalcomprasano = $r['count(*)'];
    
    $sql = "SELECT count(*) FROM compras WHERE datahora LIKE '$anoanterior%' AND confirmacaoC = 1 AND confirmacaoV = 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("erro nhrt4figfs");
    $r = mysqli_fetch_assoc($query);
    $totalcomprasano_p = $r['count(*)'];
    
    $tplconteudo = str_replace('()-totalcompras-()',$totalcompras,$tplconteudo);
    $tplconteudo = str_replace('()-totalcomprasmes-()',$totalcomprasmes,$tplconteudo);
    $tplconteudo = str_replace('()-totalcomprasmes_p-()',$totalcomprasmes_p,$tplconteudo);
    $tplconteudo = str_replace('()-totalcomprasano-()',$totalcomprasano,$tplconteudo);
    $tplconteudo = str_replace('()-totalcomprasano_p-()',$totalcomprasano_p,$tplconteudo);
    //-------------------------
    //acaba DEFINICAO DAS ESTATISTICAS
    //-------------------------
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);
} 

print $home;