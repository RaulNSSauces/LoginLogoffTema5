<?php
    /* 
    * @author: Raúl Núñez Sebastián.
    * @since: 29-10-2020.
    * Fichero de constantes para el constructor de la conexión de la base de datos (PDO);
    */

    //Constantes para la extensión PDO
 
    //ENTORNO DE MI CASA
/*
    define('DNS', "mysql:host=192.168.0.203:3306; dbname=DAW203DBProyectoTema5"); 
    define('USER', "usuarioDAW203DBProyectoTema5");
    define('PASSWORD', "P@ssw0rd");
*/
    //ENTORNO DE DESARROLLO
/*
    define('DNS', "mysql:host=192.168.20.19:3306; dbname=DAW203DBProyectoTema5"); 
    define('USER', "usuarioDAW203DBProyectoTema5");
    define('PASSWORD', "P@ssw0rd");
 */
/*
    //ENTORNO DE EXPLOTACIÓN
    define('DNS', "mysql:host=192.168.20.18:3306; dbname=DAW203DBProyectoTema5"); 
    define('USER', "usuarioDAW203DBProyectoTema5");
    define('PASSWORD', "P@ssw0rd");
*/
    //ENTORNO DE 1&1
    define('DNS', 'mysql:host=db771560035.hosting-data.io;dbname=db771560035');
    define('USER', 'dbo771560035');
    define('PASSWORD', 'Covid1234$');
?>