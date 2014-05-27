<?php

include_once 'includes/seguranca.php';

include_once 'securimage/securimage.php';
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
  echo '<br/><div align="center">O codigo de seguranca inserido esta errado.<br /><br /></div>';
  echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e tente novamente.</div>';
  exit;
}


            
            // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
               //envia de volta se nao estiver logado       
              if (!isset($_SESSION['utilizador_id']) || !isset($_SESSION['utilizador_username'])){
                  
                    //envia-o de volta para o index
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    $extra = 'index.php';
                    header ("location: http://$host$uri/$extra");


              }        
                  
                 //-----------    

             if($_POST['titulo'] != '' && $_POST['conteudo'] != ''){
                        $titulo = $_POST['titulo'];
                        $titulo = mysqli_real_escape_string($link_bd, $titulo);
                        $conteudo = $_POST['conteudo'];
                        $conteudo = mysqli_real_escape_string($link_bd, $conteudo);
                        $userid = $_SESSION['utilizador_id'];
                        $sql = "INSERT INTO mensagens_administracao (titulo, conteudo, id_utilizador) VALUES ('$titulo','$conteudo','$userid')";
                        $stmt = $link_bd->prepare($sql);
                        if ($stmt) {
                            //$stmt->bind_param('iss', $varID, $var1, $var2);

                            if ($stmt->execute()) {
                                $success = TRUE;   //or something like that
                             }else{
                                 $success = FALSE;//
                             }
                        }

                        if($success == FALSE){
                        die('houve um erro na query [' . $link_bd->error . ']');
                        }
             }
                        
                        
                        //---------------------------------------------------------------------
                  //redireciona-o para o index
                        $msg = "O sua mensagem para a administração foi enviada com sucesso.";
                        $_SESSION['mensagem_sucesso'] = $msg;
                        //redireciona para o index
                        $host = $_SERVER['HTTP_HOST'];
                        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                        $extra = 'index.php';
                        header ("location: http://$host$uri/$extra");