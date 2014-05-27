<?php
include_once '../includes/seguranca.php';
            
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
               //envia de volta se nao estiver logado       
              if(!isset($_SESSION['administrador_id']) || !isset($_SESSION['administrador_username']) || !isset($_SESSION['administrador_password'])){
                  
                    //envia-o de volta para o index
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'admin.php';
                    header ("location: http://$host$uri/$extra");


              }  
              

              
                //insere conteudo e titulo
                $titulo = $_POST['titulo'];
                $titulo = mysqli_real_escape_string($link_bd, $titulo);
                $conteudo = $_POST['conteudo'];
                $conteudo = mysqli_real_escape_string($link_bd, $conteudo);
                $nome = $_POST['nome'];
                $nome = mysqli_real_escape_string($link_bd, $nome);
                //verifica se ja ha pagina com mesmo nome
                $sql = "SELECT id FROM paginas WHERE nome = '$nome'";
                $query = mysqli_query($link_bd, $sql);
                if(!$query){
                    die("nao foi possivel fazer query em inserir pagina");
                }else if($query ->num_rows > 0){
                    //encaminha para nova paginas
                              
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'novapagina.php';
                    header ("location: http://$host$uri/$extra");
                }
                
                $userid = $_SESSION['administrador_id'];
                
                $sql = "INSERT INTO paginas (id_utilizador, nome, titulo, conteudo) VALUES ('$userid', '$nome', '$titulo', '$conteudo')";
                $stmt = $link_bd->prepare($sql);
                if ($stmt) {
                //$stmt->bind_param('iss', $varID, $var1, $var2);
                    if ($stmt->execute()) {
                        $success = TRUE;   
                    }else{
                        $success = FALSE;//
                    }
                }
                if($success == FALSE){
                    die("ocorreu um erro na insercao da pagina");
                }else{
                    $_SESSION['mensagemadmin'] = 'Página adicionada com sucesso. ';

                        }
                
                        
                



                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = 'gerirpaginas.php';
                header ("location: http://$host$uri/$extra");
              
                 //-----------    
