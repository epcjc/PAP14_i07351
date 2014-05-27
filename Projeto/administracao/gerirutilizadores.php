<?php


include_once '../includes/seguranca.php';

include_once '../WideImage/lib/WideImage.php';

$home = file_get_contents('HTML/mainadmin.html');

            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }


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
//apresenta menus
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    
    if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
        $idpag = $_GET['id'];      
    }else{
        $idpag = 1;
    }
    
    if(isset($_GET['pesquisa'])){
        $pesquisa = mysqli_real_escape_string($link_bd, $_GET['pesquisa']);
    }else{
        $pesquisa = '';
    }
    
    if(isset($_GET['ord']) && $_GET['ord']=='reports'){
        $reports = TRUE;
    }else{
        $reports = FALSE;
    }
    
    
    //calcula numeros dos utilizadores a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das utilizadores
    if($pesquisa != ''){
        $sql = "SELECT count(*) FROM utilizadores WHERE username LIKE '%$pesquisa%'";
    }else{
        $sql = "SELECT count(*) FROM utilizadores";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query sdfsdfsdf ");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalutilizadores = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar utilizadores
    if($pesquisa != ''){
        if($reports == TRUE){
            $sql = "SELECT * FROM utilizadores WHERE username LIKE '%$pesquisa%' ORDER BY nreports DESC LIMIT $ant_primeira, 10";
        }else{
            $sql = "SELECT * FROM utilizadores WHERE username LIKE '%$pesquisa%' ORDER BY datahora DESC LIMIT $ant_primeira, 10";
        }
    }else{
        if($reports == TRUE){
            $sql = "SELECT * FROM utilizadores ORDER BY nreports DESC LIMIT $ant_primeira, 10";
        }else{
            $sql = "SELECT * FROM utilizadores ORDER BY datahora DESC LIMIT $ant_primeira, 10";
        }
    }
    
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_utilizadores = $query->num_rows;
    }else{
        $max_utilizadores = 0;
    }
    $max_pagina = 10;//maximo de utilizadores por pagina
    if($totalutilizadores > 10){
        $numeropaginas = $totalutilizadores / $max_pagina;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR UTILIZADORES</p>-----------------------------------------</h3><br/>';
    $tplconteudo .= '<form action="gerirutilizadores.php" method="GET" enctype="multipart/form-data"><input type="text" name="pesquisa" value="()-pesquisa-()"><input type="submit" value ="Procurar"></form>';
    if($pesquisa != ''){
        $tplconteudo = str_replace('()-pesquisa-()', $pesquisa, $tplconteudo);
        $tplconteudo .= '<br/><a href="gerirutilizadores.php?pesquisa='.$pesquisa.'">Ordenar por data</a>';
    }else{
        $tplconteudo = str_replace('()-pesquisa-()', '', $tplconteudo);
        $tplconteudo .= '<br/><a href="gerirutilizadores.php">Ordenar por data</a>';
    }
    if($pesquisa != ''){
        $tplconteudo .= '<br/><a href="gerirutilizadores.php?pesquisa='.$pesquisa.'&ord=reports">Ordenar por nº de denúncias</a><br/><br/>';
    }else{
        $tplconteudo .= '<br/><a href="gerirutilizadores.php?ord=reports">Ordenar por nº de denúncias</a><br/><br/>';
    }

    $i = $primeira;
    while($i <= $ultima){
        $query->data_seek($i-1);
        $datarow = $query->fetch_array();         
        if($datarow['datahora'] != ''){
            
        
        $datahora = $datarow['datahora'];
        $userid = $datarow['id'];
        $pnome = htmlspecialchars($datarow['pnome']);
        $unome = htmlspecialchars($datarow['unome']);
        $username = htmlspecialchars($datarow['username']);
        $email = htmlspecialchars($datarow['email']);
        $descricao = htmlspecialchars($datarow['descricao']);
        $pais = htmlspecialchars($datarow['pais']);
        $imagem = htmlspecialchars($datarow['imagem']);
        $nvendas = $datarow['nvendas'];
        $ncompras = $datarow['ncompras'];
        $nuploads = $datarow['nuploads'];
        $ncomentarios = $datarow['ncomentarios'];
        $nvotacoes = $datarow['nvotacoes'];
        $nreports = $datarow['nreports'];
        $permissao = $datarow['permissao'];
        $datarow['datahora'] = '';
        
        //Define Permissão
        if($permissao == NULL || $permissao == 0 || $permissao==''){
            $permissao = 'Utilizadores';
        }else if($permissao == 1){
            $permissao = 'Administradores';
        }else if($permissao == 2){
            $permissao = 'Administrador geral';
        }
        
        
        //define imagem_miniatura
        if($imagem == 'imagens_utilizadores/imagem.jpg' || $imagem == '' || $imagem == NULL){
            $imagemfinal = '../imagens_utilizadores/imagem_miniatura.jpg';
        }else{
            if(file_exists('../imagens_utilizadores/'.$userid.'_miniatura.jpg')){
                $imagemfinal = '../imagens_utilizadores/'.$userid.'_miniatura.jpg';
            }else if(file_exists('../'.$imagem)){
                //cria imagem_miniatura para a noticia
                //redimensiona a imagem e guarda-a como [id_noticia]_miniatura.jpg
                        $novaimagem = WideImage::loadFromFile('../'.$imagem);
                   // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(52, 52, 'fill');
                        // Guarda a imagem
                        $imagemfinal = '../imagens_utilizadores/'.$userid.'_miniatura.jpg'; // ex: 5_miniatura, 82_miniatura
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem pequena no disco
            }else{
                $imagemfinal = '../imagens_utilizadores/imagem_miniatura.jpg';
            }
        }
        if($imagemfinal != '../imagens_utilizadores/imagem_miniatura.jpg'){
            $imagemfinal = '<a href="../imagens_utilizadores/'.$userid.'.jpg"><img src="'.$imagemfinal.'"></a>';
        }else{
            $imagemfinal = '<a href="../imagens_utilizadores/imagem.jpg"><img src="'.$imagemfinal.'"></a>';
        }
        
        //----------------
        
        
        //verifica se o administrador logado pode alterar e apagar a pagina
        if($descricao != '' && $descricao != NULL){
            if($_SESSION['administrador_rank'] > 1){
                $tplconteudo .= '<br><p><strong><font size = "+2">| Nome de utilizador:</strong> '.$username.' </font><strong>|<br><br>| Data de registo: </strong>'.$datahora.' <strong>| Utilizador nº:</strong> '.$userid.' <strong>| Categoria:</strong> '.$permissao.' <strong>| <br>| Nome:</strong> '.$pnome.' '.$unome.'<strong> | Email:</strong> '.$email.'<strong> | País:</strong> '.$pais.'<strong> | </strong><br><strong> | Descrição:</strong> '.$descricao.'<strong> | </strong><br><strong>| Nº de compras:</strong> '.$ncompras.'<strong> |  Nº de vendas:</strong> '.$nvendas.'<strong> | Nº de uploads:</strong> '.$nuploads.'<strong> | Nº de votos:</strong> '.$nvotacoes.'<strong> |<br>| Nº de comentarios:</strong> '.$ncomentarios.'<strong> | Nº de vezes que foi denúnciado:</strong> '.$nreports.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| <a href="apagarutilizador.php?id='.$userid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>, <a href="alterarutilizador.php?id='.$userid.'">Alterar</a>, <a href="reportsutilizador.php?id='.$userid.'">Ver denúncias</a></strong> </p><br>';       
            }else{
                $tplconteudo .= '<br><p><strong><font size = "+2">| Nome de utilizador:</strong> '.$username.' </font><strong>|<br><br>| Data de registo: </strong>'.$datahora.' <strong>| Utilizador nº:</strong> '.$userid.' <strong>| Categoria:</strong> '.$permissao.' <strong>| <br>| Nome:</strong> '.$pnome.' '.$unome.'<strong> | Email:</strong> '.$email.'<strong> | País:</strong> '.$pais.'<strong> | </strong><br><strong> | Descrição:</strong> '.$descricao.'<strong> | </strong><br><strong>| Nº de compras:</strong> '.$ncompras.'<strong> |  Nº de vendas:</strong> '.$nvendas.'<strong> | Nº de uploads:</strong> '.$nuploads.'<strong> | Nº de votos:</strong> '.$nvotacoes.'<strong> |<br>| Nº de comentarios:</strong> '.$ncomentarios.'<strong> | Nº de vezes que foi denúnciado:</strong> '.$nreports.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| <a href="reportsutilizador.php?id='.$userid.'">Ver denúncias</a></strong> </p><br>';       
            }
        }else{
            if($_SESSION['administrador_rank'] > 1){
                $tplconteudo .= '<br><p><strong><font size = "+2">| Nome de utilizador:</strong> '.$username.' </font><strong>|<br><br>| Data de registo: </strong>'.$datahora.' <strong>| Utilizador nº:</strong> '.$userid.' <strong>| Categoria:</strong> '.$permissao.' <strong>| <br>| Nome:</strong> '.$pnome.' '.$unome.'<strong> | Email:</strong> '.$email.'<strong> | País:</strong> '.$pais.'<strong> | </strong><br><strong>| Nº de compras:</strong> '.$ncompras.'<strong> |  Nº de vendas:</strong> '.$nvendas.'<strong> | Nº de uploads:</strong> '.$nuploads.'<strong> | Nº de votos:</strong> '.$nvotacoes.'<strong> |<br>| Nº de comentarios:</strong> '.$ncomentarios.'<strong> | Nº de vezes que foi denúnciado:</strong> '.$nreports.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| <a href="apagarutilizador.php?id='.$userid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>, <a href="alterarutilizador.php?id='.$userid.'">Alterar</a>, <a href="reportsutilizador.php?id='.$userid.'">Ver denúncias</a></strong> </p><br>';       
            }else{
                $tplconteudo .= '<br><p><strong><font size = "+2">| Nome de utilizador:</strong> '.$username.' </font><strong>|<br><br>| Data de registo: </strong>'.$datahora.' <strong>| Utilizador nº:</strong> '.$userid.' <strong>| Categoria:</strong> '.$permissao.' <strong>| <br>| Nome:</strong> '.$pnome.' '.$unome.'<strong> | Email:</strong> '.$email.'<strong> | País:</strong> '.$pais.'<strong> | </strong><br><strong>| Nº de compras:</strong> '.$ncompras.'<strong> |  Nº de vendas:</strong> '.$nvendas.'<strong> | Nº de uploads:</strong> '.$nuploads.'<strong> | Nº de votos:</strong> '.$nvotacoes.'<strong> |<br>| Nº de comentarios:</strong> '.$ncomentarios.'<strong> | Nº de vezes que foi denúnciado:</strong> '.$nreports.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| <a href="reportsutilizador.php?id='.$userid.'">Ver denúncias</a></strong> </p><br>';       
            }
        }
      }
        
        $i++;
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalutilizadores.' utilizadores.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="gerirutilizadores.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="gerirutilizadores.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
