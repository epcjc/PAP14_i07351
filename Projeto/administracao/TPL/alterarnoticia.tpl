<p> <h3>-----------------------------------------<p>ALTERAR NOTÍCIA</p>-----------------------------------------</h3><br>Notícia nº()-idnoticia-()<br>
<form name="alterarnoticia" id="alterarnoticia" enctype="multipart/form-data" action="noticiaalterada.php?id=()-idnoticia-()" method="post" onsubmit="return validaralterarnoticia(); return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="55550000" />
<br>()-imagem-()<br>
Imagem da notícia:<br> <input name="imagem" id="imagem" type="file" />
<br><br>Título:<br> <textarea class="FormElement" name="titulo" id="titulo" cols="33" rows="1" maxlength="88">()-stitulo-()</textarea>
<br><br>Conteúdo:<br> <textarea class="FormElement" name="conteudo" id="conteudo" cols="33" rows="1" maxlength="2000">()-sconteudo-()</textarea>
<br><input type="submit" value="Enviar" />
</form>