<?php
    $session = new Session();
    $user = $session->get('user');  
?>
<div class="col-md-12">
    <h1>Tu perfil</h1>
    <div class="col-md-4">
        <figure>
        <?php 
            if(is_file('../img/profiles/'.$user->getEmail().'.jpg')){
                echo '<img class="img-rounded" src="../img/profiles/'.$user->getEmail().'.jpg" width="100%"/>';
            }else{
                echo '<img class="img-rounded" src="http://lorempixel.com/200/200" width="100%" />';
            }
        ?>
        </figure>
        <form class="form form-inline" method="POST" action="./controler.php?op=uploadImage" enctype="multipart/form-data">
            <label for="image">Foto:</label>
            <input type="file" name="image" accept=".jpg"/>
            <input type="hidden" name="pkid" value="<?php echo $user->getEmail(); ?>"/>
            <br/>
            <input type="submit" name="submit" class="btn btn-success btn-block" value="Subir foto"/>
         </form>
    </div>
    <div class="col-md-4">
        <h5>Tu correo electrónico</h5>
        <p><?php echo $user->getEmail(); ?></p>
        <h5>Tu alias</h5>
        <p><?php echo $user->getAlias(); ?></p>
        <a href="#modificar" class="btn btn-info" data-toggle="modal">
            Modificar mis datos
        </a>
        <a href="./controler.php?op=delete&pkid=<?php echo $user->getEmail(); ?>" class="btn btn-danger">
            Darme de baja
        </a>
    </div>
    <div class="col-md-4">
        <h4>Cambiar contraseña</h4>
        <p><small>Si te registraste con tu cuenta de google aquí podras establecer tu contraseña para el inicio de session normal.</small></p>
        <form class="form form-horizontal" method="POST" action="./controler.php?op=changePass">
            <label for="pass1">Contraseña nueva</label>
            <input class="form-control" type="password" id="pass1" name="pass1" required/>
            <label for="pass1">Repita la contraseña</label>
            <input class="form-control" type="password" id="pass2" name="pass2" required/>
            <br/>
            <input class="btn btn-warning btn-block" type="submit" value="Cambiar contraseña"/>
        </form>
    </div>
</div>
<!-- Modal update -->
    <div class="modal fade" id="modificar" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <h5>Modificación de datos</h5>
                            <form class="form form-inline" method="post" action="./controler.php?op=update">
                                <label for="email">Email</label>
                                <input class="form-control" type="mail" name="email" id="email" value="<?php echo $user->getEmail(); ?>"/>
                                <label for="alias">Alias</label>
                                <input class="form-control" type="mail" name="alias" id="alias" value="<?php echo $user->getAlias(); ?>"/>
                                <input type="hidden" name="pkid" value="<?php echo $user->getEmail(); ?>"/>
                                <input class="btn btn-primary" type="submit" value="Guardar cambios"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>