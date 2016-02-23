<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
  
  public $recursive = -1; // default to NO JOINs
  
  public function user_id(){
    return AuthComponent::user('id');
  }
  
  public function as_array(){
    return isset($this->data[$this->alias])? $this->data[$this->alias] : $this->data;
  }
  
  public static function strip_model($rows){
    if(!is_array($rows))
      return array();
  
    if(count($rows) == 1 && !isset($rows[0]))
      return array_shift($rows);
  
    $out = array();
    foreach($rows as $r)
      $out[] = array_shift($r);
  
    return $out;
  }
  
  public static function set_id_as_key(array $rows){
    $rows = AppModel::strip_model($rows);
    $out = array();
    foreach($rows as &$r)
      $out[$r['id']] = $r;
  
    return $out;
  }
  
  public function log($msg, $log_name = null, $scope = null){
    if(is_null($log_name)){
      $callers = debug_backtrace();
      $log_name = $callers[1]['function'];
    }
  
    if(is_array($msg))
      $msg = print_r($msg, true);
  
    return CakeLog::write($log_name, $msg);
  }
  
  public function get_by_id($id, $recursive = -1){
    $orig_rec = $this->recursive;
    $this->recursive = $recursive;
  
    $this->read(null, $id);
    $arr = $recursive==-1? $this->as_array() : $this->data;
  
    $this->recursive = $orig_rec;
  
    return $arr;
  }
  
  public function beforeSave($options = array()){
  
    // Check for missing datafield fields since Cake just ignores them
    foreach(array_keys($this->data[$this->alias]) as $field)
      if(!$this->hasField($field)){
      $field_value = $this->data[$this->alias][$field];
      trigger_error("ERR DB field missing: `" . $this->alias . '`.' . $field, E_USER_ERROR);
    }
  }
  
  // Similar to Model->field() except this doesn't hit the database un-neccessarily
  public function getField($field_name, $check_db_on_fail = true){
    if(isset($this->data[$this->alias][$field_name]))
      return $this->data[$this->alias][$field_name];
    else if($check_db_on_fail && $this->id)
      return $this->field($field_name);
    else
      return null;
  }
  
  public function load_model($m){
    if(func_num_args() > 1){
      foreach(func_get_args() as $mod)
        $this->load_model($mod);
      return;
    }
  
    if(isset($this->$m))
      return;
  
    App::import('Model', $m);
    $this->$m = new $m();
  }
  
  public function save($data = null, $validate = true, $fieldList = array()){
    $result = parent::save($data, $validate, $fieldList);
  
    if($result === false){
      $validation_errors = $this->validationErrors;
      //trigger_error("Failed to save to DB", E_USER_ERROR);
    }
    else {
      // Check for warnings (like a truncated field, or missing field with no default)
      $mysql_warnings = array();
  
      foreach($this->query("show warnings") as $row)
        if(isset($row[0]['Message']))
          $mysql_warnings[] = $row[0]['Message'];
  
        $row = null;
        if(count($mysql_warnings))
          trigger_error("ERR DB warning on Save(): " . implode(" ||| ", $mysql_warnings), E_USER_ERROR);
    }
  
    return $result;
  }
  
  // Auto Loader for Models
  public function &__get($name) {
    if(!isset($this->modelsList))
      $this->modelsList = array_flip(App::objects('model'));
  
    if(isset($this->modelsList[$name])){
      try {
        $this->load_model($name);
        return $this->$name;
      } catch(Exception $e) {
      }
    }
  
    // Model not found. Let's see if our parent knows what we're trying to access
    $ret = parent::__get($name);
    return $ret; // must return a variable containing false/null, not false itself
  }
  
}
