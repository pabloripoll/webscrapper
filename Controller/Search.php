<?php
session_start();

// *** CHECK ERRORS *** //
$display_errors = true;
if( $display_errors == true ){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
} else {
  ini_set('display_errors', 0);
}

require_once '../vendor/autoloader.php';

use Model\Search;

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
        $statistic = [];
        $statistic['user'] = $_SESSION['user']['id'];
        $statistic['domain'] =  $domain;
        $statistic['count'] = $count;
        $search = new Search;
        $history = $search->getHistory($statistic);
        is_numeric($history) ? : $history = 'error';
        $statistic['history'] = $history;
        $statistics = array_merge($statistics, array($statistic));
    }
    //array_multisort($sort['news_published'], SORT_DESC, $arr);
    return $statistics;
}

function searchEngine($keywords, $page) {
    $APIKEY = '';
    $CX = '';    
    $page == 1 ? $page = 0 : $page = ($page - 1).'0';
    $pagination = '&start='.$page;
    $url = 'https://www.googleapis.com/customsearch/v1?key='.$APIKEY.'&cx='.$CX.':omuauf_lfve&q='.$keywords.$pagination;
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
    if($keywords_sanatized == '+') {
        return false;
    } else {
        return $keywords_sanatized;
    }
    
}

function search()
{
    $ajax = $_POST;    
    $json = [];
    $keywords = $_POST['keywords'];
    $keywords_sanatized = sanatizeSearch($keywords);
    is_numeric($_POST['page']) ? $page = $_POST['page'] : $page = 1;

    if (empty($keywords)) {
        $json["process"] = 1;
        $json["status"] = false;
        $json["data"]["msg"] = 'No existe valores de búsqueda';
    } else {
        if ($keywords === false) {
            $json["process"] = 1;
            $json["status"] = false;
            $json["data"]["msg"] = 'Los valores de búsqueda no son adecuados';
        } else {
            $json["process"] = 1;
            $json["status"] = true;
            $json["data"]["keywords"] = $keywords;
            $json["data"]["html"] = searchEngine($keywords_sanatized, $page);
        }
    }
	/*
     * Ajax return in JSON format 
    */
	$json["json"] = json_encode($json);
    echo json_encode($json);
}
