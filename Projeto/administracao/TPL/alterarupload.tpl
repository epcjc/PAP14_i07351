<p> <h3>-----------------------------------------<p>ALTERAR UPLOAD</p>-----------------------------------------</h3><br>Upload nº()-idupload-()<br>Enviado por: ()-username-()<br>
<form name="alterarupload" id="alterarupload" enctype="multipart/form-data" action="uploadalterado.php?id=()-idupload-()" method="post" onsubmit="return validaralterarupload(); return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="55550000" />
<br>()-imagem1-()<br>
<input name="imagem1" id="imagem1" type="file" /><br>
<br>()-imagem2-()<br>
<input name="imagem2" id="imagem1" type="file" /><br>
<br>()-imagem3-()<br>
<input name="imagem3" id="imagem1" type="file" /><br>
<br>()-imagem4-()<br>
<input name="imagem4" id="imagem1" type="file" /><br>
<br><br>Categoria:<br> 
<select  style="width:150;font-size:11px" name="categoria" id="categoria" >
    <option value="video">Video</option>
    <option value="audio">Audio</option>
    <option value="imagem">Imagem</option>
    <option value="software">Software</option>
    <option value="3d">Modelação 3D</option>
    <option value="outras">Outras categorias</option>
</select>

<br><br>Título:<br> <input name="titulo" id="titulo" type="text" value="()-titulo-()" maxlength="100"/>
<br><br>Descrição:<br> <textarea class="FormElement" name="descricao" id="descricao" cols="33" rows="1" maxlength="2000">()-descricao-()</textarea>

<br><input type="submit" value="Enviar" />
</form>