<?php
class Render {
    static function getHeader(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
    }
    static function getFooter(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/footer.php');
    }
    static function getNavBar(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/navBar.php');
    }
    static function getHeading(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/heading.php');
    }
    static function getProfile(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/profile.php');
    }
    static function getAdminNavBar(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/adminNavBar.php');
    }
    static function getStaffControls(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/staffControls.php');
    }
    static function getAdminControls(){
        include_once($_SERVER['DOCUMENT_ROOT'].'/inc/adminControls.php');
    }
    static function getUsersTable($arrayitems){
        $table = '<table class="table table-stripped table-hover">';
        $table .= '<thead><tr><th>Email</th><th>Alias</th><th>Alta</th><th>Activo</th><th>Operaciones</th></tr></thead>';
        $table .= '<tbody>';
        foreach ($arrayitems as $key => $u) {
            $checked = $u->getActivo() == 1 ? "checked" : " ";
            $op = $u->getActivo() == 1 ? "<a class='btn btn-danger' href='controler.php?op=delete&pkid=".$u->getEmail()."'>Baja</a>" : "<a class='btn btn-success' href='controler.php?op=active&pkid=".$u->getEmail()."'>Activar</a>";
            $table .= '<tr>';
            $table .= '<td>'.$u->getEmail().'</td><td>'.$u->getAlias().'</td><td>'.$u->getFechaAlta().'</td><td><input type="checkbox" '.$checked.' disabled readonly /></td><td>'.$op.'</td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        return $table;
    }
    static function getAdminsTable($arrayitems){
        $table = '<table class="table table-stripped table-hover">';
        $table .= '<thead><tr><th>Email</th><th>Alias</th><th>FechaAlta</th><th>Activo</th><th>Admin</th><th>Personal</th><th>Acciones</th></tr></thead>';
        $table .= '<tbody>';
        foreach ($arrayitems as $key => $u) {
            $activo = $u->getActivo() == 1 ? "checked" : " ";
            $admin = $u->getAdministrador() == 1 ? "checked" : " ";
            $staff = $u->getPersonal() == 1 ? "checked" : " ";
            $table .= '<tr>';
            $table .=  '<td>'.$u->getEmail().'</td>
                        <td>'.$u->getAlias().'</td>
                        <td>'.$u->getFechaAlta().'</td>
                        <td><input name="setActive" type="checkbox" '.$activo.' value="'.$u->getEmail().'" /></td>
                        <td><input name="setAdmin" type="checkbox" '.$admin.' value="'.$u->getEmail().'" /></td>
                        <td><input name="setStaff" type="checkbox" '.$staff.' value="'.$u->getEmail().'" /></td>
                        <td>
                            <a title="Enviar correo de recuperación de contraseña" class="btn btn-warning btn-xs" href="https://usergest-jmroldan00.c9users.io/user/controler.php?op=recovery&mail='.$u->getEmail().'">ECR</a>
                            <a title="Enviar correo de activación" class="btn btn-info  btn-xs" href="https://usergest-jmroldan00.c9users.io/user/controler.php?op=reactive&pkid='.$u->getEmail().'">ECA</a>
                        </td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        return $table;
    }
    static function getAlert($op, $r){
        $alertType = "alert-success";
        if($r == '-1'){
            $alertType = "alert-danger";  
        }
        if($r == '0'){
            $alertType = "alert-warning";  
        }
        $alert = '';
        switch ($op){
            case 'delete'; $alert = '<div class="alert '.$alertType.' alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Atención:</strong> Se han eliminido '.$r.' filas.
                                     </div>';
                            break;
            case 'update';  $alert = '<div class="alert '.$alertType.' alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Atención:</strong> Se han modificado '.$r.' filas.
                                     </div>';
                            break;
            case 'insert';  $alert = '<div class="alert '.$alertType.' alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Atención:</strong> Se ha insertado la fila con id '.$r.' filas.
                                     </div>';
                            break;
            default : break;
        }
        return $alert;
    }
}