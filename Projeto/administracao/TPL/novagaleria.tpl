<p> <h3>-----------------------------------------<p>Inserir imagem na galeria</p>-----------------------------------------</h3><br/>
<form name="novagaleria" id="novagaleria" enctype="multipart/form-data" action="inserirgaleria.php" method="post" onsubmit="return validarnovagaleria(); return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="55550000" />
Imagem:<br> <input name="imagem" id="imagem" type="file" />
<br><br>Descrição:<br> <textarea class="FormElement" name="descricao" id="descricao" cols="33" rows="1" maxlength="100"></textarea>
<br><input type="submit" value="Enviar" />
</form>