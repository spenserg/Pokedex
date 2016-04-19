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
  
  function wikiparse($stuff){
    $stuff = str_replace("Ã¢â‚¬â„¢S","'s",$stuff);
    $stuff = str_replace("Ã¢â‚¬â„¢A","'a",$stuff);
    $stuff = str_replace("ÃƒÂ¡","á",$stuff);
    $stuff = str_replace("ÃƒÂ¤","ä",$stuff);
    $stuff = str_replace("Ãƒâ€ž","ä",$stuff);
    $stuff = str_replace("ÃƒÂ§","ç",$stuff);
    $stuff = str_replace("ÃƒÂ©","é",$stuff);
    $stuff = str_replace("Ãƒâ€°","É",$stuff);
    $stuff = str_replace("ÃƒÂ¨","è",$stuff);
    $stuff = str_replace("ÃƒÂ¬","ě",$stuff);
    $stuff = str_replace("ÃƒÂª","ê",$stuff);
    $stuff = str_replace("ÃƒÂ","í",$stuff);
    $stuff = str_replace("Ã„Â©","ĩ",$stuff);
    $stuff = str_replace("ÃƒÂ¯","ï",$stuff);
    $stuff = str_replace("ÃƒÂ³","ó",$stuff);
    $stuff = str_replace("ÃƒÂ¸","ø",$stuff);
    $stuff = str_replace("ÃƒÂ¶","ö",$stuff);
    $stuff = str_replace("Ãƒâ€“","ö",$stuff);
    $stuff = str_replace("Ã…Â¡","š",$stuff);
    $stuff = str_replace("ÃƒÂ¼","ü",$stuff);
    $stuff = str_replace("LÃƒÂº","ú",$stuff);
    $stuff = str_replace("Ã…Â©","ũ",$stuff);
    $stuff = str_replace("ÃƒÂ±","ñ",$stuff);
    $stuff = str_replace("ÃƒÂŸ","ß",$stuff);
    $stuff = str_replace("Ã¢â‚¬â„¢","'",$stuff);
    $stuff = str_replace("Ã¢â‚¬Ëœ","'",$stuff);
    $stuff = str_replace("í¶","ö",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    
    $stuff = str_replace("[[10]]","]] [10]",$stuff);
    $stuff = str_replace(") [10]","]]",$stuff);
    $stuff = str_replace(" [10]","]]",$stuff);
    $stuff = str_replace("[[5]]","]] [5]",$stuff);
    $stuff = str_replace(") [5]","]]",$stuff);
    $stuff = str_replace(" [5]","]]",$stuff);
    $stuff = str_replace(" [ accept ","]] accept [[",$stuff);
    $stuff = str_replace("[ accept ","]] accept [[",$stuff);
    $stuff = str_replace(" [accept ","]] accept [[",$stuff);
    $stuff = str_replace("[accept ","]] accept [[",$stuff);
    $stuff = str_replace("; accept","]] accept [[",$stuff);
    $stuff = str_replace(" [or ","]] or [[",$stuff);
    $stuff = str_replace(" [or","]] or [[",$stuff);
    $stuff = str_replace(" before it's mentioned","]] before it's mentioned",$stuff);
    $stuff = str_replace("before it's mentioned","]] before it's mentioned",$stuff);
    $stuff = str_replace("answer: ","answer: [[",$stuff);
    $stuff = str_replace(" [prompt","]] prompt",$stuff);
    $stuff = str_replace(" ; prompt","]] prompt",$stuff);
    $stuff = str_replace("; prompt","]] prompt",$stuff);
    $stuff = str_replace(" (do not prompt on ","]] do not prompt on [[",$stuff);
    $stuff = str_replace(" before mention]]","]]",$stuff);
    $stuff = str_replace("' prompt on '","']] prompt on [['",$stuff);
    $stuff = str_replace(" (prompt on ","]] prompt on [[",$stuff);
    $stuff = str_replace("(prompt on ","]] prompt on [[",$stuff);
    $stuff = str_replace("ANSWER: ","ANSWER: [[",$stuff);
    $stuff = str_replace("ANSWER:","ANSWER: [[",$stuff);
    $stuff = str_replace(" ]]","]]",$stuff);
    $stuff = str_replace("[[ ","[[",$stuff);
    $stuff = str_replace('[["','"[[',$stuff);
    $stuff = str_replace('"]]',']]"',$stuff);
    $stuff = str_replace(" Of "," of ",$stuff);
    $stuff = str_replace(" The "," the ",$stuff);
    $stuff = str_replace(" And "," and ",$stuff);
    $stuff = str_replace(" A "," a ",$stuff);
    $stuff = str_replace("Viii","VIII",$stuff);
    $stuff = str_replace("Vi]]","VI]]",$stuff);
    $stuff = str_replace("iV]]","IV]]",$stuff);
    $stuff = str_replace("Iii","III",$stuff);
    $stuff = str_replace("iii","III",$stuff);
    $stuff = str_replace("Ii","II",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    $stuff = str_replace("","",$stuff);
    for($i=0; $i<5; $i++){
      $stuff = str_replace("[[[[","[[",$stuff);
      $stuff = str_replace("]]]]","]]",$stuff);
      $stuff = str_replace("[[[","[[",$stuff);
      $stuff = str_replace("]]]","]]",$stuff);
    }
    
    preg_match_all("%\[\[[tT]he [\s\S]*\]\]%U",$stuff,$new);
    foreach($new[0] as $val){
      $stuff = str_replace($val,$val." | ".str_ireplace("[[the ","[[",$val),$stuff);
    }
    preg_match_all("%\[\[[aA]n [\s\S]*\]\]%U",$stuff,$new);
    foreach($new[0] as $val){
      $stuff = str_replace($val,$val." | ".str_ireplace("[[an ","[[",$val),$stuff);
    }
    preg_match_all("%\[\[[aA] [\s\S]*\]\]%U",$stuff,$new);
    foreach($new[0] as $val){
      $stuff = str_replace($val,$val." | ".str_ireplace("[[a ","[[",$val),$stuff);
    }
    preg_match_all("%\[\[[\s\S]* or [\s\S]*\]\]%U",$stuff,$new);
    foreach($new[0] as $val){
      $stuff = str_replace($val,$val." | ".str_ireplace(" or ","]] or [[",$val),$stuff);
    }
    
    return $stuff;
  }
  
}