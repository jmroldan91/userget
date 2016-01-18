<?php 
    require_once('../classes/AutoLoad.php');
    $session = new Session();
    $db = new DataBase();
    $mng = Utils::getManager('user', $db);
    $response  = json_decode($mng->authenticate_google_OAuthtoken(), true);
    if($response['email_verified'] == 'true'){
        $user = $mng->get($response['email']);
        if($user->getEmail()!=null){
            if($user->getActivo() == 1){
                $session->set('user', $user);
                $session->set('google_user', '1');
                $response['result'] = "1";
            }else{
                $response['result'] = 'user-disabled';
            }
        }else{
            $user = new User($response['email'], $mng->getHash($response['email']));
            $r = $mng->googleUserRegister($user);
            if($r != -1){
                $user = $mng->get($response['email']);
                $session->set('user', $user);
                $session->set('google_user', '1');
                $response['result'] = 'Registro realizado';
            }else{
                $response['result'] = "Error a crear el usuario - ".$email;
            }
        }
    }
    echo json_encode($response);

    
    
    