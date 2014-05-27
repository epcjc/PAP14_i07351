<p> <h3>-----------------------------------------<p>Inserir Página</p>-----------------------------------------</h3><br/>
<form name="novapagina" id="novapagina" action="inserirpagina.php" method="post" onsubmit="return validarnovapagina(); return false;">
    Nome da Página:<br><input type="text" name="nome" id="nome">
<br><br>Título:<br> <textarea class="FormElement" name="titulo" id="titulo" cols="33" rows="1"></textarea>
<br><br>Conteúdo:<br> <textarea class="FormElement" name="conteudo" id="conteudo" cols="33" rows="1"></textarea>
<br><input type="submit" value="Enviar" />
</form>