<p> <h3>-----------------------------------------<p>Inserir Notícia</p>-----------------------------------------</h3><br/>
<form name="novanoticia" id="novanoticia" enctype="multipart/form-data" action="inserirnoticia.php" method="post" onsubmit="return validarnovanoticia(); return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="5550000" />
Imagem da notícia:<br> <input name="imagem" id="imagem" type="file" />
<br><br>Título:<br> <textarea class="FormElement" name="titulo" id="titulo" cols="33" rows="1" maxlength="50"></textarea>
<br><br>Conteúdo:<br> <textarea class="FormElement" name="conteudo" id="conteudo" cols="33" rows="1" maxlength="2000"></textarea>
<br><input type="submit" value="Enviar" />
</form>