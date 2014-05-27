				<!-- posts list -->
	        	<div id="posts-list">
	        	
	        		<h2 class="page-heading"><span>Editar upload</span></h2>	
	        	
	        	
					<article class="format-standard">
                                                
                                           <br/><br/><br/>
                                                <form name="alterarprojeto" id="alterarprojeto" enctype="multipart/form-data" action="projetoalterado.php?id=()-idupl-()" method="post" onsubmit="return validaralterarprojeto(); return false;">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="55550000" />
                                                ()-imagem1-()&nbsp;<a href="alterarprojeto.php?id=()-idupl-()&apagari=1" title="Apagar imagem nº1"><img src="HTML/img/botaoapagar.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;()-imagem2-()&nbsp;<a href="alterarprojeto.php?id=()-idupl-()&apagari=2" title="Apagar imagem nº2"><img src="HTML/img/botaoapagar.png"></a>
                                                <br/>
                                                <input class="poshytip" title="Imagem nº1" name="imagem1" id="imagem1" type="file" />                                                
                                                <input class="poshytip" title="Imagem nº2" name="imagem2" id="imagem2" type="file" /><br/>
                                                ()-imagem3-()&nbsp;<a href="alterarprojeto.php?id=()-idupl-()&apagari=3" title="Apagar imagem nº3"><img src="HTML/img/botaoapagar.png"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;()-imagem4-()&nbsp;<a href="alterarprojeto.php?id=()-idupl-()&apagari=4" title="Apagar imagem nº4"><img src="HTML/img/botaoapagar.png"></a>
                                                <br/>
                                                <input class="poshytip" title="Imagem nº3" name="imagem3" id="imagem3" type="file" />
                                                <input class="poshytip" title="Imagem nº4" name="imagem4" id="imagem4" type="file" />
                                                <br><br>
                                                <br><input type="text" class="form-poshytip" title="Editar o título do projeto/trabalho" name="titulo" id="titulo" cols="33" rows="1" maxlength="88" value="()-titulo-()"><font color="red">*</font>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select  style="width:150;font-size:11px" name="categoria" id="categoria" class="form-poshytip" title="Editar a categoria do projeto/trabalho">
                                                                <option value="video">Video</option>
                                                                <option value="audio">Audio</option>
                                                                <option value="imagem">Imagem</option>
                                                                <option value="software">Software</option>
                                                                <option value="3d">Modelação 3D</option>
                                                                <option value="outras">Outras categorias</option>
                                                </select>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" name="preco" id="preco" min="0" max="100" maxlength="5" value="()-preco-()"  class="form-poshytip" title="Editar o preço do trabalho/projeto (pode também ser deixado em branco)"/> €
                                                <br><br><textarea class="form-poshytip" title="Editar descrição do projeto/trabalho" name="descricao" id="descricao" cols="33" rows="1" maxlength="2000">()-descricao-()</textarea><font color="red">*</font><br/><br/>
                                                
                                                
                                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                                <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Imagem diferente ]</a>
                                                
                                                <br><input type="button" value="Voltar" onclick="self.location='gerirprojetos.php'"><input type="submit" value="Guardar" /> Campos com <font color="red">*</font> são obrigatórios!
                                                </form>
                                                    
					</article>
					
				
	        		
	        		
	        	</div>
	        	<!-- ENDS posts list -->
                            			<!-- sidebar -->
	        	<aside id="sidebar">
	        		
	        		<ul>
		        		<li class="block">
			        		<article class = "format-standard"><br/><br/></article><h4>NOTA:</h4>
							<ul>
                                                            <li class="cat-item">É necessário conhecer e respeitar os nossos <a href="termoscondicoes.php">termos e condições</a> ao efetuar uploads, para assim assegurar um bom funcionamento destes.</li>
                                                        </ul>
                                        

		        		</li>
	        		
	        		</ul>
	        		
	        		<em id="corner"></em>
	        	</aside>
				<!-- ENDS sidebar -->