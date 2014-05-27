        <?php
        // put your code here
        include_once'WideImage/lib/WideImage.php';
        include_once'includes/seguranca.php';
        include_once'includes/funcoes.php';
        $_SESSION["pagina"] = $_SERVER['PHP_SELF'];
        global $_SG;
                    // conectar bd
            
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
           //------------
        
        $home = file_get_contents('HTML/imain.html');
        
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
                

           
             //vai buscar o username          
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
                while ($resultado = mysqli_fetch_assoc($query)) {      //encontra o numero de mensagens por ler
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
                 //É NECESSARIO APAGAR AS IMAGENS adaptadas: *_pequena e *_media quando o utilizador altera a imagem em atualizarconta.php
             }
                
             //----------------------------------------------
            
//coloca imagem pequena na pagina
            $htmlimg = '<img src="'.$pathcompleto.'" alt="">';
            $tpl_separadorcima = str_replace("()-imagempequena-()", $htmlimg, $tpl_separadorcima);
        }
        $tpl_menu = file_get_contents('TPL/menu.tpl');
        $tpl_content = file_get_contents('TPL/content.tpl');
        $tpl_headline = file_get_contents('TPL/headline.tpl');
        if(isset($_SESSION['utilizador_id']) && isset($_SESSION['utilizador_username'])){
            $tpl_headline = str_replace('Faça o <a href="registo.php">registo</a> e junte-se a nós','',$tpl_headline);
        }
        $tpl_homeblock1 = file_get_contents('TPL/homeblock1.tpl');
        $tpl_homeblock2 = file_get_contents('TPL/homeblock2.tpl');
        $tpl_footer = file_get_contents('TPL/footer.tpl');
        $tpl_footerbottom = file_get_contents('TPL/footerbottom.tpl');
        
        $home = str_replace("()-separadorcima-()", $tpl_separadorcima, $home);
        $home = str_replace("()-top-open-()", $tpl_topopen, $home); //imagem q abre separador_cima
        $home = str_replace("()-menu-()", $tpl_menu, $home);
        $home = str_replace("()-content-()", $tpl_content, $home);
        $home = str_replace("()-headline-()", $tpl_headline, $home);
        $home = str_replace("()-homeblock1-()", $tpl_homeblock1, $home);
        $home = str_replace("()-homeblock2-()", $tpl_homeblock2, $home);
        $home = str_replace("()-footer-()", $tpl_footer, $home);
        $home = str_replace("()-footerbottom-()", $tpl_footerbottom, $home);
        
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
        

 
                       
                       
         //------------------------------------------------------------------------              
        //------------------~COMEÇA SUBSTITUIÇAO da parte do homeblock1 (noticias recentes)
        //----------------------------------------------------------------------
        
        //pesquisa 6 noticias recentes e guarda em arrays numerados de 1 a 6
        $nfoi = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
        
        $ndatahora = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $nuserid = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $noticiaid = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $ntitulo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $nconteudo = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $nimagem = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        $ncomentarios = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        
        $imagemfinal = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'');
        
        $sql = "SELECT * FROM noticias ORDER BY datahora DESC LIMIT 6";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query nas noticias recentes');
        }
        $i = 0;
        while ($i < 6){
                if($query->num_rows > $i){         
            //define dados das noticias 
                    $query->data_seek($i);
                    $datarow = $query->fetch_array();         
                    $ndatahora[$i+1] = $datarow['datahora'];
                    $nuserid[$i+1] = $datarow['id_utilizador'];
                    $noticiaid[$i+1] = $datarow['id'];
                    $ntitulo[$i+1] = $datarow['titulo'];
                    $ntitulo[$i+1] = htmlspecialchars($ntitulo[$i+1]);
                    $nconteudo[$i+1] = $datarow['conteudo'];
                    $nconteudo[$i+1] = htmlspecialchars($nconteudo[$i+1]);
                    $nimagem[$i+1] = $datarow['imagem'];
                    $ncomentarios[$i+1] = $datarow['ncomentarios'];
                    $nfoi[$i+1] = 1;
                    $i++;
                }else{
                   $i=6;
                }  
        }
        //faz a substituição das noticias
      
        $i = 1;
        while ($i < 7){
            if($nfoi[$i] == 1){
                //substitui pelos dados
                $home = str_replace('()-ntitulo'.$i.'-()', $ntitulo[$i], $home);
                //verifica tamanho do conteudo e apresenta so os 250 primeiros caracteres
                if(strlen($nconteudo[$i]) > 250){
                    $conteudor = substr($nconteudo[$i], 0, 250).'<font size=+1>...</font>';
                }else{
                    $conteudor = $nconteudo[$i];
                }
                //---------------------------------------------------------------------
                $home = str_replace('()-nconteudo'.$i.'-()', $conteudor, $home);
                $home = str_replace('()-nlink'.$i.'-()', $noticiaid[$i], $home);
                $home = str_replace('()-nlink'.$i.'-()', $noticiaid[$i], $home);
                
                //------------define data
                $ndia = $ndatahora[$i]{8} . $ndatahora[$i]{9};
                $nmes = $ndatahora[$i]{5} . $ndatahora[$i]{6};
                if(convertermes($nmes)!= FALSE){
                    $nmes = convertermes($nmes);
                }
                $nano = $ndatahora[$i]{0} . $ndatahora[$i]{1} . $ndatahora[$i]{2} . $ndatahora[$i]{3};
                $nhh = $ndatahora[$i]{11} . $ndatahora[$i]{12};
                $nmm = $ndatahora[$i]{14} . $ndatahora[$i]{15};
                //substitui data
                $home = str_replace('()-ndia'.$i.'-()', $ndia, $home);
                $home = str_replace('()-nmes'.$i.'-()', $nmes, $home);
                $home = str_replace('()-nano'.$i.'-()', $nano, $home);
                $home = str_replace('()-nHH'.$i.'-()', $nhh, $home);
                $home = str_replace('()-nMM'.$i.'-()', $nmm, $home);
                //----------------------
                //Verifica se há uma imagem para a noticia, se sim cria uma imagem pequena para a noticia se não existir, se nao usa uma default
         /*       $sql = "SELECT imagem FROM noticias WHERE id = '$noticiaid[$i]' LIMIT 1";
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die('nao foi possivel executar a query nas noticias recentes');
                } 
                $nimagem = '';
                $imagemfinal = '';
                while ($resultado = mysqli_fetch_assoc($query)){
                    $nimagem = $resultado['imagem'];
                    } */
                
                if($nimagem[$i] == 'imagens_noticias/imagem.jpg' || $nimagem[$i] == NULL || $nimagem[$i] == ''){
                    $imagemfinal[$i] = 'imagens_noticias/imagem_pequena.jpg';
                }else{
                    
                    //verifica se já foi criada uma imagem pequena para a noticia
                    if(file_exists('imagens_noticias/'.$noticiaid[$i].'_pequena.jpg')){
                        $imagemfinal[$i] = 'imagens_noticias/'.$noticiaid[$i].'_pequena.jpg';
                    }else if(file_exists($nimagem[$i])){
                        //redimensiona a imagem e guarda-a como [id_noticia]_pequena.jpg
                            $novaimagem = WideImage::load($nimagem[$i]); 
                              // Redimensiona a imagem
                            $novaimagem = $novaimagem->resize(436, 273, 'outside');
                            $novaimagem = $novaimagem->crop('center', 'center', 436, 273);
                             // Guarda a imagem
                            $imagemfinal[$i] = 'imagens_noticias/'.$noticiaid[$i].'_pequena.jpg'; // ex: 5_pequena, 82_pequena
                            $novaimagem->saveToFile($imagemfinal[$i], 40); // Coloca a imagem pequena no disco

                    }
                }
                
                if($imagemfinal[$i] != ''){
                //substitiu imagem no home
                    $home = str_replace('()-nimagem'.$i.'-()', $imagemfinal[$i], $home);
                }
                //------------------------------
                
                
            }else{
                //apaga a noticia completa
                $noticiatpl = '<figcaption>
	        					<strong>()-ntitulo'.$i.'-()</strong>
	        					<span>()-nconteudo'.$i.'-()</span>
	        					<em>()-ndia'.$i.'-() ()-nmes'.$i.'-(), ()-nano'.$i.'-()        ()-nHH'.$i.'-():()-nMM'.$i.'-()</em>
	        					<a href="noticia.php?id=()-nlink'.$i.'-()" class="opener"></a>
			        		</figcaption>
			        		
			        		<a href="noticia.php?id=()-nlink'.$i.'-()"  class="thumb"><img src="()-nimagem'.$i.'-()" alt="" /></a>'; //define a noticia com html para substituir se não existir
            //NOTA: è preciso substituir o texto da variavel noticiatpl se o ficheiro homeblock1 for alterado, para que seja detetado
                $home = str_replace($noticiatpl, '', $home);
            }
            $i ++;                
        }
        if($nfoi[1] == 0 && $nfoi[2] == 0 && $nfoi[3] == 0 && $nfoi[4] == 0 && $nfoi[5] == 0 && $nfoi[6] == 0){
            $home = str_replace('<span>NOTÍCIAS RECENTES</span>', '<span>Ainda não há notícias</span>', $home);
            $home = str_replace('<a href="noticias.php" class="more-link right">Mais notícias  &#8594;</a>', '', $home);
        }
        //TERMINA HOME BLOCK 1 - NOtICIAS RECENTES --------------------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------
        //------------
         
        //------------------------------------------------------------------------              
        //------------------~COMEÇA SUBSTITUIÇAO da parte do homeblock2 (uploads recentes)
        //----------------------------------------------------------------------
        
        //pesquisa 4 uploads recentes e guarda em arrays numerados de 1 a 4
        $ufoi = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
        
        $udatahora = array(1=>'',2=>'',3=>'',4=>'');
        $uid = array(1=>'',2=>'',3=>'',4=>'');
        $utitulo = array(1=>'',2=>'',3=>'',4=>'');
        $udescricao = array(1=>'',2=>'',3=>'',4=>'');
        $uimagem1 = array(1=>'',2=>'',3=>'',4=>'');
        $uimagem2 = array(1=>'',2=>'',3=>'',4=>'');
        $uimagem3 = array(1=>'',2=>'',3=>'',4=>'');
        $uimagem4 = array(1=>'',2=>'',3=>'',4=>'');
        
        $imagemfinal = array(1=>'',2=>'',3=>'',4=>'');
        
        $sql = "SELECT id, titulo, descricao, imagem1, imagem2, imagem3, imagem4, datahora FROM uploads ORDER BY datahora DESC LIMIT 4";
        $query = mysqli_query($link_bd, $sql);
        if(!$query){
            die('nao foi possivel executar a query nas uploads recentes - 111');
        }
        $i = 0;
        while ($i < 4){
                if($query->num_rows > $i){         
            //define dados das noticias 
                    $query->data_seek($i);
                    $datarow = $query->fetch_array();         
                    $udatahora[$i+1] = $datarow['datahora'];
                    $uid[$i+1] = $datarow['id'];
                    $utitulo[$i+1] = $datarow['titulo'];
                    $utitulo[$i+1] = htmlspecialchars($utitulo[$i+1]);
                    $udescricao[$i+1] = $datarow['descricao'];
                    $udescricao[$i+1] = htmlspecialchars($udescricao[$i+1]);
                    $uimagem1[$i+1] = $datarow['imagem1'];
                    $uimagem2[$i+1] = $datarow['imagem2'];
                    $uimagem3[$i+1] = $datarow['imagem3'];
                    $uimagem4[$i+1] = $datarow['imagem4'];
                    $ufoi[$i+1] = 1;
                    $i++;
                }else{
                   $i=4;
                }  
        }
        //faz a substituição das noticias
      
        $i = 1;
        while ($i < 5){
            if($ufoi[$i] == 1){
                //substitui pelos dados
                if(strlen($utitulo[$i]) > 20){
                    $titulor = substr($utitulo[$i], 0, 20).'<font size=+1>...</font>';
                }else{
                    $titulor = $utitulo[$i];
                }
                
                $home = str_replace('()-utitulo'.$i.'-()', $titulor, $home);
                //verifica tamanho do conteudo e apresenta so os 250 primeiros caracteres
                if(strlen($udescricao[$i]) > 125){
                    $descricaor = substr($udescricao[$i], 0, 125).'<font size=+1>...</font>';
                }else{
                    $descricaor = $udescricao[$i];
                }
                //---------------------------------------------------------------------
                $home = str_replace('()-udescricao'.$i.'-()', $descricaor, $home);
                $home = str_replace('()-ulink'.$i.'-()', 'trabalho.php?id='.$uid[$i], $home);
                $home = str_replace('()-ulink'.$i.'-()', 'trabalho.php?id='.$uid[$i], $home);
                
                //------------define data
                $udia = $udatahora[$i]{8} . $udatahora[$i]{9};
                $umes = $udatahora[$i]{5} . $udatahora[$i]{6};
                if(convertermes($umes)!= FALSE){
                    $umes = convertermes($umes);
                }
                $uano = $udatahora[$i]{0} . $udatahora[$i]{1} . $udatahora[$i]{2} . $udatahora[$i]{3};
                $uhh = $udatahora[$i]{11} . $udatahora[$i]{12};
                $umm = $udatahora[$i]{14} . $udatahora[$i]{15};
                //substitui data
                $home = str_replace('()-udia'.$i.'-()', $udia, $home);
                $home = str_replace('()-umes'.$i.'-()', $umes, $home);
                $home = str_replace('()-uano'.$i.'-()', $uano, $home);
                $home = str_replace('()-uHH'.$i.'-()', $uhh, $home);
                $home = str_replace('()-uMM'.$i.'-()', $umm, $home);
                //----------------------
                //Verifica se há uma imagem para a noticia, se sim cria uma imagem pequena para a noticia se não existir, se nao usa uma default
         /*       $sql = "SELECT imagem FROM noticias WHERE id = '$noticiaid[$i]' LIMIT 1";
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die('nao foi possivel executar a query nas noticias recentes');
                } 
                $nimagem = '';
                $imagemfinal = '';
                while ($resultado = mysqli_fetch_assoc($query)){
                    $nimagem = $resultado['imagem'];
                    } */
                
                //define qual imagem será mostrada
                if($uimagem1[$i] != NULL && $uimagem1[$i] != '' && $uimagem1[$i] != 'upload/imagem.jpg'){
                    $imagem = $uimagem1[$i];
                }else if($uimagem2[$i] != NULL && $uimagem2[$i] != '' && $uimagem2[$i] != 'upload/imagem.jpg'){
                    $imagem = $uimagem2[$i];
                }else if($uimagem3[$i] != NULL && $uimagem3[$i] != '' && $uimagem3[$i] != 'upload/imagem.jpg'){
                    $imagem = $uimagem3[$i];
                }else if($uimagem4[$i] != NULL && $uimagem4[$i] != '' && $uimagem4[$i] != 'upload/imagem.jpg'){
                    $imagem = $uimagem4[$i];
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
                        $uimagem = WideImage::loadFromFile($imagem);
                       // Redimensiona a imagem
                        $uimagem = $uimagem->resize(436, 273, 'outside');
                        $uimagem = $uimagem->crop('center', 'center', 436, 273);
                            // Guarda a imagem
                        $imagem = $checkimg;
                        $uimagem->saveToFile($imagem, 40); // Coloca a imagem 
                    }else{
                        $imagem = 'upload/imagem_pequena.jpg';
                    }
                }
                //--------------------------------
                $home = str_replace('()-uimagem'.$i.'-()', $imagem, $home);
               
                //------------------------------
                
                
            }else{
                //apaga a noticia completa
                $uploadtpl = '<figure>
		        			<figcaption>
	        					<strong>()-utitulo'.$i.'-()</strong>
	        					<span>()-udescricao'.$i.'-()</span>
	        					<em>()-udia'.$i.'-() ()-umes'.$i.'-(), ()-uano'.$i.'-() ()-uHH'.$i.'-():()-uMM'.$i.'-()</em>
	        					<a href="()-ulink'.$i.'-()" class="opener"></a>
			        		</figcaption>
			        		
			        		<a href="()-ulink'.$i.'-()"  class="thumb"><img src="()-uimagem'.$i.'-()" alt="Alt text" /></a>
		        		</figure>'; //define a noticia com html para substituir se não existir
            //NOTA: è preciso substituir o texto da variavel noticiatpl se o ficheiro homeblock1 for alterado, para que seja detetado
                $home = str_replace($uploadtpl, '', $home);                
            }
            $i ++;                
        }
                if($ufoi[1] == 0 && $ufoi[2] == 0 && $ufoi[3] == 0 && $ufoi[4] == 0){
                    $home = str_replace('<span>PROJETOS/TRABALHOS RECENTES</span>', '<span>Ainda não há projetos/trabalhos</span>', $home);
                    $home = str_replace('<a href="downloads.php" class="more-link right">Mais projetos/trabalhos  &#8594;</a>', '', $home);
                }
        //TERMINA HOME BLOCK 2 - UPLOADS RECENTES --------------------------------------------------------------
        //--------------------------------------------------------------------------------------------------------------
        //------------
        

                              //------------------------------------------------------------------------
        //---------------------------COMEÇA DEFINIÇAO DA GALERIA--------------------------               
        //------------------------------------------------------------------------               
         //esta galeria apresenta os ultimos 10 registos na base de dados
         //-------------------------------
           //procura o total de registos na base de dados
           $sql = 'SELECT count(*) FROM galeria';
           $query = mysqli_query($link_bd, $sql);
           if(!$query){
               die("morreu na contagem da galeria no index");
           }
           while ($r = mysqli_fetch_assoc($query)){
               $total_galeria = $r['count(*)'];
           }
           if (!isset($total_galeria) || $total_galeria == 0 || $total_galeria == NULL){
               //define a galeria com imagem em branco
               $definidanull = '<li>
					      <img src="HTML/img/slides/01.jpg" alt="alt text" />
					    </li>
					    <li>
					      <img src="HTML/img/slides/02.jpg" alt="alt text" />
					      <p class="flex-caption">Ainda não existem imagens nesta galeria.</p>
					    </li>
					    <li>
					      <img src="HTML/img/slides/03.jpg" alt="alt text" />
					    </li>';
               $home = str_replace('()-definir_galeria-()', $definidanull, $home);
           }else{
               //define galeria
               $gimagem = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'');
               $gdescricao = array(1=>'', 2=>'', 3=>'', 4=>'', 5=>'', 6=>'', 7=>'', 8=>'', 9=>'', 10=>'');
               $gfoi = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0, 10=>0);
               
               //define dados nos arrays
               $sql = 'SELECT imagem, descricao FROM galeria ORDER BY datahora DESC LIMIT 10';
               $query = mysqli_query($link_bd, $sql);
               if (!$query) {
                   die('morreu a definir a galeria em index');
               }
               while($r = mysqli_fetch_assoc($query)){
                   $i = 0;
                   while($i < 10){
                        if($query->num_rows > $i){
                            $query->data_seek($i);
                            $datarow = $query->fetch_array();
                            $gimagem[$i+1] = $datarow['imagem'];
                            $gdescricao[$i+1] = $datarow['descricao'];
                            $gfoi[$i+1] = 1;
                            $i++;
                        }else{
                            $i = 10;
                        }
                   }
               }
               //substitui dados pela galeria
               $i = 1;
               while ($i < 11){
                    if($gfoi[$i]==1){
                       $novafoto = file_get_contents('TPL/galeria/definir_galeria.tpl');
                       $novafoto = str_replace('()-linkimagem-()',$gimagem[$i], $novafoto);
                       $novafoto = str_replace('()-descricao-()',$gdescricao[$i], $novafoto);
                       if($i < 10){
                           $home = str_replace('()-definir_galeria-()', $novafoto.'()-definir_galeria-()', $home);
                       }else{
                           $home = str_replace('()-definir_galeria-()', $novafoto, $home);
                       }
                      $i++; 
                   }else{
                       $home = str_replace('()-definir_galeria-()', '', $home);
                       $i = 11;
                   }
               }
               
           }
                       
          
         //------------------------------------------------------------------------
      //--------------------------ACABA DEFINIÇAO DA GALERIA------------------
         //------------------------------------------------------------------------
        print $home;       
        
        ?>