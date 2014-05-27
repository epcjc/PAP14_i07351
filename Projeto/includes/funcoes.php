<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function mensagemservidor($idusr, $titulomsg, $msg){
    
                                // conectar bd
            global $_SG;
            $link_bd = mysqli_connect($_SG['bd_servidor'], $_SG['bd_user'], $_SG['bd_pass'], $_SG['bd']);
              if (!$link_bd) {
                    die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
                      }

        $sql = 'SELECT nmensagens FROM utilizadores WHERE id = '.$idusr.' LIMIT 1';
        $query = mysqli_query($link_bd, $sql);
        $r = mysqli_fetch_assoc($query);
        $nmsgs = $r['nmensagens'] + 1;              
                      //insere mensagem
        $sql = "INSERT INTO mensagens (id_utilizadorE,id_utilizadorR,apagou_utilizadorE,titulo,conteudo) VALUES (0,$idusr,1,'$titulomsg','$msg')";
        $stmt = $link_bd->prepare($sql);
        $foi = FALSE;
        if ($stmt) {
            //$stmt->bind_param('iss', $varID, $var1, $var2);
            $stmt->execute();
            $foi = TRUE;
        }
        //---------------------------------------------------
        //aumenta um nmensagens no utilizador

        if($foi){
            $ins = "UPDATE `utilizadores` SET `nmensagens` = '".$nmsgs."' WHERE `id` = '".$idusr."' LIMIT 1";
            $stmt = $link_bd->prepare($ins);
            if ($stmt) {
                    //$stmt->bind_param('iss', $varID, $var1, $var2);
                $stmt->execute();
            }
        }

        //------------------------------------
}

function convertermes($input){
    $foi = 0; //confirmação que encontrou um mes valido
    switch ($input){
        case "01":
            $foi = 1;
            return "Jan";
            break;
        case "02":
            $foi = 1;
            return "Fev";
            break;
        case "03":
            $foi = 1;
            return "Mar";
            break;
        case "04":
            $foi = 1;
            return "Abr";
            break;
        case "05":
            $foi = 1;
            return "Mai";
            break;
        case "06":
            $foi = 1;
            return "Jun";
            break;
        case "07":
            $foi = 1;
            return "Jul";
            break;
        case "08":
            $foi = 1;
            return "Ago";
            break;
        case "09":
            $foi = 1;
            return "Set";
            break;
        case "10":
            $foi = 1;
            return "Out";
            break;
        case "11":
            $foi = 1;
            return "Nov";
            break;
        case "12":
            $foi = 1;
            return "Dez";
            break;             
    }
    if($foi == 0){
        return false;
    }
}

function validarletra($input){
    $foi = 0;
    switch ($input){
        case "0": $foi = 1; break;
        case "1": $foi = 1; break;
        case "2": $foi = 1; break;
        case "3": $foi = 1; break;
        case "4": $foi = 1; break;
        case "5": $foi = 1; break;
        case "6": $foi = 1; break;
        case "7": $foi = 1; break;
        case "8": $foi = 1; break;
        case "9": $foi = 1; break;
        case "a": $foi = 1; break;
        case "b": $foi = 1; break;
        case "c": $foi = 1; break;
        case "d": $foi = 1; break;
        case "e": $foi = 1; break;
        case "f": $foi = 1; break;
        case "g": $foi = 1; break;
        case "h": $foi = 1; break;
        case "i": $foi = 1; break;
        case "j": $foi = 1; break;
        case "k": $foi = 1; break;
        case "l": $foi = 1; break;
        case "m": $foi = 1; break;
        case "n": $foi = 1; break;
        case "o": $foi = 1; break;
        case "p": $foi = 1; break;
        case "q": $foi = 1; break;
        case "r": $foi = 1; break;
        case "s": $foi = 1; break;
        case "t": $foi = 1; break;
        case "v": $foi = 1; break;
        case "w": $foi = 1; break;
        case "x": $foi = 1; break;
        case "y": $foi = 1; break;
        case "z": $foi = 1; break;
                }
    if($foi==1){
        return true;
    }else{
        return false;
    }
}


function apagardir($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            apagardir(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

function apagarconteudodir($path)
{
    $files = glob($path.'/*'); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file))
            unlink($file); // delete file
    }
}



//error_reporting(E_ALL);
/**
* Resize an image and keep the proportions
* @author Allison Beckwith 
* @param string $filename
* @param string $destFile
* @param integer $max_width
* @param integer $max_height
* @return image
*/
function resizeImage($filename, $destFile, $max_width, $max_height, $Qualidade=75,$prop=TRUE)
{
    list($orig_width, $orig_height, $type) = getimagesize($filename);

    if ($prop){
    $width = $orig_width;
    $height = $orig_height;

        # taller
        if ($height > $max_height) {
            $width = ($max_height / $height) * $width;
            $height = $max_height;
        }

        # wider
        if ($width > $max_width) {
            $height = ($max_width / $width) * $height;
            $width = $max_width;
        }

    }else{
            $width = $max_width;
            $height = $max_height;
    }
    
    $image_p = imagecreatetruecolor($width, $height);
    
    switch ($type){
        case "1": $image = imagecreatefromgif($filename);break;
        case "2":  $image = imagecreatefromjpeg($filename);break;
        case "3": $image = imagecreatefrompng($filename);break;
        default:  $image = imagecreatefromjpeg($filename);
    }
    
    imagecopyresampled($image_p, $image, 0, 0, 0, 0,$width, $height, $orig_width, $orig_height);
    return imagejpeg( $image_p , $destFile, $Qualidade);
}