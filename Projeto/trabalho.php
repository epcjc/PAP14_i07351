<?php


include_once("includes/seguranca.php");
include_once'WideImage/lib/WideImage.php';
include_once'includes/funcoes.php';
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
        
        

        $tpl_content = file_get_contents('TPL/trabalho.tpl');
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
                       //COMEÇA PARTE DA SUBSTITUITÇAO
                       //----------------------------------
                       //----------------------------------
                       if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                           $idtrabalho = $_GET['id'];
                       }else{
                           //envia de volta
                            $host = $_SERVER['HTTP_HOST'];
                            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                            $extra = 'downloads.php';
                            header ("location: http://$host$uri/$extra");
                       }
                       $sql = "SELECT * FROM uploads WHERE id = $idtrabalho";
                       $query = mysqli_query($link_bd, $sql);
                       if(!$query){
                           die("jad");
                       }
                       $foit = 0;
                       if($query->num_rows == 0){
                           $host = $_SERVER['HTTP_HOST'];
                           $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                           $extra = 'downloads.php';
                           header ("location: http://$host$uri/$extra");
                       }else{
                           while($r = mysqli_fetch_assoc($query)){
                               $datahora = $r['datahora'];
                               $idt = $r['id'];
                               $imagem = array(1=>'',2=>'',3=>'',4=>'');
                               $imagem[1] = $r['imagem1'];
                               $imagem[2] = $r['imagem2'];
                               $imagem[3] = $r['imagem3'];
                               $imagem[4] = $r['imagem4'];
                               $titulo = $r['titulo'];
                               $titulo = htmlspecialchars($titulo);
                               $preco = $r['preco'];
                               $preco = htmlspecialchars($preco);
                               $descricao = $r['descricao'];
                               $descricao = htmlspecialchars($descricao);
                               $nlikes = $r['nlikes'];
                               $ndislikes = $r['ndislikes'];
                               $categoria = $r['categoria'];
                               $categoria = htmlspecialchars($categoria);
                               $nomeorg = $r['nomeoriginal'];
                               $nomeorg = htmlspecialchars($nomeorg);
                               $ncomentarios = $r['ncomentarios'];
                               $iduser = $r['id_utilizador'];
                               $foit = 1;
                               
                           }
                       }
                       if($foit == 1){                           
                           //Verifica se foi adicionado parametro para remover favorito
                           if(isset($_GET['removerfavorito']) && $_GET['removerfavorito'] == 'sim' && isset($_SESSION['utilizador_id']) && isset($_SESSION['utilizador_username'])){
                               //remove favorito
                                $sql='SELECT id FROM favoritos_uploads WHERE id_upload='.$idt.' AND id_utilizador='.$_SESSION['utilizador_id'].' LIMIT 1';
                                $query = mysqli_query($link_bd, $sql);
                                if(!$query){
                                    die("nao deu remover favorito habsidp");
                                }
                                if($query->num_rows > 0){
                                    //apaga
                                    $proprioid = $_SESSION['utilizador_id'];
                                    $sql = "DELETE FROM favoritos_uploads WHERE id_upload=$idt AND id_utilizador=$proprioid LIMIT 1";
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
                                        die("ocorreu um erro ao apagar o favorito do utilizador");
                                    }
                                    $_SESSION['mensagem_sucesso'] = 'Este trabalho/projeto foi removido dos favoritos com sucesso. ';
                                    $host = $_SERVER['HTTP_HOST'];
                                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                                    $extra = 'trabalho.php?id='.$idt;
                                    header ("location: http://$host$uri/$extra");
                                }else{
                                    $_SESSION['mensagem_sucesso'] = 'Este trabalho/projeto não está nos seus favoritos. ';
                                    $host = $_SERVER['HTTP_HOST'];
                                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                                    $extra = 'trabalho.php?id='.$idt;
                                    header ("location: http://$host$uri/$extra");
                                }
                                
                                
                           }else if(isset($_GET['removerfavorito']) && isset($_SESSION['utilizador_id'])){
                                    $_SESSION['mensagem_erro'] = 'Não foi possível validar os parametros ao remover o favorito. ';
                                    $host = $_SERVER['HTTP_HOST'];
                                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                                    $extra = 'trabalho.php?id='.$idt;
                                    header ("location: http://$host$uri/$extra");
                            }else if(isset($_GET['removerfavorito']) && !isset($_SESSION['utilizador_id'])){
                                    $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para remover favoritos. ';
                                    $host = $_SERVER['HTTP_HOST'];
                                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                                    $extra = 'trabalho.php?id='.$idt;
                                    header ("location: http://$host$uri/$extra");
        }
                           
                        //substitui
                           //nomeoriginal
                           $home = str_replace('()-nomeoriginal-()',$nomeorg, $home);
                           
                           ///datahora
                           //define data 
                            $tpl_dia = $datahora{8} . $datahora{9};
                            $tpl_mes = $datahora{5} . $datahora{6};
                            if(convertermes($tpl_mes)!= FALSE){
                                $tpl_mes = convertermes($tpl_mes);
                            }
                            $tpl_ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
                            $tpl_hh = $datahora{11} . $datahora{12};
                            $tpl_mm = $datahora{14} . $datahora{15};                

                            $home = str_replace('()-dia-()', $tpl_dia, $home);
                            $home = str_replace('()-mes-()', $tpl_mes, $home);
                            $home = str_replace('()-ano-()', $tpl_ano, $home);
                            $home = str_replace('()-HH-()', $tpl_hh, $home);
                            $home = str_replace('()-MM-()', $tpl_mm, $home);
                           //--------
                           //id
                           $home = str_replace('()-trabalho_id-()', 'Projeto/trabalho nº'.$idt, $home);
                           $home = str_replace('()-trabalho_id2-()', $idt, $home);
                           //----------
                           //imagens
                           $foiima = array(1=>0,2=>0,3=>0,4=>0);
                           if($imagem[1] != NULL && $imagem[1] != '' && $imagem[1] != 'upload/imagem.jpg'){
                               $foiima[1] = 1;
                           }
                           if($imagem[2] != NULL && $imagem[2] != '' && $imagem[2] != 'upload/imagem.jpg'){
                               $foiima[2] = 1;
                           }
                           if($imagem[3] != NULL && $imagem[3] != '' && $imagem[3] != 'upload/imagem.jpg'){
                               $foiima[3] = 1;
                           }
                           if($imagem[4] != NULL && $imagem[4] != '' && $imagem[4] != 'upload/imagem.jpg'){
                               $foiima[4] = 1;
                           }
                           if($foiima[1]==0 && $foiima[2]==0 && $foiima[3]==0 && $foiima[4]==0){
                               //coloca apenas uma imagem default na pagina
                               $home = str_replace('()-caminhoimagem1-()','upload/imagem.jpg',$home);
                               $home = str_replace('HTML/img/slides/01.jpg','upload/imagem.jpg',$home);
                               $i=2;
                               while($i < 5){
                                   $home = str_replace('<li><a href="()-caminhoimagem'.$i.'-()" data-rel="prettyPhoto" title=""><img src="HTML/img/slides/0'.$i.'.jpg" alt="" /></a></li>','',$home);
                                   $i++;
                               }
                               
                           }else{
                               //coloca as imagens disponiveis
                               $i = 1;
                               while($i < 5){
                                   if($foiima[$i] == 1){
                                       //substitui por imagem
                                       $checkimg = substr($imagem[$i], 0, -4);
                                       $checkimg = $checkimg.'_perfil.jpg';
                                       if(file_exists($checkimg)){
                                           $subimagem = $checkimg;
                                       }else if(file_exists($imagem[$i])){
                                           //cria imagem
                                           $novaimagem = WideImage::load($imagem[$i]); 
                                            // Redimensiona a imagem
                                            $novaimagem = $novaimagem->resize(960, 473, 'outside');
                                            $novaimagem = $novaimagem->crop('center', 'center', 960, 473);
                                                         // Guarda a imagem
                                            $subimagem = $checkimg; // ex: 5_pequena, 82_pequena
                                            $novaimagem->saveToFile($subimagem, 40); // Coloca a imagem pequena no disco
                                       }else{
                                          //aplica default
                                           $subimagem = 'upload/imagem.jpg';
                                       }
                                       $home = str_replace('HTML/img/slides/0'.$i.'.jpg',$subimagem,$home);
                                       $home = str_replace('()-caminhoimagem'.$i.'-()', $imagem[$i], $home);
                                   }else{
                                       //apaga
                                       $home = str_replace('<li><a href="()-caminhoimagem'.$i.'-()" data-rel="prettyPhoto" title=""><img src="HTML/img/slides/0'.$i.'.jpg" alt="" /></a></li>','',$home);
                                   }
                                   $i++;
                               }
                           }
                           
                           //-----------
                           //titulo
                           $home = str_replace('()-titulo-()', $titulo, $home);
                           
                           //--------------
                           //preco
                           if($preco == NULL || $preco == 0 || $preco == ''){
                               $home = str_replace('()-preco-()', 'Gratuito -', $home);
                           }else{
                               $home = str_replace('()-preco-()', 'Preço: '.$preco.' € -', $home);
                           }
                           //--------------
                           //descricao
                           $home = str_replace('()-descricao-()', $descricao, $home);
                           //---------------
                           //rating
                           if($nlikes == 0 && $ndislikes == 0){
                               $home = str_replace('()-rating-()', 'Este trabalho/projeto ainda não foi avaliado.', $home);
                           }else if($nlikes == 1 && $ndislikes == 0){
                               $home = str_replace('()-rating-()', '1 utilizador gosta disto.',$home);
                           }else if($nlikes == 0 && $ndislikes == 1){
                               $home = str_replace('()-rating-()', '1 utilizador não gosta disto.',$home);
                           }else if($nlikes == 1 && $ndislikes == 1){
                               $home = str_replace('()-rating-()', '1 utilizador gosta disto, e 1 não gosta.',$home);
                           }else if($nlikes == 1 && $ndislikes > 1){
                               $home = str_replace('()-rating-()', '1 utilizador gosta disto, e '.$ndislikes.' não gostam.',$home);
                           }else if($nlikes > 1 && $ndislikes == 1){                               
                               $home = str_replace('()-rating-()', $nlikes.' utilizadores gostam disto, e 1 não gosta.',$home);                               
                           }else if($nlikes > 1 && $ndislikes == 0){                               
                               $home = str_replace('()-rating-()', $nlikes.' utilizadores gostam disto.',$home);                               
                           }else if($nlikes == 0 && $ndislikes > 1){                               
                               $home = str_replace('()-rating-()', $ndislikes.' utilizadores não gostam disto.',$home);                               
                           }else{
                               $home = str_replace('()-rating-()',$nlikes.' utilizadores gostam disto, e '.$ndislikes.' não gostam.', $home);
                           }
                           
                           //---------------
                           //categoria
                           switch($categoria){
                                case 'video': $tcat = 'Video'; break;
                                case 'software': $tcat = 'Software'; break;
                                case 'imagem': $tcat = 'Imagem'; break;
                                case '3d': $tcat = 'Modelação 3d'; break;
                                case 'outras': $tcat = 'Outras categorias'; break;
                                case 'audio': $tcat = 'Audio'; break;
                            }
                            $home = str_replace('()-categoria-()', $tcat, $home);
                           //-----------
                           //iduser e nomeuser
                           $sql = "SELECT username FROM utilizadores WHERE id = $iduser";
                           $query = mysqli_query($link_bd, $sql);
                           if(!$query){
                               die("no query trabalho dajsd");
                           }
                           while($r=  mysqli_fetch_assoc($query)){
                               $nomeuser= $r['username'];
                               $nomeuser = htmlspecialchars($nomeuser);
                           }
                           $home = str_replace('()-linkuser-()', 'perfil.php?id='.$iduser, $home);
                           $home = str_replace('()-user-()', $nomeuser, $home);
                           //------------------
                           //ncomentarios
                           if($ncomentarios == 1){
                               $home = str_replace('()-ncomentarios-()', '1 comentário', $home);
                           }else{
                               $home = str_replace('()-ncomentarios-()', $ncomentarios.' comentários', $home);
                           }
                            //---------------------
                           //---------linkvotosim linkvotonao linkreportar linkdownload
                           $home = str_replace('()-linkvotosim-()', 'votar.php?id='.$idt.'&v=s', $home);
                           $home = str_replace('()-linkvotonao-()', 'votar.php?id='.$idt.'&v=n', $home);
                           $home = str_replace('()-linkreportar-()', 'novoreport.php?t=up&id='.$idt, $home);
                           $home = str_replace('()-linkfavorito-()', 'novofavorito.php?t=up&id='.$idt, $home);
                           $home = str_replace('()-linkdownload-()', 'download.php?id='.$idt, $home);
                       }else{
                           //rencaminha para downloads
                           $host = $_SERVER['HTTP_HOST'];
                           $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                           $extra = 'downloads.php';
                           header ("location: http://$host$uri/$extra");
                       }
                       
                       if(isset($_SESSION['utilizador_id']) && isset($_SESSION['utilizador_username'])){
                       //verifica se utilizador ja votou neste trabalho
                           $sql = 'SELECT voto FROM votacoes WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_upload = '.$idt.' LIMIT 1';
                           $query = mysqli_query($link_bd, $sql);
                           if(!$query) die("nao deu query verificar votos basdk");
                           if($query->num_rows > 0){
                               //ja votou
                               $r = mysqli_fetch_assoc($query);
                               $voto = $r['voto'];
                               $txtvotosub = '<li class="cat-item"><a href="votar.php?id='.$idt.'&v=s" title="Ajude-nos a avaliar este projeto/trabalho">Gosto</a></li>
								<li class="cat-item"><a href="votar.php?id='.$idt.'&v=n" title="Ajude-nos a avaliar este projeto/trabalho">Não gosto</a></li>';
                               if($voto == 's'){
                                    $home = str_replace($txtvotosub, '<li class="cat-item">Gostei disto (<a href="votar.php?id='.$idt.'&v=r" onclick="return confirmar_removervoto(); return FALSE;"><font size="1">Remover voto</font></a>)</li>', $home);
                               }else{
                                    $home = str_replace($txtvotosub, '<li class="cat-item">Não gostei disto (<a href="votar.php?id='.$idt.'&v=r" onclick="return confirmar_removervoto(); return FALSE;"><font size="1">Remover voto</font></a>)</li>', $home);
                               }
                           }
                       //----------------------------------
                       //Verifica se utilizador já adicionou este trabalho aos favoritos
                           $sql = 'SELECT id FROM favoritos_uploads WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_upload = '.$idt.' LIMIT 1';
                           $query = mysqli_query($link_bd, $sql);
                           if(!$query) die("nao deu query verificar favoritos basdk");
                           if($query->num_rows > 0){
                               //ja adicionou
                               $txtfavsub = '<a href="novofavorito.php?t=up&id='.$idt.'" title="Adicione este projeto/trabalho aos seus favoritos">Adicionar aos Favoritos</a>';
                               $home = str_replace($txtfavsub,'<a href="trabalho.php?id='.$idt.'&removerfavorito=sim" title="Remover este projeto/trabalho dos seus favoritos" onclick="return confirmar_removerfavorito(); return FALSE;">Remover dos Favoritos</a>',$home);
                               
                           }
                       }
                       //----------------------------------
                       //----------------------------------
                       //ACABA PARTE DA SUBSTITUICAO
                       //----------------------------------
                       //----------------------------------
                      //COMEÇA PARTE DA SUBSTITUICAO DOS COMENTARIOS
                      //---------------------------------- 
        $sql = 'SELECT count(*) FROM comentarios_uploads WHERE id_upload = '.$idt;
        $query = mysqli_query($link_bd,$sql);
        if(!$query){
            die("morreu comentario npkdaddsod trabalho.pgp");
        }
        while($r = mysqli_fetch_assoc($query)){
            $totalcomentarios = $r['count(*)'];
        }
        if($totalcomentarios > 5){
            $totalpaginas = ceil($totalcomentarios/5);
        }else{
            $totalpaginas = 1;
        }
        
        //verifica em q pagina estamos
        if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= $totalpaginas){
            $pagina = $_GET['p'];
        }else{
            $pagina = 1;
        }
        //substitui botoes proxima anterior totalpaginas e linkcomentar
        if($totalcomentarios > 0){
            //proxima
            $proxpag = $pagina + 1;
            if($totalpaginas > $pagina){
                $home = str_replace('()-proxima-()','<a href="trabalho.php?id='.$idt.'&p='.$proxpag.'">Próxima &#8594;</a>',$home);
            }else{
                $home = str_replace('()-proxima-()','',$home);
            }
            //anterior
            $antpag = $pagina - 1;
            if($pagina > 1){
                $home = str_replace('()-anterior-()','<a href="trabalho.php?id='.$idt.'&p='.$antpag.'">&#8592; Anterior</a>',$home);
            }else{
                $home = str_replace('()-anterior-()','',$home);
            }
            //totalpaginas
            if($totalcomentarios > 1){
                $home = str_replace('()-totalpaginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de '.$totalcomentarios.' comentários.', $home);
            }else{
                $home = str_replace('()-totalpaginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de '.$totalcomentarios.' comentário.', $home);                
            }
        }else{
            $home = str_replace('()-anterior-() ()-totalpaginas-() ()-proxima-()', '<strong>Ainda não há comentários.</strong>', $home);
        }
                  //linkcomentar
            $home = str_replace('()-linkcomentar-()', 'comentar.php?t=u&id='.$idt, $home);
        
        //verifica o numero do primeiro comentario a apresentar
        $p_comentario = $pagina * 5 - 5;
        
        $cid = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cuserid = array(1=>'',2=>'',3=>'',4=>'',5=>'');        
        $cdatahora = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cnreports = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cconteudo = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cfoi = array(1=>0,2=>0,3=>0,4=>0,5=>0);
        
//pesquisa sql para guardar os valores dos comentarios
        $sql = 'SELECT * FROM comentarios_uploads WHERE id_upload='.$idt.' ORDER BY datahora DESC LIMIT '.$p_comentario.', 5';
        $query = mysqli_query($link_bd,$sql);
        $i = 0;
        while ($i < 5){
            if($query->num_rows > $i){         
            //define dados das noticias 
                    $query->data_seek($i);
                    $datarow = $query->fetch_array();
                    $cid[$i+1] = $datarow['id'];
                    $cuserid[$i+1] = $datarow['id_utilizador'];
                    $cdatahora[$i+1] = $datarow['datahora'];
                    $cnreports[$i+1] = $datarow['nreports'];
                    $cconteudo[$i+1] = $datarow['conteudo'];
                    $cconteudo[$i+1] = htmlspecialchars($cconteudo[$i+1]);
                    $cfoi[$i+1] = 1;
                    $i++;
            }else{
                $i = 5;
            }
            
        }
        //define maxusrs
        $sql = "SELECT count(*) FROM utilizadores";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("jasdasdoas");
        }
        while($r = mysqli_fetch_assoc($query)){
            $maxusrs = $r['count(*)'];
        }
        //ciclo para substituir
        $i = 1;
        while($i < 6){
            if($cfoi[$i]==1){
                //substitui imagemci nomeci data linkreportarci comentarioi
                //imagem
                $sql='SELECT imagem, username FROM utilizadores WHERE id = '.$cuserid[$i].' LIMIT 1';
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die("aoksdasdasd");
                }
                while($r = mysqli_fetch_assoc($query)){
                    $imagem2 = $r['imagem'];
                    $user = $r['username'];
                }
                
                if(isset($imagem2) && $imagem2!=NULL && $imagem2!=''){

                    if($imagem2 == 'imagens_utilizadores/imagem.jpg' || $imagem2 == '' || $imagem2 == NULL){
                        $imagemfinal = 'imagens_utilizadores/imagem_miniatura.jpg';
                    }else{
                        if(file_exists('imagens_utilizadores/'.$cuserid[$i].'_miniatura.jpg')){
                            $imagemfinal = 'imagens_utilizadores/'.$cuserid[$i].'_miniatura.jpg';
                        }else if(file_exists($imagem2)){
                            //cria imagem_miniatura 
                            //redimensiona a imagem e guarda-a como _miniatura.jpg
                                    $novaimagem = WideImage::loadFromFile($imagem2);
                               // Redimensiona a imagem
                                    $novaimagem = $novaimagem->resize(52, 52, 'fill');
                                    // Guarda a imagem
                                    $imagemfinal = 'imagens_utilizadores/'.$cuserid[$i].'_miniatura.jpg'; // ex: 5_miniatura, 82_miniatura
                                    $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem pequena no disco
                        }else{
                            $imagemfinal = 'imagens_utilizadores/imagem_miniatura.jpg';
                        }
                    }
                }else{
                    $imagemfinal = 'imagens_utilizadores/imagem_miniatura.jpg';
                }
                $home = str_replace('()-imagemc'.$i.'-()',$imagemfinal,$home);
                if(isset($user) && $user != '' && $user != NULL){
                    $home = str_replace('()-nomec'.$i.'-()','<a href="perfil.php?id='.$cuserid[$i].'">'.$user.'</a>',$home);
                }else{
                    $home = str_replace('()-nomec'.$i.'-()','Utilizador removido',$home);
                }
                //pesquisa para saber se utilizador ja reportou este comentario
                $jareportou = FALSE;
                if(isset($_SESSION['utilizador_id']) && isset($_SESSION['utilizador_username'])){
                    $sqlcheck = 'SELECT id FROM reports_cuploads WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_comentario = '.$cid[$i].' LIMIT 1';
                    $querycheck = mysqli_query($link_bd, $sqlcheck);
                    if(!$querycheck) die("nao deu saber se ja reportou comentario");
                    if($querycheck->num_rows > 0){
                        //ja reportou
                        $jareportou = TRUE;
                    }
                }
                //llinkreportarc
                if(isset($_SESSION['utilizador_id']) && $cuserid[$i] == $_SESSION['utilizador_id']){
                    $home = str_replace('<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="()-linkreportarc'.$i.'-()">Denunciar comentário</a></span>', '', $home);
                }else if($jareportou == TRUE){
                    $home = str_replace('<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="()-linkreportarc'.$i.'-()">Denunciar comentário</a></span>', '<span class="comment-reply-link-wrap">Denunciei este comentário</span>', $home);
                }else{
                    $home=str_replace('()-linkreportarc'.$i.'-()','reportarcomentario.php?t=u&id='.$cid[$i],$home);
                }
                //comentarioi
                $limitreport = ceil($maxusrs * 0.05);
                //limitador
                if($limitreport > 1000){
                    $limitreport = 1000;
                }
                if($limitreport <= 1){
                    $limitreport = 2;
                }


                
                if($limitreport > $cnreports[$i]){
                    $home = str_replace('()-comentario'.$i.'-()',$cconteudo[$i],$home);
                }else{
                    $home = str_replace('()-comentario'.$i.'-()','<div align="center"><font color="#400000">-este comentário atingiu o limite de denúncias e foi ocultado-</font></div>',$home);
                    $home = str_replace('<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="reportarcomentario.php?t=u&id='.$cid[$i].'">Denunciar comentário</a></span>','',$home);
                }
                
                //data
                //------------define data
                $cdia = $cdatahora[$i]{8} . $cdatahora[$i]{9};
                $cmes = $cdatahora[$i]{5} . $cdatahora[$i]{6};
                if(convertermes($cmes)!= FALSE){
                    $cmes = convertermes($cmes);
                }
                $cano = $cdatahora[$i]{0} . $cdatahora[$i]{1} . $cdatahora[$i]{2} . $cdatahora[$i]{3};
                $chh = $cdatahora[$i]{11} . $cdatahora[$i]{12};
                $cmm = $cdatahora[$i]{14} . $cdatahora[$i]{15};
                //substitui data
                $home = str_replace('()-diac'.$i.'-()', $cdia, $home);
                $home = str_replace('()-mesc'.$i.'-()', $cmes, $home);
                $home = str_replace('()-anoc'.$i.'-()', $cano, $home);
                $home = str_replace('()-HHc'.$i.'-()', $chh, $home);
                $home = str_replace('()-MMc'.$i.'-()', $cmm, $home);
                //----------------------
            }else{
                //subtitui por nada
                $tplcsub = '<li class="comment even thread-even depth-1" id="li-comment-'.$i.'">
									
									<div id="comment-'.$i.'" class="comment-body clearfix">
								     	<img alt="" src="()-imagemc'.$i.'-()" class="avatar avatar-35 photo" height="35" width="35" />      
								     	<div class="comment-author vcard">()-nomec'.$i.'-()</div>
								        <div class="comment-meta commentmetadata">
									  		<span class="comment-date">()-diac'.$i.'-() ()-mesc'.$i.'-(), ()-anoc'.$i.'-() ()-HHc'.$i.'-():()-MMc'.$i.'-()</span>
                                                                                        <span class="comment-reply-link-wrap"><a class="comment-reply-link" href="()-linkreportarc'.$i.'-()">Denunciar comentário</a></span>
										</div>
								  		<div class="comment-inner">
									   		<p>()-comentario'.$i.'-()</p>
								 		</div>
                                                                        </div>
																
								</li>';
                $home = str_replace($tplcsub,'',$home);
                
            }
            $i++;
        }
        //apaga botoes denunciar e form comentar se nao estiver logado
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
            $tplformc = '<div id="respond">
							
							<form action="comentar.php?t=u&id='.$idt.'" method="post" id="commentform">
								<h3 class="heading">Deixe um comentário</h3>
							
                                                                <textarea name="comment" id="comment"  tabindex="4" maxlength="2000"></textarea>
								<br/><br/>
                                                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                                                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                                                <a href="#" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false">[ Imagem diferente ]</a>	
								<br/><input name="submit" type="submit" id="submit" tabindex="5" value="Enviar" />
									
							</form>
						</div>
						<div class="clearfix"></div>';
            $home = str_replace($tplformc,'',$home);
            $i = 1;
            while ($i < 6){
                if($cfoi[$i]==1){
                    $botaod = '<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="reportarcomentario.php?t=u&id='.$cid[$i].'">Denunciar comentário</a></span>';
                    $home = str_replace($botaod,'',$home);
                    $i++;
                }else{
                    $i = 6;
                }
                
            }
        }
                       //----------------------------------
                      //ACABAPARTE DA SUBSTITUICAO DOS COMENTARIOS
                       //----------------------------------
        
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){ 
        //apaga menu votacoes, favorito e reportar
            $subvotacao = '<h4>VOTAÇÃO</h4>
							<ul>
								<li class="cat-item"><a href="votar.php?id='.$idt.'&v=s" title="Ajude-nos a avaliar este projeto/trabalho">Gosto</a></li>
								<li class="cat-item"><a href="votar.php?id='.$idt.'&v=n" title="Ajude-nos a avaliar este projeto/trabalho">Não gosto</a></li>
                                                        </ul>
                                        
                                                <br/>';
            $subfavorito = '<li class="cat-item"><a href="novofavorito.php?t=up&id='.$idt.'" title="Adicione este projeto/trabalho aos seus favoritos">Adicionar aos Favoritos</a></li>';
            $subreport = '<li class="cat-item"><a href="novoreport.php?t=up&id='.$idt.'" title="Denunciar este projeto/trabalho">Denunciar</a></li>';
            $home = str_replace($subvotacao,'',$home);
            $home = str_replace($subfavorito, '', $home);
            $home = str_replace($subreport, '', $home);
        }else if($_SESSION['utilizador_id'] == $iduser){
            $txtsub = '<li class="cat-item"><a href="novofavorito.php?t=up&id='.$idt.'" title="Adicione este projeto/trabalho aos seus favoritos">Adicionar aos Favoritos</a></li>';
            
            $home = str_replace($txtsub,'<li class="cat-item"><a href="alterarprojeto.php?id='.$idt.'" title="Editar este projeto/trabalho">Editar</a></li>',$home);
            $home = str_replace('<li class="cat-item"><a href="novoreport.php?t=up&id='.$idt.'" title="Denunciar este projeto/trabalho">Denunciar</a></li>','',$home);
        }

        
        
        print $home; 
