			<div class="wrapper clearfix">
			
				
					

				<!-- posts list -->
	        	<div id="posts-list" class="single-post">
	        		
	        		<h2 class="page-heading"><span>Mensagem ()-tipo-()</span></h2>	
	        		
					<article class="format-standard">
						<div class="entry-date"><div class="number">()-dia-()</div> <div class="year">()-mes-() ()-ano-()<br><br><font color="black" size="+1">()-HH-():()-MM-()</font></div></div>
						<h2  class="post-heading">()-titulo-()</h2>
                                                <div class="post-content">()-conteudo-()</div><br/>
					
					
					

						
						
                                            <!-- Respond -->				
						<div id="respond">
					<form name="novamensagem" id="novamensagem" action="enviarmensagem.php" method="post" onsubmit="return validarenviarmensagem(); return false;">
                                                <h3 class="heading">Responder a esta mensagem:</h3>
                                                <fieldset>
							<input name="destinatario"  type="hidden" maxlength="100" value="()-destinatario-()" />
							<div>
								<input name="titulo"  id="titulo" type="text" class="form-poshytip" title="Inserir Título/Assunto para a mensagem" maxlength="100" value="()-tituloresposta-()"/>
							</div>
							<div>
								<textarea  name="conteudo"  id="conteudo" rows="5" cols="20" class="form-poshytip" title="Inserir o conteúdo da mensagem" maxlength="2000"></textarea>
							</div>
                                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                                <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Imagem diferente ]</a>
                                                <p><input type="submit" value="Enviar"/></p>
						</fieldset>
					</form>
						</div>
						<div class="clearfix"></div>
						<!-- ENDS Respond -->
			
		        		</article>
		        		
		        	</div>
		        	<!-- ENDS posts list -->
                                			<!-- sidebar -->
	        	<aside id="sidebar">
	        		
	        		<ul>
		        		<li class="block">
			        		<br/><br/><h4>()-mensagem_enviada-():</h4>
							<ul>
                                                            <li class="cat-item">()-user-()<br/><font size="1">(()-bloquear-())</font></li>
                                                        </ul>
                                                <br/><h4>OPÇÕES:</h4>
							<ul>
								<li class="cat-item">()-apagarmensagem-()</li>
                                                                <li class="cat-item">()-voltar-()</li> 
                                                        </ul>
                                        

		        		</li>
	        		
	        		</ul>
	        		
	        		<em id="corner"></em>
	        	</aside>
				<!-- ENDS sidebar -->
			</div>