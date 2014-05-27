<?php


include_once("includes/seguranca.php");
include_once("includes/funcoes.php");
include_once'WideImage/lib/WideImage.php';
$_SESSION["pagina"] = $_SERVER['PHP_SELF'];
    //verifica se há um id para a noticia, se nao houver reencaminha para noticias.php
    if(!isset($_GET['id'])){
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'noticias.php';
            header ("location: http://$host$uri/$extra");
    }

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

        $tpl_content = file_get_contents('TPL/noticia.tpl');
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
        
        $home = str_replace("()-content-()", $tpl_content, $home);
        //coloca content por substituir
        
        //---------------------------------
        //---- a PARTE da substituição começa aqui
        
        
        $idnoticia = $_GET['id'];
        //procura noticia na base de dados e define valores
        $sql = "SELECT * FROM noticias WHERE id = '$idnoticia' LIMIT 1";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query na pagina da '.$idnoticia.' noticia');
        }
        if($query->num_rows == 0){ 
            //reenvia para noticias.php se não encontrar a noticia
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'noticias.php';
            header ("location: http://$host$uri/$extra");
        }
        //define valores
        $result = mysqli_fetch_assoc($query);
        $idnoticia = $result['id'];
        $imagem = $result['imagem'];
        $titulo = $result['titulo'];
        $titulo = htmlspecialchars($titulo);
        $conteudo = $result['conteudo'];
        $ncomentarios = $result['ncomentarios'];
        $iduser = $result['id_utilizador'];
        $datahora = $result['datahora'];
        
        //substitui valores
        $home = str_replace('()-noticia_id-()', 'Notícia nº '.$idnoticia, $home);
        $home = str_replace('()-titulo-()', $titulo, $home);
        $home = str_replace('()-descricao-()', $conteudo, $home);
        //define ncomentarios
        /*if($ncomentarios == 0){
            $ncomentarios = "Não há comentários.";
        }else if($ncomentarios == 1){
            $ncomentarios = "1 comentário";
        }else{
            $ncomentarios = $ncomentarios . ' comentários';
        }
        $home = str_replace('()-ncomentarios-()', $ncomentarios, $home);*/
        //--------define imagem
        $tplimagem = file_get_contents('TPL/noticias/definirimagem.tpl');
        //---------Verifica se já existe uma notícia_lista
            list($width, $height, $type, $attr) = getimagesize($imagem);
            if($width < 383 || $height < 720){
                
            
        
                if(file_exists('imagens_noticias/'.$idnoticia.'_lista.jpg')){
                    $imagem_lista = 'imagens_noticias/'.$idnoticia.'_lista.jpg';
                }else if(file_exists('imagens_noticias/'.$idnoticia.'.jpg')){
                    //cria imagem noticia_lista
                    $novaimagem = WideImage::load('imagens_noticias/'.$idnoticia.'.jpg'); 
                                  // Redimensiona a imagem
                    $novaimagem = $novaimagem->resize(720, 383, 'outside');
                                 // Guarda a imagem
                    $imagem_lista = 'imagens_noticias/'.$idnoticia.'_lista.jpg'; // ex: 5_pequena, 82_pequena
                    $novaimagem->saveToFile($imagem_lista, 40); // Coloca a imagem pequena no disco
                }else{
                    $imagem_lista ='imagens_noticias/imagem.jpg';
                }
            }else{
                $imagem_lista = $imagem;
            }
            //--------------
        $tplimagem = str_replace('()-caminhoimagem-()', $imagem_lista, $tplimagem);
        $tplimagem = str_replace('()-caminhoimagem-()', $imagem_lista, $tplimagem);
        $home = str_replace('()-imagem-()', $tplimagem, $home);
        //procura nome do utilizador
        $sql = "SELECT username FROM utilizadores WHERE id = '$iduser' LIMIT 1";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query na pagina da '.$idnoticia.' noticia');
        }else if($query->num_rows > 0){
            while ($resultado = mysqli_fetch_assoc($query)){
                $username = $resultado['username'];
            }        
        }else{
            $username = '';
        }
        $tpl_username = '<a href="perfil.php?id='.$iduser.'">'.$username.'</a>';
        $home = str_replace('()-user-()', $tpl_username, $home);
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

        
        
        //----------------------------------------
        ///COMEÇA DEFINICAO DOS COMENTARIOS
        //----------------------------------------
        //verifica total de comentarios da noticia
        $sql = 'SELECT count(*) FROM comentarios_noticias WHERE id_noticia = '.$idnoticia;
        $query = mysqli_query($link_bd,$sql);
        if(!$query){
            die("morreu comentario npkdasod noticia.pgp");
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
                $home = str_replace('()-proxima-()','<a href="noticia.php?id='.$idnoticia.'&p='.$proxpag.'">Próxima &#8594;</a>',$home);
            }else{
                $home = str_replace('()-proxima-()','',$home);
            }
            //anterior
            $antpag = $pagina - 1;
            if($pagina > 1){
                $home = str_replace('()-anterior-()','<a href="noticia.php?id='.$idnoticia.'&p='.$antpag.'">&#8592; Anterior</a>',$home);
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
            $home = str_replace('()-linkcomentar-()', 'comentar.php?t=n&id='.$idnoticia, $home);
        
        //verifica o numero do primeiro comentario a apresentar
        $p_comentario = $pagina * 5 - 5;
        
        $cid = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cuserid = array(1=>'',2=>'',3=>'',4=>'',5=>'');        
        $cdatahora = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cnreports = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cconteudo = array(1=>'',2=>'',3=>'',4=>'',5=>'');
        $cfoi = array(1=>0,2=>0,3=>0,4=>0,5=>0);
        
//pesquisa sql para guardar os valores dos comentarios
        $sql = 'SELECT * FROM comentarios_noticias WHERE id_noticia='.$idnoticia.' ORDER BY datahora DESC LIMIT '.$p_comentario.', 5';
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
                    $sqlcheck = 'SELECT id FROM reports_cnoticias WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_comentario = '.$cid[$i].' LIMIT 1';
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
                    $home=str_replace('()-linkreportarc'.$i.'-()','reportarcomentario.php?t=n&id='.$cid[$i],$home);
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
                    $home = str_replace('<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="reportarcomentario.php?t=n&id='.$cid[$i].'">Denunciar comentário</a></span>','',$home);
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
							
							<form action="comentar.php?t=n&id='.$idnoticia.'" method="post" id="commentform">
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
                    $botaod = '<span class="comment-reply-link-wrap"><a class="comment-reply-link" href="reportarcomentario.php?t=n&id='.$cid[$i].'">Denunciar comentário</a></span>';
                    $home = str_replace($botaod,'',$home);
                    $i++;
                }else{
                    $i = 6;
                }
                
            }
        }
        
        //----------------------------------------
        ///ACABA DEFINICAO DOS COMENTARIOS
        //----------------------------------------
        

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
