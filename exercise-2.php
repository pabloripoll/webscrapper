<?php

// *** CHECK ERRORS *** //
$display_errors = true; // if remains TRUE some script functionalities may not run correctly
if( $display_errors == true ){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
} else {
  ini_set('display_errors', 0);
}

class DB
{
    private $servername = "127.0.0.1";
    private $username = "*****";
    private $password = "******";
    private $dbname = "nemon";

    public function query($stmt) {
        $response = new \stdClass();
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = $conn->query($stmt);
        if ($conn->connect_error) {
            $response->error = 'Connection failed: '. $conn->connect_error;
        } else if ($sql->num_rows == 0) {
            $response->result = false;
        } else {
            $response->result = [];
            while($row = $sql->fetch_object()) {
                $response->result[] = $row;
            }
        }
        $conn->close();
        return $response;
    }
}


/* First */
$stmt = "SELECT U.rol_id, R.rol, R.id, COUNT(R.id) AS suma_rol FROM usuarios U LEFT JOIN roles R ON R.id = U.rol_id GROUP BY U.rol_id ORDER BY suma_rol DESC;";
$db = new DB();
$sql = $db->query($stmt);
if (!isset($sql->result)) {
    echo $sql->error;
} else {
    if ($sql->result == 0) {
        echo "0 results";
    } else {
        foreach($sql->result as $row) {
            echo 'Totales de rol "'.$row->rol.'" con `id` '.$row->id.' = '.$row->suma_rol.'<br>';
        }
    }
}
//echo '<pre>'; print_r($sql); echo '</pre>';
echo '<br><br>';

/* Second */
$stmt = "SELECT S.usuario_id,U.nombre FROM soporte_act S LEFT JOIN feedback F ON F.usuario_id = S.usuario_id LEFT JOIN usuarios U ON U.id = S.usuario_id WHERE F.usuario_id IS NULL";
$db = new DB();
$sql = $db->query($stmt);
if (!isset($sql->result)) {
    echo $sql->error;
} else {
    if ($sql->result == 0) {
        echo "0 results";
    } else {
        foreach($sql->result as $row) {
            echo 'Usuario '.$row->nombre.'<br>';
        }
    }
}
echo '<br><br>';

/* Three */
$stmt = "SELECT * FROM usuarios U WHERE EXISTS(SELECT usuario_id FROM feedback WHERE usuario_id=U.id) OR EXISTS(SELECT usuario_id FROM feedforth WHERE usuario_id=U.id)";
$db = new DB();
$sql = $db->query($stmt);
if (!isset($sql->result)) {
    echo $sql->error;
} else {
    if ($sql->result == 0) {
        echo "0 results";
    } else {
        foreach($sql->result as $row) {
            echo 'Usuario '.$row->id.' '.$row->nombre.' ha sido borrado de la tabla `usuarios`<br>';
        }
    }
}