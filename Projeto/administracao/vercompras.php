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
    
    //calcula numeros das compras a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das compras
    $sql = "SELECT count(*) FROM compras";
     $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query 1");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalcompras = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar as noticias
    $sql = "SELECT * FROM compras ORDER BY datahora DESC LIMIT $ant_primeira, 10";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query 2");
    }else if($query->num_rows > 0){
        $max_compras = $query->num_rows;
    }else{
        $max_compras = 0;
    }
    $max_pag = 10;//maximo de compras por pagina
    if($totalcompras > 10){
        $numeropaginas = $totalcompras / $max_pag;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = 0;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>VER COMPRAS</p>-----------------------------------------</h3><br/>';
    while($i < 10){
        
        if($query->num_rows > $i){
            $query->data_seek($i);
            $datarow = $query->fetch_array();   

            $datahora = $datarow['datahora'];
            $id_comprador = $datarow['id_comprador'];
            $id_upload = $datarow['id_upload'];
            $confirmacaoC = $datarow['confirmacaoC'];
            if($confirmacaoC == 1){
                $confirmacaoC = 'Sim';
            }else{
                $confirmacaoC = 'Não'; 
            }
            $confirmacaoV = $datarow['confirmacaoV'];
            if($confirmacaoV == 1){
                $confirmacaoV = 'Sim';
            }else{
                $confirmacaoV = 'Não'; 
            }
            $id_compra = $datarow['id'];
            $comentarioC = htmlspecialchars($datarow['comentarioC']);
            if($comentarioC == NULL || $comentarioC == ''){
                $comentarioC = 'Nenhum';
            }
            $comentarioV = htmlspecialchars($datarow['comentarioV']);
            if($comentarioV == NULL || $comentarioV == ''){
                $comentarioV = 'Nenhum';
            }
            $codigocompra = htmlspecialchars($datarow['codigo_compra']);
  

            //Faz pesquisa para saber o nome do vendedor e comprador, e id do vendedor
            $sql2 = "SELECT id_utilizador FROM uploads WHERE id = $id_upload LIMIT 1";
            $query2 = mysqli_query($link_bd, $sql2);
            if(!$query2){
                die("houve um erro com a query adafczxczxgtgwedw2146");
            }else if ($query2->num_rows > 0){
                while ($result = mysqli_fetch_assoc($query2)){
                    $id_vendedor = $result['id_utilizador'];
                } 
            }else{
                $id_vendedor = 0;
            }
            $sql2 = "SELECT username FROM utilizadores WHERE id = $id_comprador LIMIT 1";
            $query2 = mysqli_query($link_bd, $sql2);
            if(!$query2){
                die("houve um erro com a query thdg54hdfhd");
            }else if ($query2->num_rows > 0){
                while ($result = mysqli_fetch_assoc($query2)){
                    $username_comprador = htmlspecialchars($result['username']);
                } 
            }else{
                $username_comprador = 'Utilizador Removido';
            }
            if($id_vendedor != 0){
                $sql2 = "SELECT username FROM utilizadores WHERE id = $id_vendedor LIMIT 1";
                $query2 = mysqli_query($link_bd, $sql2);
                if(!$query2){
                    die("houve um erro com a query vxch311dasfs0675ud");
                }else if ($query2->num_rows > 0){
                    while ($result = mysqli_fetch_assoc($query2)){
                        $username_vendedor = htmlspecialchars($result['username']);
                    } 
                }else{
                    $username_vendedor = 'Utilizador Removido';
                }
            }else{
                $username_vendedor = 'Desconhecido(upload foi removido)';
            }

            
            
            //verifica se o administrador logado pode alterar e apagar a pagina
            if($_SESSION['administrador_rank'] > 1){
                $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Compra nº: </strong>'.$id_compra.' <strong>| Upload nº: </strong>'.$id_upload.' <strong>| Código da compra:</strong> '.$codigocompra.' <strong>| <br>| Vendedor: </strong>'.$username_vendedor.' <strong>| Comprador: </strong>'.$username_comprador.'<strong> | <br>| Comentário do vendedor: </strong>'.$comentarioV.' <strong>| <br>| Comentário do comprador: </strong>'.$comentarioC.' <strong>| <br>| Vendedor confirmou: </strong>'.$confirmacaoV.' <strong>| Comprador confirmou: </strong>'.$confirmacaoC.' <strong>| <br> </strong></p><br>'; 
            }else{
                $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Compra nº: </strong>'.$id_compra.' <strong>| Upload nº: </strong>'.$id_upload.' <strong>| <br>| Vendedor: </strong>'.$username_vendedor.' <strong>| Comprador: </strong>'.$username_comprador.'<strong> | <br>| Comentário do vendedor: </strong>'.$comentarioV.' <strong>| <br>| Comentário do comprador: </strong>'.$comentarioC.' <strong>| <br>| Vendedor confirmou: </strong>'.$confirmacaoV.' <strong>| Comprador confirmou: </strong>'.$confirmacaoC.' <strong>| <br> </strong></p><br>'; 
            }

            $i++;
        }else{
            $i = 10;
        }
        
        
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalcompras.' compras.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="vercompras.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="vercompras.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
