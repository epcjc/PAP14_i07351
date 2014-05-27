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
    
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $idpag = $_GET['id'];      
    }else{
        $idpag = 1;
    }
    
    //calcula numeros das noticias a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das noticias
    $sql = "SELECT count(*) FROM noticias";
     $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalnoticias = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar as noticias
    $sql = "SELECT * FROM noticias ORDER BY datahora DESC LIMIT $ant_primeira, 10";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_noticias = $query->num_rows;
    }else{
        $max_noticias = 0;
    }
    $max_pagina = 10;//maximo de noticias por pagina
    if($totalnoticias > 10){
        $numeropaginas = $totalnoticias / $max_pagina;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = $primeira;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR NOTÍCIAS</p>-----------------------------------------</h3><br/>';
    $tplconteudo .= '<a href="novanoticia.php">Inserir nova notícia</a><br/><br/>';
    while($i <= $ultima){
        $query->data_seek($i-1);
        $datarow = $query->fetch_array();         
        if($datarow['datahora'] != ''){
            
        
        $datahora = $datarow['datahora'];
        $userid = $datarow['id_utilizador'];
        $noticiaid = $datarow['id'];
        $titulo = $datarow['titulo'];
        $titulo = htmlspecialchars($titulo);
        $conteudo = $datarow['conteudo'];
        $conteudo = htmlspecialchars($conteudo);
        $imagem = $datarow['imagem'];
        $datarow['datahora'] = '';
        //define imagem_miniatura
        if($imagem == 'imagens_noticias/imagem.jpg' || $imagem == '' || $imagem == NULL){
            $imagemfinal = '../imagens_noticias/imagem_miniatura.jpg';
        }else{
            if(file_exists('../imagens_noticias/'.$noticiaid.'_miniatura.jpg')){
                $imagemfinal = '../imagens_noticias/'.$noticiaid.'_miniatura.jpg';
            }else if(file_exists('../'.$imagem)){
                //cria imagem_miniatura para a noticia
                //redimensiona a imagem e guarda-a como [id_noticia]_miniatura.jpg
                        $novaimagem = WideImage::loadFromFile('../'.$imagem);
                   // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(52, 35, 'fill');
                        // Guarda a imagem
                        $imagemfinal = '../imagens_noticias/'.$noticiaid.'_miniatura.jpg'; // ex: 5_miniatura, 82_miniatura
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem pequena no disco
            }else{
                $imagemfinal = '../imagens_noticias/imagem_miniatura.jpg';
            }
        }
        if($imagemfinal != '../imagens_noticias/imagem_miniatura.jpg'){
            $imagemfinal = '<a href="../imagens_noticias/'.$noticiaid.'.jpg"><img src="'.$imagemfinal.'"></a>';
        }else{
            $imagemfinal = '<a href="../imagens_noticias/imagem.jpg"><img src="'.$imagemfinal.'"></a>';
        }
        
        //----------------
        $ncomentarios = $datarow['ncomentarios'];
        //Faz pesquisa para saber o nome do administrador
        $sql2 = "SELECT username FROM utilizadores WHERE id = $userid LIMIT 1";
        $query2 = mysqli_query($link_bd, $sql2);
        if(!$query2){
            die("houve um erro com a query");
        }else if ($query2->num_rows > 0){
            while ($result = mysqli_fetch_assoc($query2)){
                $username = $result['username'];
            } 
        }else{
            $username = '';
        }
        //verifica se o administrador logado pode alterar e apagar a pagina
        if($_SESSION['administrador_username'] == $username || $_SESSION['administrador_rank'] > 1){
            $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Noticia nº:</strong> '.$noticiaid.' <strong>| Administrador:</strong> '.$username.' <strong>| <br>| Título:</strong> '.$titulo.' <strong>| <br>| Conteúdo:</strong> '.$conteudo.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| Nº de comentários:</strong> '.$ncomentarios.'<strong> | <a href="apagarnoticia.php?id='.$noticiaid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>, <a href="alterarnoticia.php?id='.$noticiaid.'">Alterar</a></strong> </p><br>';       
        }else{
            $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Noticia nº:</strong> '.$noticiaid.' <strong>| Administrador:</strong> '.$username.' <strong>| <br>| Título:</strong> '.$titulo.' <strong>| <br>| Conteúdo:</strong> '.$conteudo.'<strong> | </strong><br>  '.$imagemfinal.' <strong>| Nº de comentários:</strong> '.$ncomentarios.'<strong> | </strong> </p><br>';
        }
        }
        
        $i++;
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalnoticias.' notícias.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="gerirnoticias.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="gerirnoticias.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
