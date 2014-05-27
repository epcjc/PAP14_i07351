<?php
include_once("includes/seguranca.php");
include_once 'securimage/securimage.php';
        if($_SESSION['tentativaslogin'] >= 3){
            $securimage = new Securimage();
            if ($securimage->check($_POST['captcha_code']) == false) {
              echo '<br/><div align="center">O codigo de seguranca inserido esta errado.<br /><br /></div>';
              echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e tente novamente.</div>';
              exit;
            }else{
               $_SESSION['tentativaslogin'] = 0; 
            }
        }
        

                       // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
//session_start();
//if (isset($_SESSION['utilizador_username'])){
    //redirecionar para pagina login
    // colocar tudo no sitio como o index.php, excepto o separador de cima
    $home = file_get_contents('HTML/imain.html');
    //imain.html contém a página inicial com slider,  headline com informaçao, e os homeblock1 e 2.
        //main.html contém página inicial sem slider, headline dinamico para sub menu, não contém homeblocks. 
    
    
    $tpl_menu = file_get_contents('TPL/menu.tpl');
    $tpl_content = file_get_contents('TPL/content.tpl');
    $tpl_headline = file_get_contents('TPL/headline.tpl');
    $tpl_homeblock1 = file_get_contents('TPL/homeblock1.tpl');
    $tpl_homeblock2 = file_get_contents('TPL/homeblock2.tpl');
    $tpl_footer = file_get_contents('TPL/footer.tpl');
    $tpl_footerbottom = file_get_contents('TPL/footerbottom.tpl');
        
        
    $home = str_replace("()-menu-()", $tpl_menu, $home);
    $home = str_replace("()-content-()", $tpl_content, $home);
    $home = str_replace("()-headline-()", $tpl_headline, $home);
    $home = str_replace("()-homeblock1-()", $tpl_homeblock1, $home);
    $home = str_replace("()-homeblock2-()", $tpl_homeblock2, $home);
    $home = str_replace("()-footer-()", $tpl_footer, $home);
    $home = str_replace("()-footerbottom-()", $tpl_footerbottom, $home);
    


//    if (isset($_SESSION['utilizador_id']) && isset($_SESSION['utilizador_username'])){
        
        
        $nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
        $nome = mysqli_real_escape_string($link_bd, $nome);

        $password = (isset($_POST['password'])) ? $_POST['password'] : '';
        $password_safe = Encrypter::encrypt($password);
        $password_sha1 = sha1($password);
        if(validaruser($nome, $password_safe, $password_sha1) == true) {
            //reencaminha para index
            //envia-o de volta para o index
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");

        } else {
            $_SESSION['tentativaslogin']++;
            //envia-o de volta para o index
            $_SESSION['mensagem_erro'] = 'Utilizador e/ou Password errados.';
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
        }
           

    
    /*}else{
        $home = str_replace("()-separadorcima-()", file_get_contents('TPL/loggederror.tpl'), $home);
        $home = str_replace("()-top-open-()", file_get_contents('TPL/top-open.tpl'), $home);
    }
    

 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*}else{
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header ("location: http://$host$uri/$extra");
    // echo "<script>window.location='index.php'</script>";
}
?>*/
