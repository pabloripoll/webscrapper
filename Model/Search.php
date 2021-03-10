<?php
namespace Model;

use Database\Connection as DB;

class Search 
{

    private function setHistory($statistic){
        $user = $statistic['user'];
        $domain = $statistic['domain'];
        $count = $statistic['count'];
        $stmt = "SELECT * FROM `domains_stats` WHERE `user_id`='".$user."' AND `domain`='".$domain."';";
        $db = new DB();
        $sql = $db->getRow($stmt);
        if (!isset($sql->result)) {
            return $sql->error;
        } else {
            if ($sql->result == 0) {
                // insert
                $stmt = "INSERT INTO `domains_stats` (`user_id`,`domain`,`hits`) VALUES ('".$user."','".$domain."','".$count."');";            
            } else {
                // update
                $row = $sql->result[0];
                $count = $count + $row->hits;
                $stmt = "UPDATE `domains_stats` SET `hits`='".$count."' WHERE `user_id`='".$user."' AND `domain`='".$domain."';";
            }
            $db = new DB();
            $sql = $db->editRow($stmt);
            return $sql;
        }
    }

    public function getHistory($statistic){
        $setStatistic = self::setHistory($statistic);
        if($setStatistic === true) {
            $user = $statistic['user'];
            $domain = $statistic['domain'];
            $stmt = "SELECT * FROM `domains_stats` WHERE `user_id`='".$user."' AND `domain`='".$domain."';";
            $db = new DB();
            $sql = $db->getRow($stmt);
            if (!isset($sql->result)) {
                return $sql->error;
            } else {
                if ($sql->result == 0) {
                    return 0;
                } else {
                    foreach($sql->result as $row) {
                        return $row->hits;
                    }
                }
            }
        } else {
            return $setStatistic;
        }
    }

}