<div class="well">
  <table class="table">
    <tr class="active"><td><b>No Article</b></td>
      <td><b>Kingdom</b></td><td><b>Order</b></td></tr>
<?php foreach($table['article'] as $val){ ?>
      <tr>
        <td><?=$val['Species']['genus']." ".$val['Species']['species']?></td>
        <td><?=$val['Species']['kingdom']?></td>
        <td><?=$val['Species']['order']?></td>
      </tr>
<?php } ?>
  </table>
</div>
<div class="well">
  <table class="table">
    <tr class="active"><td><b>No Infobox</b></td></tr>
<?php foreach($table['infobox'] as $val){ ?>
      <tr><td><?=$val['Species']['genus']." ".$val['Species']['species']?></td></tr>
<?php } ?>
  </table>
</div>
<div class="well">
  <table class="table">
    <tr class="active"><td><b>No Picture</b></td></tr>
<?php foreach($table['picture'] as $val){ ?>
      <tr><td><?=$val['Species']['genus']." ".$val['Species']['species']?></td></tr>
<?php } ?>
  </table>
</div>
