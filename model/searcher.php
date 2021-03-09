<?php
session_start();

header("Content-type: application/javascript");

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax())
{
  if ( isset($_POST["action"]) && !empty($_POST["action"]) ) {
    $action = $_POST["action"];
    switch($action) {
        case "search" : search(); break;
        
    }
  }
}

class DB
{
    private $servername = "127.0.0.1";
    private $username = "root";
    private $password = "Mysql6983Perc!";
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

/* $stmt = "SELECT U.rol_id, R.rol, R.id, COUNT(R.id) AS suma_rol FROM usuarios U LEFT JOIN roles R ON R.id = U.rol_id GROUP BY U.rol_id ORDER BY suma_rol DESC;";
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
} */

function domainStats($objects) {
    $domains = [];
    foreach($objects as $key => $obj){
        $domain = str_ireplace('www.', '', parse_url($obj->link, PHP_URL_HOST));
        $domains = array_merge($domains, [$domain]);
    }
    $domains = array_count_values($domains);
    arsort($domains);

    $statistics = [];
    $i = 0;
    foreach($domains as $domain => $count){
        // DB

        $statistics = array_merge($statistics, array(['domain' => $domain, 'count' => $count, 'history' => 0]) );
    }
    //array_multisort($sort['news_published'], SORT_DESC, $arr);
    return $statistics;
}

function searchEngine($keywords) {
    $APIKEY = 'AIzaSyBZIQ7ctzV4haX-dz0ePfxVkAoEGEdNHUE';
    $url = 'https://www.googleapis.com/customsearch/v1?key='.$APIKEY.'&cx=017576662512468239146:omuauf_lfve&q='.$keywords.'&start=10';
    $body = file_get_contents($url);
    $json = json_decode($body);
    if ($json->items){        
        $json->statistics = domainStats($json->items);
        return $json;
    } else {
        return false;
    }    
}

function sanatizeSearch($keywords)
{
    $keywords = strtolower($keywords);
    $keywords = preg_replace('/[^a-zñáéíóúüçàèìòù0-9 ]/', '', $keywords);
    $exp = explode(' ', $keywords);
    $keywords_sanatized = '';
    foreach ($exp as $key =>$value) {
        $keywords_sanatized = $value.'+';
    }
    $keywords_sanatized = rtrim($keywords_sanatized, '+');
    return $keywords_sanatized;
}

function search()
{
    $ajax = $_POST;    
    $json = [];

    if (empty($_POST['keywords'])) {
        $json["process"] = 1;
        $json["status"] = false;
        $json["data"]["msg"] = 'No existe valores de búsqueda';
    } else {
        $keywords = $_POST['keywords'];
        $keywords_sanatized = sanatizeSearch($keywords);
        if ($keywords === false || empty($keywords_sanatized)) {
            $json["process"] = 1;
            $json["status"] = false;
            $json["data"]["msg"] = 'Los valores de búsqueda no son adecuados';
        } else {
            $json["process"] = 1;
            $json["status"] = true;
            $json["data"]["keywords"] = $keywords;
            $json["data"]["html"] = searchEngine($keywords_sanatized);
        }
    }
	/*
     * Ajax return in JSON format 
    */
	$json["json"] = json_encode($json);
    echo json_encode($json);
}