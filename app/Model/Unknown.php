<?php
/**
 * Unknown Model
 * 
 * app/Model/Unknown.php
 */

class Unknown extends AppModel {
  public $useTable = 'unknown';
  
  function get_all_the_things(){
    $unks = array();
    foreach($this->find('all') as $val){
      $cur_unk = $this->get_by_id($val['Unknown']['id']);
      if ($cur_unk !== NULL){
        $unks[$cur_unk['kingdom_name']][$cur_unk['order_name']][$cur_unk['family_name']][$cur_unk['id']] = $cur_unk;
      }
    }
    $num_specs = array();
    foreach($unks as $key=>$val){ //kingdom => order
      foreach($val as $ley=>$wal){ //order => family
        ksort($wal);
        $unks[$key][$ley] = $wal;
      }
      ksort($val);
      $unks[$key] = $val;
    }
    ksort($unks);
    foreach($unks as $key=>$val){
      foreach($val as $ley=>$wal){
        $num_specs = 0;
        foreach($wal as $xal){
          $num_specs += count($xal);
        }
        $unks[$key][$ley]['num_specs'] = $num_specs;
      }
    }
    return $unks;
  }
  
  function get_by_id($id){
    if (!($unk = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return NULL;
    $unk['Unknown']['order_name'] = $this->Order->get_name($unk['Unknown']['order']);
    $unk['Unknown']['family_name'] = $this->Family->get_name($unk['Unknown']['family']);
    $unk['Unknown']['kingdom_name'] = ucfirst($this->Kingdom->get_name($this->Order->get_kingdom($unk['Unknown']['order'])));
    $unk['Unknown']['filename'] = $this->get_full_filename($id);
    return $unk['Unknown'];
  }
  
  function get_full_filename($id){
    if (!($unk = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return NULL;
    $filename = DS."img".DS."pokedex".DS;
    $kingdom = $this->Order->get_kingdom($unk['Unknown']['order']);
    $filename .= "zz ".$kingdom['name'].DS;
    if ($kingdom['id'] == 2){
      //plants
      $div = $this->Order->get_division($unk['Unknown']['order']);
      $filename .= $div['name'];
    }else{
      //animals and fungi
      if ($this->Order->has_phylum($unk['Unknown']['order'])){
        $phy = $this->Order->get_phylum($unk['Unknown']['order']);
        $cls = $this->Order->get_classdiv($unk['Unknown']['order']);
        $filename .= $phy['name'].DS.$cls['name'].(($this->ClassDiv->get_nickname($cls['id'])==NULL)?"":(" (".$this->ClassDiv->get_nickname($cls['id']).")"));
      }else{
        $div = $this->Order->get_division($unk['Unknown']['order']);
        $filename .= $div['name'];
      }
    }
    return $filename.
      DS.$this->Order->get_name($unk['Unknown']['order']).(($this->Order->get_nickname($unk['Unknown']['order'])==NULL)?"":(" (".$this->Order->get_nickname($unk['Unknown']['order']).")")).
      DS.$this->Family->get_name($unk['Unknown']['family']).(($this->Family->get_nickname($unk['Unknown']['family'])==NULL)?"":(" (".$this->Family->get_nickname($unk['Unknown']['family']).")")).
      DS.$unk['Unknown']['filename'];
  }
  
  function update_with_info($data){
    $order = $this->Family->get_order($data['family']);
    if (!($unk = $this->find('first',array('conditions'=>array('family'=>$data['family'],'filename'=>$data['filename']))))){
      debug('unknown added: '.$data['genus'].' sp. ('.$this->Family->get_name($data['family']).')');
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
      if ($unk['Unknown']['order'] != $order['id'] ||
          $unk['Unknown']['family'] != $data['family'] ||
          $unk['Unknown']['genus'] != $data['genus'] ||
          $unk['Unknown']['date_found'] != $data['date_found'] ||
          $unk['Unknown']['is_wild'] != $data['is_wild'] ||
          $unk['Unknown']['notes'] != $data['notes'] ||
          $unk['Unknown']['location'] != $data['location'] ||
          $unk['Unknown']['state'] != $data['state'] ||
          $unk['Unknown']['iso'] != $data['iso'] ||
          $unk['Unknown']['filename'] != $data['filename']){
        debug('unknown updated: '.$data['genus'].' sp. ('.$this->Family->get_name($data['family']).')');
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
  
}