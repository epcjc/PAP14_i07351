<div class="wrapper clearfix">
			
				
				<h2 class="page-heading"><span>Enviar mensagem</span></h2>	
	        	
				<!-- page content -->
	        	<div id="page-content" class="clearfix">
					
				<!-- Map 
				<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true" /></script>
				<script type="text/javascript">
					function initialize() {
						var latlng = new google.maps.LatLng(-34.397, 150.644);
						var myOptions = {
						  zoom: 8,
						  center: latlng,
						  mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					var map = new google.maps.Map(document.getElementById("map_canvas"),
					    myOptions);
					}
				</script>
 
				
				<div id="map_canvas"></div>
				<!-- ENDS Map 
				
				
	        	
	        	
					<div class="map-content">
						Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.
					</div>
					
					<!-- form -->
					<form name="novamensagem" id="novamensagem" action="enviarmensagem.php" method="post" onsubmit="return validarenviarmensagem(); return false;">
                                                <fieldset>
							<br/>
                                                        <div>
								
								<input name="destinatario"  id="destinatario" type="text" class="form-poshytip" title="Inserir o destinatário da mensagem" maxlength="100" value="()-destinatario-()" />
								
							</div>
							<div>
								
								<input name="titulo"  id="titulo" type="text" class="form-poshytip" title="Inserir Título/Assunto para a mensagem" maxlength="100" />
								
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
                                                                                                
							<!-- send mail configuration 
							<input type="hidden" value="email@yourserver.com" name="to" id="to" />
							<input type="hidden" value="Enter the subject here" name="subject" id="subject" />
							<input type="hidden" value="send-mail.php" name="sendMailUrl" id="sendMailUrl" />
						 ENDS send mail configuration -->
					<!-- ENDS form -->
					
					
					<!-- contact sidebar -->
		        	<aside id="contact-sidebar">
		        		<div class="block">
			        		<h4>NOTA:</h4>
			        		<p>Todos os campos da mensagem são obrigatórios. No campo do destinatário, deve ser inserido um nome de utilizador válido. </p>
			        	</div>	        	
		        	</aside>
		        	<div class="clearfix"></div>
					<!-- ENDS contact-sidebar -->
					
				</div>	        	
	        	<!--  page content-->
	        	
	        	

	        	
			</div>