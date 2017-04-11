<?php

  // Includes
  include_once '../phpSecureLogin/includes/db_connect.inc.php';
  include_once '../phpSecureLogin/includes/functions.inc.php';
  sec_session_start();
  
  if(login_check($mysqli) != true) {
    header("Location: ../index.php?error_messages='You are not logged in!'");
    exit();
  }
  else {
    $logged = 'in';
  }

  // Includes
  include_once("../DB_config/login_credentials_DB_bpmspace_coms.inc.php"); 
  // Parameter and inputstream
  $params = json_decode(file_get_contents('php://input'), true);
  $command = $params["cmd"];
    
  //RequestHandler Class Definition starts here
  class RequestHandler {
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
    private function buildSQLWherePart($primarycols, $rowcols) {
      $where = "";
      foreach ($primarycols as $col) {
        $where = $where . $col . "='" . $rowcols[$col] . "'";
        $where = $where . " AND ";
      }
      $where = substr($where, 0, -5); // remove last ' AND ' (5 chars)
      return $where;
    }
    private function buildSQLUpdatePart($cols, $primarycols, $rows) {
      $update = "";
      // Convert everything to lowercase      
      $primarycols = array_map('strtolower', $primarycols);
      $cols = array_map('strtolower', $cols);
      // Loop every element
      foreach ($cols as $col) {
        // update only when no primary column
        if (!in_array($col, $primarycols)) {
          $update = $update . $col . "='" . $rows[$col] . "'";
          $update = $update . ", ";
        }
      }
      $update = substr($update, 0, -2); // remove last ' ,' (2 chars)
      return $update;
    }
    //================================== CREATE
    public function create($param) {
      // Inputs
      $tablename = $param["table"];
      $rowdata = $param["row"];
      // Operation
      $query = "INSERT INTO ".$tablename." VALUES ('".implode("','", $rowdata)."');";
      $res = $this->db->query($query);
      // Output
      return $res ? "1" : "0";
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
    //================================== UPDATE
    public function update($param) {
      // SQL
      $update = $this->buildSQLUpdatePart(array_keys($param["row"]), $param["primary_col"], $param["row"]);
      $where = $this->buildSQLWherePart($param["primary_col"], $param["row"]);
      $query = "UPDATE ".$param["table"]." SET ".$update." WHERE ".$where.";";
      //var_dump($query);
      $res = $this->db->query($query);
      // TODO: Check if rows where REALLY updated!
      // Output
      return $res ? "1" : "0";
    }
    //================================== DELETE
    public function delete($param) {
      /*  DELETE FROM table_name WHERE some_column=some_value AND x=1;  */
      $where = $this->buildSQLWherePart($param["primary_col"], $param["row"]);
      // Build query
      $query = "DELETE FROM ".$param["table"]." WHERE ".$where.";";
      $res = $this->db->query($query);
      // Output
      return $res ? "1" : "0";
    }
  }
  // Class Definition ends here
  // Request Handler ends here
  
  $RH = new RequestHandler();
  
  // check if at least a command is set
  if ($command != "") {
    // are there parameters?
    if ($params != "") {
      // execute with parameters
      $result = $RH->$command($params["paramJS"]);
    } else {
      // only execute
      $result = $RH->$command();
    }
    // Output
    echo $result;
    exit();
  }
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="genApp">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>bpmspace_coms_v1</title>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" media="screen" />
  <link rel="stylesheet" href="../css/bootstrap-theme.min.css" media="screen" />
  <link rel="stylesheet" href="../css/font-awesome.min.css" />
  <link rel="stylesheet" href="../css/fuelux.min.css" />
  <link rel="stylesheet" href="../css/xeditable.css" />
  <link rel="stylesheet" href="../css/jsonviewer.css" />
  <link rel="stylesheet" href="../IPMS/css/muster.css" />
</head>
<body ng-controller="genCtrl">
  <div>  <!--  body menu starts here -->
  <app></app>
  <div class="container">
    <div class="row">
      <div  class="row text-right">
        <div class="col-md-12">
          <a href='#' id="bpm-logo-care" class="btn collapsed" data-toggle="collapse" data-target="#bpm-logo, #bpm-liam-header">
            <i class="fa fa-caret-square-o-down"></i>
          </a>
        </div>
        <div class="col-md-12 collapse" id="bpm-liam-header">
          <?php include_once('../_header_LIAM.inc.php'); ?>          
        </div>
      </div>
    </div>
    <!-- Company Header -->
    <div class="row collapse">
      <div class="col-md-12" id="bpm-logo">
        <div class="col-md-6 ">
          <svg height="100" width="100">
            <rect fill="red" x="0" y="0" width="100" height="100" rx="15" ry="15"></rect>
            <text x="50" y="55" fill="white" text-anchor="middle" alignment-baseline="central">your logo</text>
          </svg>
        </div>
        <div class="col-md-6 ">
          <svg class="pull-right" height="100" width="200">
            <rect fill="blue" x="0" y="0" width="200" height="100" rx="15" ry="15"></rect>
            <text x="100" y="55" fill="white" text-anchor="middle" alignment-baseline="central">sample</text>
          </svg>
        </div>
      </div>
    </div>
  </div>
  <!--
  <div id="json-renderer" class="collapsed"></div>
  -->
  <!-- NAVIGATION -->
  <div style="margin: 0 1em;">
  <nav class="navbar navbar-nav">
    <div class="container">
      <ul class="nav nav-pills" id="bpm-menu">
        <li ng-repeat="table in tables">
          <a id="nav-{{table.table_name}}" title="Goto table {{table.table_alias}}"
            href="#{{table.table_name}}" data-toggle="tab" ng-click="changeTab()">
            <i class="{{table.table_icon}}"></i>&nbsp;{{table.table_alias}}</a>
        </li>
      </ul>
    </div>
  </nav> <!-- body content starts here  -->
  <div style="margin: 0 1em;">
    <div class="row">
      <div class="col-md-12 tab-content" id="bpm-content">

        <div ng-repeat="table in tables track by $index" class="tab-pane" id="{{table.table_name}}">
          <div class="panel panel-default panel-table" disabled>
            <div class="panel-heading">
              <h3 class="panel-title">
                <div class="pull-left" style="margin-top: .4em; font-weight: bold;">{{table.table_alias}}</div>
                <!--
                <input type="text" style="width:50px" class="form-control pull-right" ng-model="PageLimit">
                -->
                <form class="form-inline pull-right">
                  <div class="form-group">
                    <input type="text" class="form-control" style="width:200px;" placeholder="WHERE"
                      ng-model="sqlwhere[$index]" />
                    <button class="btn btn-default" title="Refresh table"
                      ng-click="refresh(table, $index);"><i class="fa fa-refresh"></i></button>
                  </div>
                </form>
                <div class="clearfix"></div>
              </h3>
            </div>
            <div class="panel-body table-responsive" style="padding:0;">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th><em class="fa fa-cog"></em></th>
                    <th ng-repeat="col in table.columnsX">{{col.COLUMN_NAME}}</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table Content -->
                  <tr ng-repeat="row in table.rows track by $index" ng-model="table"
                      data-toggle='modal' data-target="modal-container-1"
                      id="row{{'' + $parent.$index + $index}}">
                    <td class="controllcoulm">
                      <!-- Delete Button -->
                      <button id="del{{$index}}" class="btn btn-danger" title="Delete this Row"
                        ng-click="send('delete', {row:row, colum:$index, table:table})">
                        <i class="fa fa-times"></i><!-- Delete--></button>
                      <!-- Update Button -->
                      <button id="btnRow{{'' + $parent.$index + $index}}" class="btn btn-success btnUpdate" title="Update this Row"
                        ng-click="send('update', {row:row, colum:$index, table:table, x:[$index, $parent.$index]})">
                        <i class="fa fa-floppy-o"></i><!-- Update--></button>
                    </td>
                    <td ng-repeat="cell in row track by $index">
                      <!-- xeditable controllfield -->
                      <!-- <a href="#" editable-text="cell">{{ cell || "empty" }}</a> -->
                      <!-- normal Textarea -->
                      <textarea class="form-control" 
                      rows="1" cols="{{cell.length}}" 
                      ng-focus="rememberOrigin(table.table_name, table.columnames, row, cell, $parent.$parent.$index, $index)"
                      ng-blur="checkCellChange(table, row, cell, $parent.$parent.$parent.$index, $parent.$parent.$index, $index)"
                      ng-model="cell"
                      ng-if="!(table.columnames[$index] == table.primary_col)">{{cell}}</textarea>
                      <p ng-if="table.columnames[$index] == table.primary_col">{{cell}}</p>
                    </td>
                  </tr>
                  <!-- Table AddRow -->
                  <tr class="newRows">
                   <td>
                      <!-- Create Button -->
                      <button class="btn btn-primary" title="Create new Row"
                        ng-click="send('create', {row:table.newRows[0], table:table})">
                        <i class="fa fa-plus"></i><!-- Create--></button>
                   </td>
                   <td ng-repeat="col in table.newRows[0] track by $index">
                      <!--<textarea class="form-control nRws" ng-model="table.newRows[0][$index]"></textarea>-->
                      <!-- Number -->
                      <input class="form-control nRws" type="number"
                        ng-show="table.columnsX[$index].COLUMN_TYPE.indexOf('int') >= 0 && table.columnsX[$index].COLUMN_TYPE.indexOf('tiny') < 0"
                        ng-model="table.newRows[0][$index]">
                      <!-- Text -->
                      <input class="form-control nRws" type="text"
                        ng-show="table.columnsX[$index].COLUMN_TYPE.indexOf('int') < 0"
                        ng-model="table.newRows[0][$index]">
                      <!-- Date -->
                      <!-- Boolean (tinyint or boolean) -->
                      <input class="form-control nRws" type="checkbox"
                        ng-show="table.columnsX[$index].COLUMN_TYPE.indexOf('tinyint') >= 0"
                        ng-model="table.newRows[0][$index]">
                      <!-- Datatype --> 
                      <div><small>{{ table.columnsX[$index].COLUMN_TYPE }}</small></div>
                   </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                  <div class="col col-xs-6">
                    <b>Status:</b> {{status}} - {{table.count}} Entries // Showing page {{PageIndex + 1}} of {{table.count / PageLimit | ceil}}
                  </div>
                  <div class="col col-xs-6">
                    <!--<ul class="pagination hidden-xs pull-right">
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">4</a></li>
                      <li><a href="#">5</a></li>
                    </ul>-->
                    <ul class="pagination pull-right"><!-- visible-xs -->
                        <li ng-class="{disabled: PageIndex <= 0}">
                          <a href="" ng-click="gotoPage(-1, table, $index)">« Page</a></li>
                        <li ng-class="{disabled: (PageIndex + 1) >= (table.count / PageLimit)}">
                          <a href="" ng-click="gotoPage(1, table, $index)">Page »</a></li>
                    </ul>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
		<div class="modal fade" id="modal-container-1" role="dialog" >
		  <div class="modal-dialog" role="document">
		    <div class="modal-content edums-tamodal-tacontent">
		       <button type="button" class="close btn-default" data-dismiss="modal" aria-hidden="true">X </button>
		    </div>
		  </div>
		</div>
    <!-- content ends here -->
  </div>    <!--  Footer -->
    <div class="row text-center">
      <p style="margin:0;">bpmspace_coms_v1</p>
      <small>using</small>  
      <br/>            
      <small>
        <ul class="list-inline">
          <li><a target="_blank" href="http://getbootstrap.com/">Bootstrap</a></li>
          <li><a target="_blank" href="https://jquery.com/">jQuery</a></li>
          <li><a target="_blank" href="https://github.com/abodelot/jquery.json-viewer">jQuery json-viewer</a></li>
          <li><a target="_blank" href="https://angularjs.org/">AngularJS</a></li>
          <li><a target="_blank" href="http://php.net/">PHP</a></li>
          <li><a target="_blank" href="http://getfuelux.com/">FuelUX</a></li>
          <li><a target="_blank" href="https://angular-ui.github.io/">AngularUI</a></li>
          <li><a target="_blank" href="https://www.tinymce.com/">TinyMCE</a></li>
          <li><a target="_blank" href="https://vitalets.github.io/x-editable/">X-editable</a></li>
          <li><a target="_blank" href="https://github.com/peredurabefrog/phpSecureLogin">phpSecureLogin</a></li>
        </ul>
      </small>
    </div>
  </div>
  <!-- JS -->
  <script type="text/javascript" src="../js/angular.min.js"></script>
  <script type="text/javascript" src="../js/angular-sanitize.min.js"></script>
  <script type="text/javascript" src="../js/ui-bootstrap-1.3.1.min.js"></script>
  <script type="text/javascript" src="../js/ui-bootstrap-tpls-1.3.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery-2.1.4.min.js"></script>
  <script type="text/javascript" src="../js/tinymce.min.js"></script>
  <script type="text/javascript" src="../js/tinymceng.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../js/xeditable.min.js"></script>
  <!-- the line below gets replaced with the generated table -->
  <!-- replaceDBContent -->
  <!-- Angular handling-script -->
  <script type="text/javascript">tables = [{"table_name":"coms_certificate","table_alias":"Coms_certificate","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_certificate","COLUMN_NAME":"coms_certificate_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_certificate","COLUMN_NAME":"coms_certificate_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_exam","table_alias":"Coms_exam","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam","COLUMN_NAME":"coms_exam_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam","COLUMN_NAME":"coms_exam_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_exam_event","table_alias":"Coms_exam_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_event","COLUMN_NAME":"coms_exam_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_event","COLUMN_NAME":"coms_exam_event_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_exam_exam_version","table_alias":"Coms_exam_exam_version","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_exam_version","COLUMN_NAME":"coms_exam_exam_version_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_exam_version","COLUMN_NAME":"coms_exam_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_exam_version","COLUMN_NAME":"coms_exam_version_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_exam_training","table_alias":"Coms_exam_training","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_training","COLUMN_NAME":"coms_exam_training_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_training","COLUMN_NAME":"coms_exam_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_training","COLUMN_NAME":"coms_training_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_exam_version","table_alias":"Coms_exam_version","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_version","COLUMN_NAME":"coms_exam_version_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_exam_version","COLUMN_NAME":"coms_exam_version_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_participant","table_alias":"Coms_participant","is_in_menu":true,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_lastname","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"128","CHARACTER_OCTET_LENGTH":"384","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"varchar(128)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_firstname","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"128","CHARACTER_OCTET_LENGTH":"384","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"varchar(128)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_public","ORDINAL_POSITION":"4","COLUMN_DEFAULT":"0","IS_NULLABLE":"YES","DATA_TYPE":"tinyint","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"3","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"tinyint(4)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_placeofbirth","ORDINAL_POSITION":"5","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"128","CHARACTER_OCTET_LENGTH":"384","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"varchar(128)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_birthcountry","ORDINAL_POSITION":"6","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"128","CHARACTER_OCTET_LENGTH":"384","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"varchar(128)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_dateofbirth","ORDINAL_POSITION":"7","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"date","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"date","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant","COLUMN_NAME":"coms_participant_LIAM_id","ORDINAL_POSITION":"8","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_participant_exam_event","table_alias":"Coms_participant_exam_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_exam_event","COLUMN_NAME":"coms_participant_exam_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_exam_event","COLUMN_NAME":"coms_participant_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_exam_event","COLUMN_NAME":"coms_exam_event_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_participant_matriculation_number","table_alias":"Coms_participant_matriculation_number","is_in_menu":true,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_matriculation_number","COLUMN_NAME":"coms_participant_matriculation_number_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"auto_increment","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_matriculation_number","COLUMN_NAME":"coms_participant_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_matriculation_number","COLUMN_NAME":"coms__matriculation_number","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_participant_training_event","table_alias":"Coms_participant_training_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_training_event","COLUMN_NAME":"coms_participant_training_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_training_event","COLUMN_NAME":"coms_participant_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_participant_training_event","COLUMN_NAME":"coms_training_event_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_role","table_alias":"Coms_role","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_role","COLUMN_NAME":"coms_role_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_role","COLUMN_NAME":"role_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_role_liamuser","table_alias":"Coms_role_liamuser","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_role_liamuser","COLUMN_NAME":"coms_role_LIAMUSER_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_role_liamuser","COLUMN_NAME":"coms_role_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_role_liamuser","COLUMN_NAME":"coms_LIAMUSER_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_trainer","table_alias":"Coms_trainer","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_trainer","COLUMN_NAME":"coms_trainer_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_trainer","COLUMN_NAME":"coms_trainer_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_trainer_exam","table_alias":"Coms_trainer_exam","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_trainer_exam","COLUMN_NAME":"coms_trainer_exam_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_trainer_exam","COLUMN_NAME":"coms_trainer_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_trainer_exam","COLUMN_NAME":"coms_exam_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training","table_alias":"Coms_training","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training","COLUMN_NAME":"coms_training_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training","COLUMN_NAME":"coms_training_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training_event","table_alias":"Coms_training_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_event","COLUMN_NAME":"coms_training_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_event","COLUMN_NAME":"coms_training_event_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training_organisation","table_alias":"Coms_training_organisation","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation","COLUMN_NAME":"coms_training_organisation_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation","COLUMN_NAME":"coms_training_organisation_name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training_organisation_exam_event","table_alias":"Coms_training_organisation_exam_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_exam_event","COLUMN_NAME":"coms_training_organisation_exam_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_exam_event","COLUMN_NAME":"coms_training_organisation_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_exam_event","COLUMN_NAME":"coms_exam_event_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training_organisation_trainer","table_alias":"Coms_training_organisation_trainer","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_trainer","COLUMN_NAME":"coms_training_organisation_trainer_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_trainer","COLUMN_NAME":"coms_training_organisation_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_trainer","COLUMN_NAME":"coms_trainer_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"coms_training_organisation_training_event","table_alias":"Coms_training_organisation_training_event","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_training_event","COLUMN_NAME":"coms_training_organisation_training_event_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_training_event","COLUMN_NAME":"coms_training_organisation_id","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"coms_training_organisation_training_event","COLUMN_NAME":"coms_training_event_id","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"language","table_alias":"Language","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"int","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"10","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"int(11)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_replacer","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"256","CHARACTER_OCTET_LENGTH":"768","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"varchar(256)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_en","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_de","ORDINAL_POSITION":"4","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_fr","ORDINAL_POSITION":"5","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"language","COLUMN_NAME":"language_es","ORDINAL_POSITION":"6","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"utf8","COLLATION_NAME":"utf8_general_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"state","table_alias":"State","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state","COLUMN_NAME":"state_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"bigint","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"19","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"bigint(20)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state","COLUMN_NAME":"name","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"45","CHARACTER_OCTET_LENGTH":"45","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"latin1","COLLATION_NAME":"latin1_swedish_ci","COLUMN_TYPE":"varchar(45)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state","COLUMN_NAME":"form_data","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"longtext","CHARACTER_MAXIMUM_LENGTH":"4294967295","CHARACTER_OCTET_LENGTH":"4294967295","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"latin1","COLLATION_NAME":"latin1_swedish_ci","COLUMN_TYPE":"longtext","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"},{"table_name":"state_rules","table_alias":"State_rules","is_in_menu":false,"columns":[{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state_rules","COLUMN_NAME":"state_rules_id","ORDINAL_POSITION":"1","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"bigint","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"19","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"bigint(20)","COLUMN_KEY":"PRI","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state_rules","COLUMN_NAME":"state_id_FROM","ORDINAL_POSITION":"2","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"bigint","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"19","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"bigint(20)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state_rules","COLUMN_NAME":"state_id_TO","ORDINAL_POSITION":"3","COLUMN_DEFAULT":null,"IS_NULLABLE":"NO","DATA_TYPE":"bigint","CHARACTER_MAXIMUM_LENGTH":null,"CHARACTER_OCTET_LENGTH":null,"NUMERIC_PRECISION":"19","NUMERIC_SCALE":"0","CHARACTER_SET_NAME":null,"COLLATION_NAME":null,"COLUMN_TYPE":"bigint(20)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""},{"TABLE_CATALOG":"def","TABLE_SCHEMA":"bpmspace_coms_v1","TABLE_NAME":"state_rules","COLUMN_NAME":"transition_script","ORDINAL_POSITION":"4","COLUMN_DEFAULT":null,"IS_NULLABLE":"YES","DATA_TYPE":"varchar","CHARACTER_MAXIMUM_LENGTH":"255","CHARACTER_OCTET_LENGTH":"255","NUMERIC_PRECISION":null,"NUMERIC_SCALE":null,"CHARACTER_SET_NAME":"latin1","COLLATION_NAME":"latin1_swedish_ci","COLUMN_TYPE":"varchar(255)","COLUMN_KEY":"","EXTRA":"","PRIVILEGES":"select,insert,update,references","COLUMN_COMMENT":""}],"table_icon":"fa fa-square"}];
// Mustertabelle
var tables = tables

// for debugging
console.log('All tables (', tables.length, '):', tables);

var app = angular.module("genApp", ["xeditable"])
app.run(function(editableOptions) {
  editableOptions.theme = 'bs2'; // bootstrap3 theme. Can be also 'bs2', 'default'
});

app.filter('ceil', function() {
    return function(input) {
        return Math.ceil(input);
    };
});

app.controller('genCtrl', function ($scope, $http) {
  $scope.historyLog = false  
  $scope.tables = []
  $scope.debug = window.location.search.match('debug=1')
  $scope.status = "";
  $scope.PageIndex = 0;
  $scope.PageLimit = 10; // default = 10
  $scope.sqlwhere = []

$scope.gotoPage = function(inc, table, index) {
	// TODO: PageIndex for every table
	first_page = 0;
	last_page = Math.ceil(table.count / $scope.PageLimit) - 1;
	new_page = $scope.PageIndex + inc;

	if (new_page < first_page) return;
	if (new_page > last_page) return;
	$scope.PageIndex = new_page;
	console.log("Goto Page clicked!", table.table_name, "Count:", table.count);
	$scope.refresh(table, index);
}

$scope.changeTab = function() {
	$scope.PageIndex = 0;
}

$scope.initTables = function() {
	$scope.status = "Initializing...";
	tables.forEach(
		function(tbl) {
			// no need for previous deselectet tables
			if(!tbl.is_in_menu){return}
			// Request from server
			// Read content
			$http({
				url: window.location.pathname, // use same page for reading out data
				method: 'post',
				data: {
				cmd: 'read',
				paramJS: {
					tablename: tbl.table_name,
					limitStart: $scope.PageIndex * $scope.PageLimit,
					limitSize: $scope.PageLimit,
					select: "*"
				}
			}
			}).success(function(response){
				// debugging
				console.log("Table '", tbl.table_name, "'", tbl);
				console.log(" - Data:", response);
				//define additional Rows
				var newRows = [[]]
				// Create new rows by columns
				Object.keys(tbl.columns).forEach(
					function(){newRows[newRows.length-1].push('')}
				);
				//define colum headers
				var keys = ['names']
				if(response[0] && typeof response[0] == 'object'){
					keys = Object.keys(response[0])
				}
				$scope.tables.push({
					table_name: tbl.table_name,
					table_alias: tbl.table_alias,
					table_icon: tbl.table_icon,
					columnsX: tbl.columns,
					columnames: keys,
					rows: response,
					count: 0,
					newRows : newRows
				})
        // Count entries
        $scope.countEntries(tbl.table_name);
				// open first table in navbar
				 $('#nav-'+$scope.tables[0].table_name).click();
				// TODO: Platzhalter für Scope Texfelder generierung  
			});
			// Save tablenames in scope
			$scope.tablenames = $scope.tables.map(function(tbl){return tbl.table_name})
		}
	)
	$scope.status = "Initializing... done";
}

$scope.countEntries = function(table_name) {
	console.log("counting entries from table", table_name);
	$http({
		url: window.location.pathname,
		method: 'post',
		data: {
			cmd: 'read',
			paramJS: {tablename: table_name, limitStart: 0, limitSize: 1, 
				select: "COUNT(*) AS cnt"
			}
	}
	}).success(function(response){
		// Find table in scope
		act_tbl = $scope.tables.find(
			function(t){return t.table_name == table_name});
		console.log("Count Response", response)
		act_tbl.count = response[0].cnt;
		console.log(act_tbl.count);
	});
}

// Refresh Function
$scope.refresh = function(scope_tbl, index) {
	$scope.status = "Refreshing...";
  	console.log($scope.sqlwhere[index]);
	// Request from server
	$http({
		url: window.location.pathname, // use same page for reading out data
		method: 'post',
		data: {
		cmd: 'read',
		paramJS: {
			tablename: scope_tbl.table_name,
			limitStart: $scope.PageIndex * $scope.PageLimit,
			limitSize: $scope.PageLimit,
			select: "*",
      		where: $scope.sqlwhere[index]
		}
	}
	}).success(function(response){
		// Find table
		$scope.tables.find(function(tbl){
			return tbl.table_name == scope_tbl.table_name}).rows = response;
   	 	// Count entries
    	$scope.countEntries(scope_tbl.table_name);
	})
	$scope.status = "Refreshing... done";
}

$scope.initTables();

/*
  $('#json-renderer').jsonViewer($scope.tables,{collapsed: true});
*/

/*
Allround send for changes to DB
*/
$scope.send = function (cud, param){
  console.log(param.x)
  console.log("Send-Function called, Params:", param);

  var body = {cmd : 'cud', paramJS : {}};
  // unused: columName = Object.keys(param.table.columnsX[0])[param.colum];

  // Function which identifies _all_ primary columns
  function getPrimaryColumns(col) {
    var resultset = [];
    for (var i = 0; i < col.length-1; i++) {
      if (col[i].COLUMN_KEY.indexOf("PRI") >= 0) {
        // Column is primary column
        resultset.push(col[i].COLUMN_NAME);
      }
    }
    console.log("---- Primary Columns:", resultset);
    return resultset;
  }

  function convertCols(inputObj) {
    var key, keys = Object.keys(inputObj);
    var n = keys.length;
    var newobj={}
    while (n--) {
      key = keys[n];
      newobj[key.toLowerCase()] = inputObj[key];
    }
    return newobj;
  }

  // Assemble data for Create, Update, Delete Functions
  if (cud == 'create') {
    body.paramJS = {
      row: param.row,
      table: param.table.table_name,
      primary_col: param.table.primary_col
    }
    post(cud);
  }
  else if (cud == 'update') {
    var row = $scope.changeHistory.reverse()
    row.find(function(entry){if (entry.origin && (entry.rowID == param.x[0]) ){return entry.postRow} })    
    // relevant data
    body.paramJS = {
      row: convertCols(param.row),
      primary_col: getPrimaryColumns(param.table.columnsX),
      table: param.table.table_name
    }
    post(cud)
  }
  else if (cud == 'delete') {
    body.paramJS = {
      id:param.colum,
      row:param.row,
      table:param.table.table_name,
      primary_col: getPrimaryColumns(param.table.columnsX)
    }
    post(cud)
  } else {
    console.log('unknown command (not CRUD)')
  }


  function post(){    
    $http({
      url:window.location.pathname,
      method:'post',
      data: {
        cmd: cud,
        paramJS: body.paramJS
      }
    }).success(function(response){
      // Debugging
      console.log("ResponseData: ", response);
      $scope.lastResponse = response;

      // GUI Notifications for user feedback
      //-------------------- Entry Deleted
      if (cud == 'delete' && response != 0) {
        // delete from page
      	act_tbl = $scope.tables.find(
        	function(tbl){return tbl.table_name == param.table.table_name});
        //act_tbl.rows.splice(/*row-index*/param.colum, 1);
        $scope.refresh(act_tbl);
      }
      //-------------------- Entry Updated
      else if (cud == 'update' && response != 0) {
        // worked

        // TODO: There could be a better solution, here the row is stored in the client in a history
        // but what if there are more changes and it gets corrupted? better the server sends back the
        // table and the primary column content -> so then the data intergrity is garanteed

        var tblID = param.x[1];
        var rowID = param.x[0];
        // remove class fresh and update button
        $("#row"+tblID+rowID).removeClass("fresh");
        $("#btnRow"+tblID+rowID ).hide();
      }
      //-------------------- Entry Created
      else if (cud == 'create' && response != 0) {
        console.log("-> Entry was created");
      	// Find current table
      	act_tbl = $scope.tables.find(function(t){return t.table_name == param.table.table_name});
        // Clear all entry fields
        for (var x=0;x<act_tbl.newRows.length;x++) {
          for (var y=0;y<act_tbl.newRows[x].length;y++) {
            act_tbl.newRows[x][y] = '';            
          }
        }
        // Set focus on first element after adding, usability issues
        console.log("-> Focus new Row...")
        $(".nRws").first().focus();
        // TODO: Only works at the first table
        
      	// Refresh current table
      	$scope.refresh(act_tbl);
      }
    })
  }

}

/*Protokoll where what changed*/
$scope.changeHistory = [], $scope.changeHistorycounter = 0
$scope.rememberOrigin = function (table, cols, row, cell, rowID, colID){
  $scope.changeHistorycounter ++
  console.log('\n-rO: table: '+table + ', cols:' + cols + ', row:' + row + ', cell:' + cell + ', rowID:' + rowID + ', colID:' + colID)
  console.log($scope.changeHistorycounter+' '+table+' Row: '+rowID+', Col: '+colID+' - '+cols[colID])
  $scope.changeHistory.push({
   table : table,
   row : row,
   cell : cell,
   rowID : rowID,
   colID : colID,
   colname : cols[colID],
   changeHistorycounter:$scope.changeHistorycounter
 })
}

/*If cell content changed, protokoll the change*/
$scope.checkCellChange = function (table, row, cell, tblID, rowID, colID){

  console.log('#cCC: table: ' + table + ', row: ' + row +
    ', cell: ' +  cell + ', tblID: ' + tblID +
    ', rowID: ' + rowID + ', colID: ' + colID);

  // var y = row[0], x = cell, //cleanflag
  origin = $scope.changeHistory[$scope.changeHistory.length-1]

  if (cell != origin.cell) {
    // log('Texfield changed from "'+origin.cell+'" to "'+cell+'"')
    var postRow = row, keys = Object.keys(row)
    postRow[keys[colID]] = cell
    
    $scope.changeHistory[$scope.changeHistory.length-1] = {
      origin : origin,
      change : cell,
      tableID : tblID,
      rowID : rowID,
      postRow:postRow
    }
    console.log('\n$scope.changeHistory['+($scope.changeHistory.length-1)+']:');
    console.log($scope.changeHistory[$scope.changeHistory.length-1])

    $( "#row"+tblID+rowID ).addClass( "fresh" );
    $( "#btnRow"+tblID+rowID ).show();
  }
  else {
     $scope.changeHistory.slice(0, -1)
  }
}

});</script>       
</body>
</html>
