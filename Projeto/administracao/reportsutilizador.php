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
        $idutilizador = $_GET['id'];      
    }else{
        $_SESSION['mensagemadmin'] = "Esse utilizador não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirutilizadores.php';
        header ("location: http://$host$uri/$extra");
    }
    if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0){
        $idpag = $_GET['p'];
    }else{
        $idpag = 1;
    }
    
    //pesquisa para saber username do utilizador e se existe
    $sql = "SELECT username FROM utilizadores WHERE id = $idutilizador LIMIT 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query 154vd");
    }
    if($query->num_rows == 0){
        $_SESSION['mensagemadmin'] = "Esse utilizador não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirutilizadores.php';
        header ("location: http://$host$uri/$extra");
    }
    while ( $resultado = mysqli_fetch_assoc($query)){
        $username = htmlspecialchars($resultado['username']);
    }
    
    
    //calcula numeros das denuncias a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das denuncias a este utilizador
    $sql = "SELECT count(*) FROM reports_utilizadores WHERE id_reportado = ".$idutilizador;
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query 1");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalreports = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar os reports
    $sql = "SELECT * FROM reports_utilizadores WHERE id_reportado = $idutilizador ORDER BY datahora DESC LIMIT $ant_primeira, 10";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query 2");
    }else if($query->num_rows > 0){
        $max_reports = $query->num_rows;
    }else{
        $max_reports = 0;
    }
    $max_pag = 10;//maximo de reports por pagina
    if($totalreports > 10){
        $numeropaginas = $totalreports / $max_pag;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = 0;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>VER DENÙNCIAS<br><br>Utilizador: '.$username.'</p>-----------------------------------------</h3><br/>';
    while($i < 10){
        
        if($query->num_rows > $i){
            $query->data_seek($i);
            $datarow = $query->fetch_array();   
            $id_report = $datarow['id'];
            $datahora = $datarow['datahora'];
            $id_denunciante = $datarow['id_utilizador'];
            $titulo = $datarow['titulo'];
            $conteudo = $datarow['conteudo'];
            
  

            //Faz pesquisa para saber o nome do denunciante
            $sql2 = "SELECT username FROM utilizadores WHERE id = $id_denunciante LIMIT 1";
            $query2 = mysqli_query($link_bd, $sql2);
            if(!$query2){
                die("houve um erro com a query 32rv thdg54hdfhd");
            }else if ($query2->num_rows > 0){
                while ($result = mysqli_fetch_assoc($query2)){
                    $username_denunciante = htmlspecialchars($result['username']);
                } 
            }else{
                $username_denunciante = 'Utilizador Removido';
            }
            

            if($_SESSION['administrador_rank'] > 1){
                $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Utilizador que denunciou: </strong>'.$username_denunciante.' <strong>|<br>| Título: </strong>'.$titulo.' <strong>|<br>| Conteúdo:</strong> '.$conteudo.' <br><a href="apagarreport.php?t=ut&id='.$id_report.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a></p><br>'; 
            }else{
                $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Utilizador que denunciou: </strong>'.$username_denunciante.' <strong>|<br>| Título: </strong>'.$titulo.' <strong>|<br>| Conteúdo:</strong> '.$conteudo.' </p><br>'; 
            }
         

            $i++;
        }else{
            $i = 10;
        }
        
        
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalreports.' denúncias.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="reportsutilizador.php?id='.$idutilizador.'&p='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="reportsutilizador.php?id='.$idutilizador.'&p='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
