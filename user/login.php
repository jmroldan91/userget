<?php 
    require_once('../classes/AutoLoad.php');
    $session = new Session();
    if($session->get('user')!=null){
        Utils::Redirect('../index.php');
    }
    
    Render::getHeader();
?>   
    <div class="container">
        <hr/><br/>
        <div class="col-md-12">
            <div class="col-lg-6">
                <h2>Inicio de sesión</h2>
                <form class="form form-horizontal" method="POST" action="./controler.php?op=login">
                    <label for="login">Email o alias</label>
                    <input class="form-control" type="mail" name="login" id="login" required/>
                    <label for="pass1">Contraseña</label>
                    <input class="form-control" type="password" id="pass1" name="pass1" required/>
                    <hr/>
                    <input class="btn btn-primary" type="submit" value="Entrar"/>
                    <a href="#forgetpass" class="text-info pull-right" data-toggle="modal">
                        No recuerdo la contraseña
                    </a>
                </form>
            </div>
            <div class="col-lg-6">
                <h2>Registro de ususario</h2>
                <form class="form form-horizontal" method="POST" action="./controler.php?op=register">
                    <label for="mail">Email</label>
                    <input class="form-control" type="mail" name="mail" id="mail" required/>
                    <label for="pass1">Contraseña</label>
                    <input class="form-control" type="password" id="pass1" name="pass1"/>
                    <input class="form-control" type="password" id="pass2" name="pass2" placeholder="Repita la contraseña" />
                    <hr/>
                    <input class="btn btn-primary" type="submit" value="Dar de alta"/>
                </form>
            </div>
        </div>
        <hr/><br/>
        <div class="col-lg-12">
            <h3>Acceso con google</h3>
            <p>Puede iniciar sesión o registrarse con sus credenciales de google.</p>
            <div class="g-signin2" data-onsuccess="onSignIn" data-theme="light"></div>
            <script>
              function onSignIn(googleUser) {
                var auth2 = gapi.auth2.getAuthInstance();
                            auth2.signOut().then(function () {
                              console.log('User signed out.');
                            });
                // Useful data for your client-side scripts:
                var profile = googleUser.getBasicProfile();
                console.log("ID: " + profile.getId()); // Don't send this directly to your server!
                console.log("Name: " + profile.getName());
                console.log("Image URL: " + profile.getImageUrl());
                console.log("Email: " + profile.getEmail());
        
                // The ID token you need to pass to your backend:
                var id_token = googleUser.getAuthResponse().id_token;
                console.log("ID Token: " + id_token);
                 var xhttp = new XMLHttpRequest();
                 xhttp.onreadystatechange = function() {
                   if (xhttp.readyState == 4 && xhttp.status == 200) {
                       var response = JSON.parse(xhttp.responseText);
                       if(response.result == '1' && response.email_verified == 'true' && profile.getId() == response.sub){
                            //document.getElementById('demo').textContent = 'Login correcto: '+xhttp.responseText;  
                            window.location.href='../index.php';
                       }
                       else if(response.result == 'Registro realizado'){
                            document.getElementById('demo').textContent = 'Registro correcto: La sesión se iniciará automaticamente en 1s';  
                            setTimeout("location.href='../index.php'", 1000);
                       }
                       else if(response.result == '-1'){
                            document.getElementById('demo').textContent = 'Login error: '+ response.result;  
                       }
                       else if(response.result == 'user-disabled'){
                           var link = document.createElement('a');
                           link.className='btn btn-primary btn-xs';
                           link.href='controler.php?op=reactive&pkid='+profile.getEmail();
                           link.textContent = 'Enviar correo de activación';
                           document.getElementById('demo').textContent = 'Su cuanta no está activada: ';  
                           document.getElementById('demo').appendChild(link);
                       }
                       else{
                           document.getElementById('demo').textContent = 'info: ' + response.result;  
                       }
                   }
                 };
                 xhttp.open("GET", "getToken.php?token="+id_token, true);
                 xhttp.send();
              };
            </script>
            <div id="demo" class="col-md-12"></div>
        </div>
    </div>
    <!-- Modal forget password -->
    <div class="modal fade" id="forgetpass" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h5>Recuperación de contraseña</h5>
                            <p>Introduzca su correo electrónico y recibirá la información necesaria para recuperar su contraseña.</p>
                            <form class="form form-inline" method="POST" action="./controler.php?op=recovery">
                                <label for="mail">Email</label>
                                <input class="form-control" type="mail" name="mail" id="mail" required/>
                                <input class="btn btn-primary" type="submit" value="Enviar"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
    Render::getFooter();