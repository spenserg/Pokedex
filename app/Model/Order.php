<?php
/**
 * Order Model
 * 
 * app/Model/Order.php
 */

class Order extends AppModel {
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Order'];
  }
  
  function get_by_name($name){
    if (!($cc = $this->find('first',array('conditions'=>array('name'=>$name)))))
      return null;
    return $cc['Order'];
  }
    
  function needs_update($order, $filename){
    if (!file_exists($filename))
      return null;
    return (($order['updated'] == date("Y-m-d H:i:s",filemtime($filename)))?0:1);
  }
  
  function get_name($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Order']['name'];
  }
  
  function get_kingdom($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    if ($cc['Order']['class'] == null){
      return $this->Division->get_kingdom($cc['Order']['division']);
    }else{
      return $this->ClassDiv->get_kingdom($cc['Order']['class']);
    }
  }
  
  function get_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->ClassDiv->get_phylum($cc['Order']['class']);
  }
  
  function get_division($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Division->get_by_id($cc['Order']['division']);
  }
  
  function get_classdiv($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->ClassDiv->get_by_id($cc['Order']['class']);
  }
  
  function has_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return (($cc['Order']['class'] == null)?0:1);
  }
  
}
