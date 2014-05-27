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

        $tpl_content = file_get_contents('TPL/mensagem.tpl');
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
            
            $_SESSION['mensagem_erro'] = 'É necessário efetuar o Login para ver essa página.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
            
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
                            //verifica se atualizou o nmensagens depois de ver esta pagina(so se aplica a mensagem.php)
                            if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                                $sqlc = 'SELECT * FROM mensagens WHERE id = '.$_GET['id'].' LIMIT 1';
                                $queryc = mysqli_query($link_bd, $sqlc);
                                if(!$query) ("nao deu atualizar nmensagens mensagem.php");
                                $rc = mysqli_fetch_assoc($queryc);
                                if($rc['id_utilizadorR'] == $_SESSION['utilizador_id'] && $rc['porler'] == 1){
                                    if($resultado["nmensagens"] == 1){
                                        $nmensagens = 'Não há novas mensagens';  
                                    }elseif($resultado["nmensagens"] == 0){
                                        $nmensagens = 'Não há novas mensagens';
                                    }elseif($resultado["nmensagens"] == 2){
                                        $nmensagens = $resultado["nmensagens"]-1 . ' mensagem nova';
                                    }else{
                                        $nmensagens = $resultado["nmensagens"]-1 . ' mensagens novas';
                                    }
                                }else{
                                    if($resultado["nmensagens"] == 1){
                                        $nmensagens = $resultado["nmensagens"] . ' mensagem nova';  
                                    }elseif($resultado["nmensagens"] == 0){
                                        $nmensagens = 'Não há novas mensagens';
                                    }else{
                                        $nmensagens = $resultado["nmensagens"] . ' mensagens novas';
                                    }
                                }
                            }
                    //------------------------------------------------
                            


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
        
        
        //coloca content por substituir
        
        //---------------------------------
        //---- a PARTE da substituição começa aqui
        
        
        if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
            $idmensagem = $_GET['id'];
        }else{
            //envia de volta
            $_SESSION['mensagem_erro'] = 'Foi introduzido um parâmetro inválido ao ver a mensagem.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }
        //verifica se existe na bd e se o utilizador pode ver
        $sql = 'SELECT * FROM mensagens WHERE id='.$idmensagem.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu verificar se pode ver msg");
        if($query->num_rows == 0){
                        //envia de volta
            $_SESSION['mensagem_erro'] = 'A mensagem que tentou acessar não existe.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }
        
            $r = mysqli_fetch_assoc($query);
            $id_utilizadorE = $r['id_utilizadorE'];
            $id_utilizadorR = $r['id_utilizadorR'];
            if($id_utilizadorE == 0){
                $titulo = $r['titulo'];
                $conteudo = $r['conteudo'];
            }else{
                $titulo = htmlspecialchars($r['titulo']);
                $conteudo = htmlspecialchars($r['conteudo']); 
            }
            
            $datahora = $r['datahora'];
            $porler = $r['porler'];
        
            if($id_utilizadorE == $_SESSION['utilizador_id']){
                $tmensagem = 'enviada';
                //verifica outrousername
                if($id_utilizadorR == 0){
                    $outrousername = $_SG['nomewebsite'];
                }else{
                    $sql = 'SELECT username FROM utilizadores WHERE id = '.$id_utilizadorR.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("nao deu outrousername erro_ daijsad913");

                    }
                    if($query->num_rows > 0){
                        $r = mysqli_fetch_assoc($query);
                        $outrousername = '<a href="perfil.php?id='.$id_utilizadorR.'">'.htmlspecialchars($r['username']).'</a>';
                        $outrousernamesem = htmlspecialchars($r['username']);
                    }else{
                        $outrousername = 'Utilizador removido';
                    }
                }
                //-------------------
            }else if($id_utilizadorR == $_SESSION['utilizador_id']){
                $tmensagem = 'recebida';
                //verifica outrousername
                if($id_utilizadorE == 0){
                    $outrousername = $_SG['nomewebsite'];
                }else{
                    $sql = 'SELECT username FROM utilizadores WHERE id = '.$id_utilizadorE.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("nao deu outrousername erro_ daijsad913");

                    }
                    if($query->num_rows > 0){
                        $r = mysqli_fetch_assoc($query);
                        $outrousername = '<a href="perfil.php?id='.$id_utilizadorE.'">'.htmlspecialchars($r['username']).'</a>';
                        $outrousernamesem = htmlspecialchars($r['username']);
                    }else{
                        $outrousername = 'Utilizador removido';
                    }
                }
                //-------------------
            }else{
                $_SESSION['mensagem_erro'] = 'Não pode ver essa mensagem.';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header ("location: http://$host$uri/$extra");
                exit;
            }
            //substitui
            
                //verifica se o utilizador está bloqueado
                if($tmensagem == 'recebida' && $id_utilizadorE!= $_SESSION['utilizador_id'] && $id_utilizadorE != 0 && $outrousername != 'Utilizador removido'){
                    $sql = 'SELECT id FROM bloqueios WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_bloqueado = '.$id_utilizadorE.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu para ver o bloqueio");
                    if($query->num_rows > 0){
                        //esta bloqueado
                        $tpl_content = str_replace('()-bloquear-()', '<a href = "bloquear.php?id='.$id_utilizadorE.'" title="Desbloquear este utilizador">Desbloquear</a>', $tpl_content);
                    }else{
                        //nao esta bloqueado
                        $tpl_content = str_replace('()-bloquear-()', '<a href = "bloquear.php?id='.$id_utilizadorE.'" title="Bloquear este utilizador" onclick="return confirmar_bloquear(); return FALSE;">Bloquear</a>', $tpl_content);
                    }
                }else if($tmensagem == 'enviada' && $id_utilizadorR != $_SESSION['utilizador_id'] && $id_utilizadorR != 0 && $outrousername != 'Utilizador removido'){
                    $sql = 'SELECT id FROM bloqueios WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_bloqueado = '.$id_utilizadorR.' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu para ver o bloqueio2");
                    if($query->num_rows > 0){
                        //esta bloqueado
                        $tpl_content = str_replace('()-bloquear-()', '<a href = "bloquear.php?id='.$id_utilizadorR.'" title="Desbloquear este utilizador">Desbloquear</a>', $tpl_content);
                    }else{
                        //nao esta bloqueado
                        $tpl_content = str_replace('()-bloquear-()', '<a href = "bloquear.php?id='.$id_utilizadorR.'" title="Bloquear este utilizador" onclick="return confirmar_bloquear(); return FALSE;">Bloquear</a>', $tpl_content);
                    }
                }else{
                    $tpl_content = str_replace('(()-bloquear-())', '', $tpl_content);
                }
                //----------------------------
            
            //tipo
            if($id_utilizadorE == $id_utilizadorR){
                $tpl_content = str_replace('()-tipo-()', 'recebida e enviada', $tpl_content);
            }else if($tmensagem == 'enviada'){
                $tpl_content = str_replace('()-tipo-()', 'enviada', $tpl_content);
            }else{
                $tpl_content = str_replace('()-tipo-()', 'recebida', $tpl_content);
            }
            //mensagem_enviada
            if($id_utilizadorE == $id_utilizadorR){
                $tpl_content = str_replace('()-mensagem_enviada-()', 'Enviada e recebida por', $tpl_content);
            }else if($tmensagem == 'enviada'){
                $tpl_content = str_replace('()-mensagem_enviada-()', 'ENVIADA PARA', $tpl_content);
            }else{
                $tpl_content = str_replace('()-mensagem_enviada-()', 'ENVIADA POR', $tpl_content);
            }
            //user
            $tpl_content = str_replace('()-user-()', $outrousername, $tpl_content);
            //apagarmensagem e voltar
            $tpl_content = str_replace('()-apagarmensagem-()', '<a href="apagarmensagem.php?id='.$idmensagem.'" title="Apagar esta mensagem" onclick="return confirmar_apagar(); return FALSE;">Apagar</a>', $tpl_content);
            if($tmensagem == 'recebida'){
                $tpl_content = str_replace('()-voltar-()', '<a href="mensagens.php" title="Voltar para a caixa de entrada">Voltar</a>', $tpl_content);
            }elseif($id_utilizadorE == $_SESSION['utilizador_id'] && $id_utilizadorR == $_SESSION['utilizador_id']){
                $tpl_content = str_replace('()-voltar-()', '<a href="mensagens.php" title="Voltar para a caixa de entrada">Voltar</a>', $tpl_content);
            }else{
                $tpl_content = str_replace('()-voltar-()', '<a href="mensagens.php?ver=enviadas" title="Voltar para a caixa de saída">Voltar</a>', $tpl_content);
            }
            //reponder e destinatario
            if($tmensagem == 'enviada' || $id_utilizadorE == 0 || $id_utilizadorE == $id_utilizadorR || $outrousername == 'Utilizador removido'){
                //substitui form de responder
                $formresponder = '<div id="respond">
					<form name="novamensagem" id="novamensagem" action="enviarmensagem.php" method="post" onsubmit="return validarenviarmensagem(); return false;">
                                                <h3 class="heading">Responder a esta mensagem:</h3>
                                                <fieldset>
							<input name="destinatario"  type="hidden" maxlength="100" value="()-destinatario-()" />
							<div>
								<input name="titulo"  id="titulo" type="text" class="form-poshytip" title="Inserir Título/Assunto para a mensagem" maxlength="100" value="()-tituloresposta-()"/>
							</div>
							<div>
								<textarea  name="conteudo"  id="conteudo" rows="5" cols="20" class="form-poshytip" title="Inserir o conteúdo da mensagem" maxlength="2000"></textarea>
							</div>
                                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                                <a href="#" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false">[ Imagem diferente ]</a>
                                                <p><input type="submit" value="Enviar"/></p>
						</fieldset>
					</form>
						</div>';
                $tpl_content = str_replace($formresponder,'',$tpl_content);
            }else{
                $tpl_content = str_replace('()-destinatario-()', $outrousernamesem, $tpl_content);
            }
           //titulo
           $tpl_content = str_replace('()-titulo-()',$titulo,$tpl_content);
           //conteudo
           $tpl_content = str_replace('()-conteudo-()',$conteudo,$tpl_content);
           //data
                $dia = $datahora{8} . $datahora{9};
                $mes = $datahora{5} . $datahora{6};
                if(convertermes($mes)!= FALSE){
                    $mes = convertermes($mes);
                }
                $ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
                $hh = $datahora{11} . $datahora{12};
                $mm = $datahora{14} . $datahora{15};
                //substitui data
                $tpl_content = str_replace('()-dia-()', $dia, $tpl_content);
                $tpl_content = str_replace('()-mes-()', $mes, $tpl_content);
                $tpl_content = str_replace('()-ano-()', $ano, $tpl_content);
                $tpl_content = str_replace('()-HH-()', $hh, $tpl_content);
                $tpl_content = str_replace('()-MM-()', $mm, $tpl_content);
                //tituloresposta
                if($id_utilizadorR == $_SESSION['utilizador_id']){
                    //if(substr($titulo, 0, 3) == 'RE:'){
                        $tituloresposta = $titulo;
              /*      }else{
                        if(strlen($titulo) > 96){
                            $tituloresposta = 'RE: '.substr($titulo, 0, 96);
                        }else{
                            $tituloresposta = 'RE: '.$titulo;
                        }
                    }*/
                    
                    $tpl_content = str_replace('()-tituloresposta-()', $tituloresposta, $tpl_content);
                }

           //
           //
           //---DIMINUI 1 valor a menos em nmensagens utilizadores e porler=0 em mensagens
           if($id_utilizadorR == $_SESSION['utilizador_id'] && $porler == 1){
               //nmensagens
                    $sql = 'SELECT nmensagens FROM utilizadores WHERE id = '.$_SESSION['utilizador_id'].' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query){
                        die("erro xhas ldasd a");
                    }
                    while($r = mysqli_fetch_assoc($query)){
                        $ant_msg = $r['nmensagens'];
                    }
                    if($ant_msg > 0){
                        $nmsg = $ant_msg - 1;
                    }else{
                        $nmsg = 0;
                    }
                    $ins = "UPDATE `utilizadores` SET `nmensagens` = '".$nmsg."' WHERE `id` = '".$_SESSION['utilizador_id']."' LIMIT 1";
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }else{
                        die("nao foi possivel a inscricao de nmensagens no utilizador que leu");
                    }
               //porler
                    $ins = "UPDATE `mensagens` SET `porler` = 0 WHERE `id` = '".$idmensagem."' LIMIT 1";
                    $stmt = $link_bd->prepare($ins);
                    if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                                $stmt->execute();
                    }else{
                        die("nao foi possivel a inscricao de porler na mensagem q o utilizador leu");
                    }
               
           }
        //--------------------------------------------------------
        //--------------------------------------
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
