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
        
        

        $tpl_content = file_get_contents('TPL/gerirprojetos.tpl');
//-------------------------------------------------------------        
//-------começa definiçao e substituicao do $tpl_content
        //-------------------------------------------------------------
        //-------------------------------------------------------------
        $id_utilizador = $_SESSION['utilizador_id'];
        
        if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0){
            $numpagina = $_GET['p'];
        }else{
            $numpagina = 1;
        }
        
        //verifica se existe s_session mensagemprojetos
        if(!isset($_SESSION['mensagemprojetos']) || $_SESSION['mensagemprojetos'] == '' || $_SESSION['mensagemprojetos'] == NULL){
            $tpl_content = str_replace('()-mensagemprojetos-()', '', $tpl_content);
        }else{
            $tpl_content = str_replace('()-mensagemprojetos-()', $_SESSION['mensagemprojetos'], $tpl_content);
            unset($_SESSION['mensagemprojetos']);
        }
                
        //verifica se utilizador existe
        $sql = 'SELECT username FROM utilizadores WHERE id = '.$id_utilizador;
        $query = mysqli_query($link_bd, $sql);
        if(!$query || $query->num_rows == 0){
                        //Reencaminha para utilizadores.php
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
        }
        
        

        //verifica o total de uploads do user
        $sql = 'SELECT count(*) FROM uploads WHERE id_utilizador = '.$id_utilizador;
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
                        //Reencaminha para utilizadores.php
            die("morreu trabalhosuser jasj");
        }
        $r = mysqli_fetch_assoc($query);
        if($r['count(*)']== NULL || $r['count(*)']== '' || $r['count(*)']== 0 ){
            $maxuploads = 0;
        }else{
            $maxuploads = $r['count(*)'];
        }
        //define total paginas e botoes
        if($maxuploads <= 12){
            $numpaginas = 1;
        }else{
            $numpaginas = ceil($maxuploads/12);
        }
        if($maxuploads > 0){
            $tpl_content = str_replace('()-totalpaginas-()', 'Página '.$numpagina.' de '.$numpaginas.', num total de '.$maxuploads.' uploads.', $tpl_content);
            if($numpagina < $numpaginas){
                $proxpagina = $numpagina + 1;
                $tpl_content = str_replace('()-proxima-()', '<a href="gerirprojetos.php?p='.$proxpagina.'">Próxima &#8594;</a>', $tpl_content);
            }else{
                $tpl_content = str_replace('()-proxima-()', '', $tpl_content);
            }
            if($numpagina > 1){
                $antpagina = $numpagina - 1;
                $tpl_content = str_replace('()-anterior-()', '<a href="gerirprojetos.php?p='.$antpagina.'">&#8592; Anterior</a>', $tpl_content);
            }else{
                $tpl_content = str_replace('()-anterior-()', '', $tpl_content);
            }
        }else{
            //apaga 
            $tpl_content = str_replace('()-anterior-() ()-totalpaginas-() ()-proxima-()', 'Ainda não enviou nenhum trabalho/projeto.', $tpl_content);
            $tpl_content = str_replace('<div class="clearfix"></div><br/>','',$tpl_content);
            
        }
        
        //define primeiro upload a procurar
        $p_upload = $numpagina * 12 - 12;
        
        //Faz pesquisa para definir os 16 uploads recentes do utilizador
        //---------
        $idupl = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $img1 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $img2 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $img3 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $img4 = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $titulo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $nomeorig = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'');
        $foiu = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
        
        $sql = 'SELECT id, imagem1, imagem2, imagem3, imagem4, titulo, nomeoriginal FROM uploads WHERE id_utilizador = '.$id_utilizador.' ORDER BY datahora DESC LIMIT '.$p_upload.', 12';
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("nao deu querry uplaod 7xnas");
        }
        $i = 0;
        while ($i < 12){
            if($query->num_rows > $i){         
            //define dados das noticias 
                    $query->data_seek($i);
                    $datarow = $query->fetch_array();
                    $idupl[$i+1] = $datarow['id'];
                    $img1[$i+1] = $datarow['imagem1'];
                    $img2[$i+1] = $datarow['imagem2'];
                    $img3[$i+1] = $datarow['imagem3'];
                    $img4[$i+1] = $datarow['imagem4'];
                    $titulo[$i+1] = $datarow['titulo'];
                    $titulo[$i+1] = htmlspecialchars($titulo[$i+1]);
                    if(strlen($titulo[$i+1]) > 35){
                        $titulo[$i+1] = substr($titulo[$i+1], 0, 35).'<font size=+1>...</font>';
                    }
                    $nomeorig[$i+1] = $datarow['nomeoriginal'];
                    $foiu[$i+1] = 1;
                    $i++;
                }else{
                   $i=12;
                } 
        }
        
        
        //ciclo para substituir dados
        $i = 1;
        while($i < 13){
            if($foiu[$i] == 1){
                //define qual imagem será apresentada
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
                ////-------------
                //substitui pelos dados
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
                
                $tpl_content = str_replace('()-linkupload'.$i.'-()', 'trabalho.php?id='.$idupl[$i], $tpl_content);
                $tpl_content = str_replace('()-linkupload'.$i.'-()', 'trabalho.php?id='.$idupl[$i], $tpl_content);
                
                $tpl_content = str_replace('()-titulo'.$i.'-()', '<div align="center">'.$titulo[$i].'</div>', $tpl_content);
                $tpl_content = str_replace('()-opcoes'.$i.'-()', '<div align="center"><font size = "+1"><a href="alterarprojeto.php?id='.$idupl[$i].'">Editar</a> - <a href="apagarprojeto.php?id='.$idupl[$i].'" onclick="return confirmar_apagar(); return FALSE;">Apagar</a></font></div>', $tpl_content);
                $tpl_content = str_replace('HTML/img/dummies/featured-'.$i.'.jpg', $imagem, $tpl_content);
            
            }else{
                //substitui por nada
                if($i == 12 || $i == 8 || $i == 4 ){
                $textrmv = '<figure class="last">
                                                                <a href="()-linkupload'.$i.'-()" class="heading">()-titulo'.$i.'-()</a>
								<a href="()-linkupload'.$i.'-()" class="thumb"><img src="HTML/img/dummies/featured-'.$i.'.jpg" alt="Alt text" /></a>
								<a class="heading">()-opcoes'.$i.'-()</a>
							</figure>';    
                }else{
                $textrmv = '<figure>
                                                                <a href="()-linkupload'.$i.'-()" class="heading">()-titulo'.$i.'-()</a>
								<a href="()-linkupload'.$i.'-()" class="thumb"><img src="HTML/img/dummies/featured-'.$i.'.jpg" alt="Alt text" /></a>
								<a class="heading">()-opcoes'.$i.'-()</a>
							</figure>';
                }
                $tpl_content = str_replace($textrmv, '', $tpl_content);

            }
            $i++;
        }
          
        
        //-----------
                
                
                
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
