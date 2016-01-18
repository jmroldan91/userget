<?php
require_once('../classes/AutoLoad.php');
require_once('../classes/google/autoload.php');
$session = new Session();
$db = new DataBase();
$op = Request::req('op');
$mng = Utils::getManager('user', $db);
$secLevel = $mng->getLevel($session->get('user'));
switch($op){
    case 'register': $mng->userRegister(); break;
    case 'login': $mng->userLogin(); break;
    case 'logout': $mng->userLogOut(); break;
    case 'recovery': $mng->userRecoveryPass(); break;
    case 'delete': if($secLevel == 3 || $secLevel == 2 || ($secLevel != 0 && $session->get('user')->getEmail() == Request::req('pkid'))) {
                        $mng->userDisable();
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'update': if($secLevel == 3 || $secLevel == 2 || ($secLevel != 0 && $session->get('user')->getEmail() == Request::req('pkid'))){
                        $mng->userUpdate();
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'active': if($secLevel == 3 || $secLevel == 2){
                        $mng->userActivate(Request::req('pkid'));
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'create': if($secLevel == 3 || $secLevel == 2){
                        if($secLevel == 3)
                            $mng->userCreateAdmin();
                        if($secLevel == 2)
                            $mng->userCreate();
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'setAdmin': if($secLevel == 3 || $secLevel == 2){
                        $r=$mng->toogleAdmin(Request::req('pkid'));
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'setStaff': if($secLevel == 3 || $secLevel == 2){
                        $r=$mng->toogleStaff(Request::req('pkid'));
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'setActive': if($secLevel == 3 || $secLevel == 2){
                        $r=$mng->toogleActive(Request::req('pkid'));
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'changePass': if($secLevel != 0) {
                            $mng->userChangePass();
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    }  
                    break;
    case 'uploadImage': if($secLevel != 0){
                        $file = new UploadFile($_FILES['image']);
                        $file->setName(Request::req('pkid'));
                        $file->setDestination('../img/profiles/');
                        $file->upload();
                        $r = $file->getError_message();
                        Utils::Redirect('./admin.php?op='.$op.'&r='.$r);
                    }else{
                        $r="no-tiene-permiso-para-realizar-la-acción";
                    } 
                    break;
    case 'reactive': $mng->send_activation_mail(Request::req('pkid'));
                    break;
    default: $r='op-error';
}
Utils::Redirect('./admin.php?op='.$op.'&r='.$r);