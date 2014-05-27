            <form action = "adminlogin.php" method = "POST">
                Administrador:<br>
                <input name = "administrador" type = "text"><br><br>
                Palavra-passe:<br>
                <input name = "palavrap" type = "password"><br><br>
                <img id="captcha" src="../securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                <input type="text" name="captcha_code" size="10" maxlength="6" />
                <a href="#" onclick="document.getElementById('captcha').src = '../securimage/securimage_show.php?' + Math.random(); return false">[ Imagem diferente ]</a><br>

               <input type="submit" value="Entrar">
            </form>