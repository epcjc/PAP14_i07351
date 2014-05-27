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
    
    
    //calcula numeros dos uploads a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das uploads
    if($pesquisa != ''){
        $sql = "SELECT count(*) FROM uploads WHERE titulo LIKE '%$pesquisa%'";
    }else{
        $sql = "SELECT count(*) FROM uploads";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query sdfsdfsdf ");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totaluploads = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar utilizadores
    if($pesquisa != ''){
        if($reports == TRUE){
            $sql = "SELECT * FROM uploads WHERE titulo LIKE '%$pesquisa%' ORDER BY nreports DESC LIMIT $ant_primeira, 10";
        }else{
            $sql = "SELECT * FROM uploads WHERE titulo LIKE '%$pesquisa%' ORDER BY datahora DESC LIMIT $ant_primeira, 10";
        }
    }else{
        if($reports == TRUE){
            $sql = "SELECT * FROM uploads ORDER BY nreports DESC LIMIT $ant_primeira, 10";
        }else{
            $sql = "SELECT * FROM uploads ORDER BY datahora DESC LIMIT $ant_primeira, 10";
        }
    }
    
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_uploads = $query->num_rows;
    }else{
        $max_uploads = 0;
    }
    $max_pagina = 10;//maximo de uploads por pagina
    if($totaluploads > 10){
        $numeropaginas = $totaluploads / $max_pagina;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR UPLOADS</p>-----------------------------------------</h3><br/>';
    $tplconteudo .= '<form action="geriruploads.php" method="GET" enctype="multipart/form-data"><input type="text" name="pesquisa" value="()-pesquisa-()"><input type="submit" value ="Procurar"></form>';
    if($pesquisa != ''){
        $tplconteudo = str_replace('()-pesquisa-()', $pesquisa, $tplconteudo);
        $tplconteudo .= '<br/><a href="geriruploads.php?pesquisa='.$pesquisa.'">Ordenar por data</a>';
    }else{
        $tplconteudo = str_replace('()-pesquisa-()', '', $tplconteudo);
        $tplconteudo .= '<br/><a href="geriruploads.php">Ordenar por data</a>';
    }
    if($pesquisa != ''){
        $tplconteudo .= '<br/><a href="geriruploads.php?pesquisa='.$pesquisa.'&ord=reports">Ordenar por nº de denúncias</a><br/><br/>';
    }else{
        $tplconteudo .= '<br/><a href="geriruploads.php?ord=reports">Ordenar por nº de denúncias</a><br/><br/>';
    }

    $imagem = array(1=>'',2=>'',3=>'',4=>'');
    $imagemfinal = array(1=>'',2=>'',3=>'',4=>'');
    $i = 0;
    while($i < 10){
        if($query->num_rows > $i){
            $query->data_seek($i);
            $datarow = $query->fetch_array();  
            $idupl = $datarow['id'];  
            $idusr = $datarow['id_utilizador'];  
            $titulo = htmlspecialchars($datarow['titulo']);  
            $descricao = htmlspecialchars($datarow['descricao']);
            $imagem[1] = htmlspecialchars($datarow['imagem1']);
            $imagem[2] = htmlspecialchars($datarow['imagem2']);
            $imagem[3] = htmlspecialchars($datarow['imagem3']);
            $imagem[4] = htmlspecialchars($datarow['imagem4']);  
            $preco = htmlspecialchars($datarow['preco']);  
            $nlikes = $datarow['nlikes'];
            $ndislikes = $datarow['ndislikes'];
            $ncompras = $datarow['ncompras'];
            $datahora = $datarow['datahora'];
            $ncomentarios = $datarow['ncomentarios'];
            $size = $datarow['size'];
            $categoria = htmlspecialchars($datarow['categoria']);
            $nomeoriginal = htmlspecialchars($datarow['nomeoriginal']);
            $nreports = $datarow['nreports'];
            
            //pesquisa para procurar username deste utilizador
            $sqlc = 'SELECT username FROM utilizadores WHERE id = '.$idusr.' LIMIT 1';
            $queryc = mysqli_query($link_bd, $sqlc);
            if(!$queryc) die("erro ao procurar username do uploader");
            $rc = mysqli_fetch_assoc($queryc);
            $username = htmlspecialchars($rc['username']);
            
            //define imagens_mini
            $i2 = 1;
            while($i2 < 5){
                if($imagem[$i2] == 'upload/imagem.jpg' || $imagem[$i2] == '' || $imagem[$i2] == NULL){
                    $imagemfinal[$i2] = '../upload/imagem_mini.jpg';
                }else{
                    $checkimg = '../'.$imagem[$i2];
                    $checkimg = substr($checkimg, 0, -4);
                    $checkimg = $checkimg.'_mini.jpg';
                    if(file_exists($checkimg)){
                        $imagemfinal[$i2] = $checkimg;
                    }else if(file_exists('../'.$imagem[$i2])){
                        //cria imagem_miniatura para a noticia
                        //redimensiona a imagem e guarda-a como [id_noticia]_miniatura.jpg
                                $novaimagem = WideImage::loadFromFile('../'.$imagem[$i2]);
                           // Redimensiona a imagem
                                $novaimagem = $novaimagem->resize(52, 52, 'fill');
                                // Guarda a imagem
                                $imagemfinal[$i2] = $checkimg; // ex: 5_miniatura, 82_miniatura
                                $novaimagem->saveToFile($imagemfinal[$i2], 40); // Coloca a imagem pequena no disco
                    }else{
                        $imagemfinal[$i2] = '../upload/imagem_mini.jpg';
                    }
                }
                if($imagemfinal[$i2] != '../upload/imagem_mini.jpg'){
                    $imagemfinal[$i2] = '<a href="../'.$imagem[$i2].'"><img src="'.$imagemfinal[$i2].'"></a>';
                }else{
                    $imagemfinal[$i2] = '<a href="../upload/imagem.jpg"><img src="'.$imagemfinal[$i2].'"></a>';
                }
                $i2++;
            }
            
                    //verifica se o administrador logado pode alterar e apagar a pagina
            
            if($_SESSION['administrador_rank'] > 1){
                $tplconteudo .= '<br><p><strong>| Upload nº:</strong> '.$idupl.' <strong>| Data: </strong>'.$datahora.' <strong>| Nome original:</strong> '.$nomeoriginal.' <strong>| <br>| Categoria:</strong> '.$categoria.'<strong> | Preço:</strong> '.$preco.'€<strong> | Votação:</strong> '.$nlikes.' gostam e '.$ndislikes.' não gostam<strong> | Compras:</strong> '.$ncompras.'<strong> | </strong><br><strong> | Título:</strong> '.$titulo.'<strong> | </strong><br><strong> | Descrição:</strong> '.$descricao.'<strong> | </strong><br><strong> |  Tamanho:</strong> '.$size.'B <strong>| Nº de comentários:</strong> '.$ncomentarios.'<strong> | Nº de denúncias:</strong> '.$nreports.' | Utilizador:</strong> '.$username.' |<br>  '.$imagemfinal[1].' '.$imagemfinal[2].' '.$imagemfinal[3].' '.$imagemfinal[4].' <br><strong> <a href="apagarupload.php?id='.$idupl.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>, <a href="alterarupload.php?id='.$idupl.'">Alterar</a>, <a href="reportsupload.php?id='.$idupl.'">Ver denúncias</a></strong> </p><br>';       
            }else{
                $tplconteudo .= '<br><p><strong>| Upload nº:</strong> '.$idupl.' <strong>| Data: </strong>'.$datahora.' <strong>| Nome original:</strong> '.$nomeoriginal.' <strong>| <br>| Categoria:</strong> '.$categoria.'<strong> | Preço:</strong> '.$preco.'€<strong> | Votação:</strong> '.$nlikes.' gostam e '.$ndislikes.' não gostam<strong> | Compras:</strong> '.$ncompras.'<strong> | </strong><br><strong> | Título:</strong> '.$titulo.'<strong> | </strong><br><strong> | Descrição:</strong> '.$descricao.'<strong> | </strong><br><strong> |  Tamanho:</strong> '.$size.'B <strong>| Nº de comentários:</strong> '.$ncomentarios.'<strong> | Nº de denúncias:</strong> '.$nreports.' | Utilizador:</strong> '.$username.' |<br>  '.$imagemfinal[1].' '.$imagemfinal[2].' '.$imagemfinal[3].' '.$imagemfinal[4].' <br><strong> <a href="reportsupload.php?id='.$idupl.'">Ver denúncias</a></strong> </p><br>';       
            }
       
            
            
            
        }else{
            $i = 10;
        }
        $i++;
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totaluploads.' uploads.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="geriruploads.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="geriruploads.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
