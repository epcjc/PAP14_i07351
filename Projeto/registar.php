
<?php
    include_once 'includes/seguranca.php';
    include_once 'includes/funcoes.php';
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
                      
                      
                      
    $pnome = $_POST['pnome'];
    $pnome = mysqli_real_escape_string($link_bd, $pnome);
    $unome = $_POST['unome'];
    $unome = mysqli_real_escape_string($link_bd, $unome);
    $username = $_POST['username'];
    $username = mysqli_real_escape_string($link_bd, $username);
    
    $email= $_POST['email'];
    $email = mysqli_real_escape_string($link_bd, $email);
    
    
    $palavrap = $_POST['palavrap'];
    $palavrap_safe = Encrypter::encrypt($palavrap);
    $palavrap_sha1 = sha1($palavrap);
    
    if($_POST['descricao']){
       $descricao = $_POST['descricao']; 
    }else{
        $descricao = '';
    }
    $descricao = mysqli_real_escape_string($link_bd, $descricao);
    $pais = $_POST['pais'];
    $pais = mysqli_real_escape_string($link_bd, $pais);
    
    
    $result = $link_bd->query("SELECT email FROM utilizadores WHERE email='$email'");
    $result2 = $link_bd->query("SELECT username FROM utilizadores WHERE username='$username'");
    if ($result->num_rows > 0) {
            echo '<br/><div align="center">O email que inseriu ja se encontra registado.<br /><br /></div>';
          echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e insira outro.</div>';
          exit;
    } elseif ($result2->num_rows > 0) {
            echo '<br/><div align="center">O nome de utilizador que inseriu ja se encontra registado.<br /><br /></div>';
          echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e insira outro.</div>';
          exit; 
    } elseif (strlen($username) < 4 || strlen(trim($username)) < 4){
          echo '<br/><div align="center">O nome de utilizador que inseriu tem menos de 4 caracteres.<br /><br /></div>';
          echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e insira outro.</div>';
          exit;
    } elseif (strlen($username) > 50){
          echo '<br/><div align="center">O nome de utilizador que inseriu tem mais de 50 caracteres.<br /><br /></div>';
          echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e insira outro.</div>';
          exit;
    } elseif (strcasecmp($username, 'digiart') == 0 || strcasecmp($username, 'utilizador removido') == 0){
          echo '<br/><div align="center">O nome de utilizador que inseriu nao e permitido.<br /><br /></div>';
          echo '<div align="center">Por favor, volte <a href="javascript:history.go(-1)">atras</a> e insira outro.</div>';
          exit;
    } else {
    $sql = "INSERT INTO utilizadores (pnome,unome,username,email,palavrap,palavrap_sha1,descricao,pais) VALUES ('$pnome','$unome','$username','$email','$palavrap_safe','$palavrap_sha1','$descricao','$pais')";
    $stmt = $link_bd->prepare($sql);
    if ($stmt) {
        //$stmt->bind_param('iss', $varID, $var1, $var2);

        if ($stmt->execute()) {
            $success = TRUE;   //or something like that
         }else{
             $success = FALSE;
         }
    }
    
    if($success == FALSE){
        die('houve um erro na query [' . $link_bd->error . ']');
    }else{
        $idusr = $link_bd->insert_id;
        //envia mensagem de boas vindas para o novo utilizador
        $titulomsg = "Bem-vindo à Digiart";
        $msg = 'Desejamos-lhe as boas-vindas. Agora já pode começar a divulgar os seus trabalhos, e ajudar a divulgar trabalhos dos outros utilizadores. Se pretender, pode também <a href="gerirconta.php">personalizar o seu perfil e imagem pessoal</a>, para que os outros utilizadores conheçam um pouco mais sobre você.<br/><br/>Com os nossos melhores cumprimentos,<br/>Digiart';
        mensagemservidor($idusr, $titulomsg, $msg);
    }
    
    $msg = "O registo foi concluído com sucesso.";
    $_SESSION['mensagem_sucesso'] = $msg;
    //redireciona para o index
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header ("location: http://$host$uri/$extra");
    
    }

