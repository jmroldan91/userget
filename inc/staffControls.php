<?php
    $db = new DataBase();
    $mng = Utils::getManager('user', $db);
    /*Busqueda o filtrado */
    $campo = Request::get('campo');
    $filter = Request::get('filter');
    $arrayWhere =[];
    $arrayWhere['administrador'] = '0';
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
    $arrayitems = $mng->getList($page, $nrpp, $order, $arrayWhere);
    $numPages = ceil($mng->getNumReg($arrayWhere)/$nrpp); 
    $pager = new Pager($mng->getNumReg($arrayWhere), $nrpp, $page);
?>
<div class="col-md-12">
    <h1>Control de usuarios</h1>
    <div class="col-md-8">
        <?php 
            echo Render::getUsersTable($arrayitems); 
            echo $pager->getHTMLPager('admin.php', 'all');    
        ?>
    </div>
    <div class="col-md-4">
        <h2>Alta de usuarios</h2>
        <p>El usuario se activará de forma automática.</p>
        <form class="form form-horizontal" method="POST" action="./controler.php?op=create">
            <label for="email">Email</label>
            <input class="form-control" type="mail" name="email" id="email" required/>
            <label for="pass">Contraseña</label>
            <input class="form-control" type="password" id="pass" name="pass" required/>
            <input class="form-control" type="password" id="pass2" name="pass2" placeholder="Repita la contraseña" required/>
            <label for="alias">Alias</label>
            <input class="form-control" type="text" name="alias" id="alias" required/>
            <hr/>
            <input class="btn btn-primary" type="submit" value="Crear usuario"/>
        </form>
    </div>
</div>