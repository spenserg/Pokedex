<h1>Unowns</h1>
<?php foreach($unks as $kingdom=>$val){ ?>
<div class="well">
  <h3 style="display:inline"><?=$kingdom?></h3> (<?=count($val)?> order<?=(count($val)>1)?"s":""?>)&nbsp;&nbsp;&nbsp;
  <button class="btn btn-primary" type="button" id="button_<?=$kingdom?>" onclick="toggle_display('<?=$kingdom?>')">Show</button><br/>
<?php foreach($val as $order=>$wal){ ?>
  <div class="display_<?=$kingdom?>" style="display:none">
    <button class="btn btn-default" type="button" id="button_<?=$order?>" onclick="toggle_display('<?=$order?>')">Show</button>
    &nbsp;&nbsp;&nbsp;<h4 style="display:inline"><?=$order?></h4> (<?=$wal['num_specs']?> species)&nbsp;&nbsp;&nbsp;
    <div class="display_<?=$order?>" style="display:none">
<?php foreach($wal as $family=>$xal){ if ($family != "num_specs"){ ?>
      <b><?=$family?></b>
      <table class="table-condensed">
<?php foreach($xal as $yal){ ?>
        <tr>
          <td>
            <a href="/search/view?dir=<?=APP."webroot".$yal['filename']?>">
              <img src="<?=$yal['filename']?>" style="width:100px;height:100px;">
            </a>         
          </td>
          <td><?=$yal['genus']?> sp.</td>
          <td><?=date("M d, Y",strtotime($yal['date_found']))?></td>
          <td><?=$yal['location']?><?=($yal['state']=='XX')?"":(", ".$yal['state'])?>, <?=$yal['iso']?></td>
        </tr>
<?php } ?>
      </table><br/>
<?php } } ?>
  </div></div>
<?php } ?>
</div>
<?php } ?>

<script>
function toggle_display(id){
    $(".display_"+id).toggle();
    $("#button_"+id).html(($("#button_"+id).html()=="Hide")?"Show":"Hide");
  }
</script>