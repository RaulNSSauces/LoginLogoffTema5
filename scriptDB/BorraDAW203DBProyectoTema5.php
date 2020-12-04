<?php
        /**
            *@author: Raúl Núñez
            *@since: 28/11/2020
        */ 
            
        require_once "../config/confDBPDO.php";
        
            try {
                $miDB = new PDO(DNS,USER,PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = <<<EOD
                        DROP TABLE T02_Departamento;
                        DROP TABLE T01_Usuario;
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Tablas borrada</span></h3>";
            }
            catch (PDOException $excepcion) {
                $errorExcepcion = $excepcion->getCode();
                $mensajeExcepcion = $excepcion->getMessage();
                
                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;
            } finally {
                unset($miDB);
            }
?>
