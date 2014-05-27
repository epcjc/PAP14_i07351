<?php




include_once("includes/seguranca.php");
include_once("includes/funcoes.php");
include_once'WideImage/lib/WideImage.php';
$_SESSION["pagina"] = $_SERVER['PHP_SELF'];

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
                                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
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
        $tpl_content = file_get_contents('TPL/listadepaginas.tpl');
        //coloca content por substituir
        //---------------------------------------------------------
        //---------------------------FAZ SUBSTITUIÇAO DA PAGINA-----
        //-----------------------------------------------------------
        if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
            $numpagina = $_GET['id'];
        }else{
            $numpagina = 1;
        }
        //Apresenta 18 paginas por pagina
        $sql = 'SELECT count(*) FROM paginas';
        $query = mysqli_query($link_bd, $sql);
        if(!$query) die("epa listadepaginas");
        while($r = mysqli_fetch_assoc($query)){
            $countpaginas = $r['count(*)'];
        }
        if($countpaginas <= 36){
            $numpaginas = 1;
        }else{
            $numpaginas = ceil($countpaginas / 36);
        }
        //Faz substituicao de proxima anterior e totalpaginas
        $tpl_content = str_replace('()-totalpaginas-()','Página '.$numpagina.' de '.$numpaginas.'. Há '.$countpaginas.' páginas geradas no total.', $tpl_content);
        if($numpagina > 1){
            $paganterior = $numpagina - 1;
            $tpl_content = str_replace('()-anterior-()', '<a href="listadepaginas.php?id='.$paganterior.'">&#8592; Anterior</a>', $tpl_content);
        }else{
            $tpl_content = str_replace('()-anterior-()', '', $tpl_content);
        }
        if($numpagina < $numpaginas){
            $pagprox = $numpagina + 1;
            $tpl_content = str_replace('()-proxima-()', '<a href="listadepaginas.php?id='.$pagprox.'">Próxima &#8594;</a>', $tpl_content);
        }else{
            $tpl_content = str_replace('()-proxima-()', '', $tpl_content);
        }
        
        //define qual e a primeira pagina a ser apresentada
        $p_pagina = $numpagina * 36 - 36;
        
        $pid = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'',19=>'',20=>'',21=>'',22=>'',23=>'',24=>'',25=>'',26=>'',27=>'',28=>'',29=>'',30=>'',31=>'',32=>'',33=>'',34=>'',35=>'',36=>'');
        $piduser = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'',19=>'',20=>'',21=>'',22=>'',23=>'',24=>'',25=>'',26=>'',27=>'',28=>'',29=>'',30=>'',31=>'',32=>'',33=>'',34=>'',35=>'',36=>'');
        $pnome = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'',19=>'',20=>'',21=>'',22=>'',23=>'',24=>'',25=>'',26=>'',27=>'',28=>'',29=>'',30=>'',31=>'',32=>'',33=>'',34=>'',35=>'',36=>'');
        $ptitulo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'',16=>'',17=>'',18=>'',19=>'',20=>'',21=>'',22=>'',23=>'',24=>'',25=>'',26=>'',27=>'',28=>'',29=>'',30=>'',31=>'',32=>'',33=>'',34=>'',35=>'',36=>'');
        $pfoi = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0,14=>0,15=>0,16=>0,17=>0,18=>0,19=>0,20=>0,21=>0,22=>0,23=>0,24=>0,25=>0,26=>0,27=>0,28=>0,29=>0,30=>0,31=>0,32=>0,33=>0,34=>0,35=>0,36=>0);
        
        $sql = "SELECT id, id_utilizador, nome, titulo, datahora FROM paginas ORDER BY datahora DESC LIMIT $p_pagina, 36";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("morreu pacx em lista");
        }
        $i = 0;
        while($i < 36){
            if ($query->num_rows > $i){
                $query->data_seek($i);
                $datarow = $query->fetch_array();    
                $pid[$i+1] = $datarow['id'];      
                $piduser[$i+1] = $datarow['id_utilizador'];  
                $pnome[$i+1] = $datarow['nome'];
                $pnome[$i+1] = htmlspecialchars($pnome[$i+1]);
                $ptitulo[$i+1] = $datarow['titulo'];  
                $ptitulo[$i+1] = htmlspecialchars($ptitulo[$i+1]);
                $pfoi[$i+1] = 1;
                $i++;
            }else{
                $i=36;
            }
        }
        
        $conteudo = '<div style="margin-left: 150px; margin-right: 150px;">';
        $i = 1;
        while($i < 37){
            if($pfoi[$i]==1){
                $sql = 'SELECT username FROM utilizadores WHERE id='.$piduser[$i].' LIMIT 1';
                $query = mysqli_query($link_bd, $sql);
                if(!$query) die("madsoda lista a");
                while($r =  mysqli_fetch_assoc($query)){
                    $nome=$r['username'];
                }
                if($i == 36 || $pfoi[$i+1]==0){
                    $conteudo .= ' <a href="pagina.php?id='.$pid[$i].'" title="'.$ptitulo[$i].' - Criada por: '.$nome.'">'.$pnome[$i].'</a>';
                }else{
                    $conteudo .= ' <a href="pagina.php?id='.$pid[$i].'" title="'.$ptitulo[$i].' - Criada por: '.$nome.'">'.$pnome[$i].'</a>,';
                }
                

                $i++;
                
            }else{
                $i = 37;
            }
        }
        $conteudo .= '</div>';
        $tpl_content = str_replace('()-conteudo-()', $conteudo, $tpl_content);
        //----------------------------------------------------------
        //---------------------ACABA SUBSTITUIÇAO
        //_-------------------------------------------------


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



