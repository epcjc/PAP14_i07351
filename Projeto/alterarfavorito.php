<?php


include_once("includes/seguranca.php");
include_once'WideImage/lib/WideImage.php';
$_SESSION["pagina"] = $_SERVER['PHP_SELF'];
                        // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }

        $home = file_get_contents('HTML/cmain.html');
        
        //imain.html contém a página inicial com slider,  headline com informaçao, e os homeblock1 e 2.
        //main.html contém página inicial sem slider, headline dinamico para sub menu, não contém homeblocks. 
        //umain.html foi feito para para base de utilizadores.php, não contém side_headline
        
        $tpl_menu = file_get_contents('TPL/menu.tpl');
        //$tpl_headline = file_get_contents('TPL/headlinemenu.tpl');
       // $tpl_homeblock1 = file_get_contents('TPL/homeblock1.tpl');
        //$tpl_homeblock2 = file_get_contents('TPL/homeblock2.tpl');
        $tpl_footer = file_get_contents('TPL/footer.tpl');
        $tpl_footerbottom = file_get_contents('TPL/footerbottom.tpl');
        
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
                        //envia-o de volta para o index
            $_SESSION['mensagem_erro'] = 'É necessário efetuar o Login para ter acesso a essa página.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
            
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
        //$home = str_replace("()-headline-()", '', $home);
        $home = str_replace("()-homeblock1-()", '', $home);
        $home = str_replace("()-homeblock2-()", '', $home);
        $home = str_replace("()-footer-()", $tpl_footer, $home);
        $home = str_replace("()-footerbottom-()", $tpl_footerbottom, $home);
        
        //coloca tudo no sitio excepto content
        
        

        $tpl_content = file_get_contents('TPL/alterarfavorito.tpl');
        //parte da substituicao de tpl_content
        //---------
        $t = '';
        if(isset($_GET['t'])){
            switch($_GET['t']){
                case 'up': $t = 'up'; break;
                case 'ut': $t = 'ut'; break;
            }
            
        }
        if($t == ''){
            $_SESSION['mensagem_erro'] = 'Parametro inválido em adicionar favorito.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }
        if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
            $idr = $_GET['id'];
        }else{
            $_SESSION['mensagem_erro'] = 'Foi inserido um parametro inválido ao adicionar favorito.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }
        //pesquisa para verificar se ja existe favorito
        if($t == 'up'){
            $sql = 'SELECT id, nota FROM favoritos_uploads WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_upload = '.$idr.' LIMIT 1';
        }else{
            $sql = 'SELECT id, nota FROM favoritos_utilizadores WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_favorito = '.$idr.' LIMIT 1';
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu pesquisa existe nvfav ja exist");
        if($query->num_rows == 0){
            
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            
            if($t == 'up'){
                $_SESSION['mensagem_erro'] = 'Este projeto/trabalho não está nos seus favoritos.';
                $extra = 'gerirfavoritos.php';
            }else{
                $_SESSION['mensagem_erro'] = 'Este utilizador não está nos seus favoritos.';
                $extra = 'gerirfavoritos.php?ver=ut';
            }
            header ("location: http://$host$uri/$extra");
            exit;
        }else{
            $r = mysqli_fetch_assoc($query);
            $idalterar = $r['id'];
            $nota = $r['nota'];
        }

        //pesquisa para ver se existe
        if($t == 'up'){
            $sql = 'SELECT preco FROM uploads WHERE id = '.$idr.' LIMIT 1';
        }else{
            $sql = 'SELECT id, username FROM utilizadores WHERE id = '.$idr.' LIMIT 1';
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu pesquisa existe nvfavxzv f3");
        if($query->num_rows == 0){
            $_SESSION['mensagem_erro'] = 'Esse projeto ou utilizador já nao existe.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }
        if($t=='ut'){
            $r =  mysqli_fetch_assoc($query);
            $username = $r['username'];
        }
        //substitui
        if($t=='up'){
            $tpl_content=str_replace('()-info-()','<font size="5">Projeto/trabalho nº'.$idr.'</font>',$tpl_content);
            $tpl_content=str_replace('()-linkvoltar-()','gerirfavoritos.php',$tpl_content);
        }else{
            $tpl_content=str_replace('()-info-()','<font size="5">Utilizador '.$username.'</font>',$tpl_content);
            $tpl_content=str_replace('()-linkvoltar-()','gerirfavoritos.php?ver=ut',$tpl_content);
        }
        $tpl_content=str_replace('()-id-()',$idr,$tpl_content);
        $tpl_content=str_replace('()-t-()',$t,$tpl_content);
        $tpl_content = str_replace('()-nota-()', htmlspecialchars($nota), $tpl_content);
        //substitui
    /*    if($t=='up'){
            $tpl_content=str_replace('()-id-()',$idr,$tpl_content);
            $tpl_content=str_replace('()-t-()',$t,$tpl_content);
            $tpl_content=str_replace('()-tipo-()','projeto/trabalho nº',$tpl_content);
            $tpl_content=str_replace('()-info-()',$idr,$tpl_content);
            $tpl_content=str_replace('()-linkvoltar-()','trabalho.php?id='.$idr,$tpl_content);
        }else{
            $tpl_content=str_replace('()-id-()',$idr,$tpl_content);
            $tpl_content=str_replace('()-t-()',$t,$tpl_content);
            $tpl_content=str_replace('()-tipo-()','utilizador',$tpl_content);
            $tpl_content=str_replace('()-info-()',htmlspecialchars($r['username']),$tpl_content);
            $tpl_content=str_replace('()-linkvoltar-()','perfil.php?id='.$idr,$tpl_content);
        }  */
        
        //------------
        //-------------
        
        $home = str_replace("()-content-()", $tpl_content, $home);
        
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
        //
        //---------------------------------------------------------------------
        
//--------------------------------------------------------------
        
        print $home; 