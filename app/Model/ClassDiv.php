<?php
/**
 * ClassDiv Model
 * 
 * app/Model/ClassDiv.php
 */

class ClassDiv extends AppModel {
  public $useTable = 'classes';
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    $cc['ClassDiv']['folder_name'] = ($cc['ClassDiv']['nickname'] == "" || $cc['ClassDiv']['nickname'] == null)?$cc['ClassDiv']['name']:($cc['ClassDiv']['name']." (".$cc['ClassDiv']['nickname'].")");
    return $cc['ClassDiv'];
  }
  
  function get_nickname($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return ($cc['ClassDiv']['nickname']=="")?NULL:$cc['ClassDiv']['nickname'];
  }
  
  function get_name($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['ClassDiv']['name'];
  }
  
  function get_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Phylum->get_by_id($cc['ClassDiv']['phylum']);
  }
  
  function get_kingdom($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Phylum->get_kingdom($cc['ClassDiv']['phylum']);
  }

}
