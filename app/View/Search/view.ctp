<?php if ($file_error){ ?>
There was an error with the file or file not found:<br/>
<?=$fam_dir?>
<?php }else{ foreach ($spec_list as $val){ ?>
<h2><?=$name?> <?php
if (strlen($val['Specimen']['filename_for_calc']) > (strlen($name) + 4)){
  echo "(".substr($val['Specimen']['filename_for_calc'],(strlen($name)+1),-4).")";
}?> <span <?=($val['Specimen']['is_wild'])?'style="color:red">W':'style="color:blue">S'?></span></h2>
<h3><?=$common_name?></h3>

Taken on <?=date("M d, Y",strtotime($val['Specimen']['date_found']))?><br/>
<?=$val['Specimen']['location']?>, <?=($val['Specimen']['state']=="XX")?"":$val['Specimen']['state'].', '?><?=$val['Specimen']['country']?><br/>
<img src="<?=$fam_dir.$val['Specimen']['filename']?>" width="700">
<?php } } ?>