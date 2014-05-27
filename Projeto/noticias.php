<?php

include_once("includes/seguranca.php");
include_once("includes/funcoes.php");
include_once'WideImage/lib/WideImage.php';
$_SESSION["pagina"] = $_SERVER['PHP_SELF'];

        $home = file_get_contents('HTML/main.html');
        
        //imain.html contém a página inicial com slider,  headline com informaçao, e os homeblock1 e 2.
        //main.html contém página inicial sem slider, headline dinamico para sub menu, não contém homeblocks. 
        
                                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
            

            $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
        
        $tpl_menu = file_get_contents('TPL/menu.tpl');
        $tpl_headline = file_get_contents('TPL/headlinemenu_noticias.tpl');
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
        
        

        $tpl_content = file_get_contents('TPL/noticias.tpl');
        $home = str_replace("()-content-()", $tpl_content, $home);
        //coloca content por substituir
        
        //define os parametros ordem e ano
        $ord = 'datad';
        $ano = '';
        if(isset($_GET['ord'])){
            switch($_GET['ord']){
                case 'datac': $ord = 'datac'; break;
                case 'utilizadord': $ord = 'utilizadord'; break;
                case 'utilizadorc': $ord = 'utilizadorc'; break;
                case 'titulod': $ord = 'titulod'; break;
                case 'tituloc': $ord = 'tituloc'; break;     
            }
        }
        if(isset($_GET['ano']) && is_numeric($_GET['ano']) && strlen($_GET['ano']) == 4){
            $ano = $_GET['ano'];
        }
        
        //-----define o submenu ordenar e ver ano
        if($ano != ''){
            switch($ord){
                case 'datad': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datac" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'datac': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'titulod': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=tituloc" title="Ordenar por Título">Título</a></li>'; break;
                case 'tituloc': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'utilizadord': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadorc" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'utilizadorc': $definirordem = '<li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ano='.$ano.'&ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
            }     
        }else{
            switch($ord){
                case 'datad': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datac" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'datac': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'titulod': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=tituloc" title="Ordenar por Título">Título</a></li>'; break;
                case 'tituloc': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'utilizadord': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadorc" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
                case 'utilizadorc': $definirordem = '<li class="cat-item"><a href="noticias.php?ord=datad" title="Ordenar por Data">Data</a></li><li class="cat-item"><a href="noticias.php?ord=utilizadord" title="Ordenar por Utilizador">Utilizador</a></li><li class="cat-item"><a href="noticias.php?ord=titulod" title="Ordenar por Título">Título</a></li>'; break;
            }
        }
        
        $home = str_replace('()-definirordens-()', $definirordem, $home);
        //pesquisa para saber o primeiro ano em q foram adicionadas noticias e o ultimo
        $primeiradata = '';
        $ultimadata = '';
        $sql = 'SELECT datahora FROM noticias ORDER BY datahora DESC LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("erro dahsuda em noticias");
        }
        if($query->num_rows > 0){
            while($r =  mysqli_fetch_assoc($query)){
                $ultimadata = $r['datahora'];
            }
        }
        $sql = 'SELECT datahora FROM noticias ORDER BY datahora LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("erro dahsuda em noticias 2");
        }
        while($r =  mysqli_fetch_assoc($query)){
            $primeiradata = $r['datahora'];
        }
        if($primeiradata != '' && $ultimadata != ''){
            $p_ano = $primeiradata{0} . $primeiradata{1} . $primeiradata{2} . $primeiradata{3};
            $u_ano = $ultimadata{0} . $ultimadata{1} . $ultimadata{2} . $ultimadata{3};
            if($u_ano != $p_ano){
                $i = $p_ano;
                while($i <= $u_ano){
                    if($i == $u_ano){
                        $home = str_replace('()-definiranos-()', '<li class="cat-item"><a href="noticias.php?ano='.$i.'" title="'.$i.'">'.$i.'</a></li><li class="cat-item"><a href="noticias.php" title="Todos">Todos</a></li>', $home);
                    }else{
                        $home = str_replace('()-definiranos-()', '<li class="cat-item"><a href="noticias.php?ano='.$i.'" title="'.$i.'">'.$i.'</a></li>()-definiranos-()', $home);                        
                    }
                    $i++;
                } 
            }else{
                $home = str_replace('()-definiranos-()', '<li class="cat-item"><a href="noticias.php?ano='.$u_ano.'" title="'.$u_ano.'">'.$u_ano.'</a></li><li class="cat-item"><a href="noticias.php" title="Todos">Todos</a></li>', $home);
            }
        }else{
            $home = str_replace('()-definiranos-()', 'Nenhuma noticia foi inserida em qualquer ano.', $home);
        }
        //---------------------------------
        //define mensagem a aparecer no topo
        
        $mensagemtopo = '';
        if($ano != ''){
            $mensagemtopo .= 'Ano: '.$ano.',';
            switch($ord){
                case 'datad': $tord = ' Ordenadas por data(-)'; break;
                case 'datac': $tord = ' Ordenadas por data(+)'; break;
                case 'utilizadord': $tord = ' Ordenadas por utilizador(-)'; break;
                case 'utilizadorc': $tord = ' Ordenadas por utilizador(+)'; break;
                case 'titulod': $tord = ' Ordenadas por título(-)'; break;
                case 'tituloc': $tord = ' Ordenadas por título(+)'; break;     
            }
            $mensagemtopo .= $tord;
            
        }else{
            switch($ord){
                case 'datad': $tord = ' Ordenadas por data(-)'; break;
                case 'datac': $tord = ' Ordenadas por data(+)'; break;
                case 'utilizadord': $tord = ' Ordenadas por utilizador(-)'; break;
                case 'utilizadorc': $tord = ' Ordenadas por utilizador(+)'; break;
                case 'titulod': $tord = ' Ordenadas por título(-)'; break;
                case 'tituloc': $tord = ' Ordenadas por título(+)'; break;     
            }
            $mensagemtopo = $tord;
        }
        $home = str_replace('()-mensagemtopo-()', $mensagemtopo, $home);
        //------------------------------
        
                //------------------------------------------------------------------------------------------------------------------
        //--------------CASO 1 -> sem uma ordem escolhida no parametro-----------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //
        
        //procura o numero total de noticias a apresentar---------------------------------
        if($ano != ''){
           $query = $link_bd->query("SELECT count(*) FROM noticias WHERE datahora LIKE '$ano%'");
        }else{
           $query = $link_bd->query("SELECT count(*) FROM noticias"); 
        }
        
        if(!$query){
            die("morreu na query noticias xhu");
        }
        while($r = mysqli_fetch_assoc($query)){
            $totalnoticias = $r['count(*)'];
        }
        if($totalnoticias > 0){
            if($totalnoticias >= 3){ //verifica se há mais do que uma página
                $totalpaginas = $totalnoticias / 3;
                $totalpaginas = ceil($totalpaginas); //arredonda o numero sempre pra cima (ex: 2.43 = 3 ; 1,1 = 2;)
            }else{
                $totalpaginas = 1;
            }
        }else{
            $totalpaginas = 1;
        }
       //--------------------------------------------------------------------------------  
                 //define variavel com o numero da pagina para substituir ()totalpaginas()
        if(!isset($_GET['id'])){
           $numeropagina = 1; 
        }else if($_GET['id'] |= NULL && $_GET['id'] |= '' && $_GET['id'] <= $totalpaginas){
           $numeropagina = $_GET['id'];
        }else{
          $numeropagina = 1;  
        }
        //-----------------
        
        
        if($totalpaginas > 1){ //substitui total_paginas
            $home = str_replace("()-total_paginas-()", 'Página '.$numeropagina.' de '.$totalpaginas.', num total de '.$totalnoticias.' notícias.', $home);
        }else{
            $home = str_replace("()-total_paginas-()", 'Página '.$numeropagina.' de '.$totalpaginas.', num total de '.$totalnoticias.' notícia.', $home);
        }
        
        //substitui botoes anterior e proxima--------------------------------
        if($numeropagina < $totalpaginas){
            //substitui botao proxima
            $proximapagina = $numeropagina + 1;
            $tpl_proxima = file_get_contents('TPL/noticias/definirlink_proxima.tpl');
            
            $link_proxima = 'noticias.php?';
            if($ano != ''){
                $link_proxima .= 'ano='.$ano.'&';
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
            
            $link_anterior = 'noticias.php?';
            if($ano != ''){
                $link_anterior .= 'ano='.$ano.'&';
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
        //--------------------------------------------------------------------

        //---------------------------------------------------------------
        //encontra primeira noticia a ser colocada
        $foi1 = 0; //confirmacao que encontrou a noticia 1
        $numeronoticia = $numeropagina * 3 - 2;
        if($ano != ''){
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }else{
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }
       
        
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query na '.$numeronoticia.' noticia');
        }else if($query->num_rows >= $numeronoticia){
            //define dados da noticia
            $query->data_seek($numeronoticia-1);
            $datarow = $query->fetch_array();         
            $datahora = $datarow['datahora'];

            $nuserid = $datarow['id_utilizador'];
            $noticiaid = $datarow['id'];
            $titulo = $datarow['titulo'];
            $titulo = htmlspecialchars($titulo);
            $conteudo = $datarow['conteudo'];
            $imagem = $datarow['imagem'];
            $ncomentarios = $datarow['ncomentarios'];
            $foi1 = 1;
        }
        if($foi1 == 1){
            //define imagem
            $tpl_imagem = file_get_contents('TPL/noticias/definirimagem.tpl');
            //---------Verifica se já existe uma notícia_lista
            list($width, $height, $type, $attr) = getimagesize($imagem);
            if($width < 383 || $height < 720){


                    if(file_exists('imagens_noticias/'.$noticiaid.'_lista.jpg')){
                        $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg';
                    }else if(file_exists('imagens_noticias/'.$noticiaid.'.jpg')){
                        //cria imagem noticia_lista
                        $novaimagem = WideImage::load('imagens_noticias/'.$noticiaid.'.jpg'); 
                                      // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(720, 383, 'outside');
                                     // Guarda a imagem
                        $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg'; // ex: 5_pequena, 82_pequena
                        $novaimagem->saveToFile($imagem_lista, 40); // Coloca a imagem pequena no disco
                    }else{
                        $imagem_lista ='imagens_noticias/imagem.jpg';
                    }
            }else{
                $imagem_lista = $imagem;
            }
            //--------------
            
            $tpl_imagem = preg_replace('/()-caminhoimagem-()/', 'noticia.php?id='.$noticiaid, $tpl_imagem, 1);
            $tpl_imagem = str_replace('()-caminhoimagem-()', $imagem_lista, $tpl_imagem);
            $tpl_imagem = str_replace(' data-rel="prettyPhoto"','',$tpl_imagem);
            $tpl_imagem = str_replace('()','',$tpl_imagem);
            //define titulo
            $tpl_titulo = file_get_contents('TPL/noticias/definirtitulo.tpl');
            $tpl_titulo = str_replace('()-id_noticia-()', $noticiaid, $tpl_titulo);
            $tpl_titulo = str_replace('()-titulo-()', $titulo, $tpl_titulo);
            //define conteudo
            $tpl_conteudo = $conteudo;
            //define numero de comentarios
            if($ncomentarios > 1 || $ncomentarios == 0){
                $tpl_ncomentarios = $ncomentarios.' comentários.';
            }else{
                $tpl_ncomentarios = $ncomentarios.' comentário.';
            }
            //define utilizador que enviou
            $sql = "SELECT username FROM utilizadores WHERE id = '$nuserid' LIMIT 1";
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die('nao foi possivel executar a query na '.$numeronoticia.' noticia, na parte de encontrar o username.');
            }else if($query->num_rows == 0){
                die('nao foi encontrado um username para a noticia na base de dados');
            }else{
                while ($resultado = mysqli_fetch_assoc($query)) {
                    $nusername = $resultado['username'];
                    $tpl_user = '<a href="perfil.php?id=' .$nuserid. ' "> ' . $nusername . '</a>';
                }            
            }
            //-------------------------------------------
            //substitui valores no $home
            $home = str_replace('()-user1-()', $tpl_user, $home);
            $home = str_replace('()-titulo1-()', $tpl_titulo, $home);
            $home = str_replace('()-parte-conteudo1-()', $tpl_conteudo, $home);
            $home = str_replace('()-ncomments1-()', $tpl_ncomentarios, $home);
            $home = str_replace('()-imagem1-()', $tpl_imagem, $home);
            
            
            //encontra valores na $datahora1 e separa o ()-dia-() ()-mes-() ()-ano() ()-HH-() e ()-MM-()
            $tpl_dia = $datahora{8} . $datahora{9};
            $tpl_mes = $datahora{5} . $datahora{6};
            if(convertermes($tpl_mes)!= FALSE){
                $tpl_mes = convertermes($tpl_mes);
            }
            $tpl_ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
            $tpl_hh = $datahora{11} . $datahora{12};
            $tpl_mm = $datahora{14} . $datahora{15};                
            
            $home = str_replace('()-dia1-()', $tpl_dia, $home);
            $home = str_replace('()-mes1-()', $tpl_mes, $home);
            $home = str_replace('()-ano1-()', $tpl_ano, $home);
            $home = str_replace('()-HH1-()', $tpl_hh, $home);
            $home = str_replace('()-MM1-()', $tpl_mm, $home);    
        }
                    //---------------------------------------------------------------
        //fim da primeira noticia a ser colocada-------------------------------------
            //--------------------
            
                    //---------------------------------------------------------------
        //encontra segunda noticia a ser colocada
        $foi2 = 0; //confirmacao que encontrou a noticia 2
        $numeronoticia = $numeropagina * 3 - 1;
        if($ano != ''){
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }else{
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query na '.$numeronoticia.' noticia');
        }else if($query->num_rows >= $numeronoticia){
            //define dados da noticia
            $query->data_seek($numeronoticia-1);
            $datarow = $query->fetch_array(); 
            
            $datahora = $datarow['datahora'];
            $nuserid = $datarow['id_utilizador'];
            $noticiaid = $datarow['id'];
            $titulo = $datarow['titulo'];
            $titulo = htmlspecialchars($titulo);
            $conteudo = $datarow['conteudo'];
            $imagem = $datarow['imagem'];
            $ncomentarios = $datarow['ncomentarios'];
            $foi2 = 1;
        }
        if($foi2 == 1){
            //define imagem
            $tpl_imagem = file_get_contents('TPL/noticias/definirimagem.tpl');
                        //---------Verifica se já existe uma notícia_lista
            if(file_exists('imagens_noticias/'.$noticiaid.'_lista.jpg')){
                $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg';
            }else if(file_exists('imagens_noticias/'.$noticiaid.'.jpg')){
                //cria imagem noticia_lista
                $novaimagem = WideImage::load('imagens_noticias/'.$noticiaid.'.jpg'); 
                              // Redimensiona a imagem
                $novaimagem = $novaimagem->resize(720, 383, 'outside');
                             // Guarda a imagem
                $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg'; // ex: 5_pequena, 82_pequena
                $novaimagem->saveToFile($imagem_lista, 40); // Coloca a imagem pequena no disco
            }else{
                $imagem_lista ='imagens_noticias/imagem.jpg';
            }
            //--------------
            
            $tpl_imagem = preg_replace('/()-caminhoimagem-()/', 'noticia.php?id='.$noticiaid, $tpl_imagem, 1);
            $tpl_imagem = str_replace('()-caminhoimagem-()', $imagem_lista, $tpl_imagem);
            $tpl_imagem = str_replace(' data-rel="prettyPhoto"','',$tpl_imagem);
            $tpl_imagem = str_replace('()','',$tpl_imagem);
            //define titulo
            $tpl_titulo = file_get_contents('TPL/noticias/definirtitulo.tpl');
            $tpl_titulo = str_replace('()-id_noticia-()', $noticiaid, $tpl_titulo);
            $tpl_titulo = str_replace('()-titulo-()', $titulo, $tpl_titulo);
            //define conteudo
            $tpl_conteudo = $conteudo;
            //define numero de comentarios
            if($ncomentarios > 1 || $ncomentarios == 0){
                $tpl_ncomentarios = $ncomentarios.' comentários.';
            }else{
                $tpl_ncomentarios = $ncomentarios.' comentário.';
            }
            //define utilizador que enviou
            $sql = "SELECT username FROM utilizadores WHERE id = '$nuserid' LIMIT 1";
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die('nao foi possivel executar a query na '.$numeronoticia.' noticia, na parte de encontrar o username.');
            }else if($query->num_rows == 0){
                die('nao foi encontrado um username para a noticia na base de dados');
            }else{
                while ($resultado = mysqli_fetch_assoc($query)) {
                    $nusername = $resultado['username'];
                    $tpl_user = '<a href="perfil.php?id=' .$nuserid. ' "> ' . $nusername . '</a>';
                }            
            }
            //-------------------------------------------
            //substitui valores no $home
            $home = str_replace('()-user2-()', $tpl_user, $home);
            $home = str_replace('()-titulo2-()', $tpl_titulo, $home);
            $home = str_replace('()-parte-conteudo2-()', $tpl_conteudo, $home);
            $home = str_replace('()-ncomments2-()', $tpl_ncomentarios, $home);
            $home = str_replace('()-imagem2-()', $tpl_imagem, $home);
            
            
            //encontra valores na $datahora1 e separa o ()-dia-() ()-mes-() ()-ano() ()-HH-() e ()-MM-()
            $tpl_dia = $datahora{8} . $datahora{9};
            $tpl_mes = $datahora{5} . $datahora{6};
            if(convertermes($tpl_mes)!= FALSE){
                $tpl_mes = convertermes($tpl_mes);
            }
            $tpl_ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
            $tpl_hh = $datahora{11} . $datahora{12};
            $tpl_mm = $datahora{14} . $datahora{15};                
            
            $home = str_replace('()-dia2-()', $tpl_dia, $home);
            $home = str_replace('()-mes2-()', $tpl_mes, $home);
            $home = str_replace('()-ano2-()', $tpl_ano, $home);
            $home = str_replace('()-HH2-()', $tpl_hh, $home);
            $home = str_replace('()-MM2-()', $tpl_mm, $home);    
         }  
  
                    //---------------------------------------------------------------
        //fim da segunda noticia a ser colocada-------------------------------------
            //--------------------
        
                    
                    //---------------------------------------------------------------
        //encontra terceira noticia a ser colocada
        $foi3 = 0; //confirmacao que encontrou a noticia 3
        $numeronoticia = $numeropagina * 3;
        if($ano != ''){
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias WHERE datahora LIKE '$ano%' ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }else{
            switch($ord){
                case 'datad': $sql = "SELECT * FROM noticias ORDER BY datahora DESC LIMIT $numeronoticia"; break;
                case 'datac': $sql = "SELECT * FROM noticias ORDER BY datahora LIMIT $numeronoticia"; break;
                case 'utilizadord': $sql = "SELECT * FROM noticias ORDER BY id_utilizador DESC LIMIT $numeronoticia"; break;
                case 'utilizadorc': $sql = "SELECT * FROM noticias ORDER BY id_utilizador LIMIT $numeronoticia"; break;
                case 'titulod': $sql = "SELECT * FROM noticias ORDER BY titulo DESC LIMIT $numeronoticia"; break;
                case 'tituloc': $sql = "SELECT * FROM noticias ORDER BY titulo LIMIT $numeronoticia"; break;
            }
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query na '.$numeronoticia.' noticia');
        }else if($query->num_rows >= $numeronoticia){
            //define dados da noticia
            $query->data_seek($numeronoticia-1);
            $datarow = $query->fetch_array(); 
            
            $datahora = $datarow['datahora'];
            $nuserid = $datarow['id_utilizador'];
            $noticiaid = $datarow['id'];
            $titulo = $datarow['titulo'];
            $titulo = htmlspecialchars($titulo);
            $conteudo = $datarow['conteudo'];
            $imagem = $datarow['imagem'];
            $ncomentarios = $datarow['ncomentarios'];
            $foi3 = 1;
        }
        if($foi3 == 1){
            //define imagem
            $tpl_imagem = file_get_contents('TPL/noticias/definirimagem.tpl');
                        //---------Verifica se já existe uma notícia_lista
            if(file_exists('imagens_noticias/'.$noticiaid.'_lista.jpg')){
                $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg';
            }else if(file_exists('imagens_noticias/'.$noticiaid.'.jpg')){
                //cria imagem noticia_lista
                $novaimagem = WideImage::load('imagens_noticias/'.$noticiaid.'.jpg'); 
                              // Redimensiona a imagem
                $novaimagem = $novaimagem->resize(720, 383, 'outside');
                             // Guarda a imagem
                $imagem_lista = 'imagens_noticias/'.$noticiaid.'_lista.jpg'; // ex: 5_pequena, 82_pequena
                $novaimagem->saveToFile($imagem_lista, 40); // Coloca a imagem pequena no disco
            }else{
                $imagem_lista ='imagens_noticias/imagem.jpg';
            }
            //--------------
            
            $tpl_imagem = preg_replace('/()-caminhoimagem-()/', 'noticia.php?id='.$noticiaid, $tpl_imagem, 1);
            $tpl_imagem = str_replace('()-caminhoimagem-()', $imagem_lista, $tpl_imagem);
            $tpl_imagem = str_replace(' data-rel="prettyPhoto"','',$tpl_imagem);
            $tpl_imagem = str_replace('()','',$tpl_imagem);
            //define titulo
            $tpl_titulo = file_get_contents('TPL/noticias/definirtitulo.tpl');
            $tpl_titulo = str_replace('()-id_noticia-()', $noticiaid, $tpl_titulo);
            $tpl_titulo = str_replace('()-titulo-()', $titulo, $tpl_titulo);
            //define conteudo
            $tpl_conteudo = $conteudo;
            //define numero de comentarios
            if($ncomentarios > 1 || $ncomentarios == 0){
                $tpl_ncomentarios = $ncomentarios.' comentários.';
            }else{
                $tpl_ncomentarios = $ncomentarios.' comentário.';
            }
            //define utilizador que enviou
            $sql = "SELECT username FROM utilizadores WHERE id = '$nuserid' LIMIT 1";
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die('nao foi possivel executar a query na '.$numeronoticia.' noticia, na parte de encontrar o username.');
            }else if($query->num_rows == 0){
                die('nao foi encontrado um username para a noticia na base de dados');
            }else{
                while ($resultado = mysqli_fetch_assoc($query)) {
                    $nusername = $resultado['username'];
                    $tpl_user = '<a href="perfil.php?id=' .$nuserid. ' "> ' . $nusername . '</a>';
                }            
            }
            //-------------------------------------------
            //substitui valores no $home
            $home = str_replace('()-user3-()', $tpl_user, $home);
            $home = str_replace('()-titulo3-()', $tpl_titulo, $home);
            $home = str_replace('()-parte-conteudo3-()', $tpl_conteudo, $home);
            $home = str_replace('()-ncomments3-()', $tpl_ncomentarios, $home);
            $home = str_replace('()-imagem3-()', $tpl_imagem, $home);
            
            
            //encontra valores na $datahora1 e separa o ()-dia-() ()-mes-() ()-ano() ()-HH-() e ()-MM-()
            $tpl_dia = $datahora{8} . $datahora{9};
            $tpl_mes = $datahora{5} . $datahora{6};
            if(convertermes($tpl_mes)!= FALSE){
                $tpl_mes = convertermes($tpl_mes);
            }
            $tpl_ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
            $tpl_hh = $datahora{11} . $datahora{12};
            $tpl_mm = $datahora{14} . $datahora{15};                
            
            $home = str_replace('()-dia3-()', $tpl_dia, $home);
            $home = str_replace('()-mes3-()', $tpl_mes, $home);
            $home = str_replace('()-ano3-()', $tpl_ano, $home);
            $home = str_replace('()-HH3-()', $tpl_hh, $home);
            $home = str_replace('()-MM3-()', $tpl_mm, $home);    
         }  
  
                    //---------------------------------------------------------------
        //fim da terceira noticia a ser colocada-------------------------------------
            //--------------------
        
        
        //------------------------------------------------------------------------------------------------------------------
        //--------------Fim do CASO 1 -> sem uma ordem escolhida no parametro-----------------------------------------------------------
        //---------------------------------------------------------------------------------------------------------------
        //        
        //-------------------------------------------------------------------
        
        //--------------------verifica se houve noticias por adicionar e retira o texto que nao foi substituido
         //---
         if($foi1==0){//verifica primeira noticia
             $texto1 = '<article class="format-standard">
                                            <div class="entry-date"><div class="number">()-dia1-()</div> <div class="year">()-mes1-(), ()-ano1-()<br><br><font color="black" size="+1">()-HH1-():()-MM1-()</font></div></div>
						<div class="feature-image">
							()-imagem1-()
						</div>
						<h2  class="post-heading">()-titulo1-()</h2>
						<div class="excerpt">()-parte-conteudo1-()
						</div>
                                                
						<div class="meta">
							
							<div class="comments">()-ncomments1-()</div>
							<div class="user">Notícia enviada por: ()-user1-()</div>
						</div>
					</article>';
             $home = str_replace($texto1, '', $home);         
         }
         if($foi2 ==0){//verifica segunda noticia
             $texto2 = '<article class="format-standard">
						<div class="entry-date"><div class="number">()-dia2-()</div> <div class="year">()-mes2-(), ()-ano2-()<br><br><font color="black" size="+1">()-HH2-():()-MM2-()</font></div></div>
						<div class="feature-image">
							()-imagem2-()
						</div>
						<h2  class="post-heading">()-titulo2-()</h2>
						<div class="excerpt">()-parte-conteudo2-()
						</div>
                                                
						<div class="meta">
							<div class="comments">()-ncomments2-()</div>
							<div class="user">Notícia enviada por: ()-user2-()</div>
						</div>
					</article>';
             $home = str_replace($texto2, '', $home); 
         }
         if($foi3 ==0){//verifica terceira noticia
             $texto3 = '<article class="format-standard">
						<div class="entry-date"><div class="number">()-dia3-()</div> <div class="year">()-mes3-(), ()-ano3-()<br><br><font color="black" size="+1">()-HH3-():()-MM3-()</font></div></div>
						<div class="feature-image">
							()-imagem3-()
						</div>
						<h2  class="post-heading">()-titulo3-()</h2>
						<div class="excerpt">()-parte-conteudo3-()
						</div>
                                                
						<div class="meta">
							<div class="comments">()-ncomments3-()</div>
							<div class="user">Notícia enviada por: ()-user3-()</div>
						</div>
					</article>';
             $home = str_replace($texto3, '', $home); 
         }//nota: é preciso alterar as variaveis $texto se houver uma alteraçao em noticias.tpl
         
         
        
         




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
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

