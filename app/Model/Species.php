<?php
/**
 * Species Model
 * 
 * app/Model/Species.php
 */

class Species extends AppModel {
  public $useTable = 'species';
  
  function update_common_names(){
    $need_article = array();
    $need_infobox = array();
    $need_picture = array();
    $check_common = array();

    foreach($this->Species->find('all',array('conditions'=>array('common_name !='=>NULL))) as $val){
      $talk_html = get_html('https://en.wikipedia.org/wiki/Talk:'.$val['Species']['genus']."_".$val['Species']['species']);
      preg_match("%Rated ([\s\S]*)-class%U",$talk_html,$rating);
      if (count($rating) && ($rating[1] == "Redirect" || $rating[1] == "NA")){
        $article_html = get_html('https://en.wikipedia.org/wiki/'.$val['Species']['genus']."_".$val['Species']['species']);
        preg_match("%<title>([\s\S]*) - Wikipedia, the free encyclopedia</title>%U",$article_html,$rating);
        $talk_html = get_html('https://en.wikipedia.org/wiki/Talk:'.$rating[1]);
        preg_match("%Rated ([\s\S]*)-class%U",$talk_html,$rating);
      }
      $this->read(null,$val['Species']['id']);
      if (count($rating)){
        $this->set('article_rating',$rating[1]);
      }else{
        $this->set('article_rating',null);
      }
      $this->save();
    }
    
    $specs = array();
    foreach($this->Species->find('all',array('conditions'=>array('common_name !='=> null))) as $val){
      if (strcmp($val['Species']['common_name'],$val['Species']['genus'].' '.$val['Species']['species']) == 0)
        array_push($specs,$val['Species']['common_name']);
    }
    
    $null_specs = $this->Species->find('all',array('conditions'=>array('common_name'=>$specs)));
    foreach($null_specs as $val){
      $html = get_html('https://en.wikipedia.org/wiki/'.$val['Species']['genus']."_".$val['Species']['species']);
      $common_name = get_first_match($html,'%class="infobox biota"[^\>]*>[\s]<tr>[\s]<th[^\>]*>([^\/]*)%');
      if (substr($common_name,0,3) == "<i>")
        $common_name = substr($common_name,3);
      if (rtrim($common_name,"<") != ''){
        $common_name = rtrim($common_name,"<");
        if (strpos($common_name,'<sup') !== false)
          $common_name = get_first_match($common_name,'%([\s\S]*)<sup[\s\S]*%');
        if (strpos($common_name,'<b>') !== false)
          $common_name = get_first_match($common_name,'%<b>([\s\S]*)%');
        if (strpos($common_name,'<br') !== false)
          $common_name = get_first_match($common_name,'%([\s\S]*)<br%');
        if (strpos($common_name,'(') !== false)
          $common_name = get_first_match($common_name,'%([\s\S]*)\(%');
        if (strpos($common_name,',') !== false)
          $common_name = get_first_match($common_name,'%([\s\S]*),%');
        $common_name = ucfirst(strtolower(trim($common_name)));
        //if (strcmp($common_name,ucfirst(strtolower($val['Species']['genus']." ".$val['Species']['species']))) !== 0){
          $this->read(null,$val['Species']['id']);
          $this->set('common_name',$common_name);
          $this->save();
        //}
      }else{
        $this->read(null,$val['Species']['id']);
        $this->set(array(
          'common_name'=>NULL,
          'has_infobox'=>0,
          'has_picture'=>0
        ));
        $this->save();
        array_push($need_article,$val);
      }
    }

    $other_specs = $this->Species->find('all',array('conditions'=>array('AND'=>array(
      'common_name !='=>NULL,'has_infobox'=>0))));
    foreach($other_specs as $val){
      $html = get_html('https://en.wikipedia.org/wiki/'.$val['Species']['genus']."_".$val['Species']['species']);
      $this->read(null,$val['Species']['id']);
      if (strpos($html,'class="infobox biota"') === false){
        array_push($need_infobox,$val);
        $this->set('has_infobox',0);
      }else{
        $this->set('has_infobox',1);
        if (get_first_match($html,'%class="infobox biota"[^<]*<tr>[\s\S]*<td colspan="2" style="text-align: center"><a href="\/wiki\/File:([^"]*)%') === false){
          array_push($need_picture,$val);
          $this->set('has_picture',0);
        }else{
          $this->set('has_picture',1);
        }
      }
      $this->save();
    }
    return array('article'=>$need_article,'infobox'=>$need_infobox,'picture'=>$need_picture);
  }
}