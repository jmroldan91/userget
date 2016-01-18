<?php

class ManageUser extends ManagePOJO{
    protected $table='user';
    
    public function get($email) {
        $params = [];
        $params['email'] = $email;
        $r = $this->db->query($this->table, '*', $params);
        if($r!=-1){
            $row = $this->db->getRow();
            $user = new User();
            $user->set($row);
            return $user;
        }else{
            return null;
        }
    }
    public function getByAlias($alias) {
        $params = [];
        $params['alias'] = $alias;
        $r = $this->db->query($this->table, '*', $params);
        if($r!=-1){
            $row = $this->db->getRow();
            $user = new User();
            $user->set($row);
            return $user;
        }else{
            return null;
        }
    }
    
    function set(User $user, $email){
        $paramsWhere = array();
        $paramsWhere['email'] = $email;    
        $paramsSet = $user->toArray();
        unset($paramsSet['pass']);
        return $this->db->update($this->table, $paramsSet, $paramsWhere);
    }

    function getList($page = "1", $nrpp = Constant::_NRPP, $order = "1", $params = []) {
        $limit = ($page-1)*$nrpp . ',' . $nrpp;
        $this->db->query($this->table, '*', $params, $order, $limit);
        $r = [];
        while($row=$this->db->getRow()){
            $tmp = new User();
            $tmp->set($row);
            $r[] = $tmp;
        }
        return $r;
    }

    function newUserGuest($email, $pass){
        $user = new User($email, $pass);
        return $this->insert($user);
    }
    
    function newUserStaff($email, $pass, $act){
        $user = new User($email, $pass, $act);
        return $this->insert($user);
    }
    
    function newUserAdmin($email, $pass, $act, $admin, $staff){
        $user = new User($email, $pass, $act, $admin, $staff);
        return $this->insert($user);
    }
    
    function getHash($email){
        return sha1($email . Constant::_SEMILLA);
    }
    
    function userRegister(){
        $email = Request::req('mail');
        $pass1 = Request::req('pass1');
        $pass2 = Request::req('pass2');
        if($pass1 !== $pass2){
            Utils::Redirect('./login.php?r=password-error');
        }
        $r = $this->newUserGuest($email, $pass1);
        if($r!=-1){
            $hash = $this->getHash($email);
            $url = "https://usergest-jmroldan00.c9users.io/user/activar.php?m=$email&hash=$hash";
            $subject = "Confirmación de alta";
            $to = $email;
            $message = $url;
            $mail = new Mail($to, $subject, $message);
            $r = $mail->send();
            if($r===1){
                Utils::redirect('./login.php?r=alta-ok');
            }
            Utils::redirect('./login.php?r='.$r);
        }else{
            Utils::redirect('./login.php?r=insert-user-error');
        }
    }
    
    function userCreate(){
        $pass1 = Request::req('pass');
        $pass2 = Request::req('pass2');
        if($pass1 !== $pass2){
            Utils::Redirect('./admin.php?op=create&r=password-error');
        }else{
            $user = new User();
            $user->read();
            $user->setFechaAlta(date_create()->format('Y-m-d H:i:s'));
            $user->setActivo('1');
            $user->setPass(sha1($pass1));
            $user->setAdministrrador('0');
            $user->setPersonal('0');
            $v = $this->validate($user);
            if( $v == 1){
                $r = $this->insert($user);
                Utils::Redirect('./admin.php?op=create&r='.$r);
            }else{
                Utils::Redirect('./admin.php?op=create&r=user-exists-'.$v);
            }
        }
    }
    
    function userCreateAdmin(){
        $pass1 = Request::req('pass');
        $pass2 = Request::req('pass2');
        if($pass1 !== $pass2){
            Utils::Redirect('./admin.php?op=create&r=password-error');
        }else{
            $user = new User();
            $user->read();
            $user->setFechaAlta(date_create()->format('Y-m-d H:i:s'));
            $user->setPass(sha1($pass1));
            if($user->getActivo() == null) $user->setActivo('0');
            if($user->getAdministrador() == null) $user->setAdministrrador('0');
            if($user->getPersonal() == null) $user->setPersonal('0');
            $v = $this->validate($user);
            if( $v == 1){
                $r = $this->insert($user);
                Utils::Redirect('./admin.php?op=create&r='.$r);
            }else{
                Utils::Redirect('./admin.php?op=create&r=user-exists-'.$v);
            }
        }
    }
    
    function send_activation_mail($email){
        $hash = $this->getHash($email);
        $url = "https://usergest-jmroldan00.c9users.io/user/activar.php?m=$email&hash=$hash";
        $subject = "Confirmación de alta";
        $to = $email;
        $message = $url;
        $mail = new Mail($to, $subject, $message);
        $r = $mail->send();
        return $r;
    }
    
    function googleUserRegister(User $user){
        $user->setActivo('1');
        return $this->insert($user);
    }
    
    function userLogin(){
        $login = Request::req('login');
        $pass1 = Request::req('pass1');
        $user = $this->get($login);
        if($user->getEmail() == null){
            $user = $this->getByAlias($login);
        }
        if($user !== null && $user->getPass() === sha1($pass1) && $user->getActivo() == 1){
            $_SESSION['user']=$user;
            Utils::redirect('../index.php?r=ok');
        }else{
            Utils::redirect('./login.php?r=login-error');
        }
    }
    function ajaxLogin(){
        $login = Request::req('login');
        $pass1 = Request::req('pass');
        $user = $this->get($login);
        if($user->getEmail() == null){
            $user = $this->getByAlias($login);
        }
        if($user !== null && $user->getPass() === sha1($pass1) && $user->getActivo() == 1){
            $_SESSION['user']=$user;
            echo '{"result" : "1" }';
        }else{
            echo '{"result" : "0" }';
        }
    }
    
    function userLogOut(){
        session_destroy();
        Utils::redirect('./login.php');
    }
    
    function userDisable(){
        $email = Request::req('pkid');
        $user = $this->get($email);
        $paramsWhere = [];
        $paramsWhere['email'] = $email;
        $paramsSet = [];
        $paramsSet['activo'] = 0;
        if($user->getEmail()!='' || $user->getEmail()!=null){
            $r = $this->db->update($this->table, $paramsSet, $paramsWhere);
            if($r!=-1){
                $r='1';
            }
            if($user->getEmail() == $_SESSION['user']->getEmail()){
                $this->userLogOut();
            }
        }else{
            $r = '-1';
        }
        Utils::redirect('./admin.php?r='.$r);
    }   
    
    function userActivate($email=null){
        if($email == null){
            $email = Request::req('m');
        }
        $user = $this->get($email);
        $paramsWhere = [];
        $paramsWhere['email'] = $email;
        $paramsSet = [];
        $paramsSet['activo'] = 1;
        if($user->getEmail()!=''){
            $r = $this->db->update($this->table, $paramsSet, $paramsWhere);
        }else{
            $r = 'Error en la activación';
        }
        Utils::redirect('./admin.php?r=Cuanta activada con exito');
    }  
    
    function userUpdate(){
        $paramsWhere = [];
        $paramsWhere['email'] = Request::req('pkid');    
        $paramsSet = [];
        $paramsSet['email'] = Request::req('email');
        $paramsSet['alias'] = Request::req('alias');
        $r = $this->db->update($this->table, $paramsSet, $paramsWhere);
        if($r=1){
            $_SESSION['user'] = $this->get(Request::req('email'));
        }
        Utils::redirect('./admin.php?op=update&r='.$r);
    }
    
    function userChangePass(){
        $email = Request::req('mail');
        $pass1 = Request::req('pass1');
        $pass2 = Request::req('pass2');
        if($pass1 !== $pass2){
            Utils::Redirect('./recuperar.php?r=password-error');
        }else{
            $paramsWhere = [];
            $paramsWhere['email'] = $email;
            $paramsSet = [];
            $paramsSet['pass'] = sha1($pass1);
            $r = $this->db->update($this->table, $paramsSet, $paramsWhere);
            Utils::redirect('./recuperar.php?r='.$r);
        }
    }
    
    function userRecoveryPass(){
        $email = Request::req(mail);
        $user = $this->get($email);
        if($user!=null){
            $hash = $this->getHash($email);
            $url = "https://usergest-jmroldan00.c9users.io/user/recuperar.php?m=$email&hash=$hash";
            $subject = "Recuperación de contraseña";
            $to = $email;
            $message = $url;
            $mail = new Mail($to, $subject, $message);
            $r = $mail->send();
            if($r===1){
                Utils::redirect('./login.php?r=envio-ok');
            }
            Utils::redirect('./login.php?r=error-envio');
        }else{
            Utils::redirect('./login.php?r=El-email-no-esta-registrado');
        }
    }
    
    function getLevel(User $user = null){
        if($user !== null && $user->getActivo() == '1'){
            if($user->getAdministrador() == '1'){
                return '3';  //Full access
            }
            else if($user->getPersonal() == '1'){
                return '2'; //staff access
            }
            else{
                return '1'; //user access
            }
        }else{
            return '0'; //register access
        }
    }
    
    function validate(User $user){
        $validaEmail = $this->get($user->getEmail());
        $validaAlias = $this->getByAlias($user->getAlias());
        if($user->getPass()==null){
            return -2; //Contraseña en blanco
        }
        if($validaEmail->getEmail()!=null){
            return -1; //El email ya existe
        }
        if($validaAlias->getAlias()!=null){
            return 0; //El alias ya existe
        }
        return 1;
    }
    
    function toogleAdmin($pkid){
        $user = $this->get($pkid);
        if($user->getAdministrador()=='1'){
            $user->setAdministrador('0');
        }else{
            $user->setAdministrador('1');
        }
        return $this->set($user, $pkid);
    }
    
    function toogleStaff($pkid){
        $user = $this->get($pkid);
        if($user->getPersonal()=='1'){
            $user->setPersonal('0');
        }else{
            $user->setPersonal('1');
        }
        return $this->set($user, $pkid);
    }
    
    function toogleActive($pkid){
        $user = $this->get($pkid);
        if($user->getActivo()=='1'){
            $user->setActivo('0');
        }else{
            $user->setActivo('1');
        }
        return $this->set($user, $pkid);
    }
    
    function authenticate_google_OAuthtoken(){
        $id_token = Request::req('token');
        $url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token='.$id_token;
        $conexion = curl_init();
        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($conexion);
        curl_close($conexion);
        return $r;//aquí vienen todos los datos
    }
    
    function  createTable(){
        $sql = "create table if not exists `user` ( "
             . " email varchar(80) not null,"
             . " pass varchar(40) not null,"
             . " alias varchar(80) UNIQUE,"
             . " fechaAlta datetime not null,"
             . " activo tinyint not null default 0,"
             . " administrador tinyint not null default 0,"
             . " personal tinyint not null default 0,"
             . " primary key (email)"
             . ") engine=INNODB";
     return $this->db->send($sql);
    } 
}
