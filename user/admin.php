<?php 
    require_once('../classes/AutoLoad.php');
    $db = new DataBase();
    $mng = Utils::getManager('user', $db);
    $session = new Session();
    /*Alertas entrantes*/
    $r = Request::get('r');
    /*Nivel de seguridad*/
    $user = $session->get('user');
    $secLevel = $mng->getLevel($user);
    Render::getHeader();
    Render::getAdminNavBar();
    echo '<div class="container" style="margin-top: 80px; margin-bottom: 400px;">';
    if($secLevel==0){
        if($r!=null){
            echo '<h3>'.$r.'</h3><br/><a href="./login.php">Iniciar sessión</a>';
        }else{
            Utils::redirect('./login.php');
        }   
    }else{
        if($secLevel==1){
            Render::getProfile();
        }else{
            if($secLevel==2){
                Render::getProfile();
                Render::getStaffControls();
            }else{
                if($secLevel==3){
                    Render::getProfile();
                    Render::getAdminControls();
                }
            }
        }
    }
    echo '</div>';
    Render::getFooter();