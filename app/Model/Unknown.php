<?php
/**
 * Unknown Model
 * 
 * app/Model/Unknown.php
 */

class Unknown extends AppModel {
  public $useTable = 'unknown';
  
  function update_with_info($data){
    $order = $this->Family->get_order($data['family']);
    if (!($unk = $this->find('first',array('conditions'=>array('family'=>$data['family'],'filename'=>$data['filename']))))){
      debug('unknown added: '.$data['genus'].' sp.');
      $this->create();
      $this->set(array(
        'order'=>$order['id'],
        'family'=>$data['family'],
        'genus'=>$data['genus'],
        'date_found'=>$data['date_found'],
        'is_wild'=>$data['is_wild'],
        'notes'=>NULL,
        'location'=>$data['location'],
        'state'=>$data['state'],
        'iso'=>$data['iso'],
        'filename'=>$data['filename']
      ));
      $this->save();
    }else{
      debug('unknown updated: '.$data['genus'].' sp.');
      $this->read(null,$unk['Unknown']['id']);
      $this->set(array(
        'order'=>$order['id'],
        'family'=>$data['family'],
        'genus'=>$data['genus'],
        'date_found'=>$data['date_found'],
        'is_wild'=>$data['is_wild'],
        'notes'=>NULL,
        'location'=>$data['location'],
        'state'=>$data['state'],
        'iso'=>$data['iso'],
        'filename'=>$data['filename']
      ));
      $this->save();
    }
  }
  
}