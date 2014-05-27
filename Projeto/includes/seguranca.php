<?php
//if (session_id() != null)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['tentativaslogin'])){
    $_SESSION['tentativaslogin'] = 0;
}
if(!isset($_SESSION['a_tentativaslogin'])){
    $_SESSION['a_tentativaslogin'] = 0;
}

$_SG['conectaServidor'] = true;    // Abre uma conexão com o servidor MySQL?


$_SG['caseSensitive'] = false;     // Usar case-sensitive? Onde 'thiago' é diferente de 'THIAGO'

 

$_SG['validaSempre'] = true;       // Deseja validar o usuário e a senha a cada carregamento de página?

// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.

$_SG['paginaLogin'] = 'index.php'; // Página de login

 

$_SG['tabela'] = 'utilizadores';       // Nome da tabela onde os usuários são salvos


$_SG['bd_servidor'] = 'localhost';
$_SG['bd_user'] = 'i07351';
$_SG['bd_pass'] = 'amorim';
$_SG['bd'] = 'i07351';
$_SG['caminhoservidor'] = '/home/i07351/public_html/';
$_SG['nomewebsite'] = 'Digiart';

//$_SG['caminho_disco'] = 'c:/xampp/projeto';



//$_SG['link_BD'] = mysqli_connect('localhost', 'root', '', 'projeto_bd');
// ==============================


// Verifica se precisa fazer a conexão com o MySQL

//if ($_SG['conectaServidor'] == true) {

/*$mysql_id = mysql_connect('localhost', 'root', '');
mysql_select_db('projeto_bd',$mysql_id );
if(!$mysql_id){ 
    die('erro ao ligar à base de dados'.mysql_error());
}
if($mysql_id){
    print('A ligação à base de dados foi efectuada com sucesso');
}
if(!mysql_select_db('projeto_bd',$mysql_id)){
    die('Erro ao ligar a tabela'.mysql_error());
}*/

  // $_SG['link'] = mysql_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha']) or die("MySQL: Não foi possível conectar-se ao servidor [".$_SG['servidor']."].");
    //mysql_select_db($_SG['bd'], $_SG['link']) or die("MySQL: Não foi possível conectar-se ao banco de dados [".$_SG['banco']."].");

    

  //  mysqli_close($link);

    
    //$link = mysql_connect("localhost","root","","projeto_bd") or die("Error " . mysql_error($link));
   // }


// Verifica se precisa iniciar a sessão


/**
049
* Função que valida um usuário e senha
050
*
051
* @param string $usuario - O usuário a ser validado
052
* @param string $senha - A senha a ser validada
053
*
054
* @return bool - Se o usuário foi validado ou não (true/false)
055
*/

function validaruser($utilizador, $password, $password_sha1) {

$foi = 0;
global $_SG;


// Usa a função addslashes para escapar as aspas

$nutilizador = addslashes($utilizador);

$npassword = addslashes($password);
$npassword_sha1 = addslashes($password_sha1);


            // conectar bd
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
$cS = ($_SG['caseSensitive']) ? 'BINARY' : '';                     
$sql = "SELECT `id`, `username` FROM `".$_SG['tabela']."` WHERE ".$cS." `username` = '".$nutilizador."' AND ".$cS." `palavrap` = '".$npassword."' AND ".$cS." `palavrap_sha1` = '".$npassword_sha1."' LIMIT 1";

$query = mysqli_query($link_bd, $sql);
if (!$query) {
    echo "Não foi possível executar a consulta ($sql) no banco de dados: " . mysqli_error();
    return false;
}else if(mysqli_num_rows($query) == 0) {
    return false;
    
}else{
    while ($resultado = mysqli_fetch_assoc($query)) {
        $user_id = $resultado["id"];
        $user_username = $resultado["username"];
        $foi = 1;
    }
}
//$resultado = mysql_fetch_assoc($query);

// Verifica se encontrou algum registro

if (!$foi) {

// Nenhum registro foi encontrado => o usuário é inválido


return false;


 

} else {

// O registro foi encontrado => o usuário é valido

 

// Definimos dois valores na sessão com os dados do usuário

$_SESSION['utilizador_id'] = $user_id; // Pega o valor da coluna 'id do registo encontrado no MySQL

$_SESSION['utilizador_username'] = $user_username; // Pega o valor da coluna 'nome' do registo encontrado no MySQL

 

// Verifica a opção se sempre validar o login

if ($_SG['validaSempre'] == true) {

// Definimos dois valores na sessão com os dados do login

$_SESSION['utilizador_login'] = $utilizador;

$_SESSION['utilizador_password'] = $password;

}

 

return true;

}

}

function userimagem($user_id){ //encontra a imagem de perfil do utilizador na bd e retorna o caminho
    global $_SG;
            // conectar bd
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }
                      
$cS = ($_SG['caseSensitive']) ? 'BINARY' : '';                     
$sql = "SELECT `imagem` FROM `".$_SG['tabela']."` WHERE ".$cS." `id` = '".$user_id."' LIMIT 1";

$query = mysqli_query($link_bd, $sql);
if (!$query) {
    echo "Não foi possível executar a consulta ($sql) no banco de dados: " . mysqli_error();
    return false;
}else if(mysqli_num_rows($query) == 0) {
    return false;
    
}else{
    while ($resultado = mysqli_fetch_assoc($query)) {
        if($resultado["imagem"] != NULL){
            return $resultado["imagem"];
        }else{
            return false;
        }
        
    }
}
}//---------------------

//_-------------------------------------------diminuir imagem de perfil
function diminuirimagem($imagempath){
    
}
//---------------------------------------------




class Encrypter {
 
    private static $Key = "porto";
 
    public static function encrypt ($input) {
        $output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(Encrypter::$Key), $input, MCRYPT_MODE_CBC, md5(md5(Encrypter::$Key))));
        return $output;
    }
 
    public static function decrypt ($input) {
        $output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(Encrypter::$Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5(Encrypter::$Key))), "\0");
        return $output;
    }
 
}

/*function protegePagina() {

global $_SG;

 

if (!isset($_SESSION['utilizador_id']) OR !isset($_SESSION['utilizador_username'])) {

// Não há usuário logado, manda pra página de login

expulsaVisitante();

} else if (!isset($_SESSION['utilizador_id']) OR !isset($_SESSION['utilizador_username'])) {

// Há usuário logado, verifica se precisa validar o login novamente

if ($_SG['validaSempre'] == true) {

// Verifica se os dados salvos na sessão batem com os dados do banco de dados

if (!validaruser($_SESSION['utilizador_login'], $_SESSION['utilizador_password'])) {

// Os dados não batem, manda pra tela de login

expulsaVisitante();

}

}

}

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

