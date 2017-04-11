<?php
  // Includes
  include_once("../DB_config/login_credentials_DB_bpmspace_coms_RO.inc.php"); 
  // Parameter and inputstream
  $params = json_decode(file_get_contents('php://input'), true);
  $command = $params["cmd"];
    
  //VerifyHandler Class Definition starts here
  class VerifyHandler {
    // Variables
    private $db;

    public function __construct() {
      // create DB connection object - Data comes from config file
      $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      // check connection
      if($db->connect_errno){
        printf("Connect failed: %s", mysqli_connect_error());
        exit();
      }
      $db->query("SET NAMES utf8");
      $this->db = $db;
    }
    // Format data for output
    private function parseToJSON($result) {
      $results_array = array();
      if (!$result) return false;
      while ($row = $result->fetch_assoc()) {
        $results_array[] = $row;
      }
      return json_encode($results_array);
    }


    //================================== READ
    public function read($param) {
      $where = isset($param["where"]) ? $param["where"] : "";
      if (trim($where) <> "") $where = " WHERE ".$param["where"];
      // SQL
      $query = "SELECT ".$param["select"]." FROM ".
        $param["tablename"].$where." LIMIT ".$param["limitStart"].",".$param["limitSize"].";"; 
      //var_dump($query);
      $res = $this->db->query($query);
      return $this->parseToJSON($res);
    }

  }
  // Class Definition ends here
  // Verify Handler ends here


