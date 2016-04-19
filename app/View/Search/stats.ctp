<h1>Quick</h1>
<div id="quick">
  <table class="table table-condensed">
    <tr><td></td><td><b>Total</b></td><td><b>Overall</b></td>   
<?php foreach($phydiv_totals as $key=>$val){ ?>
      <td><b><?=$key?></b></td>
<?php } ?>
    </tr>
<?php foreach($list as $yr_key=>$yr_arr){ ?>
    <tr>
      <td style="background-color:#A9A9A9"><span style="font-size:20px; font-weight:bold"><?=$yr_key?></span></td>
      <td class="warning" style="text-align:center"><b><?=$total_list[$yr_key]['total']?></b></td>  
      <td class="danger" style="text-align:center"><b><?=$total_list[$yr_key]['overall']?></b></td>
<?php foreach($phydiv_totals as $key=>$val){ ?>
      <td style="text-align:center"<?=($total_list[$yr_key][$key] != 0)?' class="info"':''?>">
<?php if ($total_list[$yr_key][$key] != 0){
echo '<b><a href="/search/search/1?phydiv='.$key.
      '&start_date='.$yr_key.'-01-01&end_date='.$yr_key.'-12-31'.'">'.
      $total_list[$yr_key][$key].'</a></b>';
      }else{ echo "-"; } ?>
      </td>
<?php } ?>
    </tr>
<?php } ?>
<tr><td></td><td></td><td></td>
<?php foreach($phydiv_totals as $val){ ?>
    <td class="warning" style="text-align:center"><b><?=$val?></b></td>
<?php } ?>
  </tr>
</table>
</div>

<h1>Detailed</h1>
<div id="detailed">
<?php foreach($list as $yr_key=>$yr_arr){ ?>
  <span style="font-weight:bold; font-size:25px"><?=$yr_key?></span><br/>
  <table class="table table-condensed">
    <tr><td></td><td><b>Total</b></td>
<?php foreach($all_phyla as $val){ ?>
      <td><b><?=$val['Phylum']['name']?></b></td>
<?php } ?>
<?php foreach($all_divs as $val){ ?>
      <td><b><?=$val['Division']['name']?></b></td>
<?php } ?>
    </tr>

<?php foreach($yr_arr as $mon_key=>$mon_arr){ ?>
    <tr>
      <td style="background-color:#A9A9A9"><b><?=date('M',mktime(0,0,0,$mon_key,1,2000))?></b></td>
      <td class="warning" style="text-align:center"><?=($total_list[$yr_key][$mon_key] != 0)?('<b><a href="/search/search/1?start_date='.
        $yr_key.'-'.$mon_key.'-01'.'&end_date='.date("Y-m-t",strtotime($yr_key.'-'.$mon_key.'-01')).
        '">'.$total_list[$yr_key][$mon_key].'</a></b>'):"0"?></td>
<?php foreach($mon_arr as $phydiv_key=>$wal){ if ($phydiv_key != 'average'){ ?>
      <td style="text-align:center"<?php
if ($wal>0){
  echo ($wal > $yr_arr[$mon_key]['average'])?'class="danger"':'class="info"';
  echo'><a href="/search/search/1?phydiv='.$phydiv_key.
        '&start_date='.$yr_key.'-'.$mon_key.'-01'.
        '&end_date='.date("Y-m-t",strtotime($yr_key.'-'.$mon_key.'-01')).'">'.$wal.'</a>';
}else{
  echo">-";
} ?></td>
<?php } } ?>
    </tr>
<?php } ?>
    <tr><td><b>Total</b></td><td style="text-align:center"><b><?=$total_list[$yr_key]['total']?></b></td>      
<?php foreach($all_phyla as $val){ ?>
      <td class="warning" style="text-align:center">
<?php if ($total_list[$yr_key][$val['Phylum']['name']] != 0){
echo '<b><a href="/search/search/1?phydiv='.$val['Phylum']['name'].
      '&start_date='.$yr_key.'-01-01&end_date='.$yr_key.'-12-31'.'">'.
      $total_list[$yr_key][$val['Phylum']['name']].'</a></b>';
      }else{ echo "0"; } ?>
      </td>
<?php } ?>
<?php foreach($all_divs as $val){ ?>
      <td class="warning" style="text-align:center">
<?php if ($total_list[$yr_key][$val['Division']['name']] != 0){
echo '<b><a href="/search/search/1?phydiv='.$val['Division']['name'].
      '&start_date='.$yr_key.'-01-01&end_date='.$yr_key.'-12-31'.'">'.
      $total_list[$yr_key][$val['Division']['name']].'</a></b>';
      }else{ echo "0"; } ?>
      </td>
<?php } ?>
    </tr>
    <tr><td><b>Overall</b></td style="text-align:center"><td><b><?=$total_list[$yr_key]['overall']?></b></td></tr>
  </table><br/><br/>
<?php } ?>
</div>

