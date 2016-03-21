<?php
/**
 * Specimen Model
 * 
 * app/Model/Specimen.php
 */

class Specimen extends AppModel {
  public $useTable = 'specimen';
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Specimen'];
  }
  
  function get_name($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Specimen']['name'];
  }
  
  function get_kingdom($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_kingdom($cc['Specimen']['family']);
  }
  
  function get_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_phylum($cc['Specimen']['family']);
  }
  
  function get_division($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_division($cc['Specimen']['family']);
  }
  
  function get_classdiv($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_classdiv($cc['Specimen']['family']);
  }
  
  function get_order($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_order($cc['Specimen']['family']);
  }
  
  function get_family($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->get_by_id($cc['Specimen']['family']);
  }
  
  function has_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Family->has_phylum($cc['Specimen']['family']);
  }
  
  function month_to_num($month_str){
    switch($month_str){
      case "Jan":
      case "January":
        return 1;
        break;
      case "Feb":
      case "February":
        return 2;
        break;
      case "Mar":
      case "March":
        return 3;
        break;
      case "Apr":
      case "April":
        return 4;
        break;
      case "May":
        return 5;
        break;
      case "Jun":
      case "June":
        return 6;
        break;
      case "July":
      case "Jul":
        return 7;
        break;
      case "Aug":
      case "August":
        return 8;
        break;
      case "Sep":
      case "Sept":
      case "September":
        return 9;
        break;
      case "Oct":
      case "October":
        return 10;
        break;
      case "Nov":
      case "November":
        return 11;
        break;
      case "Dec":
      case "December":
        return 12;
        break;
    }
  }
  
  function update_db(){
    $root_dir = APP."webroot".DS."img".DS."pokedex".DS;
    $new_deleted = array();
    $new_added = array();
    foreach(array("zz plants","zz animals","zz fungi") as $kingdom){
      $king_dir = scandir($root_dir.$kingdom.DS);
      foreach($king_dir as $phydiv){
        if (substr($phydiv,0,1) != "." && substr($phydiv,0,2) != "aa"){
          if ($kingdom == "zz plants" || $kingdom == "zz fungi"){
            if (!($cur_div = $this->Division->find('first',array('conditions'=>array('name'=>$phydiv))))){
              $this->Division->create();
              $this->Division->save(array(
                'name'=>$phydiv,
                'kingdom'=>(($kingdom == "zz plants")?2:3)
              ));
              $cur_div_id = $this->Division->id;
            }else{
              $cur_div_id = $cur_div['Division']['id'];
            }
            foreach(scandir($root_dir.$kingdom.DS.$phydiv.DS) as $order){
              if (substr($order,0,1) != "." && substr($order,0,2) != "aa"){
                $ord_sep = explode("(",$order);
                $ord_com_name = (count($ord_sep) > 1)?substr($ord_sep[1],0,-1):"";
                $order_name = trim($ord_sep[0]);
                $ord_fold_name = (count($ord_sep) > 1)?$order_name." (".$ord_com_name.")":$order_name;
                if (!($cur_ord = $this->Order->find('first',array('conditions'=>array('name'=>$order_name))))){
                  $this->Order->create();
                  $this->Order->save(array(
                    'name'=>$order_name,
                    'class'=>null,
                    'division'=>$cur_div_id,
                    'nickname'=>$ord_com_name
                  ));
                  $cur_ord_id = $this->Order->id;
                  $cur_ord = $this->Order->find('first',array('conditions'=>array('name'=>$order_name)));
                }else{
                  $cur_ord_id = $cur_ord['Order']['id'];
                }
                if (filemtime($root_dir.$kingdom.DS.$phydiv.DS.$ord_fold_name.DS."aa ".$order_name." pic info copy.html") > strtotime($cur_ord['Order']['updated'])){
                  debug("order updated: ".$cur_ord['Order']['name']);
                  foreach(scandir($root_dir.$kingdom.DS.$phydiv.DS.$ord_fold_name) as $family){
                    if (substr($family,0,1) != "."){
                      if (substr($family,0,2) != "aa"){
                        $fam_sep = explode("(",$family);
                        $fam_com_name = (count($fam_sep) > 1)?substr($fam_sep[1],0,-1):"";
                        $family_name = trim($fam_sep[0]);
                        $fam_fold_name = (count($fam_sep) > 1)?$family_name." (".$fam_com_name.")":$family_name;
                        if (!($cur_fam = $this->Family->find('first',array('conditions'=>array('name'=>$family_name))))){
                          $this->Family->create();
                          $this->Family->save(array(
                            'name'=>$family_name,
                            'order'=>$cur_ord_id,
                            'nickname'=>$fam_com_name
                          ));
                          $cur_fam_id = $this->Family->id;
                        }else{
                          $cur_fam_id = $cur_fam['Family']['id'];
                          $this->Family->read(null,$cur_fam_id);
                          $this->Family->set('nickname',$fam_com_name);
                          $this->Family->save();
                        }
                      }else{
                        if (is_file($root_dir.$kingdom.DS.$phydiv.DS.$order.DS.$family)){
                          $info = new SplfileInfo($root_dir.$kingdom.DS.$phydiv.DS.$order.DS.$family);
                          if($info->getExtension() == "html"){ //aa blah pic info copy
                            $order_obj = $this->Order->get_by_name($order);
                            if ($this->Order->needs_update($order_obj,$root_dir.$kingdom.DS.$phydiv.DS.$order.DS.$family)){
                              $this->update_db_with_info($root_dir.$kingdom.DS.$phydiv.DS.$order.DS.$family);
                              $this->Order->read(null,$order_obj['id']);
                              $this->Order->set('updated',date("Y-m-d H:i:s",filemtime($root_dir.$kingdom.DS.$phydiv.DS.$order.DS.$family)));
                              $this->Order->save();
                            }
                          }
                        }
                      }
                    }
                  }// end of scan order folder for families
                }// end of if order updated < html doc updated
              }
            }
          }else if ($kingdom == "zz animals"){
            $phy_sep = explode("(",$phydiv);
            $phy_com_name = (count($phy_sep) > 1)?substr($ord_sep[1],0,-1):"";
            $phylum_name = trim($phy_sep[0]);
            if (!($cur_phy = $this->Phylum->find('first',array('conditions'=>array('name'=>$phylum_name))))){
              $this->Phylum->create();
              $this->Phylum->save(array(
                'name'=>$phylum_name,
                'kingdom'=>1,
                'nickname'=>$phy_com_name
              ));
              $cur_phy_id = $this->Phylum->id;
            }else{
              $cur_phy_id = $cur_phy['Phylum']['id'];
            }
            foreach(scandir($root_dir.$kingdom.DS.$phydiv) as $classdiv){
              if (substr($classdiv,0,1) != "." && substr($classdiv,0,2) != "aa"){
                $cls_sep = explode("(",$classdiv);
                $cls_com_name = (count($cls_sep) > 1)?substr($cls_sep[1],0,-1):"";
                $classdiv_name = trim($cls_sep[0]);
                $cls_fold_name = (count($cls_sep) > 1)?$classdiv_name." (".$cls_com_name.")":$classdiv_name;
                if (!($cur_classdiv = $this->ClassDiv->find('first',array('conditions'=>array('name'=>$classdiv_name))))){
                  $this->ClassDiv->create();
                  $this->ClassDiv->save(array(
                    'name'=>$classdiv_name,
                    'phylum'=>$cur_phy_id,
                    'nickname'=>$cls_com_name
                  ));
                  $cur_classdiv_id = $this->ClassDiv->id;
                }else{
                  $cur_classdiv_id = $cur_classdiv['ClassDiv']['id'];
                }
                foreach(scandir($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name) as $order){
                  if (substr($order,0,1) != "." && substr($order,0,2) != "aa"){
                    $ord_sep = explode("(",$order);
                    $ord_com_name = (count($ord_sep) > 1)?substr($ord_sep[1],0,-1):"";
                    $order_name = trim($ord_sep[0]);
                    $ord_fold_name = (count($ord_sep) > 1)?$order_name." (".$ord_com_name.")":$order_name;
                    if (!($cur_ord = $this->Order->find('first',array('conditions'=>array('name'=>$order_name))))){
                      $this->Order->create();
                      $this->Order->save(array(
                        'name'=>$order_name,
                        'class'=>$cur_classdiv_id,
                        'division'=>null,
                        'nickname'=>$ord_com_name
                      ));
                      $cur_ord_id = $this->Order->id;
                      $cur_ord = $this->Order->find('first',array('conditions'=>array('name'=>$order_name)));
                    }else{
                      $cur_ord_id = $cur_ord['Order']['id'];
                    }
                    
                    if (filemtime($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS."aa ".$order_name." pic info copy.html") > strtotime($cur_ord['Order']['updated'])){
                    
                    foreach(scandir($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name) as $family){
                      if (substr($family,0,1) != "."){
                        if (substr($family,0,2) != "aa"){
                          $fam_sep = explode("(",$family);
                          $fam_com_name = (count($fam_sep) > 1)?substr($fam_sep[1],0,-1):"";
                          $family_name = trim($fam_sep[0]);
                          $fam_fold_name = (count($fam_sep) > 1)?$family_name." (".$fam_com_name.")":$family_name;
                          if (!($cur_fam = $this->Family->find('first',array('conditions'=>array('name'=>$family_name))))){
                            $this->Family->create();
                            $this->Family->save(array(
                              'name'=>$family_name,
                              'order'=>$cur_ord_id,
                              'nickname'=>$fam_com_name
                            ));
                            $cur_fam_id = $this->Family->id;
                          }else{
                            $cur_fam_id = $cur_fam['Family']['id'];
                            $this->Family->read(null,$cur_fam_id);
                            $this->Family->set('nickname',$fam_com_name);
                            $this->Family->save();
                          }
                        }else{
                          if (is_file($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS.$family)){
                            $info = new SplfileInfo($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS.$family);
                            if ($info->getExtension() == "html"){ //aa blah pic info copy
                              $order_obj = $this->Order->get_by_name($order_name);
                              if ($this->Order->needs_update($order_obj,$root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS.$family)){
                                $this->update_db_with_info($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS.$family);
                                $this->Order->read(null,$order_obj['id']);
                                $this->Order->set('updated',date("Y-m-d H:i:s",filemtime($root_dir.$kingdom.DS.$phydiv.DS.$cls_fold_name.DS.$ord_fold_name.DS.$family)));
                                $this->Order->save();
                              }
                            }
                          }
                        }
                      }
                    }

                    }

                  }
                }
              }
            }
          }
        }
      }
    }
    $all_specs = $this->find('all');
    foreach($all_specs as $spec){
      $fam_dir = $this->Family->get_dir($spec['Specimen']['family']);
      if (!file_exists($fam_dir.$spec['Specimen']['filename'])){
        $species_id = $spec['Specimen']['species_id'];
        debug("File Deleted: ".$fam_dir.$spec['Specimen']['filename']);
        $species_name = $spec['Specimen']['genus']." ".$spec['Specimen']['species'];
        $this->delete($spec['Specimen']['id']);
        if (!$this->find('all',array('conditions'=>array('species_id'=>$species_id)))){
          $this->Species->delete($species_id);
          debug("Species Deleted: ". $species_name);
        }
      }
    }
  }

  function update_db_with_info($filename){
    $info = $this->get_info($filename);
    foreach($info as $wal){
      $family = $this->Family->find('first',array('conditions'=>array('name'=>$wal['name'])));
      foreach($wal as $key=>$val){
        if (is_int($key)){ //not family name
          if ($val['species'] != "sp" && $val['species'] != "sp." && $val['species'] != "species"){
            if (!($specimen = $this->Specimen->find('first',array('conditions'=>array('filename'=>$val['filename']))))){
              $this->Specimen->create();
              $this->Specimen->save(array(
                'genus'=>$val['genus'],
                'species'=>$val['species'],
                'family'=>$val['family'],
                'date_found'=>$val['date_found'],
                'location'=>$val['location'],
                'state'=>($val['state']==null)?("XX"):$val['state'],
                'iso'=>$val['iso'],
                'is_wild'=>$val['is_wild'],
                'filename'=>$val['filename'],
                'notes'=>$val['notes']
              ));
              $new_spec_id = $this->Specimen->id;
              debug("Specimen added: ".$val['filename']);
              if ($spec = $this->Species->find('first',array('conditions'=>array('genus'=>$val['genus'],'species'=>$val['species'])))){
                $this->Specimen->read(null,$new_spec_id);
                $this->Specimen->set('species_id',$spec['Species']['id']);
                $this->Specimen->save();
                if ($val['is_wild']){
                  $this->Species->read(null,$spec['Species']['id']);
                  $this->Species->set('is_wild',1);
                  $this->Species->save();
                }
                if (strtotime($val['date_found']) < strtotime($spec['Species']['date_found'])){
                  $this->Species->read(null,$spec['Species']['id']);
                  $this->Species->set('date_found',$val['date_found']);
                  $this->Species->save();
                }
              }else{
                $this->Species->create();
                $this->Species->set(array(
                  'genus'=>$val['genus'],
                  'species'=>$val['species'],
                  'family'=>$val['family'],
                  'date_found'=>$val['date_found'],
                  'is_wild'=>$val['is_wild'],
                  'notes'=>$val['notes']
                ));
                $this->Species->save();
                $new_species_id = $this->Species->id;
                $this->Specimen->read(null,$new_spec_id);
                $this->Specimen->set('species_id',$new_species_id);
                $this->Specimen->save();
                debug("New Species: ".$val['genus']." ".$val['species']);
              }
            }else{ //specimen already in db
              $this->Specimen->read(null,$specimen['Specimen']['id']);
              $this->Specimen->set(array(
                'date_found'=>$val['date_found'],
                'location'=>$val['location'],
                'state'=>$val['state'],
                'is_wild'=>$val['is_wild'],
                'notes'=>$val['notes']
              ));
              $this->Specimen->save();
              $lowest_date = date("Y-m-d");
              $spec_is_wild = 0;
              foreach($this->Specimen->find('all',array('conditions'=>array('species_id'=>$specimen['Specimen']['species_id']))) as $ral){
                $t = explode("-",$ral['Specimen']['date_found']);
                $u = explode("-",$lowest_date);
                if (mktime(0,0,1,$t[1],$t[2],$t[0]) < mktime(0,0,1,$u[1],$u[2],$u[0])){
                  $lowest_date = $ral['Specimen']['date_found'];
                }
                if ($ral['Specimen']['is_wild'])
                  $spec_is_wild = 1;
              }
              $this->Species->read(null,$specimen['Specimen']['species_id']);
              $this->Species->set(array(
                'date_found'=>$lowest_date,
                'is_wild'=>$spec_is_wild
              ));
              $this->Species->save();
            }
          }else{ //Unknown species
            $this->Unknown->update_with_info($val);
          }
        }
      } // end foreach
    }
  }
  
  function pic_check(){
    $bad_img_arr = array();
    $root_dir = APP."webroot".DS."img".DS."pokedex".DS;
    $alt_root = "img".DS."pokedex".DS;
    $kingdoms = $this->Kingdom->find('all');
    foreach($kingdoms as $kingdom){
      if ($kingdom['Kingdom']['id'] == 2 || $kingdom['Kingdom']['id'] == 3){ //plants and fungi
        $temp_div = $this->Division->find('all',array('conditions'=>array('kingdom'=>$kingdom['Kingdom']['id'])));
        foreach($temp_div as $division){
          $temp_ord = $this->Order->find('all',array('conditions'=>array('division'=>$division['Division']['id'])));
          foreach($temp_ord as $order){
            if (!(file_exists($root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                  $division['Division']['name'].DS.$order['Order']['name'].DS.
                  "aa ".$order['Order']['name']." pic info copy.html"))){
              //can't find info file
              array_push($bad_img_arr,array(
                'type'=>'check pic info file',
                'root'=>$root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                  $division['Division']['name'].DS.$order['Order']['name'],
                'filename'=>$order['Order']['name']." pic info copy.html"
              ));
            }else{
                  
              $temp_info_file = $this->get_info($root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                  $division['Division']['name'].DS.$order['Order']['name'].DS.
                  "aa ".$order['Order']['name']." pic info copy.html");
              foreach($temp_info_file as $files){
                $fam_name = $files['name'];
                foreach ($files as $key=>$ind_file){
                  if (is_int($key)){
                    if (!(file_exists($alt_root."zz ".$kingdom['Kingdom']['name'].DS.
                    $division['Division']['name'].DS.$order['Order']['name'].DS.
                    $fam_name.DS.$ind_file['filename']))){
                      //bad file or file doesn't exist
                      array_push($bad_img_arr,array(
                        'root'=>$alt_root."zz ".$kingdom['Kingdom']['name'].DS.
                              $division['Division']['name'].DS.$order['Order']['name'].DS.
                              $fam_name.DS,
                        'filename'=>$ind_file['filename']
                      ));
                    }
                  }
                }
              }
            }
          }
        }
      }else if ($kingdom['Kingdom']['id'] == 1){ //animals
        $temp_phy = $this->Phylum->find('all',array('conditions'=>array('kingdom'=>$kingdom['Kingdom']['id'])));
        foreach($temp_phy as $phylum){
          $temp_cls = $this->ClassDiv->find('all',array('conditions'=>array('phylum'=>$phylum['Phylum']['id'])));
          foreach($temp_cls as $class){
            $cls_fold_name = ($class['ClassDiv']['nickname'] == "")?$class['ClassDiv']['name']:($class['ClassDiv']['name']." (".$class['ClassDiv']['nickname'].")");
            $temp_ord = $this->Order->find('all',array('conditions'=>array('class'=>$class['ClassDiv']['id'])));
            foreach($temp_ord as $order){
              $ord_fold_name = ($order['Order']['nickname'] == "")?$order['Order']['name']:($order['Order']['name']." (".$order['Order']['nickname'].")");
              if (!(file_exists($root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                  $phylum['Phylum']['name'].DS.$cls_fold_name.DS.
                  $ord_fold_name.DS.
                  "aa ".$order['Order']['name']." pic info copy.html"))){
              //can't find info file
                array_push($bad_img_arr,array(
                  'type'=>'check pic info file',
                  'root'=>$root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                      $phylum['Phylum']['name'].DS.$cls_fold_name.DS.
                      $ord_fold_name,
                  'filename'=>$order['Order']['name']." pic info copy.html"
                ));
              }else{ 
                $temp_info_file = $this->get_info($root_dir."zz ".$kingdom['Kingdom']['name'].DS.
                    $phylum['Phylum']['name'].DS.$cls_fold_name.DS.
                    $ord_fold_name.DS.
                    "aa ".$order['Order']['name']." pic info copy.html");
                foreach($temp_info_file as $files){
                  $fam_name = $files['name'];
                  $cur_fam = $this->Family->find('first',array('conditions'=>array('name'=>$fam_name)));
                  $fam_fold_name = ($cur_fam['Family']['nickname'] == "")?$cur_fam['Family']['name']:($cur_fam['Family']['name']." (".$cur_fam['Family']['nickname'].")");
                  foreach ($files as $key=>$ind_file){
                    if (is_int($key)){
                      if (!(file_exists($alt_root."zz ".$kingdom['Kingdom']['name'].DS.
                          $phylum['Phylum']['name'].DS.$cls_fold_name.DS.
                          $ord_fold_name.DS.$fam_fold_name.DS.$ind_file['filename']))){
                        //bad file or file doesn't exist
                        array_push($bad_img_arr,array(
                          'root'=>$alt_root."zz ".$kingdom['Kingdom']['name'].DS.
                              $phylum['Phylum']['name'].DS.$cls_fold_name.DS.
                              $ord_fold_name.DS.$fam_fold_name.DS,
                          'filename'=>$ind_file['filename']
                        ));
                      }
                    }
                  }
                }
              }
            }
          }
        }       
      }
    }
    return count($bad_img_arr)?$bad_img_arr:"all files ok";
  }

  function find_by_date($start_date,$end_date){
    $spec = array();
    foreach($this->Specimen->find('all',array('conditions'=>array('date_found >='=>$start_date,'date_found <='=>$end_date))) as $val){
      $val['Specimen']['fam_dir'] = $this->Family->get_dir($val['Specimen']['family'],false);
      $spec[$val['Specimen']['id']] = $val['Specimen'];
    }
    return $spec;
  }

  function get_info_regex($filename){
    $ord_arr = array();
    $fam_arr = array();
    $cur_genus = "";
    $cur_species = "";
    
    preg_match("/<body>([\s\S]*)<\/body>/",file_get_contents($filename),$matches);
    preg_match_all('/<p class="p([\s\S]*)<\/p>/U',$matches[1],$match_fams);
    foreach($match_fams[1] as $val){
      if (substr($val,0,1) == "1"){
        if (count($fam_arr)){
          $ord_arr[$family['id']] = $fam_arr;
        }
        $fam_arr = array();
        preg_match('/<span class="s1">([\s\S]*)<\/span>/',$val,$fam_name);
        $family = $this->Family->get_by_name($fam_arr['name'] = $fam_name[1]);
      }
      if (substr($val,0,1) == "2"){
        preg_match('/2">([\s\S]*) - \(([\s\S]*)\) \[([WS])\] ([\s\S]*)/',$val,$info);
        if (count($info)){
          if (preg_match('/<span class="Apple-tab-span"/',$val)){
            //number first
            preg_match('/<\/span>([\s\S]*)/',$info[1],$x);
            $info[1] = $x[1];
            foreach(explode(",",$info[1]) as $wal){
              $loc_info = explode(",",$info[4]);
              $obj = array_pop($loc_info);
              if(preg_match('/\{/',$obj)){
                //not USA
                $country_code = substr($obj,-3,2);
                $state = "XX";
                $location = substr($info[4],0,-5);
              }else{
                //USA
                $country_code = "US";
                $state = substr($obj,-2,2);
                $location = substr($info[4],0,-4);
              }
              preg_match('/([\s\S]*) ([0-9]*), ([0-9][0-9][0-9][0-9])/',$info[2],$date_arr);
              array_push($fam_arr,array(
                'genus'=>$cur_genus,
                'species'=>$cur_species,
                'family'=>$family['id'],
                'date_found'=>$date_arr[3]."-".sprintf("%02d",$this->month_to_num($date_arr[1]))."-".((strlen($date_arr[2])==1)?"0":"").$date_arr[2],
                'location'=>$location,
                'state'=>$state,
                'iso'=>$country_code,
                'is_wild'=>($info[3] == "W")?1:0,
                'filename'=>(($cur_species=="sp")?"aa unknown/":"").$cur_genus.' '.$cur_species.'/'.$cur_genus.' '.$cur_species.' '.$wal.'.jpg',
                'notes'=>null
            ));
            }
          }else{
            //species name first
            $loc_info = explode(",",$info[4]);
            $obj = array_pop($loc_info);
            if(preg_match('/\{/',$obj)){
              //not USA
              $country_code = substr($obj,-3,2);
              $state = "XX";
              $location = substr($info[4],0,-5);
            }else{
              //USA
              $country_code = "US";
              $state = substr($obj,-2,2);
              $location = substr($info[4],0,-4);
            }
            preg_match('/([\s\S]*) ([0-9]*), ([0-9][0-9][0-9][0-9])/',$info[2],$date_arr);
            $species_name = explode(" ",$info[1]);
            array_push($fam_arr,array(
              'genus'=>$species_name[0],
              'species'=>$species_name[1],
              'family'=>$family['id'],
              'date_found'=>$date_arr[3]."-".sprintf("%02d",$this->month_to_num($date_arr[1]))."-".((strlen($date_arr[2])==1)?"0":"").$date_arr[2],
              'location'=>$location,
              'state'=>$state,
              'iso'=>$country_code,
              'is_wild'=>($info[3] == "W")?1:0,
              'filename'=>(($species_name[1]=="sp")?"aa unknown/":"")."sp.jpg",
              'notes'=>null
            ));
          }
        }else{
          //species name alone on line
          preg_match('/2">([\s\S]*) ([\s\S]*)/',$val,$species_name);
          $cur_genus = $species_name[1];
          $cur_species = $species_name[2];
        }
      }
    }
    $ord_arr[$family['id']] = $fam_arr;
    return $ord_arr;
  }
  
  function get_info($filename){
    return $this->get_info_regex($filename);
  }
}