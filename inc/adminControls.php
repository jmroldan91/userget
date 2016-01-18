<?php
    $db = new DataBase();
    $mng = Utils::getManager('user', $db);
    /*Busqueda o filtrado */
    $campo = Request::get('campo');
    $filter = Request::get('filter');
    $arrayWhere =[];
    if($filter!=null && $filter!=""){
        $arrayWhere[$campo] = '%'.$filter.'%';
    }
    /*Listado paginado*/
    $nrpp = Request::get('nrpp');
    if($nrpp==null || $nrpp==""){
        $nrpp= Constant::_NRPP;
    }
    $order = Request::get('order');
    if($order==null || $order==""){
        $order = 1;
    }
    $page = Request::get('page');
    if($page==null || $page==""){
        $page = 1;
    }
    $query = new QueryString();
    $arrayitems = $mng->getList($page, $nrpp, $order, $arrayWhere);
    $pager = new Pager($mng->getNumReg($arrayWhere), $nrpp, $page);
?>
<div class="col-md-12">
    <h1>Control de usuarios</h1>
    <div class="col-md-8">
        <?php 
            echo Render::getAdminsTable($arrayitems); 
            echo $pager->getHTMLPager('admin.php', 'all'); 
        ?>
    </div>
    <div class="col-md-4">
        <h2>Alta de usuarios</h2>
        <form class="form form-horizontal" method="POST" action="./controler.php?op=create">
            <label for="email">Email</label>
            <input class="form-control" type="mail" name="email" id="email" required/>
            <label for="pass">Contraseña</label>
            <input class="form-control" type="password" id="pass" name="pass" required/>
            <input class="form-control" type="password" id="pass2" name="pass2" placeholder="Repita la contraseña" required/>
            <label for="alias">Alias</label>
            <input class="form-control" type="text" name="alias" id="alias" required/>
            <label for="activo">Activo</label>
            <input type="checkbox" name="activo" id="activo" value="1" />
            <label for="administrador">Administrador</label>
            <input type="checkbox" name="administrador" id="administrador" value="1" />
            <label for="personal">Personal</label>
            <input type="checkbox" name="personal" id="personal" value="1" />
            <hr/>
            <input class="btn btn-primary" type="submit" value="Crear usuario"/>
        </form>
    </div>
</div>
<script>
    (function controllers(){
        var btnAct = document.getElementsByName('setActive');
        var btnAdm = document.getElementsByName('setAdmin');
        var btnStf = document.getElementsByName('setStaff');
        for(var i=0; i<btnAct.length;i++){
            btnAct[i].onclick = function(ev){
                window.location.href='https://usergest-jmroldan00.c9users.io/user/controler.php?op=setActive&pkid='+ev.target.value;
            };
        }
        for(var i=0; i<btnAdm.length;i++){
            btnAdm[i].onclick = function(ev){
                window.location.href='https://usergest-jmroldan00.c9users.io/user/controler.php?op=setAdmin&pkid='+ev.target.value;
            };
        }
        for(var i=0; i<btnStf.length;i++){
            btnStf[i].onclick = function(ev){
                window.location.href='https://usergest-jmroldan00.c9users.io/user/controler.php?op=setStaff&pkid='+ev.target.value;
            };
        }
    })();
</script>