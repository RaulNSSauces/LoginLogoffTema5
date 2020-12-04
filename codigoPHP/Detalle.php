<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Detalle</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
        <table style="width: 50%; margin: auto;">
            <h2 style="text-align: center;">Variables superglobales</h2> 
            <form>
                <input class="btn" type="button" onclick="history.back()" name="volver" value="ATÁS">
            </form>
<?php
        
    /* 
        * @author: Raúl Núñez Sebastián.
        * @since: 23-11-2020.
        * Detalle - Mostrar el contenido de las variables superglobales y phpinfo().
    */
?>
<?php
    session_start();
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_COOKIE</td>
    </tr>
<?php
    if(!empty($_COOKIE)){
        foreach ($_COOKIE as $campo => $valor) { //Recorro con un foreach la variable $_COOKIE.
?>
    <tr>
        <td style="background-color:#a0bff5; font-weight: bold;"><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr> 
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_SESSION</td>
    </tr>
<?php
    if(!empty($_SESSION)){
        foreach ($_SESSION as $campo => $valor) { //Recorro con un foreach la variable $_SESSION.   
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr> 
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_SERVER</td>
    </tr>
<?php
    if(!empty($_SERVER)){
        foreach ($_SERVER as $campo => $valor) { //Recorro con un foreach la variable $_SERVER.
?>
    <tr>
        <td style="background-color:#a0bff5; font-weight: bold;"><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr>
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_GET</td>
    </tr>
<?php
    if(!empty($_GET)){
        foreach ($_GET as $campo => $valor) { //Recorro con un foreach la variable $_GET.
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr>
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_POST</td>
    </tr>
<?php
    if(!empty($_POST)){
        foreach ($_POST as $campo => $valor) { //Recorro con un foreach la variable $_POST.
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr>
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_FILE</td>
    </tr>
<?php
    if(!empty($_FILE)){
        foreach ($_FILE as $campo => $valor) { //Recorro con un foreach la variable $_FILE.
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr>          
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_REQUEST</td>
    </tr>
<?php
    if(!empty($_REQUEST)){
        foreach ($_REQUEST as $campo => $valor) { //Recorro con un foreach la variable $_REQUEST.          
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr> 
<?php
        }
    }
?>
    <tr>
        <td style="background-color: #fac15c; font-weight: bold; text-align: center;" colspan="2">$_ENV</td>
    </tr>
<?php
    if(!empty($_ENV)){
        foreach ($_ENV as $campo => $valor) { //Recorro con un foreach la variable $_GLOBALS_ENV.
?>
    <tr>
        <td><?php echo $campo?></td>
        <td><?php echo $valor?></td>
    </tr>
<?php
        }
    }
?>
        </table>
            <h2 style="text-align: center;">PHP INFO</h2>
        <?php
           phpinfo();
        ?>         
    </body>
</html>