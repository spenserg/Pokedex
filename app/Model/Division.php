<?php
/**
 * Division Model
 * 
 * app/Model/Division.php
 */

class Division extends AppModel {
  
  function get_by_id($id){
    if (!($div = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $div['Division'];
  }
  
  function get_fam_ids($div_id){
    $orders = $this->Order->find('all',array('conditions'=>array('division'=>$div_id)));
    $ord_ids = array();
    foreach($orders as $val){
      array_push($ord_ids,$val['Order']['id']);
    }
    if (count($ord_ids) == 1){
      $families = $this->Family->find('all',array('conditions'=>array('order'=>$ord_ids[0])));
    }else{
      $families = $this->Family->find('all',array('conditions'=>array('order'=>$ord_ids)));
    }
    $fam_ids = array();
    foreach($families as $val){
      array_push($fam_ids,$val['Family']['id']);
    }
    return $fam_ids;
  }
  
  function get_name($id){
    if (!($div = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $div['Division']['name'];
  }
  
  function get_kingdom($id){
    if (!($div = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Kingdom->get_by_id($div['Division']['kingdom']);
  }
    
}