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
        
        $home = file_get_contents('HTML/upmain.html');
        
        //imain.html contém a página inicial com slider,  headline com informaçao, e os homeblock1 e 2.
        //main.html contém página inicial sem slider, headline dinamico para sub menu, não contém homeblocks. 
        
        $tpl_menu = file_get_contents('TPL/menu.tpl');
        //$tpl_headline = file_get_contents('TPL/headlinemenu.tpl');
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

            
            $tpl_separadorcima = file_get_contents('TPL/separadorcima.tpl');
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
            if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
                $idupl = $_GET['id'];
            }else{
                $_SESSION['mensagem_erro'] = 'Esse projeto não existe.';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header ("location: http://$host$uri/$extra");
                exit;
            }
            //pesquisa para saber se existe e se foi carregado pelo utilizador
            $sql = "SELECT id_utilizador, imagem1, imagem2, imagem3, imagem4, descricao, titulo, preco, categoria, nomeoriginal FROM uploads WHERE id = $idupl";
            $query = mysqli_query($link_bd, $sql);
            if(!$query) die("morreu pesquisa par saber alterar projeto");
            $r = mysqli_fetch_assoc($query);
            if($query->num_rows == 0){
                $_SESSION['mensagem_erro'] = 'Esse projeto não existe.';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header ("location: http://$host$uri/$extra");
                exit;
            }else if($r['id_utilizador'] != $_SESSION['utilizador_id']){
                $_SESSION['mensagem_erro'] = 'Não pode alterar este projeto.';
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'index.php';
                header ("location: http://$host$uri/$extra");
                exit;
            }
            $img = array(1=>$r['imagem1'],2=>$r['imagem2'],3=>$r['imagem3'],4=>$r['imagem4']);
            $idusr = $r['id_utilizador'];
            $descricao = $r['descricao'];
            $titulo = $r['titulo'];
            $preco = $r['preco'];
            $categoria = $r['categoria'];
            $nomeoriginal = $r['nomeoriginal'];
            
            //verifica se foi adicionado parametro para apagar imagem
            
            if(isset($_GET['apagari'])){
                $apagari = 0;
                switch($_GET['apagari']){
                    case 1: $apagari = 1; break;
                    case 2: $apagari = 2; break;
                    case 3: $apagari = 3; break;
                    case 4: $apagari = 4; break;
                }
            
            //remove imagem
                if($apagari != 0 && $img[$apagari] != NULL && $img[$apagari] != '' && $img[$apagari] != 'upload/imagem.jpg'){
                    apagarconteudodir('upload/'.$idusr.'/'.$nomeoriginal.'/imagem'.$apagari);
                    $constnull = NULL;
                    $sql="UPDATE uploads SET imagem$apagari = '$constnull' WHERE id = $idupl LIMIT 1";
                    $stmt = $link_bd->prepare($sql);
                    if(!$stmt) die("amks apagarimagem");
                    if(!$stmt->execute()) die("ajisjda apagarimagem");
                }
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'alterarprojeto.php?id='.$idupl;
                header ("location: http://$host$uri/$extra");
            }
            
            
            //-----------------------------------------
            
        
        
        $home = str_replace("()-separadorcima-()", $tpl_separadorcima, $home);
        $home = str_replace("()-top-open-()", $tpl_topopen, $home);
        $home = str_replace("()-menu-()", $tpl_menu, $home);
        $home = str_replace("()-headline-()", '', $home);
        $home = str_replace("()-homeblock1-()", '', $home);
        $home = str_replace("()-homeblock2-()", '', $home);
        $home = str_replace("()-footer-()", $tpl_footer, $home);
        $home = str_replace("()-footerbottom-()", $tpl_footerbottom, $home);
        
        //coloca tudo no sitio excepto content
        
        

        $tpl_content = file_get_contents('TPL/alterarprojeto.tpl');
        //comeca substituicao de tpl_content
        //imagens
        $tpl_content = str_replace('()-idupl-()', $idupl, $tpl_content);
        
        $i = 1;
        while($i < 5){
            if($img[$i] == NULL || $img[$i] == '' || $img[$i] == 'upload/imagem.jpg'){
                $imagemfinal = 'upload/imagem_miniatura.jpg';
            }else{
                $checkimg = $img[$i];
                $checkimg = substr($checkimg, 0, -4);
                $checkimg = $checkimg.'_miniatura.jpg';
                if(file_exists($checkimg)){
                    $imagemfinal = $checkimg;
                }else if(file_exists($img[$i])){
                        $novaimagem = WideImage::loadFromFile($img[$i]);
                       // Redimensiona a imagem
                        $novaimagem = $novaimagem->resize(218, 137, 'outside');
                        $novaimagem = $novaimagem->crop('center', 'center', 218, 137);
                            // Guarda a imagem
                        $imagemfinal = $checkimg;
                        $novaimagem->saveToFile($imagemfinal, 40); // Coloca a imagem 
                }else{
                    $imagemfinal = 'upload/imagem_miniatura.jpg';
                }
            }
            if($imagemfinal == 'upload/imagem_miniatura.jpg'){
                $imagemfinal = '<img src="upload/imagem_miniatura.jpg">';
                $tpl_content = str_replace('<a href="alterarprojeto.php?id='.$idupl.'&apagari='.$i.'" title="Apagar imagem nº'.$i.'"><img src="HTML/img/botaoapagar.png"></a>','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$tpl_content);
            }else{
                $imagemfinal = '<a href="'.$img[$i].'"><img src="'.$imagemfinal.'"></a>';
            }
            $tpl_content = str_replace('()-imagem'.$i.'-()', $imagemfinal, $tpl_content);
            $i++;
        }
        //titulo descricao e preco
        $tpl_content = str_replace('()-titulo-()', $titulo, $tpl_content);
        $tpl_content = str_replace('()-descricao-()', $descricao, $tpl_content);
        $tpl_content = str_replace('()-preco-()', $preco, $tpl_content);
        //categoria
        $tpl_content = str_replace('<option value="'.$categoria.'">','<option value="'.$categoria.'" selected>', $tpl_content);
        //--------------------------------
        
        
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
?>
