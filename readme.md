
## Ejercicio 1
Documentacion utilizada para realizar el ejercicio 1

https://www.googleapis.com/customsearch/v1?key=INSERT_YOUR_API_KEY&cx=HASH:omuauf_lfve&q=KEYWORDS

https://developers.google.com/custom-search/v1/using_rest

https://developers.google.com/custom-search/v1/introduction#identify_your_application_to_google_with_api_key

https://developers.google.com/custom-search/v1/reference/rest/v1/cse/list?apix=true

https://developers.google.com/custom-search/v1/site_restricted_api


## Ejercicio 2
Teniendo en cuenta la configuración del projecto Web Scrapper, las respuestas del ejercicio 2 paso a responder de la siguiente manera.

1- Cuántos Usuarios hay por cada tipo de Rol ordenados de forma descendente en función del Rol más usado al menos.
```bash
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
```
2- Listar aquellos Usuarios que habiendo recibido soporte (soporte_act) nunca han dado feedback.
```bash
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
```
3- Eliminar los Ratones en el que la marca coincida con algunas de las marcas de teclados o monitores.
```bash
$stmt = "SELECT * FROM ratones R WHERE EXISTS(SELECT marca FROM teclados WHERE marca=R.marca) OR EXISTS(SELECT marca FROM monitores WHERE marca=R.marca)";
$db = new DB();
$sql = $db->query($stmt);
if (!isset($sql->result)) {
    echo $sql->error;
} else {
    if ($sql->result == 0) {
        echo "0 results";
    } else {
        foreach($sql->result as $row) {
            $stmt = "DELETE FROM ratones WHERE id=".$row->id.";";
            $db = new DB();
            $sql = $db->query($stmt);
            echo 'Ratón id:'.$row->id.' marca:'.$row->marca.' ha sido borrado de la tabla `ratones`<br>';
        }
    }
}
```