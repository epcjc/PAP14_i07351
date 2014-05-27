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
        
        

        $tpl_content = file_get_contents('TPL/utilizadoresbloqueados.tpl');
        
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
        
        //define mensagem topo
        $mensagemtopo = '';
        if(isset($_SESSION['mensagem_mensagens'])){
            $mensagemtopo = '<font color="green">'.$_SESSION['mensagem_mensagens'].'</font><br/>';
            unset($_SESSION['mensagem_mensagens']);
        }

        //define total páginas proxima e anterior
        $home = str_replace('()-apagartodas-()','',$home);
        
        
        $mensagemtopo .= "Utilizadores bloqueados";
        $tpl_content = str_replace('()-mensagemtopo-()',$mensagemtopo,$tpl_content);
        
        $sql = 'SELECT count(*) FROM bloqueios WHERE id_utilizador = '.$_SESSION['utilizador_id'];
        
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("nao deu query countx utilizadoresbloqueados");
        $r = mysqli_fetch_assoc($query);
        $totalbloqueios = $r['count(*)'];
        if($totalbloqueios > 32){
            $totalpaginas = ceil($totalbloqueios/32);
        }else{
            $totalpaginas = 1;
        }
        
        if($pagina < $totalpaginas){
                $proxpag = $pagina + 1;
                $proximafull = '<a href="utilizadoresbloqueados.php?p='.$proxpag.'"><font color="#D0D0D0">Próxima &#8594;</font></a>';
        }else{
            $proximafull = '';
        }
        $tpl_content = str_replace('()-proxima-()', $proximafull, $tpl_content);
        
        if($pagina > 1){
                $antpag = $pagina - 1;
                $anteriorfull = '<a  href="utilizadoresbloqueados.php?p='.$antpag.'"><font color="#D0D0D0">&#8592; Anterior </font></a>';
         }else{
           $anteriorfull = ''; 
        }
        $tpl_content = str_replace('()-anterior-()',$anteriorfull,$tpl_content);
        
        //adiciona um paragrafo antes de totalpaginas
        if($totalbloqueios <= 6){
            $tpl_content=str_replace('()-total_paginas-()', '<br/>()-total_paginas-()', $tpl_content);
        }
        
        
        if($totalbloqueios==0){
            
                $tpl_content=str_replace('()-total_paginas-()', '<br/>Não há nenhum utilizador bloqueado.', $tpl_content);
            
            
        }else{
            if($totalbloqueios == 1){
                $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de um utilizador bloqueado.', $tpl_content);
            }else{
                $tpl_content=str_replace('()-total_paginas-()', 'Página '.$pagina.' de '.$totalpaginas.', num total de '.$totalbloqueios.' utilizadores bloqueados.', $tpl_content);
            }
        }    
        
        if($totalbloqueios == 0){
            $definirsub = '<br/>
                                                <h2  class="post-heading">()-definirutilizador-()</h2>';
            $tpl_content = str_replace($definirsub, '', $tpl_content);
        }else{
            //define utilizadores bloqueados
            $p_utilizador = $pagina * 32 - 32;
            $conteudo = '';
            $sql = 'SELECT id_bloqueado, datahora FROM bloqueios WHERE id_utilizador = '.$_SESSION['utilizador_id'].' ORDER BY datahora DESC LIMIT '.$p_utilizador.', 32';
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die("nao deu query em define utilizadores blocks0");
            }
            $i = 0;
            while ($i < 32){
                if($query->num_rows > $i){
                    $query->data_seek($i);
                    $datarow = $query->fetch_array();
                    $idbloqueado = $datarow['id_bloqueado'];
                    //pesquisa username do bloqueado
                        $sql2 = "SELECT username FROM utilizadores WHERE id=$idbloqueado LIMIT 1";
                        $query2 = mysqli_query($link_bd, $sql2);
                        if(!$query2) die("nao deu pesquisa username do bloquedado");
                        $r = mysqli_fetch_assoc($query2);
                        if($query2->num_rows > 0){
                            $usernameb = htmlspecialchars($r['username']);
                        }else{
                            $usernameb = 'Utilizador bloqueado';
                        }
                    //---------------
                    $imaisum = $i + 1;
                    if($query->num_rows > $imaisum){
                        $tpl_content = str_replace('()-definirutilizador-()','<font size = "4"><a href="perfil.php?id='.$idbloqueado.'">'.$usernameb.'</a></font><font size = "2">(<a href="bloquear.php?id='.$idbloqueado.'" title="Desbloquear este utilizador" onclick="return confirmar_desbloquear(); return FALSE;">Desbloquear</a>)</font> ()-definirutilizador-()',$tpl_content);
                    }else{
                        $tpl_content = str_replace('()-definirutilizador-()','<font size = "4"><a href="perfil.php?id='.$idbloqueado.'">'.$usernameb.'</a></font><font size = "2">(<a href="bloquear.php?id='.$idbloqueado.'" title="Desbloquear este utilizador" onclick="return confirmar_desbloquear(); return FALSE;">Desbloquear</a>)</font>',$tpl_content);
                    }
                    $i++;
                }else{
                    $i = 32;
                    $tpl_content = str_replace('()-definirutilizador-()','',$tpl_content);
                }
                
            }
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


