
     ,-----.,--.                  ,--. ,---.   ,--.,------.  ,------.
    '  .--./|  | ,---. ,--.,--. ,-|  || o   \  |  ||  .-.  \ |  .---'
    |  |    |  || .-. ||  ||  |' .-. |`..'  |  |  ||  |  \  :|  `--, 
    '  '--'\|  |' '-' ''  ''  '\ `-' | .'  /   |  ||  '--'  /|  `---.
     `-----'`--' `---'  `----'  `---'  `--'    `--'`-------' `------'
    ----------------------------------------------------------------- 


Proyecto de módulo de gestión de ususarios con PHP/MySql/js y login y registro con credenciales de google.

Fecha pevista de finalización 10/01/2016
/user/
Vistas html (templates)
    -Formulario de registro
    -Formulario de login
    -Modificación de perfil de ususario
    -Recordar contraseñalias
    -Alta de ususarios por administrador
Controladores PHP
    -user/controler.php
        Alta, baja, modoficacion, login, logout, google(alta y login)
Modelo (Clases php) /classes/
    -user.php: clase POJO para la tabla user
    -manageUser.php: Clase de control de la tabla user (insert, update, delete, validate)
    -database.php: PDO to MySQL
Modulo Mail:
    -oAuth para la API de google
    
El modulo hace uso de varias clases útiles en /classes/
    Mail, Server, Session, Render...
        

Juan Manuel Roldán Rueda

juanroldan.freelance@gmail.com
