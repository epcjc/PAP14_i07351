				<!-- posts list -->
	        	<div id="posts-list">
	        	
	        		<h2 class="page-heading"><span>Upload</span></h2>	
	        	
	        	
					<article class="format-standard">
						
                                                <form name="upload" id="upload" enctype="multipart/form-data" action="uploading.php" method="post" onsubmit="return validarupload(); return false;">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000000" />
                                                <h3 class="heading">Enviar este projeto/trabalho:</h3> <input name="projeto" id="projeto" type="file" /><font color="red">*</font>
                                                <br><br><h3 class="heading">Imagens para demonstração/antevisão:</h3><input name="imagem1" id="imagem1" type="file" />
                                                <input name="imagem2" id="imagem2" type="file" />
                                                <br><input name="imagem3" id="imagem3" type="file" />
                                                <input name="imagem4" id="imagem4" type="file" />
                                                <br><br>
                                                <br><input type="text"  class="form-poshytip" title="Escrever um título para o projeto/trabalho" name="titulo" id="titulo" cols="33" rows="1" maxlength="88"><font color="red">*</font>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select  style="width:150;font-size:11px" name="categoria" id="categoria" class="form-poshytip" title="Escolher a categoria">
                                                                <option value="video">Video</option>
                                                                <option value="audio">Audio</option>
                                                                <option value="imagem">Imagem</option>
                                                                <option value="software">Software</option>
                                                                <option value="3d">Modelação 3D</option>
                                                                <option value="outras" selected>Outras categorias</option>
                                                </select>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" name="preco" id="preco" min="0" max="100" maxlength="5"  class="form-poshytip" title="Escolher um preço (pode também ser deixado em branco)"/> €
                                                <br><br><textarea  class="form-poshytip" title="Escrever uma descrição para o projeto/trabalho" name="descricao" id="descricao" cols="33" rows="1" maxlength="2000"></textarea><font color="red">*</font><br/><br/>
                                                
                                                
                                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                                <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Imagem diferente ]</a>
                                                
                                                <br><input type="submit" value="Enviar" /> Campos com <font color="red">*</font> são obrigatórios!
                                                </form>
                                                    
					</article>
					
				
	        		
	        		
	        	</div>
	        	<!-- ENDS posts list -->
                            			<!-- sidebar -->
	        	<aside id="sidebar">
	        		
	        		<ul>
		        		<li class="block">
			        		<article class="format-standard"><br/><br/></article><h4>NOTA:</h4>
							<ul>
                                                            <li class="cat-item">É necessário conhecer e respeitar os nossos <a href="termoscondicoes.php">termos e condições</a> ao efetuar um upload, para assim assegurar um bom funcionamento.</li>
                                                        </ul>
                                        

		        		</li>
	        		
	        		</ul>
	        		
	        		<em id="corner"></em>
	        	</aside>
				<!-- ENDS sidebar -->