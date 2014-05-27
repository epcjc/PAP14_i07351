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
if($_SESSION['administrador_rank'] != 2){
    //envia-o de volta para o admin.php
        $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode acessar esta página";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'admin.php';
        header ("location: http://$host$uri/$extra");
}
//apresenta menus
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    
    if(isset($_GET['id']) && is_numeric($_GET['id'] && ($_GET['id'] > 0))){
        $idpag = $_GET['id'];      
    }else{
        $idpag = 1;
    }
    
    //calcula numeros de registos a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total de registos
    $sql = "SELECT count(*) FROM galeria";
     $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalregistos = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar os registos
    $sql = "SELECT * FROM galeria ORDER BY datahora DESC LIMIT $ant_primeira, 10";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_registos = $query->num_rows;
    }else{
        $max_registos = 0;
    }
    $max_pagina = 10;//maximo de registos por pagina
    if($totalregistos > 10){
        $numeropaginas = $totalregistos / $max_pagina;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = $primeira;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR GALERIA</p>-----------------------------------------</h3><br/>';
    $tplconteudo .= '<a href="novagaleria.php">Inserir nova imagem</a><br/>Nota: Apenas as ultimas 10 imagens adicionadas serão mostradas na galeria<br/><br/>';
    while($i <= $ultima){
        $query->data_seek($i-1);
        $datarow = $query->fetch_array();         
        if($datarow['datahora'] != ''){
            
        
        $datahora = $datarow['datahora'];
        $registoid = $datarow['id'];
        $descricao = $datarow['descricao'];
        $descricao = htmlspecialchars($descricao);
        $imagem = $datarow['imagem'];
        $datarow['datahora'] = '';
        //define imagem_miniatura

            if(file_exists('../galeria/'.$registoid.'_miniatura.jpg')){
                $imagemfinal = '../galeria/'.$registoid.'_miniatura.jpg';
            }else{
                //cria imagem_miniatura para a imagem
                //redimensiona a imagem e guarda-a como [id_registo]_miniatura.jpg
                        $novaimagem = WideImage::loadFromFile('../'.$imagem);
                   // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(52, 35, 'fill');
                        // Guarda a imagem
                        $imagemfinal = '../galeria/'.$registoid.'_miniatura.jpg'; // ex: 5_miniatura, 82_miniatura
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem pequena no disco
            }
        
        $imagemfinal = '<a href="../galeria/'.$registoid.'.jpg"><img src="'.$imagemfinal.'"></a>';
        //----------------
      


            $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Imagem nº:</strong> '.$registoid.' <strong>| <br>| Descrição:</strong> '.$descricao.'<strong> | </strong><br>  '.$imagemfinal.' <strong> | <a href="apagargaleria.php?id='.$registoid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>, <a href="alterargaleria.php?id='.$registoid.'">Alterar</a></strong> </p><br>';       

        }
        
        $i++;
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalregistos.' imagens na galeria.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="gerirgaleria.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="gerirgaleria.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>


