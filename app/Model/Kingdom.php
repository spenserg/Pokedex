<?php
/**
 * Kingdom Model
 * 
 * app/Model/Kingdom.php
 */

class Kingdom extends AppModel {
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Kingdom'];
  }
  
  function get_name($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Kingdom']['name'];
  }
  
  function get_all($id){
    $cc = $this->find('all');
    $list = array();
    foreach($cc as $val){
      array_push($list,$val['Kingdom']);
    }
    return $list;
  }

}