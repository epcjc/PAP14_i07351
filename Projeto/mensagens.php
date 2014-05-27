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
        $tpl_headline = file_get_contents('TPL/headlinemenu_mensagens.tpl');
       // $tpl_homeblock1 = file_get_contents('TPL/homeblock1.tpl');
        //$tpl_homeblock2 = file_get_contents('TPL/homeblock2.tpl');
        $tpl_footer = file_get_contents('TPL/footer.tpl');
        $tpl_footerbottom = file_get_contents('TPL/footerbottom.tpl');
        
        if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
                        //envia-o de volta para o index
            $_SESSION['mensagem_erro'] = 'É necessário efetuar o Login para ver essa página.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
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
        
        

        $tpl_content = file_get_contents('TPL/mensagens.tpl');
        
        //coloca content por substituir
        //
        //----------------------------------------------------------
        //----------------------------------------------------------
        //------------------começa definiçao e substituicao 
        //----------------------------------------------------------
        $iduser = $_SESSION['utilizador_id'];
//adiciona numero de novas mensagens no menu da direita em caixa de entrada
        $sql = 'SELECT count(*) FROM mensagens WHERE id_utilizadorR = '.$iduser.' AND porler = 1 AND apagou_utilizadorR = 0';
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu ver novas mensagens");
        $r = mysqli_fetch_assoc($query);
        if($r['count(*)'] > 1){
            $home = str_replace('<li class="cat-item"><a href="mensagens.php" title="Ver mensagens recebidas">Caixa de entrada</a></li>', '<li class="cat-item"><a href="mensagens.php" title="Ver mensagens recebidas">Caixa de entrada ('.$r['count(*)'].')</a></li>', $home);
        }
            
        
        
        if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0){
            $pagina = $_GET['p'];
        }else{
            $pagina = 1;
        }
        $ver = 'recebidas';
        if(isset($_GET['ver']) && $_GET['ver'] == 'enviadas'){
            $ver = 'enviadas';
        }
        //define mensagem topo
        $mensagemtopo = '';
        if(isset($_SESSION['mensagem_mensagens'])){
            $mensagemtopo = '<font color="green">'.$_SESSION['mensagem_mensagens'].'</font><br/>';
            unset($_SESSION['mensagem_mensagens']);
        }

        //define total páginas proxima e anterior
        if($ver == 'recebidas'){
            $mensagemtopo .= 'Caixa de entrada';
            $home = str_replace('()-apagartodas-()','<li class="cat-item"><a href="apagarmensagem.php?apagar=recebidas" title="Apagar todas as mensagens recebidas" onclick="return confirmar_apagartodasmensagensR(); return FALSE;">Apagar todas</a></li>',$home);
            $sql = 'SELECT count(*) FROM mensagens WHERE id_utilizadorR = '.$iduser.' AND apagou_utilizadorR = 0';
        }else{
            $mensagemtopo .= 'Caixa de saída';
            $home = str_replace('()-apagartodas-()','<li class="cat-item"><a href="apagarmensagem.php?apagar=enviadas" title="Apagar todas as mensagens enviadas" onclick="return confirmar_apagartodasmensagensE(); return FALSE;">Apagar todas</a></li>',$home);
            $sql = 'SELECT count(*) FROM mensagens WHERE id_utilizadorE = '.$iduser.' AND apagou_utilizadorE = 0';
        }
        $tpl_content = str_replace('()-mensagemtopo-()',$mensagemtopo,$tpl_content);
        
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu query countx mensagens");
        $r = mysqli_fetch_assoc($query);
        $totalmensagens = $r['count(*)'];
        if($totalmensagens > 10){
            $totalpaginas = ceil($totalmensagens/10);
        }else{
            $totalpaginas = 1;
        }
        
        if($pagina < $totalpaginas){
            if($ver == 'recebidas'){
                $proxpag = $pagina + 1;
                $proximafull = '<a href="mensagens.php?p='.$proxpag.'"><font color="#D0D0D0">Próxima &#8594;</font></a>';
            }else{
                $proxpag = $pagina + 1;
                $proximafull = '<a href="mensagens.php?ver=enviadas&p='.$proxpag.'"><font color="#D0D0D0">Próxima &#8594;</font></a>';
            }
            
        }else{
            $proximafull = '';
        }
        $tpl_content = str_replace('()-proxima-()', $proximafull, $tpl_content);
        
        if($pagina > 1){
            if($ver == 'recebidas'){
                $antpag = $pagina - 1;
                $anteriorfull = '<a  href="mensagens.php?p='.$antpag.'"><font color="#D0D0D0">&#8592; Anterior </font></a>';
            }else{
                $antpag = $pagina - 1;
                $anteriorfull = '<a  href="mensagens.php?ver=enviadas&p='.$antpag.'"><font color="#D0D0D0">&#8592; Anterior </font></a>';
            } 
        }else{
           $anteriorfull = ''; 
        }
        $tpl_content = str_replace('()-anterior-()',$anteriorfull,$tpl_content);
        if($totalmensagens==0){
            if($ver=="recebidas"){
                $tpl_content=str_replace('()-total_paginas-()', '<br/><br/><br/>Não há nenhuma mensagem recebida.', $tpl_content);
            }else{
                $tpl_content=str_replace('()-total_paginas-()', '<br/><br/><br/>Não há nenhuma mensagem enviada.', $tpl_content);
            }
        }else{
            if($totalmensagens == 1){
                    if($ver=="recebidas"){
                        $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de uma mensagem recebida.', $tpl_content);
                    }else{
                        $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de uma mensagem enviada.', $tpl_content);
                    }
            }else{
                if($ver=="recebidas"){
                    $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de '.$totalmensagens.' mensagens recebidas.', $tpl_content);
                }else{
                    $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de '.$totalmensagens.' mensagens enviadas.', $tpl_content);
                }
           }
        }    
        
        
        //verifica primeira mensagem a apresentar
        $p_mensagem = $pagina * 10 - 10;
        
        $mfoi = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0);
        $mid = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mid_utilizadorE = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mid_utilizadorR = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mapagou_utilizadorR = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mapagou_utilizadorE = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mporler = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mtitulo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mconteudo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $mdatahora = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        $outrousername = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'');
        
        if($ver == 'recebidas'){
                    $sql = 'SELECT * FROM mensagens WHERE id_utilizadorR = '.$iduser.' AND apagou_utilizadorR = 0 ORDER BY datahora DESC LIMIT '.$p_mensagem.', 10';
        }else{
                    $sql = 'SELECT * FROM mensagens WHERE id_utilizadorE = '.$iduser.' AND apagou_utilizadorE = 0 ORDER BY datahora DESC LIMIT '.$p_mensagem.', 10';
        }
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("nao deu query listar mensagens");
        }
        $i = 0;
        while ($i < 10){
            if($query->num_rows > $i){
                $query->data_seek($i);
                $datarow = $query->fetch_array();
                $mid[$i+1] = $datarow['id']; 
                $mid_utilizadorE[$i+1] = $datarow['id_utilizadorE']; 
                $mid_utilizadorR[$i+1] = $datarow['id_utilizadorR']; 
                $mapagou_utilizadorR[$i+1] = $datarow['apagou_utilizadorR'];
                $mapagou_utilizadorE[$i+1] = $datarow['apagou_utilizadorE'];
                $mporler[$i+1] = $datarow['porler']; 
                $mtitulo[$i+1] = htmlspecialchars($datarow['titulo']); 
                $mconteudo[$i+1] = htmlspecialchars($datarow['conteudo']); 
                $mdatahora [$i+1] = $datarow['datahora'];
                $mfoi[$i+1] = 1;
                //faz pesquisa e guarda o username do  outrousername, verifica se id do utilizadorE é 0, se for, significa que é uma mensagem automatica enviada do servidor
                if($mid_utilizadorE[$i+1] == 0){
                    $outrousername[$i+1] = $_SG['nomewebsite'];
                }else{
                    if($ver=='recebidas'){
                        $sqlcheck= 'SELECT username FROM utilizadores WHERE id='.$mid_utilizadorE[$i+1].' LIMIT 1';
                        $querycheck = mysqli_query($link_bd, $sqlcheck);
                        if(!$querycheck) die("nao deu guardar username outro");
                        if($querycheck->num_rows > 0){
                            $rcheck = mysqli_fetch_assoc($querycheck);
                            $outrousername[$i+1] = '<a href="perfil.php?id='.$mid_utilizadorE[$i+1].'">'.htmlspecialchars($rcheck['username']).'</a>';
                        }else{
                            $outrousername[$i+1] = 'Utilizador removido';
                        }
                    }else{
                        $sqlcheck= 'SELECT username FROM utilizadores WHERE id='.$mid_utilizadorR[$i+1].' LIMIT 1';
                        $querycheck = mysqli_query($link_bd, $sqlcheck);
                        if(!$querycheck) die("nao deu guardar username outro R");
                        if($querycheck->num_rows > 0){
                            $rcheck = mysqli_fetch_assoc($querycheck);
                            $outrousername[$i+1] = '<a href="perfil.php?id='.$mid_utilizadorR[$i+1].'">'.htmlspecialchars($rcheck['username']).'</a>';
                        }else{
                            $outrousername[$i+1] = 'Utilizador removido';
                        }
                    }
                }
                //--------------------
                $i++;
            }else{
                $i = 10;
            }
        }
        $i = 1;
        while($i < 11){
            if($mfoi[$i] == 1){
                //substitui data
                $mdia = $mdatahora[$i]{8} . $mdatahora[$i]{9};
                $mmes = $mdatahora[$i]{5} . $mdatahora[$i]{6};
                if(convertermes($mmes)!= FALSE){
                    $mmes = convertermes($mmes);
                }
                $mano = $mdatahora[$i]{0} . $mdatahora[$i]{1} . $mdatahora[$i]{2} . $mdatahora[$i]{3};
                $mhh = $mdatahora[$i]{11} . $mdatahora[$i]{12};
                $mmm = $mdatahora[$i]{14} . $mdatahora[$i]{15};
                //substitui data
                $tpl_content = str_replace('()-dia'.$i.'-()', $mdia, $tpl_content);
                $tpl_content = str_replace('()-mes'.$i.'-()', $mmes, $tpl_content);
                $tpl_content = str_replace('()-ano'.$i.'-()', $mano, $tpl_content);
                $tpl_content = str_replace('()-HH'.$i.'-()', $mhh, $tpl_content);
                $tpl_content = str_replace('()-MM'.$i.'-()', $mmm, $tpl_content);
                //substitui titulo
                if(strlen($mtitulo[$i]) > 40){
                    $titulor = substr($mtitulo[$i], 0, 40).'<font size=+1>...</font>';
                }else{
                    $titulor = $mtitulo[$i];
                }
                $tpl_content = str_replace('()-titulo'.$i.'-()', $titulor, $tpl_content);
                //substitui nova
                if($mporler[$i] == 1 && $ver == "recebidas"){
                    $tpl_content = str_replace('()-nova'.$i.'-()', '(Nova)', $tpl_content);
                }else{
                    $tpl_content = str_replace('()-nova'.$i.'-()', '', $tpl_content);
                }
                //substitui opcoes-> apagar
                $tpl_content = str_replace('()-opcoes'.$i.'-()', '<a href="apagarmensagem.php?id='.$mid[$i].'" title="Apagar esta mensagem" onclick="return confirmar_apagar(); return FALSE;"><img src="HTML/img/botaoapagar_mini.png"></a>', $tpl_content);
                //substitui link
                $tpl_content= str_replace('()-link'.$i.'-()', 'mensagem.php?id='.$mid[$i], $tpl_content);
                //substitui user
                if($ver == "recebidas"){
                    $tpl_content = str_replace('()-user'.$i.'-()','Enviada por: '.$outrousername[$i],$tpl_content);
                }else{
                    $tpl_content = str_replace('()-user'.$i.'-()','Enviada para: '.$outrousername[$i],$tpl_content);
                }

                
                //-----------------------------------
            }else{
                $msgrmv = '<article class="format-standard">
                                                <br/>
						<div class="entry-date2"><div class="number">()-dia'.$i.'-()</div> <div class="year">()-mes'.$i.'-(), ()-ano'.$i.'-()<br><font color="black" size="+1">()-HH'.$i.'-():()-MM'.$i.'-()</font></div></div>
                                                <h2  class="post-heading"><a href="()-link'.$i.'-()">()-titulo'.$i.'-()</a><font size="-1" color="#A80000">()-nova'.$i.'-()</font><font size="-1"> ()-opcoes'.$i.'-()</font></h2>
                                                
                                                <div class="meta">
                                                    <div class="user">
                                                        ()-user'.$i.'-()
                                                    </div>
						</div>
					</article>';
                $tpl_content = str_replace($msgrmv,'',$tpl_content);
            }
            $i++;
        }
        
        
        //----------------------------------------------------------
        //------------------acaba definicao e substituicao 
        //----------------------------------------------------------
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
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


