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
        
        

        $tpl_content = file_get_contents('TPL/perfil.tpl');
//-------------------------------------------------------------        
//-------começa definiçao e substituicao do $tpl_content
        //-------------------------------------------------------------
        //-------------------------------------------------------------
        if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
            $id_utilizador = $_GET['id'];
        }else{
            //Reencaminha para utilizadores.php
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'utilizadores.php';
            header ("location: http://$host$uri/$extra");
        }
        
        //verifica se foi adicionado parametro para remover favorito
        if(isset($_GET['removerfavorito']) && $_GET['removerfavorito'] == 'sim' && isset($_SESSION['utilizador_id'])){
            $sql = 'SELECT id FROM favoritos_utilizadores WHERE id_utilizador = '.$_SESSION['utilizador_id'].' AND id_favorito = '.$id_utilizador.' LIMIT 1';
            $query = mysqli_query($link_bd, $sql);
            if(!$query) die(" nao deu 1jdasj fav");
            if($query->num_rows > 0){
                //apaga favorito
                $r = mysqli_fetch_assoc($query);
                $idfav = $r['id'];
                    $sql = "DELETE FROM favoritos_utilizadores WHERE id=$idfav LIMIT 1";
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
                        $_SESSION['mensagem_sucesso'] = 'Este utilizador foi removido dos favoritos com sucesso. ';
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'perfil.php?id='.$id_utilizador;
                        header ("location: http://$host$uri/$extra");
                
            }else{
                $_SESSION['mensagem_erro'] = 'Este utilizador não está nos seus favoritos. ';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'perfil.php?id='.$id_utilizador;
                header ("location: http://$host$uri/$extra");
            }
        }else if(isset($_GET['removerfavorito']) && isset($_SESSION['utilizador_id'])){
                $_SESSION['mensagem_erro'] = 'Não foi possível validar os parametros ao remover o favorito. ';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'perfil.php?id='.$id_utilizador;
                header ("location: http://$host$uri/$extra");
        }else if(isset($_GET['removerfavorito']) && !isset($_SESSION['utilizador_id'])){
                $_SESSION['mensagem_erro'] = 'É necessário efetuar o login para remover favoritos. ';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'perfil.php?id='.$id_utilizador;
                header ("location: http://$host$uri/$extra");
        }
        //----------------------------------------------------------
        
        //faz pesquisa para guardar os dados do utilizador
        $sql = 'SELECT username, imagem, descricao, datahora, ncomentarios, nvotacoes, nuploads FROM utilizadores WHERE id = '.$id_utilizador.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("nao foi possivel perfilphp");
        }
        $foi = 0;
        while ($r = mysqli_fetch_assoc($query)){
            $nome = $r['username'];
            $nome = htmlspecialchars($nome);
            $imagem = $r['imagem'];
            $descricao = $r['descricao'];
            $descricao = htmlspecialchars($descricao);
            $datahora = $r['datahora'];
            $ncomments = $r['ncomentarios'];
            $nuploads = $r['nuploads'];
            $nvotacoes = $r['nvotacoes'];
            $foi = 1;
        }
        if($foi == 0){
            //envia de volta para index
            $_SESSION['mensagem_erro'] = 'O perfil que procurava não existe.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
        }
        //substitui dados do utilizador
        if($imagem == NULL || $imagem == 'imagens_utilizadores/imagem.jpg' || $imagem == ''){
            $imagem_final = 'imagens_utilizadores/imagem_perfil.jpg';
        }else if(file_exists($imagem)){
            if(file_exists('imagens_utilizadores/'.$id_utilizador.'_perfil.jpg')){
                $imagem_final = 'imagens_utilizadores/'.$id_utilizador.'_perfil.jpg';
            }else{
                //cria imagem perfil para o utilizador
                    $nimagem = WideImage::loadFromFile($imagem);
                   // Redimensiona a imagem
                    $nimagem = $nimagem->resize(960, 573, 'outside');
                    $nimagem = $nimagem->crop('center', 'center', 960, 601);
                        // Guarda a imagem
                    $imagem_final = 'imagens_utilizadores/'.$id_utilizador.'_perfil.jpg';
                    $nimagem->saveToFile($imagem_final, 40); // Coloca a imagem 
            }
        }else{
            $imagem_final = 'imagens_utilizadores/imagem_perfil.jpg';
        }
      
        $tpl_content = str_replace('HTML/img/slides/01.jpg', $imagem_final, $tpl_content);
        $tpl_content = str_replace('()-nome-()', $nome, $tpl_content);
        $tpl_content = str_replace('()-descricao-()', $descricao, $tpl_content);
        $tpl_content = str_replace('()-ncomentarios-()', $ncomments, $tpl_content);
        $tpl_content = str_replace('()-nuploads-()', $nuploads, $tpl_content);
        $tpl_content = str_replace('()-nvotacoes-()', $nvotacoes, $tpl_content);
        $tpl_content = str_replace('()-linkmensagem-()', 'novamensagem.php?id='.$id_utilizador, $tpl_content);
        $tpl_content = str_replace('()-linkfavoritos-()', 'novofavorito.php?t=ut&id='.$id_utilizador, $tpl_content);
        $tpl_content = str_replace('()-linkreportar-()', 'novoreport.php?t=ut&id='.$id_utilizador, $tpl_content);
                        
                //------------define data
                $dia = $datahora{8} . $datahora{9};
                $mes = $datahora{5} . $datahora{6};
                if(convertermes($mes)!= FALSE){
                    $mes = convertermes($mes);
                }
                $ano = $datahora{0} . $datahora{1} . $datahora{2} . $datahora{3};
                //substitui data
                $tpl_content = str_replace('()-dia-()', $dia, $tpl_content);
                $tpl_content = str_replace('()-mes-()', $mes, $tpl_content);
                $tpl_content = str_replace('()-ano-()', $ano, $tpl_content);
                //----------------------
                
                //remove menu adicionar reportar e enviar mensagem se não tiver logado
                if(!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
                    $menusubs = '<div align="left">
                                                <a href="novamensagem.php?id='.$id_utilizador.'" class="launch">Enviar mensagem |</a>
                                                <a href="novofavorito.php?t=ut&id='.$id_utilizador.'" class="launch">| Adicionar aos favoritos |</a>
                                                <a href="novoreport.php?t=ut&id='.$id_utilizador.'" class="launch">| Denunciar</a>
                                                </div>';
                
                    $tpl_content = str_replace($menusubs, '', $tpl_content);
                }else if($_SESSION['utilizador_id'] == $id_utilizador){
                    $menusubs = '<div align="left">
                                                <a href="novamensagem.php?id='.$id_utilizador.'" class="launch">Enviar mensagem |</a>
                                                <a href="novofavorito.php?t=ut&id='.$id_utilizador.'" class="launch">| Adicionar aos favoritos |</a>
                                                <a href="novoreport.php?t=ut&id='.$id_utilizador.'" class="launch">| Denunciar</a>
                                                </div>';
                
                    $tpl_content = str_replace($menusubs, '<div align="left"><a href="gerirconta.php" class="launch">Editar perfil</a></div>', $tpl_content);
                }else{
                    //Verifica se ja adicionou aos favoritos
                    $jaadd = FALSE;
                    $sql = 'SELECT id FROM favoritos_utilizadores WHERE id_favorito = '.$id_utilizador.' AND id_utilizador = '.$_SESSION['utilizador_id'].' LIMIT 1';
                    $query = mysqli_query($link_bd, $sql);
                    if(!$query) die("nao deu para verificar favoritos se ja adicionou");
                    if($query->num_rows > 0){
                        //ja adicionou
                        $jaadd = TRUE;
                    }
                    if($jaadd == TRUE){
                        $tpl_content = str_replace('<a href="novofavorito.php?t=ut&id='.$id_utilizador.'" class="launch">| Adicionar aos favoritos |</a>','<a href="perfil.php?id='.$id_utilizador.'&removerfavorito=sim" class="launch" onclick="return confirmar_removerfavorito(); return FALSE;">| Remover dos favoritos |</a>',$tpl_content);
                    }
                    
                }
                
        


        //Faz pesquisa para definir os 4 uploads recentes do utilizador
        //---------
        $idupl = array(1=>'',2=>'',3=>'',4=>'');
        $img1 = array(1=>'',2=>'',3=>'',4=>'');
        $img2 = array(1=>'',2=>'',3=>'',4=>'');
        $img3 = array(1=>'',2=>'',3=>'',4=>'');
        $img4 = array(1=>'',2=>'',3=>'',4=>'');
        $titulo = array(1=>'',2=>'',3=>'',4=>'');
        $nomeorig = array(1=>'',2=>'',3=>'',4=>'');
        $foiu = array(1=>0,2=>0,3=>0,4=>0);
        $sql = 'SELECT id, imagem1, imagem2, imagem3, imagem4, titulo, nomeoriginal FROM uploads WHERE id_utilizador = '.$id_utilizador.' ORDER BY datahora DESC LIMIT 4';
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die("nao deu querry uplaod 7xnas");
        }
        $i = 0;
        while ($i < 4){
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
                   $i=4;
                } 
        }
        
        
        //ciclo para substituir dados
        $i = 1;
        while($i < 5){
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
                
                $tpl_content = str_replace('()-titulo'.$i.'-()', $titulo[$i], $tpl_content);
                $tpl_content = str_replace('HTML/img/dummies/featured-'.$i.'.jpg', $imagem, $tpl_content);
            
            }else{
                //substitui por nada
                if($i == 4){
                $textrmv = '<figure class="last">
								<a href="()-linkupload4-()" class="thumb"><img src="HTML/img/dummies/featured-4.jpg" alt="Alt text" /></a>
								<a href="()-linkupload4-()" class="heading">()-titulo4-()</a>
							</figure>';    
                }else{
                $textrmv = '<figure>
								<a href="()-linkupload'.$i.'-()" class="thumb"><img src="HTML/img/dummies/featured-'.$i.'.jpg" alt="Alt text" /></a>
								<a href="()-linkupload'.$i.'-()" class="heading">()-titulo'.$i.'-()</a>
							</figure>';
                }
                $tpl_content = str_replace($textrmv, '', $tpl_content);

            }
            $i++;
        }
                if($foiu[1] == 0 && $foiu[2] == 0 && $foiu[3] == 0 && $foiu[4] == 0){
                    $tpl_content = str_replace('<p align="right"><a href="()-linkprojetos-()">Ver mais</a></p>', '', $tpl_content);
                    
                    $tpl_content = str_replace('<div class="related-heading">Uploads</div>', '<div class="related-heading">Este utilizador ainda não enviou nenhum projeto/trabalho</div>', $tpl_content);
                }else if($nuploads <= 4){
                    $tpl_content = str_replace('<p align="right"><a href="()-linkprojetos-()">Ver mais</a></p>', '', $tpl_content);
                }else{
                    
                    $tpl_content = str_replace('()-linkprojetos-()', 'trabalhosutilizador.php?id='.$id_utilizador, $tpl_content);
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



