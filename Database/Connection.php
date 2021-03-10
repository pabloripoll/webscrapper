<?php
namespace Database;

class Connection
{
    private $servername = "127.0.0.1";
    private $username = "webscrapper";
    private $password = "******";
    private $dbname = "webscrapper";

    public function getRow($stmt) {
        $response = new \stdClass();
        $conn = new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
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

    public function editRow($stmt) {
        $response = new \stdClass();
        $conn = new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = $conn->query($stmt);
        if ($conn->connect_error) {
            $response->error = 'Connection failed: '. $conn->connect_error;
        } else {
            $response = true;
        }
        $conn->close();
        return $response;
    }

}