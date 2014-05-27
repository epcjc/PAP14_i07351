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

        $home = file_get_contents('HTML/umain.html');
        
                 //verificar se existe uma mensagem de erro para apresentar
            $mensagemerro = '';
            if(isset($_SESSION['mensagem_erro'])){
                if($_SESSION['mensagem_erro'] != ''){
                    $mensagemerro = $_SESSION['mensagem_erro'];
                    $home = str_replace("()-mensagemerro-()", $mensagemerro, $home);
                    $_SESSION['mensagem_erro'] = '';
                }else{
                    $home = str_replace("()-mensagemerro-()", $mensagemerro, $home);
                }
            
            }else{
                $home = str_replace("()-mensagemerro-()", $mensagemerro, $home);
            }
               //--------------------------   
        
         //verificar se existe uma mensagem de sucesso para apresentar
            $mensagemsucesso = '';
            if(isset($_SESSION['mensagem_sucesso'])){
                if($_SESSION['mensagem_sucesso'] != ''){
                    $mensagemsucesso = $_SESSION['mensagem_sucesso'];
                    $home = str_replace("()-mensagemsucesso-()", $mensagemsucesso, $home);
                    $_SESSION['mensagem_sucesso'] = '';
                }else{
                    $home = str_replace("<br>()-mensagemsucesso-()", $mensagemsucesso, $home);
                }
            
            }else{
                $home = str_replace("<br>()-mensagemsucesso-()", $mensagemsucesso, $home);
            }
               //--------------------------
        
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
        
        

        $tpl_content = file_get_contents('TPL/download.tpl');
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
        //-------COMEÇA DEFINIÇAO DA PáGINA DE DOWNLOAD--------------------------------------
       //------------------------------------------------------------------------
       //------------------------------------------------------------------------

                if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                    $idupl = $_GET['id'];
                }else{
                    $_SESSION['mensagem_erro'] = 'Houve um parâmetro inválido inserido na página de download.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
                }
                //verifica se existe na bd
                $sql = 'SELECT titulo, preco, id_utilizador, nomeoriginal, size, type FROM uploads WHERE id = '.$idupl.' LIMIT 1';
                $query = mysqli_query($link_bd, $sql);
                if(!$query) die("nao deu para ver o preco");
                if($query->num_rows == 0){
                    $_SESSION['mensagem_erro'] = 'Um parâmetro inválido foi inserido na página de download.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
                }
                $r = mysqli_fetch_assoc($query);
                $titulo = htmlspecialchars($r['titulo']);
                $preco = htmlspecialchars($r['preco']);
                $idusr = $r['id_utilizador'];
                $nomeorg = htmlspecialchars($r['nomeoriginal']);
                $tamanho = $r['size'];
                $tipo = $r['type'];
                
                $podedownload = FALSE;
                if($preco == 0 || $preco == NULL || $preco == ''){
                    $podedownload = TRUE;
                }else{
                    if(!isset($_SESSION['utilizador_id'])){
                        $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ter acesso a este download.';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'trabalho.php?id='.$idupl;
                        header ("location: http://$host$uri/$extra");
                        exit;
                    }
                    //verifica se utilizador logado comprou este trab
                    $sql = 'SELECT id FROM compras WHERE id_upload = '.$idupl.' AND id_comprador = '.$_SESSION['utilizador_id'].' AND confirmacaoC = 1 AND confirmacaoV = 1 LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu verificar compra");
                    if($query->num_rows > 0){
                        $podedownload = TRUE;
                        $r = mysqli_fetch_assoc($query);
                        $idcompra = $r['id'];
                    }else if($idusr == $_SESSION['utilizador_id']){
                        $podedownload = TRUE;
                    }
                }
                if($podedownload == FALSE){//guarda codigo_compra e id se o vendedor ja tiver confirmado
                    $sql = 'SELECT id, codigo_compra FROM compras WHERE id_upload = '.$idupl.' AND id_comprador = '.$_SESSION['utilizador_id'].' AND confirmacaoV = 1 LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu guardar codigo e id");
                    if($query->num_rows > 0){
                        $r = mysqli_fetch_assoc($query);
                        $idcompra = $r['id'];
                        $codigo = $r['codigo_compra'];
                    }
                }
                
                //guarda email do user q fez o upload
                $sql = 'SELECT email FROM utilizadores WHERE id = '.$idusr.' LIMIT 1';
                $query = mysqli_query($link_bd, $sql);
                if(!$query) die("nao deu guardar email");
                $r = mysqli_fetch_assoc($query);
                if($query->num_rows > 0){
                    $emailvendedor = $r['email'];
                }else if($podedownload == FALSE){
                    $_SESSION['mensagem_erro'] = 'Lamentamos que o utilizador que enviou esse trabalho/projeto já não exista.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
                    exit;
                }
                
                if($podedownload == TRUE){
                    //apresenta download ao utilizador
                    $home = str_replace('()-conteudo-()', 'Agradecemos a sua participação. O seu download irá começar dentro de instantes.', $home);
                    if($preco > 0){
                        //apresenta ficheiro que esta protegido na bd
                        
                        $sql = 'SELECT ficheiro FROM uploads_protegidos WHERE id_upload = '.$idupl.' LIMIT 1';
                        $query = mysqli_query($link_bd, $sql);
                        if(!$query || $query->num_rows == 0) die("nao deu para apresentar ficheiro protegido");
                        $r = mysqli_fetch_assoc($query);
                        $file = $r['ficheiro'];
                        header("Content-length: ".strlen($file));
                        header("Content-type: $tipo");
                        header("Content-disposition: download; filename=$nomeorg"); //disposition of download forces a download
                        echo $file; 
                    }else if(file_exists('upload/'.$idusr.'/'.$nomeorg.'/'.$nomeorg)){
                        //apresenta ficheiro que esta no disco
                        $file = 'upload/'.$idusr.'/'.$nomeorg.'/'.$nomeorg;
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($file).'"');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                    }

                    
                }else{
                    //download bloqueado, apresenta opcao para o utilizador comprar
                    //Verifica se foi já foi solicitada uma compra ao vendedor e se o vendedor já aceitou
                    $sql = 'SELECT confirmacaoV FROM compras WHERE id_upload = '.$idupl.' AND id_comprador = '.$_SESSION['utilizador_id'];
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu solicitaocao verificar");
                    $r = mysqli_fetch_assoc($query);
                    if($query->num_rows > 0 && $r['confirmacaoV'] == 1){
                        $conteudo = '<br/>A sua solicitação de compra já foi aceite pelo autor deste projeto/trabalho. Clique no link abaixo para finalizar esta compra. <br/><br/>';
                        $conteudo .= '<div align="center"><form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="business" value="'.$emailvendedor.'">
                                    <input type="hidden" name="currency_code" value="EUR">
                                    <input type="hidden" name="item_name" value="Digiart - Download Digital número '.$idupl.' - '.$titulo.'">
                                    <input type="hidden" name="amount" value="'.$preco.'">
                                    <input type="hidden" name="return" value="http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/trabalho.php?id='.$idupl.'">
                                    <input type="hidden" name="notify_url" value="http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/ipn.php?compra='.$idcompra.'&c='.$codigo.'">
                                    <input type="image" src="http://www.paypal.com/pt_BR/i/btn/x-click-but01.gif" 
                                        border="0" name="submit" alt="Faça pagamentos online com Paypal - É fácil, gratuito, e seguro!">
                                </form></div>';
                        $home = str_replace('()-conteudo-()', $conteudo,$home);
                    }else if($query->num_rows > 0){
                        $home = str_replace('()-conteudo-()', '<br/>Já foi enviada uma solicitação de compra ao autor deste projeto/trabalho. Por favor, aguarde que esta seja aceite, para que a compra possa ser finalizada.',$home);
                    }else{
                        $home = str_replace('()-conteudo-()', '<br/>Este trabalho/projeto encontra-se definido com o preço de '.$preco.'€. Estará disponível para download após a sua compra ser efetuada. <br/><br/><div align="center"><font size = 3><a href="comprar.php?id='.$idupl.'">Efetuar compra</a></font></div>',$home);
                    }
                    
                }
                  $home = str_replace('()-linkvoltar-()', 'trabalho.php?id='.$idupl,$home);  
                  $home = str_replace('()-titulo-()', 'Projeto/trabalho nº'.$idupl,$home);     
       //------------------------------------------------------------------------
       //---------------------ACABA DEFINIÇAO DA PAGINA DE DOWNLOAD----------------------
        //------------------------------------------------------------------------
                       
        print $home; 
