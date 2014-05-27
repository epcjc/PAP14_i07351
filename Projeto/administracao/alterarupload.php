<?php
include_once '../includes/seguranca.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
    $idupl = $_GET['id'];
}else{
    //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "O upload que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'geriruploads.php';
        header ("location: http://$host$uri/$extra");  
    }
    
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }

        
                      //--------------------------------------------------------
                      
    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/alterarupload.tpl');

//-------------------------------------------------------
    $sql = "SELECT id_utilizador,imagem1,imagem2,imagem3,imagem4,titulo,descricao,categoria FROM uploads WHERE id = $idupl LIMIT 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("morreu alterar upl");
    if($query->num_rows == 0){
        $_SESSION['mensagemadmin'] = "O upload que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'geriruploads.php';
        header ("location: http://$host$uri/$extra"); 
        exit;
    }
    $r = mysqli_fetch_assoc($query);
    
//define valores
    $idusr = $r['id_utilizador'];
    $imagem = array(1=>'',2=>'',3=>'',4=>'');
    $imagemfinal = array(1=>'',2=>'',3=>'',4=>'');
    $imagem[1] = htmlspecialchars($r['imagem1']);
    $imagem[2] = htmlspecialchars($r['imagem2']);
    $imagem[3] = htmlspecialchars($r['imagem3']);
    $imagem[4] = htmlspecialchars($r['imagem4']);
    $categoria = htmlspecialchars($r['categoria']);
    $titulo = htmlspecialchars($r['titulo']);
    $descricao = htmlspecialchars($r['descricao']);
    
    $sql = "SELECT username FROM utilizadores WHERE id = $idusr LIMIT 1";
    $query = mysqli_query($link_bd, $sql);
    if(!$query) die("morreu alterar upl slc usr");
    if($query->num_rows == 0){
        $username = 'Utilizador removido';
    }else{
        $r = mysqli_fetch_assoc($query);
        $username = htmlspecialchars($r['username']);
    }    
//define imagens
    $i = 1;
    while ($i < 5){
        if($imagem[$i] == 'upload/imagem.jpg' || $imagem[$i] == '' || $imagem[$i] == NULL){
            $imagemfinal[$i] = '<img src="../upload/imagem_miniatura.jpg">';
        }else{
            $checkimg = '../'.$imagem[$i];
            $checkimg = substr($checkimg, 0, -4);
            $checkimg = $checkimg.'_pequena.jpg';
            if(file_exists($checkimg)){
                $imagemfinal[$i] = '<a href="../'.$imagem[$i].'"><img src="'.$checkimg.'"></a>';
            }else if(file_exists('../'.$imagem[$i])){
                $novaimagem = WideImage::loadFromFile('../'.$imagem[$i]);
               // Redimensiona a imagem
                $novaimagem = $novaimagem->resize(436, 273, 'outside');
                $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                    // Guarda a imagem
                $imagemfinal[$i] = $checkimg;
                $novaimagem->saveToFile($imagemfinal[$i], 40); // Coloca a imagem 
                $imagemfinal[$i] = '<a href="../'.$imagem[$i].'"><img src="'.$checkimg.'"></a>';
            }else{
                $imagemfinal[$i] = '<img src="../upload/imagem_miniatura.jpg">';
            }
        }
        $i++;
    }
//faz substituicao no tplconteudo
    //idupload username
    $tplconteudo = str_replace('()-idupload-()', $idupl, $tplconteudo);
    $tplconteudo = str_replace('()-username-()', $username, $tplconteudo);
    //imagem1, etc
    $tplconteudo = str_replace('()-imagem1-()', $imagemfinal[1], $tplconteudo);
    $tplconteudo = str_replace('()-imagem2-()', $imagemfinal[2], $tplconteudo);
    $tplconteudo = str_replace('()-imagem3-()', $imagemfinal[3], $tplconteudo);
    $tplconteudo = str_replace('()-imagem4-()', $imagemfinal[4], $tplconteudo);
    //categoria titulo e descricao
    $tplconteudo = str_replace('<option value="'.$categoria.'">', '<option value="'.$categoria.'" selected>', $tplconteudo);
    $tplconteudo = str_replace('()-titulo-()', $titulo, $tplconteudo);
    $tplconteudo = str_replace('()-descricao-()', $descricao, $tplconteudo);
    
//apresenta menus e conteudo(form de alterAR noticia)
 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
