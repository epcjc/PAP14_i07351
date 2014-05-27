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
            
                        //envia para index.php
             //envia-o de volta para o index
                    $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para ver essa página.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");
            
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
        
        
        $ver = 'up';
        if(isset($_GET['ver']) && $_GET['ver'] == 'ut'){
            $ver = 'ut';
        }
        if($ver == 'up'){
            $tpl_content = file_get_contents('TPL/gerirfavoritos_up.tpl');
        }else{
            $tpl_content = file_get_contents('TPL/gerirfavoritos_ut.tpl');
        }
        
        //verifica se o parametro apagar foi adicionado ao url
        //e apaga
        if(isset($_GET['apagar']) && is_numeric($_GET['apagar']) && $_GET['apagar'] > 0){
            $idapagar = mysqli_real_escape_string($link_bd, $_GET['apagar']);
            if($ver == 'up'){
                $sql = 'SELECT id FROM favoritos_uploads WHERE id = '.$idapagar.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
            }else{
                $sql = 'SELECT id FROM favoritos_utilizadores WHERE id = '.$idapagar.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
            }
            $query = mysqli_query($link_bd, $sql);
            if(!$query) die("um erro em apagar favorito 1");
            if($query->num_rows == 0){
                $_SESSION['mensagemfavoritos'] = 'Um parametro inválido foi inserido ao apagar o favorito.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    if($ver == 'up'){
                        $extra = 'gerirfavoritos.php';
                    }else{
                        $extra = 'gerirfavoritos.php?ver=ut';
                    }
                    header ("location: http://$host$uri/$extra");
                    exit;
            }
            //apaga e reencaminha-o de volta
                    if($ver == 'up'){
                        $sql = 'DELETE FROM favoritos_uploads WHERE id='.$idapagar.' AND id_utilizador='.$_SESSION['utilizador_id'].' LIMIT 1';
                    }else{
                        $sql = 'DELETE FROM favoritos_utilizadores WHERE id='.$idapagar.' AND id_utilizador='.$_SESSION['utilizador_id'].' LIMIT 1';
                    }
                    
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
                        die("ocorreu um erro ao apagar o favorito");
                    }
                       //reencaminha
                    $_SESSION['mensagemfavoritos'] = 'O favorito foi apagado com sucesso';
                    
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    if($ver == 'up'){
                        $extra = 'gerirfavoritos.php';
                    }else{
                        $extra = 'gerirfavoritos.php?ver=ut';
                    }
                    header ("location: http://$host$uri/$extra");
                    exit;
            //------
            
        }else{
            if(isset($_GET['apagar'])){
                    $_SESSION['mensagemfavoritos'] = 'Foi inserido um parametro inválido ao apagar o favorito.';
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    if($ver == 'up'){
                        $extra = 'gerirfavoritos.php';
                    }else{
                        $extra = 'gerirfavoritos.php?ver=ut';
                    }
                    header ("location: http://$host$uri/$extra");
            }
        }
        
//-------------------------------------------------------------        
//-------começa definiçao e substituicao do $tpl_content
        //-------------------------------------------------------------
        //-------------------------------------------------------------
        $id_utilizador = $_SESSION['utilizador_id'];
        
        if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0){
            $pagina = $_GET['p'];
        }else{
            $pagina = 1;
        }
        
        //verifica se existe s_session mensagemprojetos
        if(!isset($_SESSION['mensagemfavoritos']) || $_SESSION['mensagemfavoritos'] == '' || $_SESSION['mensagemfavoritos'] == NULL){
            $tpl_content = str_replace('()-mensagemfavoritos-()', '', $tpl_content);
        }else{
            $tpl_content = str_replace('()-mensagemfavoritos-()', $_SESSION['mensagemfavoritos'], $tpl_content);
            unset($_SESSION['mensagemfavoritos']);
        }
                
        //verifica se utilizador existe
        $sql = 'SELECT username FROM utilizadores WHERE id = '.$id_utilizador;
        $query = mysqli_query($link_bd, $sql);
        if(!$query || $query->num_rows == 0){
                        //Reencaminha para utilizadores.php
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'logout.php';
            header ("location: http://$host$uri/$extra");
        }
        
        //define totalpaginas anterior e proxima
        if($ver == 'up'){
            $sql = 'SELECT count(*) FROM favoritos_uploads WHERE id_utilizador = '.$_SESSION['utilizador_id'];
        }else{
            $sql = 'SELECT count(*) FROM favoritos_utilizadores WHERE id_utilizador = '.$_SESSION['utilizador_id'];
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("morreu lgo na primeira");
        $r = mysqli_fetch_assoc($query);
        $maxregistos = $r['count(*)'];
        
        if($ver=='up'){
            if($maxregistos > 12){
                $paginas = ceil($maxregistos/12);
            }else{
                $paginas = 1;
            }
        }else{
            if($maxregistos > 9){
                $paginas = ceil($maxregistos/9);
            }else{
                $paginas = 1;
            }
        }
        
        if($maxregistos == 0){
            if($ver == 'up'){
                $tpl_content = str_replace('()-anterior-() ()-totalpaginas-() ()-proxima-()', '<h3>Ainda não foi adicionado nenhum trabalho/projeto favorito</h3><br/><br/><br/><br/>', $tpl_content);
            }else{
                $tpl_content = str_replace('()-anterior-() ()-totalpaginas-() ()-proxima-()', '<h3>Ainda não foi adicionado nenhum utilizador favorito</h3><br/><br/><br/><br/>', $tpl_content);
            }
        }else{
            if($ver == 'up'){
                $tpl_content = str_replace('()-totalpaginas-()', 'Página '.$pagina.' de '.$paginas.', num total de '.$maxregistos.' trabalhos/projetos favoritos.', $tpl_content);
            }else{
                $tpl_content = str_replace('()-totalpaginas-()', 'Página '.$pagina.' de '.$paginas.', num total de '.$maxregistos.' utilizadores favoritos.', $tpl_content);
            } 
                //define anterior e proxima
                if($pagina < $paginas){
                    $proxpag = $pagina + 1;
                    if($ver == 'up'){
                        $tpl_content = str_replace('()-proxima-()', '<a href="gerirfavoritos.php?p='.$proxpag.'">Próxima &#8594;</a>', $tpl_content);
                    }else{
                        $tpl_content = str_replace('()-proxima-()', '<a href="gerirfavoritos.php?ver=ut&p='.$proxpag.'">Próxima &#8594;</a>', $tpl_content);
                    }
                }else{
                    $tpl_content = str_replace('()-proxima-()', '', $tpl_content);
                }
                if($pagina > 1){
                    $antpag = $pagina - 1;
                    if($ver == 'up'){
                        $tpl_content = str_replace('()-anterior-()', '<a href="gerirfavoritos.php?p='.$antpag.'">&#8592; Anterior</a>', $tpl_content);
                    }else{
                        $tpl_content = str_replace('()-anterior-()', '<a href="gerirfavoritos.php?ver=ut&p='.$antpag.'">&#8592; Anterior</a>', $tpl_content);
                    }
                }else{
                    $tpl_content = str_replace('()-anterior-()', '', $tpl_content);
                }
                //_-------------
        }
                //define primeiro registo a procurar
                if($ver == 'up'){
                    $p_registo = $pagina * 12 - 12;
                }else{
                    $p_registo = $pagina * 9 - 9;
                }
                
                //define valores dos arrays
                if($ver == 'up'){
                    //a ver uploads
                    $idfav = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $idsprocurados = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $nota = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    //define idsprocurados
                    $sql = 'SELECT id, id_upload, nota FROM favoritos_uploads WHERE id_utilizador = '.$_SESSION['utilizador_id'].' ORDER BY datahora DESC LIMIT '.$p_registo.', 12';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu idsprocurados 1");
                    $i = 0;
                    while($i < 12){
                        if($query->num_rows > $i){
                            $query->data_seek($i);
                            $datarow = $query->fetch_array();
                            $idsprocurados[$i+1] = $datarow['id_upload'];
                            $idfav[$i+1] = $datarow['id'];
                            $nota[$i+1] = htmlspecialchars($datarow['nota']);
                            $i++;
                        }else{
                            $i=12;
                        }
                    }
                    //---------------------
                    $idupl = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $img1 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $img2 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $img3 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $img4 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $titulo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $nomeorig = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
                    $foiu = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
                    
                    //define valores dos arrays
                    $i = 1;
                    while ($i < 13){
                        if($idsprocurados[$i] != ''){
                            $sql = 'SELECT id, imagem1, imagem2, imagem3, imagem4, titulo, nomeoriginal FROM uploads WHERE id = '.$idsprocurados[$i].' LIMIT 1';
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query) die("nao deu definir valores arrays 1");
                            $r = mysqli_fetch_assoc($query);
                            if($query->num_rows > 0){
                                $idupl[$i] = $r['id'];
                                $img1[$i] = $r['imagem1'];
                                $img2[$i] = $r['imagem2'];
                                $img3[$i] = $r['imagem3'];
                                $img4[$i] = $r['imagem4'];
                                $titulo[$i] = htmlspecialchars($r['titulo']);
                                $nomeorig[$i] = htmlspecialchars($r['nomeoriginal']);
                                $foiu[$i] = 1;
                            }
                        }
                        $i++;
                    } 
                      //--------------------------
                     //parte da definição final e substituição
                     $i = 1;
                     while($i < 13){
                         if($foiu[$i] == 1){
                             //imagem
                             if($img1[$i] != NULL && $img1[$i] != '' && $img1[$i] != 'upload/imagem.jpg'){
                                $imagem = $img1[$i];
                            }else if($img2[$i] != NULL && $img2[$i] != '' && $img2[$i] != 'upload/imagem.jpg'){
                                $imagem = $img2[$i];
                            }else if($img3[$i] != NULL && $img3[$i] != '' && $img3[$i] != 'upload/imagem.jpg'){
                                $imagem = $img3[$i];
                            }else if($img4[$i] != NULL && $img4[$i] != '' && $img4[$i] != 'upload/imagem.jpg'){
                                $imagem = $img4[$i];
                            }else{
                                $imagem = 'upload/imagem_pequena.jpg';
                            }
                            if($imagem != 'upload/imagem_pequena.jpg'){
                                $checkimg = $imagem;
                                $checkimg = substr($checkimg, 0, -4);
                                $checkimg = $checkimg.'_pequena.jpg';
                                if(file_exists($checkimg)){
                                    $imagem = $checkimg;
                                }else if(file_exists($imagem)){
                                    //cria imagem perfil para o utilizador
                                    $pimagem = WideImage::loadFromFile($imagem);
                                   // Redimensiona a imagem
                                    $pimagem = $pimagem->resize(436, 273, 'outside');
                                    $pimagem = $pimagem->crop('center', 'center', 436, 273);
                                        // Guarda a imagem
                                    $imagem = $checkimg;
                                    $pimagem->saveToFile($imagem, 40); // Coloca a imagem 
                                }else{
                                    $imagem = 'upload/imagem_pequena.jpg';
                                }
                            }
                            //resto das subs
                            $tpl_content = str_replace('()-linkupload'.$i.'-()', 'trabalho.php?id='.$idupl[$i], $tpl_content);

                            $tpl_content = str_replace('()-titulo'.$i.'-()', $titulo[$i], $tpl_content);
                            $tpl_content = str_replace('()-opcoes'.$i.'-()', '<div align="center"><font size = "+1"><a href="alterarfavorito.php?t=up&id='.$idsprocurados[$i].'">Alterar</a> - <a href="gerirfavoritos.php?ver=up&apagar='.$idfav[$i].'" onclick="return confirmar_removerfavorito(); return FALSE;">Remover</a></font></div>', $tpl_content);
                            $tpl_content = str_replace('HTML/img/dummies/featured-'.$i.'.jpg', $imagem, $tpl_content);
                            if($nota[$i] == '' || $nota[$i] == NULL){
                                $tpl_content = str_replace('()-nota'.$i.'-()','nenhuma', $tpl_content);
                            }else{
                                $tpl_content = str_replace('()-nota'.$i.'-()',$nota[$i], $tpl_content);
                            }
                            
                         }else{
                             //apaga o tpl
                             if($i == 4 || $i == 8 || $i == 12){
                                 $txtrmv = '<figure class="last">
                                                                <div align="center"><a href="()-linkupload'.$i.'-()" class="heading">()-titulo'.$i.'-()</a></div>
								<a href="()-linkupload'.$i.'-()" class="thumb"><img src="HTML/img/dummies/featured-'.$i.'.jpg" alt="Alt text" /></a>
								Nota: ()-nota'.$i.'-() <br/><a class="heading"> ()-opcoes'.$i.'-()</a>
							</figure>';
                             }else{
                                 $txtrmv = '<figure>
                                                                <div align="center"><a href="()-linkupload'.$i.'-()" class="heading">()-titulo'.$i.'-()</a></div>
								<a href="()-linkupload'.$i.'-()" class="thumb"><img src="HTML/img/dummies/featured-'.$i.'.jpg" alt="Alt text" /></a>
								Nota: ()-nota'.$i.'-() <br/><a class="heading"> ()-opcoes'.$i.'-()</a>
							</figure>';
                             }
                             
                             $tpl_content = str_replace($txtrmv, '', $tpl_content);
                         }
                         $i++;
                     }
                     if($maxregistos == 0){
                         //apaga espaçamentos que sobram
                         $esprmv = '<div class="clearfix"></div><br/><div class="clearfix"></div><br/>
                                                        
							
							
							
							
							
							
						<div class="clearfix"></div><br/><div class="clearfix"></div><br/>';
                         $tpl_content=str_replace($esprmv, '', $tpl_content);
                     }
                    //----------------------
                    
                }else{
                    //a ver utilizadores
                    $idfav = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'');
                    $idsprocurados = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'');
                    $nota = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'');
                    //define idsprocurados
                    $sql = 'SELECT id, id_favorito, nota FROM favoritos_utilizadores WHERE id_utilizador = '.$_SESSION['utilizador_id'].' ORDER BY datahora DESC LIMIT '.$p_registo.', 9';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu idsprocurados 2");
                    $i = 0;
                    while($i < 9){
                        if($query->num_rows > $i){
                            $query->data_seek($i);
                            $datarow = $query->fetch_array();
                            $idfav[$i+1] = $datarow['id'];
                            $idsprocurados[$i+1] = $datarow['id_favorito'];
                            $nota[$i+1] = htmlspecialchars($datarow['nota']);
                            $i++;
                        }else{
                            $i=9;
                        }
                    }
                    //---------------------
                      $foiu = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0);
                      $iduser = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
                      $nome = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
                      $datahora = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
                      $imagem = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
                      $imagemfinal = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
                      
                      //define valores dos arrays
                    $i = 1;
                    while ($i < 10){
                        if($idsprocurados[$i] != ''){
                            $sql = 'SELECT id, username, imagem, datahora FROM utilizadores WHERE id = '.$idsprocurados[$i].' LIMIT 1';
                            $query = mysqli_query($link_bd, $sql);
                            if(!$query) die("nao deu definir valores arrays 2");
                            $r = mysqli_fetch_assoc($query);
                            if($query->num_rows > 0){
                                $iduser[$i] = $r['id'];
                                $nome[$i] = htmlspecialchars($r['username']);
                                $datahora[$i] = $r['datahora'];
                                $imagem[$i] = $r['imagem'];
                                $foiu[$i] = 1;
                            }
                        }
                        $i++;
                    } 
                      //--------------------------
                    //parte da definição final e substituição
                    $i = 1;
                     while($i < 10){
                         if($foiu[$i] == 1){
                                $tpl_content = str_replace('()-nome'.$i.'-()',$nome[$i],$tpl_content);
                               //verifica comprimento da descricao e apresenta apenas 300 caracteres
                               if(!isset($nota[$i]) || $nota[$i] == '' || $nota[$i] == NULL){
                                   $tpl_content = str_replace('()-nota'.$i.'-()','nenhuma',$tpl_content);
                               }else{
                                   $tpl_content = str_replace('()-nota'.$i.'-()',$nota[$i],$tpl_content);
                               }
                                
                               $linkuser = 'perfil.php?id='.$iduser[$i];               
                               $tpl_content = str_replace('()-linkperfil'.$i.'-()',$linkuser,$tpl_content); 
                            $tpl_content = str_replace('()-opcoes'.$i.'-()', '<font size = "+1"><a href="alterarfavorito.php?t=ut&id='.$idsprocurados[$i].'">Alterar</a> - <a href="gerirfavoritos.php?ver=ut&apagar='.$idfav[$i].'" onclick="return confirmar_removerfavorito(); return FALSE;">Remover</a></font>', $tpl_content);
                               //--------
                               //-----//------------define data
                            $udia = $datahora[$i]{8} . $datahora[$i]{9};
                            $umes = $datahora[$i]{5} . $datahora[$i]{6};
                            if(convertermes($umes)!= FALSE){
                                $umes = convertermes($umes);
                            }
                            $uano = $datahora[$i]{0} . $datahora[$i]{1} . $datahora[$i]{2} . $datahora[$i]{3};
                            //substitui data
                            $tpl_content = str_replace('()-dia'.$i.'-()', $udia, $tpl_content);
                            $tpl_content = str_replace('()-mes'.$i.'-()', $umes, $tpl_content);
                            $tpl_content = str_replace('()-ano'.$i.'-()', $uano, $tpl_content);

                           //substitui imagem
                           if($imagem[$i] == 'imagens_utilizadores/imagem.jpg' || $imagem[$i] == '' || $imagem[$i] == NULL){
                               $imagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg';
                           }else if(file_exists('imagens_utilizadores/'.$iduser[$i].'_lista.jpg')){
                               $imagemfinal[$i] = 'imagens_utilizadores/'.$iduser[$i].'_lista.jpg';
                           }else if(file_exists($imagem[$i])){
                               //cria uma imagem _lista para o utilizador e guarda-a 
                               $novaimagem = WideImage::load($imagem[$i]); 
                                          // Redimensiona a imagem
                               $novaimagem = $novaimagem->resize(436, 273, 'outside');
                               $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                                         // Guarda a imagem
                               $imagemfinal[$i] = 'imagens_utilizadores/'.$iduser[$i].'_lista.jpg'; // ex: 5_lista, 82_lista
                               $novaimagem->saveToFile($imagemfinal[$i], 40); // Coloca a imagem lista no disco
                           }else{
                               $imagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg'; 
                           }
                           //substitui imagem
                           $tpl_content = str_replace('()-imagem'.$i.'-()', $imagemfinal[$i], $tpl_content);
                             
                         }else{
                             //apaga o tpl
                             $txtrmv = '<figure>
	        			<figcaption>
	    					<strong><font size = +1>()-nome'.$i.'-()</font></strong>
	    					<span>Nota: ()-nota'.$i.'-()</span>
	    					<em>()-dia'.$i.'-() ()-mes'.$i.'-(), ()-ano'.$i.'-()</em>
	    					<a href="()-linkperfil'.$i.'-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil'.$i.'-()"  class="thumb"><img src="()-imagem'.$i.'-()" alt="Alt text" /></a>
	        		</figure>';
                             $tpl_content = str_replace($txtrmv, '', $tpl_content);
                             $tpl_content = str_replace('()-opcoes'.$i.'-()', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $tpl_content);
                         }
                         $i++;
                     }
                     if($maxregistos == 0){
                         //apaga espaçamentos que sobram
                         $esprmv = '<br/><div class="portfolio-thumbs clearfix" >
                                    
	        		
	        		
	        		
	        		
	        		
	        		
                                </div>';
                         $tpl_content=str_replace($esprmv, '', $tpl_content);
                     }
                    //----------------------
                }
            
     
        //-------------------------------------------------------------
        //-------------------------------------------------------------
        //acaba definiçao e substituicao do tpl_content
        //-------------------------------------------------------------
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
        
        print $home; 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
