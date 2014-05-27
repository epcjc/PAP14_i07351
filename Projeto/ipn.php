<?php
// tell PHP to log errors to ipn_errors.log in this directory
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_log/ipn_errors.log');
include('includes/ipnlistener/ipnlistener.php');
include('includes/seguranca.php');
include('includes/funcoes.php');


// intantiate the IPN listener

$listener = new IpnListener();

// tell the IPN listener to use the PayPal test sandbox
$listener->use_sandbox = true;

// try to process the IPN POST
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}

                                // conectar bd
    global $_SG;
    $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
      if (!$link_bd) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
              }


// TODO: Handle IPN Response here
if ($verified) {

    $errmsg = '';   // stores errors from fraud checks
    
    // 1. Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    //APARTE. 

       
    //verifica se ha parametro de compra

    if(isset($_GET['compra']) && is_numeric($_GET['compra']) && $_GET['compra'] > 0){
        
        $idcompra = $_GET['compra'];
        //procura na bd para confirmar existencia
        $sql = 'SELECT id_comprador, id_upload FROM compras WHERE id = '.$idcompra.' AND confirmacaoV = 1 LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        $r = mysqli_fetch_assoc($query);
        if(!$query) $errmsg .= "Não deu para confirmar existencia da compra na bd na query || ";
        if($query->num_rows == 0){
            $errmsg .= "Não encontrou esta compra na bd: $idcompra || ";
        }else{
            $idcomprador = $r['id_comprador'];
            $idupload = $r['id_upload'];
        }
        
        //procura upload na bd
        $sql = 'SELECT id_utilizador, preco FROM uploads WHERE id = '.$idupload.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        $r = mysqli_fetch_assoc($query);
        if(!$query) $errmsg .= "Não deu para confirmar existencia do upload na bd na query || ";
        if($query->num_rows == 0){
            $errmsg .= "Não encontrou este upload na bd: $idupload || ";
        }else{
            $idvendedor = $r['id_utilizador'];
            $preco = number_format((float)$r['preco'], 2, '.', '');
        }
         
        //procura utilizador vendedor na bd
        $sql = 'SELECT email FROM utilizadores WHERE id = '.$idvendedor.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        $r = mysqli_fetch_assoc($query);
        if(!$query) $errmsg .= "Não deu para confirmar existencia da vendedor na bd na query || ";
        if($query->num_rows == 0){
            $errmsg .= "Não encontrou este vendedor na bd: $idvendedor || ";
        }else{
            $emailvendedor = $r['email'];
        }
        
        // 2. Make sure seller email matches your primary account email.
        if ($_POST['receiver_email'] != $emailvendedor) {
            $errmsg .= "receiver_email does not match: ";
            $errmsg .= $_POST['receiver_email'].' || ';
        }
            
        
        // 3. Make sure the amount(s) paid match
        if ($_POST['mc_gross'] != $preco) {
            $errmsg .= "mc_gross(preco) does not match: ";
            $errmsg .= $_POST['mc_gross'].' || ';
        }
    }else{
        $errmsg .= "Parametro 1 inválido na compra || ";
    }

    
    // 4. Make sure the currency code matches
    if ($_POST['mc_currency'] != 'USD') {
        $errmsg .= "mc_currency does not match: ";
        $errmsg .= $_POST['mc_currency'].' || ';
    }

    // 5: Check for duplicate txn_id
    $txn_id = mysqli_real_escape_string($link_bd, $_POST['txn_id']);
    $sql = 'SELECT id FROM compras WHERE txn_id = '.$txn_id.' LIMIT 1';
    $query = mysqli_query($link_bd, $sql);
    if(!$query) $errmsg .= "Não deu para confirmar duplicacao do txn_id || ";
    if($query->num_rows > 0){
        $errmsg .= "Este txn_id já se encontra na bd: $txn_id || ";
    }
    
    // 6: Verifica se o codigo de compra introduzido é valido
    if(isset($_GET['c'])){
        $c = mysqli_real_escape_string($link_bd, $_POST['c']);
        $sql = 'SELECT codigo_compra FROM compras WHERE id = '.$idcompra.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        $r = mysqli_fetch_assoc($query);
        if(!$query) $errmsg .= "Não deu para confirmar codigo da compra || ";
        if($query->num_rows == 0 || $r['codigo_compra'] != $c){
            $errmsg .= "Não encontrou na bd ou codigo esta errado: $txn_id || ";
        }
    }else{
        $errmsg .= "Parametro 2 inválido na compra || ";
    }
    
    if ($errmsg != '') {
    
        // manually investigate errors from the fraud checking
        $body = "FALHAS NO IPN: $errmsg |||---||| ";
        $body .= $listener->getTextReport();
        file_put_contents('ipn_log/utilizador-'.$idcomprador.'.txt', $body, FILE_APPEND);
        mensagemservidor($idcomprador, 'Transferencia nº'.$idcompra.' falhou', 'Não foi possível confirmar a sua transferencia realizada em '.$_POST['payment_date'].' (upload nº'.$idupload.') por um dos seguintes motivos: O estado do pagamento não está definido como completo; O email do vendedor não corresponde; O preço não corresponde; A conversão da moeda não corresponde; Os códigos não correspondem. <br/><br/> Para mais informações, contacte a administração do nosso web-site.');
        
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'mensagens.php';
        header ("location: http://$host$uri/$extra");
        exit;
        
    } else {
        
        // tudo verificado: process order here
        $ins = "UPDATE `compras` SET `confirmacaoC` = 1 WHERE `id` = '".$idcompra."' LIMIT 1";
        $stmt = $link_bd->prepare($ins);
        if ($stmt) {
                //$stmt->bind_param('iss', $varID, $var1, $var2);
            $stmt->execute();
            mensagemservidor($idcomprador, 'Transferencia nº'.$idcompra.' concluída', 'A sua transferencia realizada em '.$_POST['payment_date'].'(upload nº'.$idupload.') foi concluída. Agradecemos a sua participação. Pode agora efetuar o <a href="trabalho.php?id='.$idupload.'">download aqui</a>. ');
            
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'mensagens.php';
            header ("location: http://$host$uri/$extra");
            exit;
            
        }else{
            $_SESSION['mensagem_erro'] = 'Há um erro a precisar de ser reparado em ipn 166';
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header ("location: http://$host$uri/$extra");
            exit;
        }

    }
    
} else {
    // manually investigate the invalid IPN
    file_put_contents('ipn_log/IPN_invalidos.txt', $listener->getTextReport(), FILE_APPEND);
    
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
    header ("location: http://$host$uri/$extra");
    exit;
}
