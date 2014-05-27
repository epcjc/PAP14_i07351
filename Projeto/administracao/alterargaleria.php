
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
    $idregisto = $_GET['id'];
}else{
    //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A imagem da galeria que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirgaleria.php';
        header ("location: http://$host$uri/$extra");  
    }
                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      

    

    $tplmenu = file_get_contents('TPL/menuadmin.tpl');
    $tplconteudo = file_get_contents('TPL/alterargaleria.tpl');


//insere id da noticia 2 vezes, imagem, titulo e conteudo

    $tplconteudo = str_replace('()-idregisto-()', $idregisto, $tplconteudo);
    $tplconteudo = str_replace('()-idregisto-()', $idregisto, $tplconteudo);
    $sql = "SELECT descricao, imagem FROM galeria WHERE id=$idregisto";
    
    $query = mysqli_query($link_bd, $sql);
    if(!$query){
        die("ocorreu um erro na query da imagem em alterarregisto.php");
    }
    if($query->num_rows == 0){
            //envia-o de volta para o gerirnoticias.php
        $_SESSION['mensagemadmin'] = "A imagem da galeria que tentou alterar não existe.";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'gerirgaleria.php';
        header ("location: http://$host$uri/$extra");  
    }
    
        while($result = mysqli_fetch_assoc($query)){
            $descricao = $result['descricao'];
            $imagem = $result['imagem'];
        }
        

            if(file_exists('../galeria/'.$idregisto.'_pequena.jpg')){
                $imagemfinal = '../galeria/'.$idregisto.'_pequena.jpg';
            }else{
                //cria imagem_pequena para a noticia
                //redimensiona a imagem e guarda-a como [id_noticia]_pequena.jpg
                        $novaimagem = WideImage::loadFromFile('../'.$imagem);
                   // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(436, 273, 'fill');
                        // Guarda a imagem
                        $imagemfinal = '../galeria/'.$registoid.'_pequena.jpg'; // ex: 5_pequena, 82_pequena
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem pequena no disco
            }

        
        $tplconteudo = str_replace('()-imagem-()', '<a href="../'.$imagem.'"><img src="'.$imagemfinal.'"></a>',$tplconteudo);
        $tplconteudo = str_replace('()-sdescricao-()', $descricao, $tplconteudo);
        //if($imagem == 'imagens_noticias/imagem.jpg' || $imagem == NULL || $imagem == ''){
            
       // }
    

//apresenta menus e conteudo(form de alterAR noticia)
 
    
    $home = str_replace('()-menuadmin-()', $tplmenu, $home);
    $home = str_replace('()-conteudo-()', $tplconteudo, $home);


print $home;
