<?php
/**
 * Phylum Model
 * 
 * app/Model/Phylum.php
 */

class Phylum extends AppModel {
  public $useTable = 'phyla';
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    $cc['Phylum']['folder_name'] = ($cc['Phylum']['nickname'] == "" || $cc['Phylum']['nickname'] == null)?$cc['Phylum']['name']:($cc['Phylum']['name']." (".$cc['Phylum']['nickname'].")");
    return $cc['Phylum'];
  }
  
  function get_fam_ids($phy_id){
    $classdivs = $this->ClassDiv->find('all',array('conditions'=>array('phylum'=>$phy_id)));
    $class_ids = array();
    foreach($classdivs as $val){
      array_push($class_ids,$val['ClassDiv']['id']);
    }
    if (count($class_ids) == 1){
      $orders = $this->Order->find('all',array('conditions'=>array('class'=>$class_ids[0])));
    }else{
      $orders = $this->Order->find('all',array('conditions'=>array('class'=>$class_ids)));
    }
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
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Phylum']['name'];
  }
  
  function get_kingdom($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Kingdom->get_by_id($cc['Phylum']['kingdom']);
  }
  
}