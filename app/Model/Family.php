<?php
/**
 * Family Model
 * 
 * app/Model/Family.php
 */

class Family extends AppModel {
  public $useTable = 'families';
  
  function get_by_id($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    $cc['Family']['folder_name'] = ($cc['Family']['nickname'] == "" || $cc['Family']['nickname'] == null)?$cc['Family']['name']:($cc['Family']['name']." (".$cc['Family']['nickname'].")");
    return $cc['Family'];
  }
  
  function get_by_name($name){
    if (!($cc = $this->find('first',array('conditions'=>array('name'=>$name)))))
      $cc = $this->find('first',array('conditions'=>array('name'=>array_shift(explode(" ",$name)))));
    return $this->get_by_id($cc['Family']['id']);
  }
  
  function get_nickname($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return ($cc['Family']['nickname']=="")?NULL:$cc['Family']['nickname'];
  }
  
  function get_dir($id,$get_full_dir = true){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    $kingdom = $this->get_kingdom($id);
    $order = $this->get_order($id);
    $ord_fold = ($order['nickname'] == "")?$order['name']:$order['name']." (".$order['nickname'].")";
    $family = $this->get_by_id($id);
    $fam_fold = ($family['nickname'] == "")?$family['name']:$family['name']." (".$family['nickname'].")";
    if (!$this->has_phylum($id)){
      $division = $this->get_division($id);
      $fam_dir = DS."zz ".$kingdom['name'].DS.$division['name'].DS.
          $ord_fold.DS.$fam_fold.DS;
    }else{
      $phylum = $this->get_phylum($id);
      $classdiv = $this->get_classdiv($id);
      $phy_fold = ($phylum['nickname'] == "")?$phylum['name']:$phylum['name']." (".$phylum['nickname'].")";
      $cls_fold = ($classdiv['nickname'] == "")?$classdiv['name']:$classdiv['name']." (".$classdiv['nickname'].")";
      $fam_dir = DS."zz ".$kingdom['name'].DS.$phy_fold.DS.$cls_fold.DS.
          $ord_fold.DS.$fam_fold.DS;
    }
    return ($get_full_dir)?(APP.'webroot'.DS.'img'.DS.'pokedex'.$fam_dir):(DS.'img'.DS.'pokedex'.$fam_dir);
  }
  
  function get_name($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $cc['Family']['name'];
  }
  
  function get_kingdom($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->get_kingdom($cc['Family']['order']);
  }
  
  function get_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->get_phylum($cc['Family']['order']);
  }
  
  function get_division($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->get_division($cc['Family']['order']);
  }
  
  function get_classdiv($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->get_classdiv($cc['Family']['order']);
  }
  
  function get_order($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->get_by_id($cc['Family']['order']);
  }
  
  function has_phylum($id){
    if (!($cc = $this->find('first',array('conditions'=>array('id'=>$id)))))
      return null;
    return $this->Order->has_phylum($cc['Family']['order']);
  }
  
  function get_spider_fams(){
    $source = '<strong>Actinopodidae</strong></td>
      <td>Simon, 1892</td>
      <td>| <a title="Detail" href="/familydetail/1"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/1/Actinopodidae">Genera</a> | 
       <a title="Classic view" href="/family/1/Actinopodidae">Catalog</a></span>
      </td>
      <td>2015-02-18      </td></tr>
          <tr><td>2. <strong>Agelenidae</strong></td>
      <td>C. L. Koch, 1837</td>
      <td>| <a title="Detail" href="/familydetail/2"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/2/Agelenidae">Genera</a> | 
       <a title="Classic view" href="/family/2/Agelenidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>3. <strong>Amaurobiidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/3"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/3/Amaurobiidae">Genera</a> | 
       <a title="Classic view" href="/family/3/Amaurobiidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>4. <strong>Ammoxenidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/4"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/4/Ammoxenidae">Genera</a> | 
       <a title="Classic view" href="/family/4/Ammoxenidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>5. <strong>Amphinectidae</strong></td>
      <td>Forster & Wilton, 1973</td>
      <td>| <a title="Detail" href="/familydetail/5"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/5/Amphinectidae">Genera</a> | 
       <a title="Classic view" href="/family/5/Amphinectidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>6. <strong>Anapidae</strong></td>
      <td>Simon, 1895</td>
      <td>| <a title="Detail" href="/familydetail/6"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/6/Anapidae">Genera</a> | 
       <a title="Classic view" href="/family/6/Anapidae">Catalog</a></span>
      </td>
      <td>2015-10-08      </td></tr>
          <tr><td>7. <strong>Antrodiaetidae</strong></td>
      <td>Gertsch, 1940</td>
      <td>| <a title="Detail" href="/familydetail/7"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/7/Antrodiaetidae">Genera</a> | 
       <a title="Classic view" href="/family/7/Antrodiaetidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>8. <strong>Anyphaenidae</strong></td>
      <td>Bertkau, 1878</td>
      <td>| <a title="Detail" href="/familydetail/8"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/8/Anyphaenidae">Genera</a> | 
       <a title="Classic view" href="/family/8/Anyphaenidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>9. <strong>Araneidae</strong></td>
      <td>Clerck, 1757</td>
      <td>| <a title="Detail" href="/familydetail/9"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/9/Araneidae">Genera</a> | 
       <a title="Classic view" href="/family/9/Araneidae">Catalog</a></span>
      </td>
      <td>2016-01-14      </td></tr>
          <tr><td>10. <strong>Archaeidae</strong></td>
      <td>C. L. Koch & Berendt, 1854</td>
      <td>| <a title="Detail" href="/familydetail/10"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/10/Archaeidae">Genera</a> | 
       <a title="Classic view" href="/family/10/Archaeidae">Catalog</a></span>
      </td>
      <td>2015-09-07      </td></tr>
          <tr><td>11. <strong>Atypidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/11"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/11/Atypidae">Genera</a> | 
       <a title="Classic view" href="/family/11/Atypidae">Catalog</a></span>
      </td>
      <td>2015-02-10      </td></tr>
          <tr><td>12. <strong>Austrochilidae</strong></td>
      <td>Zapfe, 1955</td>
      <td>| <a title="Detail" href="/familydetail/12"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/12/Austrochilidae">Genera</a> | 
       <a title="Classic view" href="/family/12/Austrochilidae">Catalog</a></span>
      </td>
      <td>2015-04-08      </td></tr>
          <tr><td>13. <strong>Barychelidae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/13"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/13/Barychelidae">Genera</a> | 
       <a title="Classic view" href="/family/13/Barychelidae">Catalog</a></span>
      </td>
      <td>2015-07-22      </td></tr>
          <tr><td>14. <strong>Caponiidae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/14"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/14/Caponiidae">Genera</a> | 
       <a title="Classic view" href="/family/14/Caponiidae">Catalog</a></span>
      </td>
      <td>2015-09-07      </td></tr>
          <tr><td>15. <strong>Chummidae</strong></td>
      <td>Jocqué, 2001</td>
      <td>| <a title="Detail" href="/familydetail/15"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/15/Chummidae">Genera</a> | 
       <a title="Classic view" href="/family/15/Chummidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>16. <strong>Cithaeronidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/16"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/16/Cithaeronidae">Genera</a> | 
       <a title="Classic view" href="/family/16/Cithaeronidae">Catalog</a></span>
      </td>
      <td>2015-09-07      </td></tr>
          <tr><td>17. <strong>Clubionidae</strong></td>
      <td>Wagner, 1887</td>
      <td>| <a title="Detail" href="/familydetail/17"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/17/Clubionidae">Genera</a> | 
       <a title="Classic view" href="/family/17/Clubionidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>18. <strong>Corinnidae</strong></td>
      <td>Karsch, 1880</td>
      <td>| <a title="Detail" href="/familydetail/18"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/18/Corinnidae">Genera</a> | 
       <a title="Classic view" href="/family/18/Corinnidae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>19. <strong>Ctenidae</strong></td>
      <td>Keyserling, 1877</td>
      <td>| <a title="Detail" href="/familydetail/19"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/19/Ctenidae">Genera</a> | 
       <a title="Classic view" href="/family/19/Ctenidae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>20. <strong>Ctenizidae</strong></td>
      <td>Thorell, 1887</td>
      <td>| <a title="Detail" href="/familydetail/20"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/20/Ctenizidae">Genera</a> | 
       <a title="Classic view" href="/family/20/Ctenizidae">Catalog</a></span>
      </td>
      <td>2015-06-10      </td></tr>
          <tr><td>21. <strong>Cyatholipidae</strong></td>
      <td>Simon, 1894</td>
      <td>| <a title="Detail" href="/familydetail/21"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/21/Cyatholipidae">Genera</a> | 
       <a title="Classic view" href="/family/21/Cyatholipidae">Catalog</a></span>
      </td>
      <td>2015-02-25      </td></tr>
          <tr><td>22. <strong>Cybaeidae</strong></td>
      <td>Banks, 1892</td>
      <td>| <a title="Detail" href="/familydetail/22"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/22/Cybaeidae">Genera</a> | 
       <a title="Classic view" href="/family/22/Cybaeidae">Catalog</a></span>
      </td>
      <td>2015-05-06      </td></tr>
          <tr><td>23. <strong>Cycloctenidae</strong></td>
      <td>Simon, 1898</td>
      <td>| <a title="Detail" href="/familydetail/23"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/23/Cycloctenidae">Genera</a> | 
       <a title="Classic view" href="/family/23/Cycloctenidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>24. <strong>Cyrtaucheniidae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/24"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/24/Cyrtaucheniidae">Genera</a> | 
       <a title="Classic view" href="/family/24/Cyrtaucheniidae">Catalog</a></span>
      </td>
      <td>2015-02-02      </td></tr>
          <tr><td>25. <strong>Deinopidae</strong></td>
      <td>C. L. Koch, 1850</td>
      <td>| <a title="Detail" href="/familydetail/25"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/25/Deinopidae">Genera</a> | 
       <a title="Classic view" href="/family/25/Deinopidae">Catalog</a></span>
      </td>
      <td>2014-09-08      </td></tr>
          <tr><td>26. <strong>Desidae</strong></td>
      <td>Pocock, 1895</td>
      <td>| <a title="Detail" href="/familydetail/26"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/26/Desidae">Genera</a> | 
       <a title="Classic view" href="/family/26/Desidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>27. <strong>Dictynidae</strong></td>
      <td>O. Pickard-Cambridge, 1871</td>
      <td>| <a title="Detail" href="/familydetail/27"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/27/Dictynidae">Genera</a> | 
       <a title="Classic view" href="/family/27/Dictynidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>28. <strong>Diguetidae</strong></td>
      <td>F. O. Pickard-Cambridge, 1899</td>
      <td>| <a title="Detail" href="/familydetail/28"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/28/Diguetidae">Genera</a> | 
       <a title="Classic view" href="/family/28/Diguetidae">Catalog</a></span>
      </td>
      <td>2015-04-08      </td></tr>
          <tr><td>29. <strong>Dipluridae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/29"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/29/Dipluridae">Genera</a> | 
       <a title="Classic view" href="/family/29/Dipluridae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>30. <strong>Drymusidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/30"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/30/Drymusidae">Genera</a> | 
       <a title="Classic view" href="/family/30/Drymusidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>31. <strong>Dysderidae</strong></td>
      <td>C. L. Koch, 1837</td>
      <td>| <a title="Detail" href="/familydetail/31"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/31/Dysderidae">Genera</a> | 
       <a title="Classic view" href="/family/31/Dysderidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>32. <strong>Eresidae</strong></td>
      <td>C. L. Koch, 1845</td>
      <td>| <a title="Detail" href="/familydetail/32"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/32/Eresidae">Genera</a> | 
       <a title="Classic view" href="/family/32/Eresidae">Catalog</a></span>
      </td>
      <td>2015-09-08      </td></tr>
          <tr><td>33. <strong>Euctenizidae</strong></td>
      <td>Raven, 1985</td>
      <td>| <a title="Detail" href="/familydetail/33"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/33/Euctenizidae">Genera</a> | 
       <a title="Classic view" href="/family/33/Euctenizidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>34. <strong>Eutichuridae</strong></td>
      <td>Lehtinen, 1967</td>
      <td>| <a title="Detail" href="/familydetail/113"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/113/Eutichuridae">Genera</a> | 
       <a title="Classic view" href="/family/113/Eutichuridae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>35. <strong>Filistatidae</strong></td>
      <td>Ausserer, 1867</td>
      <td>| <a title="Detail" href="/familydetail/34"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/34/Filistatidae">Genera</a> | 
       <a title="Classic view" href="/family/34/Filistatidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>36. <strong>Gallieniellidae</strong></td>
      <td>Millot, 1947</td>
      <td>| <a title="Detail" href="/familydetail/35"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/35/Gallieniellidae">Genera</a> | 
       <a title="Classic view" href="/family/35/Gallieniellidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>37. <strong>Gnaphosidae</strong></td>
      <td>Pocock, 1898</td>
      <td>| <a title="Detail" href="/familydetail/36"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/36/Gnaphosidae">Genera</a> | 
       <a title="Classic view" href="/family/36/Gnaphosidae">Catalog</a></span>
      </td>
      <td>2016-01-18      </td></tr>
          <tr><td>38. <strong>Gradungulidae</strong></td>
      <td>Forster, 1955</td>
      <td>| <a title="Detail" href="/familydetail/37"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/37/Gradungulidae">Genera</a> | 
       <a title="Classic view" href="/family/37/Gradungulidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>39. <strong>Hahniidae</strong></td>
      <td>Bertkau, 1878</td>
      <td>| <a title="Detail" href="/familydetail/38"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/38/Hahniidae">Genera</a> | 
       <a title="Classic view" href="/family/38/Hahniidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>40. <strong>Hersiliidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/39"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/39/Hersiliidae">Genera</a> | 
       <a title="Classic view" href="/family/39/Hersiliidae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>41. <strong>Hexathelidae</strong></td>
      <td>Simon, 1892</td>
      <td>| <a title="Detail" href="/familydetail/40"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/40/Hexathelidae">Genera</a> | 
       <a title="Classic view" href="/family/40/Hexathelidae">Catalog</a></span>
      </td>
      <td>2015-04-08      </td></tr>
          <tr><td>42. <strong>Holarchaeidae</strong></td>
      <td>Forster & Platnick, 1984</td>
      <td>| <a title="Detail" href="/familydetail/41"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/41/Holarchaeidae">Genera</a> | 
       <a title="Classic view" href="/family/41/Holarchaeidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>43. <strong>Homalonychidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/42"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/42/Homalonychidae">Genera</a> | 
       <a title="Classic view" href="/family/42/Homalonychidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>44. <strong>Huttoniidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/43"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/43/Huttoniidae">Genera</a> | 
       <a title="Classic view" href="/family/43/Huttoniidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>45. <strong>Hypochilidae</strong></td>
      <td>Marx, 1888</td>
      <td>| <a title="Detail" href="/familydetail/44"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/44/Hypochilidae">Genera</a> | 
       <a title="Classic view" href="/family/44/Hypochilidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>46. <strong>Idiopidae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/45"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/45/Idiopidae">Genera</a> | 
       <a title="Classic view" href="/family/45/Idiopidae">Catalog</a></span>
      </td>
      <td>2015-10-08      </td></tr>
          <tr><td>47. <strong>Lamponidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/46"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/46/Lamponidae">Genera</a> | 
       <a title="Classic view" href="/family/46/Lamponidae">Catalog</a></span>
      </td>
      <td>2014-07-25      </td></tr>
          <tr><td>48. <strong>Leptonetidae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/47"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/47/Leptonetidae">Genera</a> | 
       <a title="Classic view" href="/family/47/Leptonetidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>49. <strong>Linyphiidae</strong></td>
      <td>Blackwall, 1859</td>
      <td>| <a title="Detail" href="/familydetail/48"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/48/Linyphiidae">Genera</a> | 
       <a title="Classic view" href="/family/48/Linyphiidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>50. <strong>Liocranidae</strong></td>
      <td>Simon, 1897</td>
      <td>| <a title="Detail" href="/familydetail/49"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/49/Liocranidae">Genera</a> | 
       <a title="Classic view" href="/family/49/Liocranidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>51. <strong>Liphistiidae</strong></td>
      <td>Thorell, 1869</td>
      <td>| <a title="Detail" href="/familydetail/50"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/50/Liphistiidae">Genera</a> | 
       <a title="Classic view" href="/family/50/Liphistiidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>52. <strong>Lycosidae</strong></td>
      <td>Sundevall, 1833</td>
      <td>| <a title="Detail" href="/familydetail/51"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/51/Lycosidae">Genera</a> | 
       <a title="Classic view" href="/family/51/Lycosidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>53. <strong>Malkaridae</strong></td>
      <td>Davies, 1980</td>
      <td>| <a title="Detail" href="/familydetail/52"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/52/Malkaridae">Genera</a> | 
       <a title="Classic view" href="/family/52/Malkaridae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>54. <strong>Mecicobothriidae</strong></td>
      <td>Holmberg, 1882</td>
      <td>| <a title="Detail" href="/familydetail/53"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/53/Mecicobothriidae">Genera</a> | 
       <a title="Classic view" href="/family/53/Mecicobothriidae">Catalog</a></span>
      </td>
      <td>2015-04-08      </td></tr>
          <tr><td>55. <strong>Mecysmaucheniidae</strong></td>
      <td>Simon, 1895</td>
      <td>| <a title="Detail" href="/familydetail/54"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/54/Mecysmaucheniidae">Genera</a> | 
       <a title="Classic view" href="/family/54/Mecysmaucheniidae">Catalog</a></span>
      </td>
      <td>2015-01-14      </td></tr>
          <tr><td>56. <strong>Micropholcommatidae</strong></td>
      <td>Hickman, 1944</td>
      <td>| <a title="Detail" href="/familydetail/55"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/55/Micropholcommatidae">Genera</a> | 
       <a title="Classic view" href="/family/55/Micropholcommatidae">Catalog</a></span>
      </td>
      <td>2015-07-08      </td></tr>
          <tr><td>57. <strong>Microstigmatidae</strong></td>
      <td>Roewer, 1942</td>
      <td>| <a title="Detail" href="/familydetail/56"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/56/Microstigmatidae">Genera</a> | 
       <a title="Classic view" href="/family/56/Microstigmatidae">Catalog</a></span>
      </td>
      <td>2015-04-08      </td></tr>
          <tr><td>58. <strong>Migidae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/57"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/57/Migidae">Genera</a> | 
       <a title="Classic view" href="/family/57/Migidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>59. <strong>Mimetidae</strong></td>
      <td>Simon, 1881</td>
      <td>| <a title="Detail" href="/familydetail/58"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/58/Mimetidae">Genera</a> | 
       <a title="Classic view" href="/family/58/Mimetidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>60. <strong>Miturgidae</strong></td>
      <td>Simon, 1886</td>
      <td>| <a title="Detail" href="/familydetail/59"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/59/Miturgidae">Genera</a> | 
       <a title="Classic view" href="/family/59/Miturgidae">Catalog</a></span>
      </td>
      <td>2015-06-09      </td></tr>
          <tr><td>61. <strong>Mysmenidae</strong></td>
      <td>Petrunkevitch, 1928</td>
      <td>| <a title="Detail" href="/familydetail/60"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/60/Mysmenidae">Genera</a> | 
       <a title="Classic view" href="/family/60/Mysmenidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>62. <strong>Nemesiidae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/61"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/61/Nemesiidae">Genera</a> | 
       <a title="Classic view" href="/family/61/Nemesiidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>63. <strong>Nephilidae</strong></td>
      <td>Simon, 1894</td>
      <td>| <a title="Detail" href="/familydetail/62"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/62/Nephilidae">Genera</a> | 
       <a title="Classic view" href="/family/62/Nephilidae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>64. <strong>Nesticidae</strong></td>
      <td>Simon, 1894</td>
      <td>| <a title="Detail" href="/familydetail/63"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/63/Nesticidae">Genera</a> | 
       <a title="Classic view" href="/family/63/Nesticidae">Catalog</a></span>
      </td>
      <td>2015-12-07      </td></tr>
          <tr><td>65. <strong>Nicodamidae</strong></td>
      <td>Simon, 1897</td>
      <td>| <a title="Detail" href="/familydetail/64"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/64/Nicodamidae">Genera</a> | 
       <a title="Classic view" href="/family/64/Nicodamidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>66. <strong>Ochyroceratidae</strong></td>
      <td>Fage, 1912</td>
      <td>| <a title="Detail" href="/familydetail/65"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/65/Ochyroceratidae">Genera</a> | 
       <a title="Classic view" href="/family/65/Ochyroceratidae">Catalog</a></span>
      </td>
      <td>2015-06-03      </td></tr>
          <tr><td>67. <strong>Oecobiidae</strong></td>
      <td>Blackwall, 1862</td>
      <td>| <a title="Detail" href="/familydetail/66"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/66/Oecobiidae">Genera</a> | 
       <a title="Classic view" href="/family/66/Oecobiidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>68. <strong>Oonopidae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/67"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/67/Oonopidae">Genera</a> | 
       <a title="Classic view" href="/family/67/Oonopidae">Catalog</a></span>
      </td>
      <td>2015-12-16      </td></tr>
          <tr><td>69. <strong>Orsolobidae</strong></td>
      <td>Cooke, 1965</td>
      <td>| <a title="Detail" href="/familydetail/68"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/68/Orsolobidae">Genera</a> | 
       <a title="Classic view" href="/family/68/Orsolobidae">Catalog</a></span>
      </td>
      <td>2015-07-27      </td></tr>
          <tr><td>70. <strong>Oxyopidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/69"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/69/Oxyopidae">Genera</a> | 
       <a title="Classic view" href="/family/69/Oxyopidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>71. <strong>Palpimanidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/70"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/70/Palpimanidae">Genera</a> | 
       <a title="Classic view" href="/family/70/Palpimanidae">Catalog</a></span>
      </td>
      <td>2015-09-15      </td></tr>
          <tr><td>72. <strong>Pararchaeidae</strong></td>
      <td>Forster & Platnick, 1984</td>
      <td>| <a title="Detail" href="/familydetail/71"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/71/Pararchaeidae">Genera</a> | 
       <a title="Classic view" href="/family/71/Pararchaeidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>73. <strong>Paratropididae</strong></td>
      <td>Simon, 1889</td>
      <td>| <a title="Detail" href="/familydetail/72"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/72/Paratropididae">Genera</a> | 
       <a title="Classic view" href="/family/72/Paratropididae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>74. <strong>Penestomidae</strong></td>
      <td>Simon, 1903</td>
      <td>| <a title="Detail" href="/familydetail/73"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/73/Penestomidae">Genera</a> | 
       <a title="Classic view" href="/family/73/Penestomidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>75. <strong>Periegopidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/74"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/74/Periegopidae">Genera</a> | 
       <a title="Classic view" href="/family/74/Periegopidae">Catalog</a></span>
      </td>
      <td>2014-09-17      </td></tr>
          <tr><td>76. <strong>Philodromidae</strong></td>
      <td>Thorell, 1870</td>
      <td>| <a title="Detail" href="/familydetail/75"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/75/Philodromidae">Genera</a> | 
       <a title="Classic view" href="/family/75/Philodromidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>77. <strong>Pholcidae</strong></td>
      <td>C. L. Koch, 1850</td>
      <td>| <a title="Detail" href="/familydetail/76"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/76/Pholcidae">Genera</a> | 
       <a title="Classic view" href="/family/76/Pholcidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>78. <strong>Phrurolithidae</strong></td>
      <td>Banks, 1892</td>
      <td>| <a title="Detail" href="/familydetail/115"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/115/Phrurolithidae">Genera</a> | 
       <a title="Classic view" href="/family/115/Phrurolithidae">Catalog</a></span>
      </td>
      <td>2015-12-16      </td></tr>
          <tr><td>79. <strong>Phyxelididae</strong></td>
      <td>Lehtinen, 1967</td>
      <td>| <a title="Detail" href="/familydetail/77"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/77/Phyxelididae">Genera</a> | 
       <a title="Classic view" href="/family/77/Phyxelididae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>80. <strong>Pimoidae</strong></td>
      <td>Wunderlich, 1986</td>
      <td>| <a title="Detail" href="/familydetail/78"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/78/Pimoidae">Genera</a> | 
       <a title="Classic view" href="/family/78/Pimoidae">Catalog</a></span>
      </td>
      <td>2014-10-27      </td></tr>
          <tr><td>81. <strong>Pisauridae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/79"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/79/Pisauridae">Genera</a> | 
       <a title="Classic view" href="/family/79/Pisauridae">Catalog</a></span>
      </td>
      <td>2015-12-23      </td></tr>
          <tr><td>82. <strong>Plectreuridae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/80"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/80/Plectreuridae">Genera</a> | 
       <a title="Classic view" href="/family/80/Plectreuridae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>83. <strong>Prodidomidae</strong></td>
      <td>Simon, 1884</td>
      <td>| <a title="Detail" href="/familydetail/81"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/81/Prodidomidae">Genera</a> | 
       <a title="Classic view" href="/family/81/Prodidomidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>84. <strong>Psechridae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/82"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/82/Psechridae">Genera</a> | 
       <a title="Classic view" href="/family/82/Psechridae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>85. <strong>Salticidae</strong></td>
      <td>Blackwall, 1841</td>
      <td>| <a title="Detail" href="/familydetail/83"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/83/Salticidae">Genera</a> | 
       <a title="Classic view" href="/family/83/Salticidae">Catalog</a></span>
      </td>
      <td>2016-01-18      </td></tr>
          <tr><td>86. <strong>Scytodidae</strong></td>
      <td>Blackwall, 1864</td>
      <td>| <a title="Detail" href="/familydetail/84"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/84/Scytodidae">Genera</a> | 
       <a title="Classic view" href="/family/84/Scytodidae">Catalog</a></span>
      </td>
      <td>2015-12-10      </td></tr>
          <tr><td>87. <strong>Segestriidae</strong></td>
      <td>Simon, 1893</td>
      <td>| <a title="Detail" href="/familydetail/85"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/85/Segestriidae">Genera</a> | 
       <a title="Classic view" href="/family/85/Segestriidae">Catalog</a></span>
      </td>
      <td>2015-05-04      </td></tr>
          <tr><td>88. <strong>Selenopidae</strong></td>
      <td>Simon, 1897</td>
      <td>| <a title="Detail" href="/familydetail/86"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/86/Selenopidae">Genera</a> | 
       <a title="Classic view" href="/family/86/Selenopidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>89. <strong>Senoculidae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/87"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/87/Senoculidae">Genera</a> | 
       <a title="Classic view" href="/family/87/Senoculidae">Catalog</a></span>
      </td>
      <td>2015-09-21      </td></tr>
          <tr><td>90. <strong>Sicariidae</strong></td>
      <td>Keyserling, 1880</td>
      <td>| <a title="Detail" href="/familydetail/88"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/88/Sicariidae">Genera</a> | 
       <a title="Classic view" href="/family/88/Sicariidae">Catalog</a></span>
      </td>
      <td>2015-09-09      </td></tr>
          <tr><td>91. <strong>Sinopimoidae</strong></td>
      <td>Li & Wunderlich, 2008</td>
      <td>| <a title="Detail" href="/familydetail/89"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/89/Sinopimoidae">Genera</a> | 
       <a title="Classic view" href="/family/89/Sinopimoidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>92. <strong>Sparassidae</strong></td>
      <td>Bertkau, 1872</td>
      <td>| <a title="Detail" href="/familydetail/90"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/90/Sparassidae">Genera</a> | 
       <a title="Classic view" href="/family/90/Sparassidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>93. <strong>Stenochilidae</strong></td>
      <td>Thorell, 1873</td>
      <td>| <a title="Detail" href="/familydetail/91"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/91/Stenochilidae">Genera</a> | 
       <a title="Classic view" href="/family/91/Stenochilidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>94. <strong>Stiphidiidae</strong></td>
      <td>Dalmas, 1917</td>
      <td>| <a title="Detail" href="/familydetail/92"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/92/Stiphidiidae">Genera</a> | 
       <a title="Classic view" href="/family/92/Stiphidiidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>95. <strong>Symphytognathidae</strong></td>
      <td>Hickman, 1931</td>
      <td>| <a title="Detail" href="/familydetail/93"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/93/Symphytognathidae">Genera</a> | 
       <a title="Classic view" href="/family/93/Symphytognathidae">Catalog</a></span>
      </td>
      <td>2015-07-13      </td></tr>
          <tr><td>96. <strong>Synaphridae</strong></td>
      <td>Wunderlich, 1986</td>
      <td>| <a title="Detail" href="/familydetail/94"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/94/Synaphridae">Genera</a> | 
       <a title="Classic view" href="/family/94/Synaphridae">Catalog</a></span>
      </td>
      <td>2015-07-08      </td></tr>
          <tr><td>97. <strong>Synotaxidae</strong></td>
      <td>Simon, 1894</td>
      <td>| <a title="Detail" href="/familydetail/95"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/95/Synotaxidae">Genera</a> | 
       <a title="Classic view" href="/family/95/Synotaxidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>98. <strong>Telemidae</strong></td>
      <td>Fage, 1913</td>
      <td>| <a title="Detail" href="/familydetail/96"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/96/Telemidae">Genera</a> | 
       <a title="Classic view" href="/family/96/Telemidae">Catalog</a></span>
      </td>
      <td>2015-09-28      </td></tr>
          <tr><td>99. <strong>Tetrablemmidae</strong></td>
      <td>O. Pickard-Cambridge, 1873</td>
      <td>| <a title="Detail" href="/familydetail/98"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/98/Tetrablemmidae">Genera</a> | 
       <a title="Classic view" href="/family/98/Tetrablemmidae">Catalog</a></span>
      </td>
      <td>2016-01-14      </td></tr>
          <tr><td>100. <strong>Tetragnathidae</strong></td>
      <td>Menge, 1866</td>
      <td>| <a title="Detail" href="/familydetail/99"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/99/Tetragnathidae">Genera</a> | 
       <a title="Classic view" href="/family/99/Tetragnathidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>101. <strong>Theraphosidae</strong></td>
      <td>Thorell, 1869</td>
      <td>| <a title="Detail" href="/familydetail/100"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/100/Theraphosidae">Genera</a> | 
       <a title="Classic view" href="/family/100/Theraphosidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>102. <strong>Theridiidae</strong></td>
      <td>Sundevall, 1833</td>
      <td>| <a title="Detail" href="/familydetail/101"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/101/Theridiidae">Genera</a> | 
       <a title="Classic view" href="/family/101/Theridiidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>103. <strong>Theridiosomatidae</strong></td>
      <td>Simon, 1881</td>
      <td>| <a title="Detail" href="/familydetail/102"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/102/Theridiosomatidae">Genera</a> | 
       <a title="Classic view" href="/family/102/Theridiosomatidae">Catalog</a></span>
      </td>
      <td>2015-10-27      </td></tr>
          <tr><td>104. <strong>Thomisidae</strong></td>
      <td>Sundevall, 1833</td>
      <td>| <a title="Detail" href="/familydetail/103"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/103/Thomisidae">Genera</a> | 
       <a title="Classic view" href="/family/103/Thomisidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>105. <strong>Titanoecidae</strong></td>
      <td>Lehtinen, 1967</td>
      <td>| <a title="Detail" href="/familydetail/104"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/104/Titanoecidae">Genera</a> | 
       <a title="Classic view" href="/family/104/Titanoecidae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>106. <strong>Trachelidae</strong></td>
      <td>Simon, 1897</td>
      <td>| <a title="Detail" href="/familydetail/114"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/114/Trachelidae">Genera</a> | 
       <a title="Classic view" href="/family/114/Trachelidae">Catalog</a></span>
      </td>
      <td>2016-01-12      </td></tr>
          <tr><td>107. <strong>Trechaleidae</strong></td>
      <td>Simon, 1890</td>
      <td>| <a title="Detail" href="/familydetail/105"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/105/Trechaleidae">Genera</a> | 
       <a title="Classic view" href="/family/105/Trechaleidae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>108. <strong>Trochanteriidae</strong></td>
      <td>Karsch, 1879</td>
      <td>| <a title="Detail" href="/familydetail/106"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/106/Trochanteriidae">Genera</a> | 
       <a title="Classic view" href="/family/106/Trochanteriidae">Catalog</a></span>
      </td>
      <td>2015-11-20      </td></tr>
          <tr><td>109. <strong>Trogloraptoridae</strong></td>
      <td>Griswold, Audisio & Ledford, 2012</td>
      <td>| <a title="Detail" href="/familydetail/107"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/107/Trogloraptoridae">Genera</a> | 
       <a title="Classic view" href="/family/107/Trogloraptoridae">Catalog</a></span>
      </td>
      <td>- -     </td></tr>
          <tr><td>110. <strong>Udubidae</strong></td>
      <td>Griswold & Polotow, 2015</td>
      <td>| <a title="Detail" href="/familydetail/116"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/116/Udubidae">Genera</a> | 
       <a title="Classic view" href="/family/116/Udubidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>111. <strong>Uloboridae</strong></td>
      <td>Thorell, 1869</td>
      <td>| <a title="Detail" href="/familydetail/108"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/108/Uloboridae">Genera</a> | 
       <a title="Classic view" href="/family/108/Uloboridae">Catalog</a></span>
      </td>
      <td>2016-01-07      </td></tr>
          <tr><td>112. <strong>Viridasiidae</strong></td>
      <td>Lehtinen, 1967</td>
      <td>| <a title="Detail" href="/familydetail/117"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/117/Viridasiidae">Genera</a> | 
       <a title="Classic view" href="/family/117/Viridasiidae">Catalog</a></span>
      </td>
      <td>2015-10-29      </td></tr>
          <tr><td>113. <strong>Zodariidae</strong></td>
      <td>Thorell, 1881</td>
      <td>| <a title="Detail" href="/familydetail/109"><img alt="detail" src="/img/glyphicons_027_search.png"></a> |  
        <span class="actionLink"><a title="Genera list" href="/genlist/109/Zodariidae">Genera</a> | 
       <a title="Classic view" href="/family/109/Zodariidae">Catalog</a></span>
      </td>
      <td>2016-01-13      </td></tr>
          <tr><td>114. <strong>Zoropsidae</strong>';
    $html = array();
    preg_match_all("%<strong>([\s\S]*)<\/strong>%U",$source,$html);
    $str = "";
    $rating = array();
    $spider_fams = array();
    
    /*
    foreach($html[1] as $val){
      $talk_html = get_html('https://en.wikipedia.org/wiki/Talk:'.$val);
      preg_match("%Rated ([\s\S]*)-class%U",$talk_html,$rating);
      if ($rating[1] == "Redirect" || $rating[1] == "NA"){
        $article_html = get_html('https://en.wikipedia.org/wiki/'.$val);
        preg_match("%<title>([\s\S]*) - Wikipedia, the free encyclopedia</title>%U",$article_html,$rating);
        $talk_html = get_html('https://en.wikipedia.org/wiki/Talk:'.$rating[1]);
        preg_match("%Rated ([\s\S]*)-class%U",$talk_html,$rating);
      }
      if (count($rating)){
        $spider_fams[$val]=$rating[1];
      }else{
        $spider_fams[$val]="Unknown";
      }
     }
     asort($spider_fams);
     * 
     */
    //}
    
    return $html;
  }

}