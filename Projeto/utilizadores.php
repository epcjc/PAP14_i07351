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
        
        

        $tpl_content = file_get_contents('TPL/utilizadores.tpl');
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
        //---------------------------------------------------------
        //-----------------------------------------------------------------
        //----------------------Começa substituiçao dos utilizadores e definições das paginas encontradas, dos botoes proxima e anterior
                       //--------------------------------------------------------------------------------
                       //-----------------------------------------------------------------------------------
          //definicao dos arrays
          $ufoi = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0);
          $uid = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          $unome = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          $udatahora = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          $udescricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          $uimagem = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          $uimagemfinal = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'');
          
          
                  
          
                 //---------------------------------------------------------------------------------     
          ////----------------Caso 1--SEM NENHUMA LETRA como parametro(todos os utilizadores)-------
                       //.-----------------------------------------------------
    if(!isset($_GET['pesquisa']) || $_GET['pesquisa'] == ''){    
          $home = str_replace('()-pesquisa-()', '', $home);
            $foix = 1;
          if(!isset($_GET['letra']) || $_GET['letra'] == '' || strlen($_GET['letra']) > 1){
         
                  $foix = 0;
              
          }
          
          if(!isset($_GET['letra']) || $foix != 1){
            //ordena utilizadores por datahora, apresenta os ultimos 9 
            //verificar o total de utilizadores a apresentar para definir botoes 
            $sql = 'select count(*) from utilizadores';
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die("nao foi possivel fazer a query em utilizadores");
            }
            while($result = mysqli_fetch_assoc($query)){
                $totalusers = $result['count(*)'];
            }
            if(!isset($totalusers)){
                die("nao foi possivel fazer a query em utilizadores 2");
            }
            if($totalusers > 9){
               $totalpaginas = ceil($totalusers / 9);
            }else{
                $totalpaginas = 1;
            }  
            //verifica a página em q estamos
            if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] <= $totalpaginas && $_GET['p'] != 0){
                $numeropagina = $_GET['p'];
            }else{
                $numeropagina = 1;
            }
            //definiçao dos botoes prox e anterior, e informacoes das paginas
            $home = str_replace('()-numeropaginas-()','Página '.$numeropagina.' de '.$totalpaginas.'. Total de '.$totalusers.' utilizadores.', $home);
           if($numeropagina == 1){
               $home = str_replace('()-botaoanterior-()','',$home);
                }else{
               $paginaanterior = $numeropagina-1;
               $home = str_replace('()-botaoanterior-()','<a  href="utilizadores.php?p='.$paginaanterior.'">&#8592; Anterior </a>', $home);
                }
           if($numeropagina >= $totalpaginas){
               $home = str_replace('()-botaoproxima-()','',$home);
           }else{
               $paginaprox = $numeropagina + 1;
               $home = str_replace('()-botaoproxima-()','<a href="utilizadores.php?p='.$paginaprox.'"> Próxima &#8594;</a>',$home);
           } 
           //------
           //define qual é o primeiro utilizador do array de 9, de acordo com a página em que estamos
           $primeirouser = $numeropagina * 9 - 8;    
           $ant_primeirouser = $primeirouser - 1;
           //---
           //faz pesquisa SQL do primeiro ao ultimo user, ordenado por data
           $sql = "SELECT * from utilizadores ORDER BY datahora DESC LIMIT $ant_primeirouser, 9";
           $query = mysqli_query($link_bd, $sql);
           if(!$query){
               die("ocorreu um erro na listagem dos utilizadores");
           }
           while($r = mysqli_fetch_assoc($query)){
               //ciclo para definir os valores dos utilizadores
               $i = 0;
               while ($i < 9){
                   if($query->num_rows > $i){          
                        $query->data_seek($i);
                        $datarow = $query->fetch_array(); 
                        $uid[$i+1] = $datarow['id'];
                        $unome[$i+1] = $datarow['username'];
                        $unome[$i+1] = htmlspecialchars($unome[$i+1]);
                        $uimagem[$i+1] = $datarow['imagem'];
                        $udescricao[$i+1] = $datarow['descricao'];
                        $udescricao[$i+1] = htmlspecialchars($udescricao[$i+1]);
                        $udatahora[$i+1] = $datarow['datahora'];
                        $ufoi[$i+1] = 1;
                        $i++;
                   }else{
                       $i = 9;
                   }
               }
           }
           
           //ciclo para substituir valores dos utilizadores
           $i = 1;
           while($i < 10){
               if($ufoi[$i] == 1){
                   //coloca nome,descricao, e link duas vezes
                   $home = str_replace('()-nome'.$i.'-()',$unome[$i],$home);
                   //verifica comprimento da descricao e apresenta apenas 300 caracteres
                   if(strlen($udescricao[$i]) > 300){
                       $descricaocurta = substr($udescricao[$i], 0, 300).'<font size = +1>...</font>';
                   }else{
                       $descricaocurta = $udescricao[$i];
                   }
                   
                   $home = str_replace('()-descricao'.$i.'-()',$descricaocurta,$home);
                   $linkuser = 'perfil.php?id='.$uid[$i];
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home);                   
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home); 
                   //--------
                   //-----//------------define data
                $udia = $udatahora[$i]{8} . $udatahora[$i]{9};
                $umes = $udatahora[$i]{5} . $udatahora[$i]{6};
                if(convertermes($umes)!= FALSE){
                    $umes = convertermes($umes);
                }
                $uano = $udatahora[$i]{0} . $udatahora[$i]{1} . $udatahora[$i]{2} . $udatahora[$i]{3};
                //substitui data
                $home = str_replace('()-dia'.$i.'-()', $udia, $home);
                $home = str_replace('()-mes'.$i.'-()', $umes, $home);
                $home = str_replace('()-ano'.$i.'-()', $uano, $home);
               
               //substitui imagem
               if($uimagem[$i] == 'imagens_utilizadores/imagem.jpg' || $uimagem[$i] == '' || $uimagem[$i] == NULL){
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg';
               }else if(file_exists('imagens_utilizadores/'.$uid[$i].'_lista.jpg')){
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg';
               }else if(file_exists($uimagem[$i])){
                   //cria uma imagem _lista para o utilizador e guarda-a 
                   $novaimagem = WideImage::load($uimagem[$i]); 
                              // Redimensiona a imagem
                   $novaimagem = $novaimagem->resize(436, 273, 'outside');
                   $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                             // Guarda a imagem
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg'; // ex: 5_lista, 82_lista
                   $novaimagem->saveToFile($uimagemfinal[$i], 40); // Coloca a imagem lista no disco
               }else{
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg'; 
               }
               //substitui imagem
               $home = str_replace('()-imagem'.$i.'-()', $uimagemfinal[$i], $home);
               
               
               }else{
                   //substitui os dados vazios
                   //é necessario alterar a variavel textosubs se o ficheiro utilizadores.tpl for alterado
                   $textosubs = '<figure>
	        			<figcaption>
	    					<strong><font size = +1>()-nome'.$i.'-()</font></strong>
	    					<span>()-descricao'.$i.'-()</span>
	    					<em>()-dia'.$i.'-() ()-mes'.$i.'-(), ()-ano'.$i.'-()</em>
	    					<a href="()-linkperfil'.$i.'-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil'.$i.'-()"  class="thumb"><img src="()-imagem'.$i.'-()" alt="Alt text" /></a>
	        		</figure>';
                   $home = str_replace($textosubs,'',$home);
               }
               $i++;
           }
           
               
           
            
            
        //----------------------------------------------------
        //------------fim do caso 1-----------------------
        //---------------------------------------------
        }else{
        
//--------------------------------
        //CASO 2---Com uma letra como parametro
 //---------------------------------------------------

            //verifica se o parametro letra é valido e se sim, apresenta os utilizadores cujo username começa por essa letra
            if (validarletra($_GET['letra']) == false){
                //reencaminha para utilizadores.php sem parametro
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'utilizadores.php';
                header ("location: http://$host$uri/$extra");
            }
                //faz busca mysql para listar 
            //-----------------------------------
            //------------------------------------
            
            //ordena utilizadores por datahora, apresenta os ultimos 9 
            //verificar o total de utilizadores a apresentar para definir botoes 
            $letra = $_GET['letra'];
            
            $sql = "select count(*) from utilizadores WHERE username LIKE '$letra%'";
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die("nao foi possivel fazer a query em utilizadores3");
            }
            while($result = mysqli_fetch_assoc($query)){
                $totalusers = $result['count(*)'];
            }
            if(!isset($totalusers)){
                die("nao foi possivel fazer a query em utilizadores 4");
            }
            if($totalusers > 9){
               $totalpaginas = ceil($totalusers / 9);
            }else{
                $totalpaginas = 1;
            }  
            //verifica a página em q estamos
            if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] <= $totalpaginas && $_GET['p'] != 0){
                $numeropagina = $_GET['p'];
            }else{
                $numeropagina = 1;
            }
            //definiçao dos botoes prox e anterior, e informacoes das paginas
            $home = str_replace('()-numeropaginas-()','Página '.$numeropagina.' de '.$totalpaginas.'. Total de '.$totalusers.' utilizadores.', $home);
           if($numeropagina == 1){
               $home = str_replace('()-botaoanterior-()','',$home);
                }else{
               $paginaanterior = $numeropagina-1;
               $home = str_replace('()-botaoanterior-()','<a  href="utilizadores.php?letra='.$letra.'&p='.$paginaanterior.'">&#8592; Anterior </a>', $home);
                }
           if($numeropagina >= $totalpaginas){
               $home = str_replace('()-botaoproxima-()','',$home);
           }else{
               $paginaprox = $numeropagina + 1;
               $home = str_replace('()-botaoproxima-()','<a href="utilizadores.php?letra='.$letra.'&p='.$paginaprox.'"> Próxima &#8594;</a>',$home);
           } 
           //------
           //define qual é o primeiro utilizador do array de 9, de acordo com a página em que estamos
           $primeirouser = $numeropagina * 9 - 8;    
           $ant_primeirouser = $primeirouser - 1;
           //---
           //faz pesquisa SQL do primeiro ao ultimo user, ordenado por data
           $sql = "SELECT * from utilizadores WHERE username LIKE '$letra%'ORDER BY datahora DESC LIMIT $ant_primeirouser, 9";
           $query = mysqli_query($link_bd, $sql);
           if(!$query){
               die("ocorreu um erro na listagem dos utilizadores5");
           }
           while($r = mysqli_fetch_assoc($query)){
               //ciclo para definir os valores dos utilizadores
               $i = 0;
               while ($i < 9){
                   if($query->num_rows > $i){          
                        $query->data_seek($i);
                        $datarow = $query->fetch_array(); 
                        $uid[$i+1] = $datarow['id'];
                        $unome[$i+1] = $datarow['username'];
                        $uimagem[$i+1] = $datarow['imagem'];
                        $udescricao[$i+1] = $datarow['descricao'];
                        $udatahora[$i+1] = $datarow['datahora'];
                        $ufoi[$i+1] = 1;
                        $i++;
                   }else{
                       $i = 9;
                   }
               }
           }
           
           //ciclo para substituir valores dos utilizadores
           $i = 1;
           while($i < 10){
               if($ufoi[$i] == 1){
                   //coloca nome,descricao, e link duas vezes
                   $home = str_replace('()-nome'.$i.'-()',$unome[$i],$home);
                   //verifica comprimento da descricao e apresenta apenas 300 caracteres
                   if(strlen($udescricao[$i]) > 300){
                       $descricaocurta = substr($udescricao[$i], 0, 300).'<font size = +1>...</font>';
                   }else{
                       $descricaocurta = $udescricao[$i];
                   }
                   
                   $home = str_replace('()-descricao'.$i.'-()',$descricaocurta,$home);
                   $linkuser = 'perfil.php?id='.$uid[$i];
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home);                   
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home); 
                   //--------
                   //-----//------------define data
                $udia = $udatahora[$i]{8} . $udatahora[$i]{9};
                $umes = $udatahora[$i]{5} . $udatahora[$i]{6};
                if(convertermes($umes)!= FALSE){
                    $umes = convertermes($umes);
                }
                $uano = $udatahora[$i]{0} . $udatahora[$i]{1} . $udatahora[$i]{2} . $udatahora[$i]{3};
                //substitui data
                $home = str_replace('()-dia'.$i.'-()', $udia, $home);
                $home = str_replace('()-mes'.$i.'-()', $umes, $home);
                $home = str_replace('()-ano'.$i.'-()', $uano, $home);
               
               //substitui imagem
               if($uimagem[$i] == 'imagens_utilizadores/imagem.jpg' || $uimagem[$i] == '' || $uimagem[$i] == NULL){
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg';
               }else if(file_exists('imagens_utilizadores/'.$uid[$i].'_lista.jpg')){
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg';
               }else if(file_exists($uimagem[$i])){
                   //cria uma imagem _lista para o utilizador e guarda-a 
                   $novaimagem = WideImage::load($uimagem[$i]); 
                              // Redimensiona a imagem
                   $novaimagem = $novaimagem->resize(436, 273, 'outside');
                   $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                             // Guarda a imagem
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg'; // ex: 5_lista, 82_lista
                   $novaimagem->saveToFile($uimagemfinal[$i], 40); // Coloca a imagem lista no disco
               }else{
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg'; 
               }
               //substitui imagem
               $home = str_replace('()-imagem'.$i.'-()', $uimagemfinal[$i], $home);
               
               
               }else{
                   //substitui os dados vazios
                   //é necessario alterar a variavel textosubs se o ficheiro utilizadores.tpl for alterado
                   $textosubs = '<figure>
	        			<figcaption>
	    					<strong><font size = +1>()-nome'.$i.'-()</font></strong>
	    					<span>()-descricao'.$i.'-()</span>
	    					<em>()-dia'.$i.'-() ()-mes'.$i.'-(), ()-ano'.$i.'-()</em>
	    					<a href="()-linkperfil'.$i.'-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil'.$i.'-()"  class="thumb"><img src="()-imagem'.$i.'-()" alt="Alt text" /></a>
	        		</figure>';
                   $home = str_replace($textosubs,'',$home);
               }
               $i++;
           }
            if($totalusers == 0){
                $textosubs2 = '<figure>
	        			<figcaption>
                                                <strong><font size = +1>()-nome1-()</font></strong>
	    					<span>()-descricao1-()</span>
	    					<em>()-dia1-() ()-mes1-(), ()-ano1-()</em>
	    					<a href="()-linkperfil1-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil1-()"  class="thumb"><img src="()-imagem1-()" alt="Alt text" /></a>
	        		</figure>';
                $home = str_replace($textosubs2, 'Não existem utilizadores com esse nome.', $home);
                $home = str_replace('Página 1 de 1. Total de 0 utilizadores.','',$home);
            }
            //----------------------------------
            //---------fim do caso 2---------
            //-------------------------------
        }
    }else{
        //caso de pesquisa com $_POST['pesquisa']
        //----------------------------------
        //-----------------------------------
        $home = str_replace('()-pesquisa-()', $_GET['pesquisa'], $home);
        $pesquisa = mysqli_real_escape_string($link_bd, $_GET['pesquisa']);
            
            $sql = "select count(*) from utilizadores WHERE username LIKE '%$pesquisa%'";
            $query = mysqli_query($link_bd, $sql);
            if(!$query){
                die("nao foi possivel fazer a query em utilizadoresPESQUISA CASCAS");
            }
            while($result = mysqli_fetch_assoc($query)){
                $totalusers = $result['count(*)'];
            }
            if(!isset($totalusers)){
                die("nao foi possivel fazer a query em utilizadores PESQUISA CASCAVV");
            }
            if($totalusers > 9){
               $totalpaginas = ceil($totalusers / 9);
            }else{
                $totalpaginas = 1;
            }  
            //verifica a página em q estamos
            if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] <= $totalpaginas && $_GET['p'] != 0){
                $numeropagina = $_GET['p'];
            }else{
                $numeropagina = 1;
            }
            //definiçao dos botoes prox e anterior, e informacoes das paginas
            $home = str_replace('()-numeropaginas-()','Página '.$numeropagina.' de '.$totalpaginas.'. Total de '.$totalusers.' utilizadores.', $home);
           if($numeropagina == 1){
               $home = str_replace('()-botaoanterior-()','',$home);
                }else{
               $paginaanterior = $numeropagina-1;
               $home = str_replace('()-botaoanterior-()','<a  href="utilizadores.php?pesquisa='.$pesquisa.'&p='.$paginaanterior.'">&#8592; Anterior </a>', $home);
                }
           if($numeropagina >= $totalpaginas){
               $home = str_replace('()-botaoproxima-()','',$home);
           }else{
               $paginaprox = $numeropagina + 1;
               $home = str_replace('()-botaoproxima-()','<a href="utilizadores.php?pesquisa='.$pesquisa.'&p='.$paginaprox.'"> Próxima &#8594;</a>',$home);
           } 
           //------
           //define qual é o primeiro utilizador do array de 9, de acordo com a página em que estamos
           $primeirouser = $numeropagina * 9 - 8;    
           $ant_primeirouser = $primeirouser - 1;
           //---
           //faz pesquisa SQL do primeiro ao ultimo user, ordenado por data
           $sql = "SELECT * from utilizadores WHERE username LIKE '%$pesquisa%'ORDER BY datahora DESC LIMIT $ant_primeirouser, 9";
           $query = mysqli_query($link_bd, $sql);
           if(!$query){
               die("ocorreu um erro na listagem dos utilizadores na pesquisa basda");
           }
           while($r = mysqli_fetch_assoc($query)){
               //ciclo para definir os valores dos utilizadores
               $i = 0;
               while ($i < 9){
                   if($query->num_rows > $i){          
                        $query->data_seek($i);
                        $datarow = $query->fetch_array(); 
                        $uid[$i+1] = $datarow['id'];
                        $unome[$i+1] = $datarow['username'];
                        $uimagem[$i+1] = $datarow['imagem'];
                        $udescricao[$i+1] = $datarow['descricao'];
                        $udatahora[$i+1] = $datarow['datahora'];
                        $ufoi[$i+1] = 1;
                        $i++;
                   }else{
                       $i = 9;
                   }
               }
           }
           
           //ciclo para substituir valores dos utilizadores
           $i = 1;
           while($i < 10){
               if($ufoi[$i] == 1){
                   //coloca nome,descricao, e link duas vezes
                   $home = str_replace('()-nome'.$i.'-()',$unome[$i],$home);
                   //verifica comprimento da descricao e apresenta apenas 300 caracteres
                   if(strlen($udescricao[$i]) > 300){
                       $descricaocurta = substr($udescricao[$i], 0, 300).'<font size = +1>...</font>';
                   }else{
                       $descricaocurta = $udescricao[$i];
                   }
                   
                   $home = str_replace('()-descricao'.$i.'-()',$descricaocurta,$home);
                   $linkuser = 'perfil.php?id='.$uid[$i];
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home);                   
                   $home = str_replace('()-linkperfil'.$i.'-()',$linkuser,$home); 
                   //--------
                   //-----//------------define data
                $udia = $udatahora[$i]{8} . $udatahora[$i]{9};
                $umes = $udatahora[$i]{5} . $udatahora[$i]{6};
                if(convertermes($umes)!= FALSE){
                    $umes = convertermes($umes);
                }
                $uano = $udatahora[$i]{0} . $udatahora[$i]{1} . $udatahora[$i]{2} . $udatahora[$i]{3};
                //substitui data
                $home = str_replace('()-dia'.$i.'-()', $udia, $home);
                $home = str_replace('()-mes'.$i.'-()', $umes, $home);
                $home = str_replace('()-ano'.$i.'-()', $uano, $home);
               
               //substitui imagem
               if($uimagem[$i] == 'imagens_utilizadores/imagem.jpg' || $uimagem[$i] == '' || $uimagem[$i] == NULL){
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg';
               }else if(file_exists('imagens_utilizadores/'.$uid[$i].'_lista.jpg')){
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg';
               }else if(file_exists($uimagem[$i])){
                   //cria uma imagem _lista para o utilizador e guarda-a 
                   $novaimagem = WideImage::load($uimagem[$i]); 
                              // Redimensiona a imagem
                   $novaimagem = $novaimagem->resize(436, 273, 'outside');
                   $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                             // Guarda a imagem
                   $uimagemfinal[$i] = 'imagens_utilizadores/'.$uid[$i].'_lista.jpg'; // ex: 5_lista, 82_lista
                   $novaimagem->saveToFile($uimagemfinal[$i], 40); // Coloca a imagem lista no disco
               }else{
                   $uimagemfinal[$i] = 'imagens_utilizadores/imagem_lista.jpg'; 
               }
               //substitui imagem
               $home = str_replace('()-imagem'.$i.'-()', $uimagemfinal[$i], $home);
               
               
               }else{
                   //substitui os dados vazios
                   //é necessario alterar a variavel textosubs se o ficheiro utilizadores.tpl for alterado
                   $textosubs = '<figure>
	        			<figcaption>
	    					<strong><font size = +1>()-nome'.$i.'-()</font></strong>
	    					<span>()-descricao'.$i.'-()</span>
	    					<em>()-dia'.$i.'-() ()-mes'.$i.'-(), ()-ano'.$i.'-()</em>
	    					<a href="()-linkperfil'.$i.'-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil'.$i.'-()"  class="thumb"><img src="()-imagem'.$i.'-()" alt="Alt text" /></a>
	        		</figure>';
                   $home = str_replace($textosubs,'',$home);
               }
               $i++;
           }
            if($totalusers == 0){
                $textosubs2 = '<figure>
	        			<figcaption>
                                                <strong><font size = +1>()-nome1-()</font></strong>
	    					<span>()-descricao1-()</span>
	    					<em>()-dia1-() ()-mes1-(), ()-ano1-()</em>
	    					<a href="()-linkperfil1-()" class="opener"></a>
		        		</figcaption>
		        		
		        		<a href="()-linkperfil1-()"  class="thumb"><img src="()-imagem1-()" alt="Alt text" /></a>
	        		</figure>';
                $home = str_replace($textosubs2, 'Não existem utilizadores com esse nome.', $home);
                $home = str_replace('Página 1 de 1. Total de 0 utilizadores.','',$home);
            }
        //--------------------------------
        //---------------------acaba caso da pesquisa
        //-----------------------------------------------------
    }      
        print $home; 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



