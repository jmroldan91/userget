<?php
require_once('../classes/AutoLoad.php');
$db = new DataBase();
$session = new Session();
$email = Request::get('m');
$hash = Request::get('hash');
$mng = Utils::getManager('user', $db);
if($hash == $mng->getHash($email)){
    $mng->userActivate();
}else{
    Render::getHeader();
    echo '<div class="container"><h3>Ha habido un error en la activación, intenterlo mas tarde o contacte con el administrador.</h3></div>';
    Render::getFooter();
}
