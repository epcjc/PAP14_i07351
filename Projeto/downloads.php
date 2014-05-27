<?php

include_once("includes/seguranca.php");
include_once("includes/funcoes.php");
include_once'WideImage/lib/WideImage.php';
$_SESSION["pagina"] = $_SERVER['PHP_SELF'];
                        // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
        $home = file_get_contents('HTML/main.html');
        
        //imain.html contém a página inicial com slider,  headline com informaçao, e os homeblock1 e 2.
        //main.html contém página inicial sem slider, headline dinamico para sub menu, não contém homeblocks. 
        
        $tpl_menu = file_get_contents('TPL/menu.tpl');
        $tpl_headline = file_get_contents('TPL/headlinemenu_downloads.tpl');
       // $tpl_homeblock1 = file_get_contents('TPL/homeblock1.tpl');
        //$tpl_homeblock2 = file_get_contents('TPL/homeblock2.tpl');
        $tpl_footer = file_get_contents('TPL/footer.tpl');
        $tpl_footerbottom = file_get_contents('TPL/footerbottom.tpl');
        
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
            if($_SESSION['tentativaslogin'] >= 3){
                $tpl_separadorcima = file_get_contents('TPL/separadorcima_seguro.tpl');
            }else{
                $tpl_separadorcima = file_get_contents('TPL/separadorcima.tpl');
            }
            $tpl_topopen = file_get_contents('TPL/top-open.tpl');
            //separadorcima.tpl contém o form de login, aparece apenas quando o utilizador não está logado.
        }else{
            
            $tpl_separadorcima = file_get_contents('TPL/logged.tpl');
            $nome = $_SESSION["utilizador_username"]; //aparece o nome de utilizador no separador de cima
            
            $tpl_topopen = file_get_contents('TPL/top-open2.tpl');
            //logged.tpl contém informaçoes sobre a conta, opçoes para gerir a conta, opçao para logout, aparece apenas quando o utilizador está logado.
            //pesquisa mysql para saber o numero de mensagens
            

            
            $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
            $sql = "SELECT `nmensagens` FROM `".$_SG['tabela']."` WHERE ".$cS." `username` = '".$nome."' LIMIT 1";

            $query = mysqli_query($link_bd, $sql);
            if (!$query) {
                echo "Não foi possível executar a consulta ($sql) no banco de dados: " . mysqli_error();
        
            }else if(mysqli_num_rows($query) == 0) {
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'logout.php';
                header ("location: http://$host$uri/$extra");

            }else{
                while ($resultado = mysqli_fetch_assoc($query)) {
                        if($resultado["nmensagens"] == 1){
                            $nmensagens = $resultado["nmensagens"] . ' mensagem nova';  
                        }elseif($resultado["nmensagens"] == 0){
                            $nmensagens = 'Não há novas mensagens';
                        }else{
                            $nmensagens = $resultado["nmensagens"] . ' mensagens novas';
                        }


                        }
                    }
             $id = $_SESSION["utilizador_id"];
             $tpl_separadorcima = str_replace("()-nome-()", '<a href="perfil.php?id='.$id.'"><font color="#E0E0E0">'.htmlspecialchars($nome).'</font></a>', $tpl_separadorcima);
             $tpl_separadorcima = str_replace("()-nmensagens-()", $nmensagens, $tpl_separadorcima);
        
                          //encontra imagem do utilizador----------------
             $imagempath = ''; //caminho da imagem
             $path = 'imagens_utilizadores/'; //caminho da pasta
             $foi = 0; //confirmaçao que tem imagem
             //verifica se o utilizador ja tem uma imagem pequena
             $filename = $path.$_SESSION['utilizador_id'].'_pequena.jpg';
             if (!file_exists($filename)) { //se não existir

                if(userimagem($_SESSION["utilizador_id"]) == FALSE){
                    $imagempath = 'imagens_utilizadores/imagem.jpg';
                }else{//encontra o caminho da imagem
                    $imagempath = userimagem($_SESSION["utilizador_id"]);
                    $foi = 1;
                }
                if($foi == 1){
                    // Carrega a imagem a ser manipulada
                    $imagem = WideImage::loadFromFile($imagempath);
                   // Redimensiona a imagem
                    $imagem = $imagem->resize(100, 75);
                        // Guarda a imagem
                    $pathcompleto = $path.$_SESSION['utilizador_id'].'_pequena.jpg'; // ex: 5_pequena, 82_pequena
                    $imagem->saveToFile($pathcompleto, 40); // Coloca a imagem pequena no disco
               }else{
                    $pathcompleto = $path.'imagem_pequena.jpg';     
                     }
               
             }else{
                 $pathcompleto = $path.$_SESSION['utilizador_id'].'_pequena.jpg';
             }
                
             //----------------------------------------------
            
//coloca imagem pequena na pagina
            $htmlimg = '<img src="'.$pathcompleto.'" alt="">';
            $tpl_separadorcima = str_replace("()-imagempequena-()", $htmlimg, $tpl_separadorcima);
             
            }

        
        $home = str_replace("()-separadorcima-()", $tpl_separadorcima, $home);
        $home = str_replace("()-top-open-()", $tpl_topopen, $home);
        $home = str_replace("()-menu-()", $tpl_menu, $home);
        $home = str_replace("()-headline-()", $tpl_headline, $home);
        $home = str_replace("()-homeblock1-()", '', $home);
        $home = str_replace("()-homeblock2-()", '', $home);
        $home = str_replace("()-footer-()", $tpl_footer, $home);
        $home = str_replace("()-footerbottom-()", $tpl_footerbottom, $home);
        
        //coloca tudo no sitio excepto content
        
        

        $tpl_content = file_get_contents('TPL/downloads.tpl');
        $home = str_replace("()-content-()", $tpl_content, $home);
        //coloca content por substituir
        //
        //----------------------------------------------------------
        //----------------------------------------------------------
        //------------------começa definiçao e substituicao dos downloads
        //----------------------------------------------------------
        //-define valores dos paramentros cat e ord
        $cat = '';
        $ord = 'datad';
        if(isset($_GET['cat'])){
            switch($_GET['cat']){
                case 'vid': $cat = 'video'; break;
                case 'aud': $cat = 'audio'; break;
                case 'sof': $cat = 'software'; break;
                case 'ima': $cat = 'imagem'; break;
                case 'mod': $cat = '3d'; break;
                case 'out': $cat = 'outras'; break;
            }
        }
        if(isset($_GET['ord'])){
            switch($_GET['ord']){
                case 'datac': $ord = 'datac'; break;
                case 'tituloc': $ord = 'tituloc'; break;
                case 'titulod': $ord = 'titulod'; break;
                case 'utilizadorc': $ord = 'utilizadorc'; break;
                case 'utilizadord': $ord = 'utilizadord'; break;
                case 'precoc': $ord = 'precoc'; break;
                case 'precod': $ord = 'precod'; break;
                case 'gostosc': $ord = 'gostosc'; break;
                case 'gostosd': $ord = 'gostosd'; break;
            }
        }
        //substitui mensagemtopo e links do submenu
        $mensagemtopo = '';
        $linkbaseord = 'downloads.php?';
        if($cat != ''){
            switch($cat){
                case 'video':
                    $mensagemtopo .= 'Categoria: Video, ';
                    $linkbaseord .= 'cat=vid&';
                    break;
                case 'audio':
                    $mensagemtopo .= 'Categoria: Audio, ';
                    $linkbaseord .= 'cat=aud&';
                    break;
                case 'software':
                    $mensagemtopo .= 'Categoria: Software, ';
                    $linkbaseord .= 'cat=sof&';
                    break;
                case 'imagem':
                    $mensagemtopo .= 'Categoria: Imagem, ';
                    $linkbaseord .= 'cat=ima&';
                    break;
                case '3d':
                    $mensagemtopo .= 'Categoria: Modelação 3d, ';
                    $linkbaseord .= 'cat=mod&';
                    break;
                case 'outras':
                    $mensagemtopo .= 'Categoria: Outras, ';
                    $linkbaseord .= 'cat=out&';
                    break;
            }
        }
        $ordemsql = '';
        switch($ord){
                case 'datad':
                    $mensagemtopo .= 'Ordenado por data(-)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datac', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'datahora DESC';
                    break;
                case 'datac':
                    $mensagemtopo .= 'Ordenado por data(+)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'datahora';
                    break;
                case 'tituloc':
                    $mensagemtopo .= 'Ordenado por título(+)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'titulo';
                    break;
                case 'titulod':
                    $mensagemtopo .= 'Ordenado por título(-)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=tituloc', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'titulo DESC';
                    break;
                case 'utilizadorc':
                    $mensagemtopo .= 'Ordenado por utilizador(+)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'id_utilizador';
                    break;
                case 'utilizadord':
                    $mensagemtopo .= 'Ordenado por utilizador(-)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadorc', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'id_utilizador DESC';
                    break;
                case 'precoc':
                    $mensagemtopo .= 'Ordenado por preço(+)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'preco';
                    break;
                case 'precod':
                    $mensagemtopo .= 'Ordenado por preço(-)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precoc', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'preco DESC';
                    break;
                case 'gostosc':
                    $mensagemtopo .= 'Ordenado por Pontuação(+)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosd', $home);
                    $ordemsql = 'nlikes ASC, ndislikes DESC';
                    break;
                case 'gostosd':
                    $mensagemtopo .= 'Ordenado por Pontuação(-)';
                    $home = str_replace('()-linkorddata-()',$linkbaseord.'ord=datad', $home);
                    $home = str_replace('()-linkordtitulo-()',$linkbaseord.'ord=titulod', $home);
                    $home = str_replace('()-linkordpreco-()',$linkbaseord.'ord=precod', $home);
                    $home = str_replace('()-linkorduti-()',$linkbaseord.'ord=utilizadord', $home);
                    $home = str_replace('()-linkordgostos-()',$linkbaseord.'ord=gostosc', $home);
                    $ordemsql = 'nlikes DESC, ndislikes ASC';
                    break;
            }
        
        $home = str_replace('()-mensagemtopo-()',$mensagemtopo, $home);
        
        //--------------
        //-pesquisa sql para saber o total de projetos a apresentar
        if($cat != ''){
            $sql = "SELECT count(*) FROM uploads WHERE categoria = '$cat'";
        }else{
            $sql = "SELECT count(*) FROM uploads";
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("in downloads erro 212fjas");
        }
        while ($r = mysqli_fetch_assoc($query)){
            $totalprojetos = $r['count(*)'];
        }
        if($totalprojetos > 0){
            //Define total de paginas a apresentar
            if($totalprojetos <= 3){
                $totalpaginas = 1;
            }else{
                $totalpaginas = $totalprojetos / 3;
                $totalpaginas = ceil($totalpaginas); 
            }
        }else{
            $totalpaginas = 1;
        }
        //define pagina em que estamos
        if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
            $numeropagina = $_GET['id'];
        }else{
            $numeropagina = 1;
        }
        //substitui ()-total_paginas-()
        if($totalprojetos > 1){
            $home = str_replace('()-total_paginas-()', 'Página '.$numeropagina.' de '.$totalpaginas.', num total de '.$totalprojetos.' downloads disponíveis.', $home);            
        }else{
            $home = str_replace('()-total_paginas-()', 'Página '.$numeropagina.' de '.$totalpaginas.', num total de '.$totalprojetos.' download disponível.', $home);            
        }
                //substitui botoes anterior e proxima--------------------------------
        if($numeropagina < $totalpaginas){
            //substitui botao proxima
            $proximapagina = $numeropagina + 1;
            $tpl_proxima = file_get_contents('TPL/noticias/definirlink_proxima.tpl');
            
            $link_proxima = 'downloads.php?';
            if($cat != ''){
                if($cat == '3d'){
                    $link_proxima .= 'cat=mod&';
                }else{
                    $link_proxima .= 'cat='.$cat{0}.$cat{1}.$cat{2}.'&';
                }
                   
            }
            if($ord != 'datad'){
                $link_proxima .= 'ord='.$ord.'&';
            }
            $link_proxima .= 'id='.$proximapagina;
            
            $tpl_proxima = str_replace('()-link-()', $link_proxima, $tpl_proxima);
        }else{
            //substitui botao proxima para nao apresentar nada
            $tpl_proxima = '';
        }
        
        if($numeropagina > 1){
            //substitui botao anterior
            $paginaanterior = $numeropagina - 1;
            $tpl_anterior = file_get_contents('TPL/noticias/definirlink_anterior.tpl');
            
            $link_anterior = 'downloads.php?';
            if($cat != ''){
                if($cat == '3d'){
                    $link_anterior .= 'cat=mod&';
                }else{
                    $link_anterior .= 'cat='.$cat{0}.$cat{1}.$cat{2}.'&';
                }
            }
            if($ord != 'datad'){
                $link_anterior .= 'ord='.$ord.'&';
            }
            $link_anterior .= 'id='.$paginaanterior;
            
            $tpl_anterior = str_replace('()-link-()', $link_anterior, $tpl_anterior);
        }else{
            //substitui botao anterior para nao apresentar nada
            $tpl_anterior = '';
        }
        //faz a substituiçao dos botoes no $home
        $home = str_replace('()-proxima-()', $tpl_proxima, $home);
        $home = str_replace('()-anterior-()', $tpl_anterior, $home);
        
        //define primeiro projeto a ser apresentado
        $p_projeto = $numeropagina * 3 - 3;
        //faz pesquisa sql para guardar os arrays
        $foi = array(1=>0, 2=>0,3=>0);
        $idupl = array(1=>'',2=>'',3=>'');
        $datahora = array(1=>'',2=>'',3=>'');
        $imagem1 = array(1=>'',2=>'',3=>'');
        $imagem2 = array(1=>'',2=>'',3=>'');
        $imagem3 = array(1=>'',2=>'',3=>'');
        $imagem4 = array(1=>'',2=>'',3=>'');
        $titulo = array(1=>'',2=>'',3=>'');
        $preco = array(1=>'',2=>'',3=>'');
        $nlikes = array(1=>'',2=>'',3=>'');
        $ndislikes = array(1=>'',2=>'',3=>'');
        $descricao = array(1=>'',2=>'',3=>'');
        $categoria = array(1=>'',2=>'',3=>'');
        $ncomentarios = array(1=>'',2=>'',3=>'');
        $idusr = array(1=>'',2=>'',3=>'');
        
        if($cat != ''){
            $sql = "SELECT id, datahora, imagem1, imagem2, imagem3, imagem4, titulo, preco, nlikes, ndislikes, descricao, categoria, ncomentarios, id_utilizador FROM uploads WHERE categoria = '$cat' ORDER BY $ordemsql LIMIT $p_projeto, 3";
        }else{
            $sql = 'SELECT id, datahora, imagem1, imagem2, imagem3, imagem4, titulo, preco, nlikes, ndislikes, descricao, categoria, ncomentarios, id_utilizador FROM uploads ORDER BY '.$ordemsql.' LIMIT '.$p_projeto.', 3';
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("nao deu downloads erro hcnahsodk");
        }
        $i = 0;
        while ($i < 3){
            if($query->num_rows > $i){
                $query->data_seek($i);
                $datarow = $query->fetch_array();
                $idupl[$i+1] = $datarow['id'];
                $datahora[$i+1] = $datarow['datahora'];
                $imagem1[$i+1] = $datarow['imagem1'];
                $imagem2[$i+1] = $datarow['imagem2'];
                $imagem3[$i+1] = $datarow['imagem3'];
                $imagem4[$i+1] = $datarow['imagem4'];
                $titulo[$i+1] = $datarow['titulo'];
                $titulo[$i+1] = htmlspecialchars($titulo[$i+1]);
                $preco[$i+1] = $datarow['preco'];
                $preco[$i+1] = htmlspecialchars($preco[$i+1]);
                $nlikes[$i+1] = $datarow['nlikes'];
                $ndislikes[$i+1] = $datarow['ndislikes'];
                $descricao[$i+1] = $datarow['descricao'];
                $descricao[$i+1] = htmlspecialchars($descricao[$i+1]);
                $categoria[$i+1] = $datarow['categoria'];
                $categoria[$i+1] = htmlspecialchars($categoria[$i+1]);
                $ncomentarios[$i+1] = $datarow['ncomentarios'];
                $idusr[$i+1] = $datarow['id_utilizador'];
                $foi[$i+1] = 1;
            }else{
                $i = 3;
            }
            $i++;
        }
        //parte da substituicao
        $i = 1;
        while ($i < 4){
            if($foi[$i] == 1){
                $home = str_replace('()-link'.$i.'-()', 'trabalho.php?id='.$idupl[$i], $home);
                if($preco[$i] == 0 || $preco[$i] == NULL || $preco[$i] == ''){
                    $precot = 'Gratuito - ';
                }else{
                    $precot = 'Preço: '.$preco[$i].' € - ';
                }
                $home = str_replace('()-preco'.$i.'-()', $precot, $home);
                if($nlikes[$i]==0 && $ndislikes[$i]==0){
                    $home = str_replace('()-rating'.$i.'-()', 'Ainda ninguém avaliou este trabalho/projeto', $home);
                }else if($nlikes[$i]==0 && $ndislikes[$i]==1){
                    $home = str_replace('()-rating'.$i.'-()', '1 utilizador não gosta disto', $home);
                }else if($nlikes[$i]==1 && $ndislikes[$i]==0){
                    $home = str_replace('()-rating'.$i.'-()', '1 utilizador gosta disto', $home);
                }else if($nlikes[$i]==1 && $ndislikes[$i]==1){
                    $home = str_replace('()-rating'.$i.'-()', '1 utilizador gosta disto, e 1 não gosta', $home);
                }else if($nlikes[$i]==1 && $ndislikes[$i]>1){
                    $home = str_replace('()-rating'.$i.'-()', '1 utilizador gosta disto, e '.$ndislikes[$i].' não gostam', $home);
                }else if($nlikes[$i]>1 && $ndislikes[$i]==1){
                    $home = str_replace('()-rating'.$i.'-()', $nlikes[$i].' utilizadores gostam disto, e 1 não gosta', $home);
                }else if($nlikes[$i]>1 && $ndislikes[$i]==0){
                    $home = str_replace('()-rating'.$i.'-()', $nlikes[$i].' utilizadores gostam disto', $home);
                }else if($nlikes[$i] == 0 && $ndislikes[$i] > 1){
                    $home = str_replace('()-rating'.$i.'-()', $ndislikes[$i].' utilizadores não gostam disto.', $home);
                }else{
                    $home = str_replace('()-rating'.$i.'-()', $nlikes[$i].' utilizadores gostam disto, e '.$ndislikes[$i].' não gostam.', $home);
                }
                if(strlen($descricao[$i]) > 250){
                    $descricaor = substr($descricao[$i], 0, 250).'<font size=+1>...</font>';
                }else{
                    $descricaor = $descricao[$i];
                }
                if(strlen($titulo[$i]) > 88){
                    $titulor = substr($titulo[$i], 0, 88).'<font size=+1>...</font>';
                }else{
                    $titulor = $titulo[$i];
                }
                $home = str_replace('()-parte-conteudo'.$i.'-()', $descricaor, $home);
                $home = str_replace('()-titulo'.$i.'-()', $titulor, $home);
                $tcat = '';
                switch($categoria[$i]){
                    case 'video': $tcat = 'Video'; break;
                    case 'software': $tcat = 'Software'; break;
                    case 'imagem': $tcat = 'Imagem'; break;
                    case '3d': $tcat = 'Modelação 3d'; break;
                    case 'outras': $tcat = 'Outras categorias'; break;
                    case 'audio': $tcat = 'Audio'; break;
                }
                $home = str_replace('()-categoria'.$i.'-()', $tcat, $home);
                if($ncomentarios[$i] == 0){
                   $txtcoment = '0 comentários';
                }else if($ncomentarios[$i] == 1){
                   $txtcoment = '1 comentário';
                    
                }else{
                   $txtcoment = $ncomentarios[$i].' comentários';
                }
                $home = str_replace('()-ncomments'.$i.'-()', $txtcoment, $home);
                $sql = 'SELECT username FROM utilizadores WHERE id = '.$idusr[$i].' LIMIT 1';
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die("downloads erro ncagstwoqms");
                }
                while ($r = mysqli_fetch_assoc($query)){
                    $nomeusr = $r['username'];
                }
                $home = str_replace('()-user'.$i.'-()', '<a href="perfil.php?id='.$idusr[$i].'">'.$nomeusr.'</a>', $home);
                
                //define qual das imagens vai user
                $imagemu = '';
                if($imagem1[$i] != NULL && $imagem1[$i] != '' && $imagem1[$i] != 'upload/imagem.jpg'){
                    $imagemu = $imagem1[$i];
                }else if($imagem2[$i] != NULL && $imagem2[$i] != '' && $imagem2[$i] != 'upload/imagem.jpg'){
                    $imagemu = $imagem2[$i];
                }else if($imagem3[$i] != NULL && $imagem3[$i] != '' && $imagem3[$i] != 'upload/imagem.jpg'){
                    $imagemu = $imagem3[$i];
                }else if($imagem4[$i] != NULL && $imagem4[$i] != '' && $imagem4[$i] != 'upload/imagem.jpg'){
                    $imagemu = $imagem4[$i];
                }else{
                    $imagemu = 'upload/imagem.jpg';
                }
                if($imagemu != 'upload/imagem.jpg'){
                    $checkimg = substr($imagemu, 0, -4);
                    $checkimg = $checkimg.'_perfil.jpg';
                    if(file_exists($checkimg)){
                        $imagemfinal = $checkimg;
                    }else if(file_exists($imagemu)){
                        //cria imagem perfil para o utilizador
                        $novaimagem = WideImage::loadFromFile($imagemu);
                       // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(960, 473, 'outside');
                        $novaimagem = $novaimagem->crop('center', 'center', 960, 473);
                            // Guarda a imagem
                        $imagemfinal = $checkimg;
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem 
                    }else{
                        $imagemfinal = 'upload/imagem.jpg';
                    }
                
                }else{
                    $imagemfinal = $imagemu;
                }
                
                $home = str_replace('()-imagem'.$i.'-()', '<a href="trabalho.php?id='.$idupl[$i].'"><img src="'.$imagemfinal.'" alt="" /></a>', $home);
                //define data
                $udia = $datahora[$i]{8} . $datahora[$i]{9};
                $umes = $datahora[$i]{5} . $datahora[$i]{6};
                if(convertermes($umes)!= FALSE){
                    $umes = convertermes($umes);
                }
                $uano = $datahora[$i]{0} . $datahora[$i]{1} . $datahora[$i]{2} . $datahora[$i]{3};
                $uhh = $datahora[$i]{11} . $datahora[$i]{12};
                $umm = $datahora[$i]{14} . $datahora[$i]{15};
                //substitui data
                $home = str_replace('()-dia'.$i.'-()', $udia, $home);
                $home = str_replace('()-mes'.$i.'-()', $umes, $home);
                $home = str_replace('()-ano'.$i.'-()', $uano, $home);
                $home = str_replace('()-HH'.$i.'-()', $uhh, $home);
                $home = str_replace('()-MM'.$i.'-()', $umm, $home);
                
                
            }else{
                //substitui o projeto I por nada
                $tpl_proj = '<article class="format-standard">
						<div class="entry-date"><div class="number">()-dia'.$i.'-()</div> <div class="year">()-mes'.$i.'-(), ()-ano'.$i.'-()<br><br><font color="black" size="+1">()-HH'.$i.'-():()-MM'.$i.'-()</font></div></div>
						<div class="feature-image">
							()-imagem'.$i.'-()
						</div>
						<h2  class="post-heading"><a href="()-link'.$i.'-()">()-titulo'.$i.'-()</a></h2>
                                                <font color="#700000">()-preco'.$i.'-()</font> <font color="#999900">()-rating'.$i.'-()<br/></font>
						<div class="excerpt">()-parte-conteudo'.$i.'-()
						</div>
						<div class="meta">
                                                        <div class="categories">()-categoria'.$i.'-()</div>
							<div class="comments">()-ncomments'.$i.'-()</div>
							<div class="user">Enviado por: ()-user'.$i.'-()</div>
						</div>
					</article>';
                $home = str_replace($tpl_proj, '', $home);
            }
            $i++;
        }
        //-----------------------
        
        
        //----------------------------------------------------------
        //------------------acaba definicao e substituicao dos downloads
        //----------------------------------------------------------
        
//---------------------------------------------------------------------
        //------------COMEÇA DEFINIÇÃO das páginas geradas-----------------------
        //----------------------------------------------------------------------
        
                //faz pesquisa para saber o numero de páginas na base de dados
                $sql = "SELECT COUNT(*) FROM paginas";
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die('ocorreu um erro na query das paginas geradas');
                }
                while($res = mysqli_fetch_assoc($query)){
                    $maxpaginas = $res['COUNT(*)'];
                }
                if(!isset($maxpaginas)){
                    $maxpaginas = 0;
                }
                if ($maxpaginas == 0 || $maxpaginas == NULL){//não mostra nada
                    $subtext = '<li><a href="pagina.php">OUTRAS</a><ul>()-definirpaginas-()</ul></li>';
                    $home = str_replace($subtext, '', $home);
                }else{// define as paginas existentes
                    
                            $limite = 5; //maximo de paginas que apresenta no menu
                            $sql = "SELECT * FROM paginas ORDER BY datahora DESC LIMIT $limite";
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query){
                                die("houve um erro na query na listagem das paginasgeradas");
                            }
                                //ciclo para definir as paginas
                                $i = 1;
                                $cont = 0; //contagem de paginas que foram inseridas
                                $numpaginas=$query->num_rows;
                                $tplpaginas = file_get_contents('TPL/paginas/definirpagina.tpl');
                                
                                while($i <= $limite){
                                    if( $numpaginas >= $i){         
                                    //define dados da pagina $i 
                                        $query->data_seek($i-1);
                                        $datarow = $query->fetch_array();
                                        $nomepagina = $datarow['nome'];
                                        $idpagina = $datarow['id'];
                                        
                                        
                                        $home = str_replace('()-definirpaginas-()', $tplpaginas.'()-definirpaginas-()', $home);
                                        $home = str_replace('()-nomepagina-()', htmlspecialchars($nomepagina), $home);
                                        $home = str_replace('()-idpagina-()', $idpagina, $home);
                                        $cont++;
                                        }
                                    $i++;
                                //
                                 }
                                    if($maxpaginas > 5){
                                        $linkvermais = '<li><a href="listadepaginas.php"><font color="#C8C8C8">VER MAIS</font></a></li>';
                                        $home = str_replace('()-definirpaginas-()', $linkvermais, $home);
                                    }else{
                                        $home = str_replace('()-definirpaginas-()', '', $home);
                                    }
                    
                       }
                
        
        //------------------------------------------------------------------
        //------------------ACABa DEFINIÇAO DAS Páginas geradas--------------
        //------------------------------------------------------------------
        
        print $home;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

