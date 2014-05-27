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
    
    if(isset($_GET['apagar'])){
        if($_SESSION['administrador_rank'] <= 1){
            $_SESSION['mensagemadmin'] = "Apenas o administrador geral pode apagar mensagens da administração.";
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'gerirmensagens.php?id='.$idpag;
            header ("location: http://$host$uri/$extra");
        }
        if($_GET['apagar'] > 0 && is_numeric($_GET['apagar'])){
            //Verifica se existe e apaga
            $msgapagar = $_GET['apagar'];
            $sql = 'SELECT datahora FROM mensagens_administracao WHERE id = '.$msgapagar.' LIMIT 1';
            $query = mysqli_query($link_bd, $sql);
            if(!$query) die("erro na query hadsn 9482");
            if($query->num_rows > 0){
                //apaga
                $sql = "DELETE FROM mensagens_administracao WHERE id=$msgapagar LIMIT 1";
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
                        die("ocorreu um erro ao apagar a msg query");
                    }else{
                        $_SESSION['mensagemadmin'] = 'Mensagem apagada com sucesso. ';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'gerirmensagens.php?id='.$idpag;
                        header ("location: http://$host$uri/$extra");
                         }
                    
            }else{
                $_SESSION['mensagemadmin'] = "A mensagem que tentou apagar não existe.";
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirmensagens.php?id='.$idpag;
                header ("location: http://$host$uri/$extra");
            }
        }else{
            $_SESSION['mensagemadmin'] = "Houve um parametro inválido inserido ao apagar mensagem.";
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'gerirmensagens.php?id='.$idpag;
            header ("location: http://$host$uri/$extra");
        }
    }
    
    //calcula numeros das paginas a apresentar
    $primeira = $idpag * 10 - 9;
    $ant_primeira = $primeira - 1;
    $ultima = $idpag * 10;

    //pesquisa SQL para saber o total das msgs
    $sql = "SELECT count(*) FROM mensagens_administracao";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query asdascccccc");
    }else{
        while ( $resultado = mysqli_fetch_assoc($query)){
            $totalmsgs = $resultado['count(*)'];
        }
    }

    //pesquisa SQL para selecionar as noticias
    $sql = "SELECT * FROM mensagens_administracao ORDER BY datahora DESC LIMIT $ant_primeira, 10";
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("nao foi possivel fazer a query ");
    }else if($query->num_rows > 0){
        $max_msgs = $query->num_rows;
    }else{
        $max_msgs = 0;
    }
    $max_pag = 10;//maximo de noticias por pagina
    if($totalmsgs > 10){
        $numeropaginas = $totalmsgs / $max_pag;
        $numeropaginas = ceil($numeropaginas);
    }else{
        $numeropaginas = 1;
    }
    
    $i = 1;
    $tplconteudo = '<p> <h3>-----------------------------------------<p>GERIR MENSAGENS DA ADMINISTRAÇÃO</p>-----------------------------------------</h3><br/>';
    while($i <= $max_pag){
        $query->data_seek($i-1);
        $datarow = $query->fetch_array();         
        if($query->num_rows >= $i){
            

            $datahora = $datarow['datahora'];
            $userid = $datarow['id_utilizador'];
            $msgsid = $datarow['id'];
            $titulo = $datarow['titulo'];
            $titulo = htmlspecialchars($titulo);
            $conteudo = $datarow['conteudo'];
            $conteudo = htmlspecialchars($conteudo);
            $respondida = $datarow['respondida'];
            $respondida = htmlspecialchars($respondida);
            $resposta = $datarow['resposta'];
            $resposta = htmlspecialchars($resposta);

            //Faz pesquisa para saber o nome do utilizador
            $sql2 = "SELECT username FROM utilizadores WHERE id = $userid LIMIT 1";
            $query2 = mysqli_query($link_bd, $sql2);
            if(!$query2){
                die("houve um erro com a query daascxzcz564543");
            }else if ($query2->num_rows > 0){
                while ($result = mysqli_fetch_assoc($query2)){
                    $username = $result['username'];
                } 
            }else{
                $username = 'Utilizador removido';
            }
            //verifica se o administrador logado pode alterar e apagar a pagina
            if($_SESSION['administrador_rank'] > 1){
                if($respondida == 0){
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Mensagem nº: </strong>'.$msgsid.' <strong>| Enviada por:</strong> '.$username.' <strong>| <br>| Título: </strong>'.$titulo.'<strong> | <br>| Conteúdo: </strong>'.$conteudo.' <strong>| <br> | Resposta: </strong>nenhuma <strong>| <br> <a href="respondermensagem.php?id='.$msgsid.'">Responder</a>, <a href="gerirmensagens.php?id='.$idpag.'&apagar='.$msgsid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>  </strong></p><br>'; 
                }else{
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Mensagem nº: </strong>'.$msgsid.' <strong>| Enviada por:</strong> '.$username.' <strong>| <br>| Título: </strong>'.$titulo.'<strong> | <br>| Conteúdo: </strong>'.$conteudo.' <strong>| <br> | Resposta: </strong>'.$resposta.' <strong>| <br> <a href="respondermensagem.php?id='.$msgsid.'">Responder</a>, <a href="gerirmensagens.php?id='.$idpag.'&apagar='.$msgsid.'" onclick="return confirmar_apagarnoticia(); return FALSE;">Apagar</a>  </strong></p><br>';
                }
            }else{
                if($respondida == 0){
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Mensagem nº: </strong>'.$msgsid.' <strong>| Enviada por:</strong> '.$username.' <strong>| <br>| Título: </strong>'.$titulo.'<strong> | <br>| Conteúdo: </strong>'.$conteudo.' <strong>| <br> | Resposta: </strong>nenhuma <strong>| <br> <a href="respondermensagem.php?id='.$msgsid.'">Responder</a>   </strong></p><br>'; 
                }else{
                    $tplconteudo .= '<p><strong>| </strong>'.$datahora.' <strong>|<br>| Mensagem nº: </strong>'.$msgsid.' <strong>| Enviada por:</strong> '.$username.' <strong>| <br>| Título: </strong>'.$titulo.'<strong> | <br>| Conteúdo: </strong>'.$conteudo.' <strong>| <br> | Resposta: </strong>'.$resposta.' <strong>| <br>    </strong></p><br>';
                }
            }

            $i++;
        }else{
            $i = $max_pag + 1;
        }
        
        
    }
    //------------------------------------------   
   
    
    //coloca links para a proxima e anterior pagina, se existirem
    $tplconteudo .= '<p>Página '.$idpag.' de '.$numeropaginas.'. Total de '.$totalmsgs.' mensagens para a administração.</p>';
    if($idpag > 1){
        $paganterior = $idpag - 1;
        $tplconteudo .= '<a href="gerirmensagens.php?id='.$paganterior.'">Anterior </a>';
    }
    
    if($numeropaginas > $idpag){
       $pagproxima = $idpag + 1;
        $tplconteudo .= '<a href="gerirmensagens.php?id='.$pagproxima.'">Próxima</a>';  
    }
    
    
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
?>
