<?php
/**
 * Country Model
 * 
 * app/Model/Country.php
 */

class Country extends AppModel {
  public $useTable = 'country';
  
  function update_postal_codes(){
    preg_match('/Street level Format([\s\S]*)<\/table>/',get_html('https://en.wikipedia.org/wiki/List_of_postal_codes'),$contents);
    preg_match_all('/<td><a href="[\s\S]*>([\s\S]*)<\/a>/U',$contents[1],$countries);
    
    for ($x = 0; $x < (count($countries[1])/2); $x++) {
      if (!($this->find('all',array('conditions'=>array('code'=>$countries[1][2*$x+1]))))){
        $this->create();
        $this->save(array(
          'code'=>$countries[1][2*$x+1],
          'name'=>$countries[1][2*$x]
        ));
      }
    }
  }
  
  function get_name($code){
    if(!($country = $this->find('first',array('conditions'=>array('code'=>$code)))))
      return null;
    return $country['Country']['name'];
  }
  
  function get_code($name){
    if(!($country = $this->find('first',array('conditions'=>array('name'=>$name)))))
      return null;
    return $country['Country']['code'];
  }
  
}