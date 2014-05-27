<p> <h3>-----------------------------------------<p>ALTERAR PÁGINA</p>-----------------------------------------</h3><br>Página nº()-idpag-()<br>
<form name="alterarpagina" id="alterarpagina" action="paginaalterada.php?id=()-idpag-()" method="post" onsubmit="return validaralterarpagina(); return false;">
<br>()-nomepag-()<br>
Nome:<br> <input name="nome" id="nome" type="text" value="()-snome-()" maxlength="15"/>
<br><br>Título:<br> <textarea class="FormElement" name="titulo" id="titulo" cols="33" rows="1" maxlength="200">()-stitulo-()</textarea>
<br><br>Conteúdo:<br> <textarea class="FormElement" name="conteudo" id="conteudo" cols="33" rows="1" maxlength="2000">()-sconteudo-()</textarea>
<br><input type="submit" value="Enviar" />
</form>