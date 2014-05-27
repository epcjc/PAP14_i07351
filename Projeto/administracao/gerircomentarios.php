<?php


include_once '../includes/seguranca.php';


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
    
    $ver = 'n';//n = noticia / u = upload
    if(isset($_GET['ver']) && $_GET['ver'] == 'u'){
        $ver = 'u';
    }
    
    $ord = 'd';
    $ordq = 'datahora DESC';
    if(isset($_GET['ord']) && $_GET['ord'] == 'r'){
        $ord = 'r';
        $ordq = 'nreports DESC';
    }
    //-------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------    
    //se houver parametro apagar, apaga e reenvia-o para a msma pagina sem parametro
    //-------------------------------------------------------------------------------
    if(isset($_GET['apagar'])){
        if(is_numeric($_GET['apagar']) && $_GET['apagar'] > 0){
            //verifica se existe e se tem permissao para apagar
            $idapagar = $_GET['apagar'];
            if($ver == 'n'){
                $sql = 'SELECT id_utilizador FROM comentarios_noticias WHERE id = '.$idapagar.' LIMIT 1';
            }else{
                $sql = 'SELECT id_utilizador FROM comentarios_uploads WHERE id = '.$idapagar.' LIMIT 1';
            }
            $query = mysqli_query($link_bd, $sql);
            if(!$query) die(" nao deu na parte kadjs do apsd 4");
            if($query->num_rows == 0){
                $_SESSION['mensagemadmin'] = "Houve um parametro inválido inserido ao apagar o comentário. ";
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord;
                header ("location: http://$host$uri/$extra");
            }
            $r = mysqli_fetch_assoc($query);
            if($r['id_utilizador'] == $_SESSION['administrador_id'] || $_SESSION['administrador_rank'] >= 2){
                //apaga comentario e reenvia de volta
                if($ver == 'n'){
                    $sql = "DELETE FROM comentarios_noticias WHERE id=$idapagar";
                }else{
                    $sql = "DELETE FROM comentarios_uploads WHERE id=$idapagar";
                }
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
                    die("ocorreu um erro ao apagar o comentario");
                }
                
                $_SESSION['mensagemadmin'] = "O comentário foi apagado com sucesso. ";
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord;
                header ("location: http://$host$uri/$extra");
            }else{
                $_SESSION['mensagemadmin'] = "Não tem permissão para apagar esse comentário. ";
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord;
                header ("location: http://$host$uri/$extra");
            }
 
        }else{
            $_SESSION['mensagemadmin'] = "Houve um parametro inválido inserido ao apagar o comentário. ";
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord;
            header ("location: http://$host$uri/$extra");
        }
    }
    //-------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------
    
    //calcula numeros das paginas a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das noticias
    if($ver == 'n'){
        $sql = "SELECT count(*) FROM comentarios_noticias";
    }else{
        $sql = "SELECT count(*) FROM comentarios_uploads";
    }
     $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalcomentarios = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar as noticias
    if($ver == 'n'){
        $sql = "SELECT * FROM comentarios_noticias ORDER BY $ordq LIMIT $ant_primeira, 10";
    }else{
        $sql = "SELECT * FROM comentarios_uploads ORDER BY $ordq LIMIT $ant_primeira, 10";
    }
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_comentarios = $query->num_rows;
    }else{
        $max_comentarios = 0;
    }
    $max_pag = 10;//maximo de noticias por pagina
    if($totalcomentarios > 10){
        $numeropaginas = $totalcomentarios / $max_pag;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = 1;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR COMENTÁRIOS</p>-----------------------------------------</h3><br/>';
    if($ver == 'n'){
        $tplconteudo .= '<strong>A ver comentários das notícias </strong><br/>(<a href="gerircomentarios.php?ver=u">Ver comentários dos uploads</a>)<br/><br/>';
    }else{
        $tplconteudo .= '<strong>A ver comentários dos uploads </strong><br/>(<a href="gerircomentarios.php">Ver comentários das notícias</a>)<br/><br/>';
    }
    if($ord == 'd'){
        $tplconteudo .= '<strong>Comentários ordenados por data</strong>';
        $tplconteudo .= '<br/>(<a href="gerircomentarios.php??ver='.$ver.'&ord=r">Ordenar por nº de denúncias</a>)<br/><br/>';
    }else{
        $tplconteudo .= '<strong>Comentários ordenados por nº de denúncias</strong>';
        $tplconteudo .= '<br/>(<a href="gerircomentarios.php?ver='.$ver.'">Ordenar por data</a>)<br/><br/>';
    }
    while($i <= $max_pag){
        $query->data_seek($i-1);
        $datarow = $query->fetch_array();         
        if($query->num_rows >= $i){
            
            $idcomentario = $datarow['id'];
            $datahora = $datarow['datahora'];
            $userid = $datarow['id_utilizador'];
            $conteudo = $datarow['conteudo'];
            $conteudo = htmlspecialchars($conteudo);
            if($ver == 'n'){
                $idforeign = $datarow['id_noticia'];
            }else{
                $idforeign = $datarow['id_upload'];
            }
            $nreports = $datarow['nreports'];
            

            //Faz pesquisa para saber o nome do utilizador
            $sql2 = "SELECT username FROM utilizadores WHERE id = $userid LIMIT 1";
            $query2 = mysqli_query($link_bd, $sql2);
            if(!$query2){
                die("houve um erro com a query");
            }else if ($query2->num_rows > 0){
                while ($result = mysqli_fetch_assoc($query2)){
                    $username = htmlspecialchars($result['username']);
                } 
            }else{
                $username = 'Utilizador Removido';
            }
            //verifica se o administrador logado pode alterar e apagar a pagina
            if($_SESSION['administrador_username'] == $username || $_SESSION['administrador_rank'] > 1){
                if($ver == 'n'){
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Comentário nº: </strong>'.$idcomentario.' <strong>| Utilizador:</strong> '.$username.' <strong>| <br>| Comentário: </strong>'.$conteudo.' <strong>|<br>| Notícia nº: </strong>'.$idforeign.'<strong> | Nº de denúncias: </strong>'.$nreports.' <strong>| <br> <a href="gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord.'&apagar='.$idcomentario.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a> </strong></p><br>';
                }else{
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Comentário nº: </strong>'.$idcomentario.' <strong>| Utilizador:</strong> '.$username.' <strong>| <br>| Comentário: </strong>'.$conteudo.' <strong>|<br>| Upload nº: </strong>'.$idforeign.'<strong> | Nº de denúncias: </strong>'.$nreports.' <strong>| <br> <a href="gerircomentarios.php?id='.$idpag.'&ver='.$ver.'&ord='.$ord.'&apagar='.$idcomentario.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a> </strong></p><br>';
                }
                 
            }else{
            
                if($ver == 'n'){
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Comentário nº: </strong>'.$idcomentario.' <strong>| Utilizador:</strong> '.$username.' <strong>| <br>| Comentário: </strong>'.$conteudo.' <strong>|<br>| Notícia nº: </strong>'.$idforeign.'<strong> | Nº de denúncias: </strong>'.$nreports.' <strong>| </strong></p><br>';
                }else{
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Comentário nº: </strong>'.$idcomentario.' <strong>| Utilizador:</strong> '.$username.' <strong>| <br>| Comentário: </strong>'.$conteudo.' <strong>|<br>| Upload nº: </strong>'.$idforeign.'<strong> | Nº de denúncias: </strong>'.$nreports.' <strong>| </strong></p><br>';
                }
                
            }

            $i++;
        }else{
            $i = $max_pag+1;
        }
        
        
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalcomentarios.' comentários.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="gerircomentarios.php?id='.$paganterior.'&ver='.$ver.'&ord='.$ord.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="gerircomentarios.php?id='.$pagproxima.'&ver='.$ver.'&ord='.$ord.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
