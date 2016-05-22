<?php

App::uses('AppController','Controller');

class SearchController extends AppController {

  public $uses = false; // autoloader
  
  public function beforeFilter(){
    parent::beforeFilter();
  }
  
  public function index(){    
    $this->Specimen->update_db(0);
    //$this->Species->update_common_names();
    //debug($this->Specimen->find_missing());
    
    //debug($this->Specimen->find('all',array('conditions'=>array('state'=>null))));
    //debug($this->Specimen->get_info_regex(APP."webroot/img/pokedex/zz animals/Chordata/Actinopterygii (Bony Fishies)/Mugiliformes/aa Mugiliformes pic info copy.html"));
    //debug(file_get_contents(APP."webroot".DS."img".DS."pokedex".DS."testfile copy.html"));
         
    $this->set('caught',count($this->Species->find('all',array('conditions'=>array('is_wild'=>1)))));
    $this->set('seen',count($this->Species->find('all')));
    
    //debug($this->Family->get_spider_fams());    
  }
  
  public function unowns(){
    $this->set('unks',$this->Unknown->get_all_the_things());
  }
  
  public function article_work(){
    $table = $this->Species->update_common_names();
    $article = array();
    foreach($table['article'] as $val){
      $temp_spec = $val;
      $kingdom = $this->Family->get_kingdom($val['Species']['family']);
      $order = $this->Family->get_order($val['Species']['family']);
      $temp_spec['Species']['kingdom'] = $kingdom['name'];
      $temp_spec['Species']['order'] = $order['name'];
      $temp_spec['Species']['common_name'] = $val['Species']['common_name'];
      if ($kingdom['id'] == 1){
        array_unshift($article,$temp_spec);
      }else{
        array_push($article,$temp_spec);
      }
    }
    $table['article'] = $article;
    $this->set('table',$table);
  }
  
  public function stats(){
    $all_spec = $this->Species->find('all');
    $all_phyla = $this->Phylum->find('all');
    $all_divs = $this->Division->find('all');
    $month_list = array();
    $yr_list = array();
    foreach($all_spec as $val){
      $date = $val['Species']['date_found'];
      if (!array_key_exists(substr($date,0,4),$month_list)){
        $month_list[substr($date,0,4)] = array();
        $yr_list[substr($date,0,4)] = array('total'=>0);
        foreach(array("01","02","03","04","05","06","07","08","09","10","11","12") as $wal){
          foreach($all_phyla as $xal){
            $month_list[substr($date,0,4)][$wal][$xal['Phylum']['name']] = 0;
            $yr_list[substr($date,0,4)][$wal] = 0;
          }
          foreach($all_divs as $xal){
            $month_list[substr($date,0,4)][$wal][$xal['Division']['name']] = 0;
            $yr_list[substr($date,0,4)][$wal] = 0;
          }
        }
      }
      if (($cur_phy = $this->Family->get_phylum($val['Species']['family'])) == null)
        $cur_phy = $this->Family->get_division($val['Species']['family']);
      $month_list[substr($date,0,4)][substr($date,5,2)][$cur_phy['name']]++;
      $yr_list[substr($date,0,4)][substr($date,5,2)]++;
      $yr_list[substr($date,0,4)]['total']++;
    }
    ksort($yr_list);
    ksort($month_list);
    $overall_total = 0;
    foreach($yr_list as $key=>$val){
      $overall_total += $val['total'];
      $yr_list[$key]['overall'] = $overall_total;
    }
    $phydiv_totals = array();
    foreach($month_list as $yr_key=>$yr_arr){
      foreach($all_phyla as $xal){
        $yr_list[$yr_key][$xal['Phylum']['name']] = 0;
      }
      foreach($all_divs as $xal){
        $yr_list[$yr_key][$xal['Division']['name']] = 0;
      }
      foreach($yr_arr as $mon_key=>$phy_arr){
        $avg = 0;
        foreach($phy_arr as $phy_key=>$phy_tots){
          $yr_list[$yr_key][$phy_key] += $phy_tots;
          if (isset($phydiv_totals[$phy_key])){
            $phydiv_totals[$phy_key] += $phy_tots;
          }else{
            $phydiv_totals[$phy_key] = $phy_tots;
          }
        }
      }
    }
    
    //Find average for each month
    foreach($month_list as $monkey=>$mons){
      foreach($mons as $physkey=>$phys){
        $avg = 0;
        foreach($phys as $monval){
          $avg += $monval;
        }
        $month_list[$monkey][$physkey]['average'] = ceil($avg/(count($phys)));
      }
    }
    
    arsort($phydiv_totals);
    $this->set('phydiv_totals',$phydiv_totals);
    $this->set('list',$month_list);
    $this->set('total_list',$yr_list);
    $this->set('all_phyla',$all_phyla);
    $this->set('all_divs',$all_divs);
  }
  
  public function browse(){
    if ($this->request->is("post")){
      $dir = $_POST['old_dir'].DS.$_POST['new_dir'];
      if (is_file($dir)){
        $is_image = getimagesize($dir) ? 1 : 0;
      }else{
        $is_image = 0;
      }
      if ($is_image)
        $this->redirect("/search/view?dir=".$dir);
      $this->set('is_image',$is_image);
      $this->set('dir',$dir);
      $this->set('list',$list = scandir($dir));
      $has_folders = 0;
      foreach($list as $val){
        if (!(strpos($val, '.') !== FALSE))
          $has_folders = 1;
      }
      if (!$has_folders && is_numeric(end(explode(" ",substr(end($list),0,-4))))){
        $this->redirect("/search/view?dir=".$dir.DS.array_pop($list));
      }
    }else{
      $this->set('list',array(0=>'animals',1=>'plants',2=>'fungi'));
      $this->set('dir',APP."webroot".DS."img".DS."pokedex");
      $this->set('is_image',0);
    }
  }
  
  public function view(){
    $file_error = 0;
    if (!isset($_GET['dir']) || !file_exists($_GET['dir']) || !getimagesize($_GET['dir']) ? 1 : 0)
      $file_error = 1;
    $name_arr = explode(" ",array_shift(explode(".",array_pop(explode("/",$_GET['dir'])))));
    if (!($info = $this->Specimen->find('all',array('conditions'=>array('genus'=>$name_arr[0],'species'=>$name_arr[1]))))){
      //Unknown
      $file_arr = explode("/",$_GET['dir']);
      $temp_fam = $this->Family->get_by_name(($file_arr[count($file_arr)-2]=="aa unknown")?$file_arr[count($file_arr)-3]:$file_arr[count($file_arr)-2]);
      $counter = 0;
      foreach($this->Unknown->find('all',array('conditions'=>array('genus'=>$name_arr[0],'family'=>$temp_fam['id']))) as $val){
        $info[$counter]['Specimen'] = $val['Unknown'];
        $counter++;
      }
    }
    foreach($info as $key=>$val){
      $info[$key]['Specimen']['country'] = $this->Country->get_name($val['Specimen']['iso']);
      $info[$key]['Specimen']['filename_for_calc'] = array_pop(explode("/",$info[$key]['Specimen']['filename']));
    }
    if ($species = $this->Species->find('first',array('conditions'=>array('genus'=>$name_arr[0],'species'=>$name_arr[1])))){
      $this->set('common_name',$species['Species']['common_name']);
    }else{
      $this->set('common_name',null);
    }
    $this->set('spec_list',$info);
    $this->set('fam_dir',$this->Family->get_dir($info[0]['Specimen']['family'],false));
    $this->set('name',$name_arr[0]." ".(($name_arr[1]=="sp")?"sp.":$name_arr[1]));
    $this->set('file_error',$file_error);
  }
  
  function gallery(){
    function cmp($a,$b){
      //sort by date, then by name
      if ($a['date_found'] == $b['date_found']){
        if (strcmp($a['genus'],$b['genus']) == 0){
          return strcmp($a['species'],$b['species']);
        }else{
          return strcmp($a['genus'],$b['genus']);
        }
      }
      return ($a['date_found'] < $b['date_found'])?-1:1;
    }
    $spec = array();
    if ($this->request->is("post")){
      if ($_POST['year']==0)
        $_POST['year'] = date("Y");
      $start_date = date("Y-m-d",mktime(0,0,1,($_POST['month']==0)?1:$_POST['month'],1,$_POST['year']));
      $end_date = date("Y-m-t",mktime(0,0,1,($_POST['month']==0)?12:$_POST['month'],1,$_POST['year']));
      $spec = $this->Specimen->find_by_date($start_date,$end_date);
      uasort($spec,'cmp');
    }
    $this->set('spec_list',$spec);
    $this->set('sel_mo',isset($_POST['month'])?$_POST['month']:0);
    $this->set('sel_yr',isset($_POST['year'])?$_POST['year']:0);
  }
  
  function search(){
    $list = array();
    $cond_arr = array();
    if (isset($_GET['start_date']) && isset($_GET['end_date'])){
      $from = date("Y-m-d",strtotime($_GET['start_date']));
      $to = date("Y-m-d",strtotime($_GET['end_date']));
    }
    if ($this->request->is("post") || (count($_GET) > 0)){
      $cmp_phydiv_name = array(); //list of divisions and phyla
      if ($this->request->is("post")){
        $from = ($_POST['start_date'] != "")?date("Y-m-d",strtotime($_POST['start_date'])):"2003-08-29";
        $to = ($_POST['end_date'] != "")?date("Y-m-d",strtotime($_POST['end_date'])):date("Y-m-d");
        $state_init_arr = array();
        $all_states = array();
        
        //usa map
          foreach($_POST as $key=>$val){
            array_push($all_states,$key);
            if (strlen($key) == 2 && $val == 1){
              array_push($state_init_arr,$key);
              if ($key == "MD")
                array_push($state_init_arr,"DC");
            }
          }
          if (count($state_init_arr)){
            //states
            $state_arr = $state_init_arr;
            $country_arr = "US";
          }else{
            //world
            $state_arr = $all_states;
            $country_arr = array();
            array_push($state_arr,"XX");
            if (isset($_POST['AFR'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%AFR%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
            if (isset($_POST['ASI'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%ASI%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
            if (isset($_POST['EUR'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%EUR%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
            if (isset($_POST['OCE'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%OCE%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
            if (isset($_POST['NAM'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%NAM%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
            if (isset($_POST['SAM'])){
              foreach($this->Country->find('all',array('conditions'=>array('OR'=>array('continent LIKE'=>"%SAM%",'continent'=>null)))) as $yal){
                array_push($country_arr,$yal['Country']['code']);
              }
            }
          }
          
        $spec_list_cond = array(
          'date_found >=' => date("Y-m-d",strtotime($from)),
          'date_found <=' => date("Y-m-d",strtotime($to))
        );
        $spec_list_cond['state'] = $state_arr;
        if (count($country_arr))
          $spec_list_cond['iso'] = $country_arr;
        if ($this->request->is("post")){
          $name = $_POST['name'];
          if ($name != ""){
            if (strpos($name," ") === false){
              $spec_list_cond['OR'] = array(
                'genus LIKE' => "%$name%",
                'species LIKE' => "%$name%"
              );
            }else{
              $name_split = explode(" ",$name);
              $spec_list_cond['genus LIKE'] = "%".$name_split[0]."%";
              $spec_list_cond['species LIKE'] = "%".$name_split[1]."%";
            }
          }
          if($_POST['common_name'] != ""){
            $comm_name = $_POST['common_name'];
            $spec_cn_ids = array();
            foreach($this->Species->find('all',array('conditions'=>array("common_name LIKE"=>"%$comm_name%"))) as $eal){
              array_push($spec_cn_ids,$eal['Species']['id']);
            }
            $spec_list_cond['species_id'] = $spec_cn_ids;
          }
          if ($_POST['is_wild'] != 2)
            $spec_list_cond['is_wild'] = $_POST['is_wild'];
          $phy_arr = array();
          $div_arr = array();
          foreach($_POST as $key=>$val){
            if (strlen($key) > 4){
              if (substr($key,0,5) == "divis")
                array_push($div_arr,$val);
              if (substr($key,0,5) == "phylu")
                array_push($phy_arr,$val);
            }
          }
          $fam_arr = array();
          foreach($phy_arr as $val){
            foreach(($this->Phylum->get_fam_ids($val)) as $wal){
              array_push($fam_arr,$wal);
            }
          }
          foreach($div_arr as $val){
            foreach(($this->Division->get_fam_ids($val)) as $wal){
              array_push($fam_arr,$wal);
            }
          }
        }
        if (count($fam_arr) == 1)
          $spec_list_cond['family'] = $fam_arr[0];
        if (count($fam_arr) > 1)
          $spec_list_cond['family'] = $fam_arr;
        $spec_list_pre = $this->Specimen->find('all',array('conditions'=>$spec_list_cond));
        //end of if (post)
      }else{
        //if $_GET
        $spec_list_cond['date_found >='] = $_GET['start_date'];
        $spec_list_cond['date_found <='] = $_GET['end_date'];
        if (isset($_GET['phydiv'])){
          if ($phy = $this->Phylum->find('all',array('conditions'=>array('name'=>$_GET['phydiv'])))){
            $spec_list_cond['family'] = $this->Phylum->get_fam_ids($phy[0]['Phylum']['id']);
          }else{
            $div = $this->Division->find('all',array('conditions'=>array('name'=>$_GET['phydiv'])));
            $spec_list_cond['family'] = $this->Division->get_fam_ids($div[0]['Division']['id']);
          }
        }
        $spec_list_pre = $this->Species->find('all',array('conditions'=>$spec_list_cond));
      }
      $spec_list = array();
      foreach($spec_list_pre as $val){
        foreach($val as $wal){
          array_push($spec_list,$wal);
        }
      }
      
      unset($spec_list_cond['OR']['species LIKE']);
      unset($spec_list_cond['species_id']);
      unset($spec_list_cond['species LIKE']);
      
      $unknown_list = $this->Unknown->find('all',array('conditions'=>$spec_list_cond));
      $all_unknowns = $this->Unknown->find('all');
      
      if (!(count($unknown_list) == count($all_unknowns))){
        foreach($unknown_list as $val){
          $val['Unknown']['species'] = "sp.";
          array_push($spec_list,$val['Unknown']);
        }
      }
      
      foreach($spec_list as $val){
        if (!array_key_exists($val['genus']." ".$val['species'],$list)){
          if (!($this->request->is("post"))){
            //convert from species to relevant specimen
            $ral = $this->Specimen->find('first',array('conditions'=>array(
              'genus'=>$val['genus'],
              'species'=>$val['species'],
              'date_found >='=>$_GET['start_date'],
              'date_found <='=>$_GET['end_date']
            )));
            if (count($ral)){
              $val['date_found'] = $ral['Specimen']['date_found'];
              $val['filename'] = $ral['Specimen']['filename'];
              $val['is_wild'] = $ral['Specimen']['is_wild'];
              $val['state'] = $ral['Specimen']['state'];
              $val['iso'] = $ral['Specimen']['iso'];
            }
          }
          $fam = $this->Family->get_by_id($val['family']);
          if ($this->Family->has_phylum($val['family'])){
            $phydiv = $this->Family->get_phylum($val['family']);
          }else{
            $phydiv = $this->Family->get_division($val['family']);
          }
          if ($whatevs = $this->Species->find('first',array('conditions'=>array('genus'=>$val['genus'],'species'=>$val['species'])))){
            $val['common_name'] = $whatevs['Species']['common_name'];
          }else{
            $val['common_name'] = "None";
          }
          $file_dir = $this->Family->get_dir($val['family']);
          $list[$val['genus']." ".$val['species']] = array(
            'date'=>$val['date_found'],
            'family_name'=>$fam['name'],
            'family_id'=>$fam['id'],
            'family_dir'=>$this->Family->get_dir($fam['id'],false),
            'filename'=>$file_dir.$val['filename'],
            'phydiv'=>$phydiv['name'],
            'is_wild'=>(($val['is_wild'])?"Y":"N"),
            'state'=>$val['state'],
            'iso'=>$val['iso'],
            'common_name'=>$val['common_name']
          );
        }
      }
      ksort($list);
    }
    $this->set('all_divisions',$this->Division->find('all'));
    $this->set('all_phyla',$this->Phylum->find('all'));
    $this->set('results',$list);
    $this->set('post_info',$this->request->is("post")?$_POST:array());
    $this->set('get_info',isset($_GET)?$_GET:array());
  }

  public function songs(){
    
    debug($this->Song->find('all',array('conditions'=>array('title LIKE'=>"%(%"))));
      
    //debug($matches);

    /*
    $matches = array();
    preg_match_all("/([\S\s]*\S)[\s]*([0-9]*\:[0-9][0-9])\s[\s]*([\s\S]*)\n/U",
      file_get_contents(APP."webroot/files/all_songs.txt"),
      $matches);
    $songs = array();
    for ($x = 0; $x <= (count($matches[0])-1); $x++) {
      $this->Song->create();
      $this->Song->save(array(
        'title'=>$matches[1][$x],
        'artist'=>$matches[3][$x],
        'time'=>$matches[2][$x],
        'artist_is_complex'=>((strpos($matches[3][$x],",")===false && strpos($matches[3][$x],"feat.")===false)?0:1)
      ));
    }
     * 
     */

  }
  
  public function db_browse($division = null, $id = null){
    $cc = array();
    if ($division == 2){ //phylum
      $cc = $this->Phylum->find('all',array('conditions'=>array('kingdom'=>$id)));
      $new_div = 4;
      $type = "Phyla";
    }else if ($division == 3){ //division
      $cc = $this->Division->find('all',array('conditions'=>array('kingdom'=>$id)));
      $new_div = 5;
      $type = "Divisions";
    }else if ($division == 4){ //class with phylum
      $cc = $this->ClassDiv->find('all',array('conditions'=>array('phylum'=>$id)));
      $new_div = 6;
      $type = "Classes";
    }else if ($division == 5){ //class with division
      $cc = $this->ClassDiv->find('all',array('conditions'=>array('division'=>$id)));
      $new_div = ($id == 6)?6:7;
      $type = "Classes";
    }else if ($division == 6){ //order with class
      $cc = $this->Order->find('all',array('conditions'=>array('class'=>$id)));
      $new_div = 8;
      $type = "Orders";
    }else if ($division == 7){ //order with division
      $cc = $this->Order->find('all',array('conditions'=>array('division'=>$id)));
      $new_div = 8;
      $type = "Orders";
    }else if ($division == 8){ //family
      $cc = $this->Family->find('all',array('conditions'=>array('order'=>$id)));
      $new_div = 1;
      $type = "Families";
    }
    if (!count($cc)){
      $cc = $this->Kingdom->find('all');
      $new_div = 2;
      $type = "Kingdoms";
    }
    $list = array();
    foreach($cc as $val){
      foreach ($val as $key=>$wal){
        if ($wal['name']=='plants')
          $new_div = 3;
        array_push($list,array(
          'name'=>ucfirst($wal['name']),
          'division'=>$new_div,
          'id'=>$wal['id']
        ));
      }
    }
    $this->set('type',$type);
    $this->set('list',$list);
  }

}