<form id="search_form" method="post" action="/search/gallery">
  <div class="well">
    <div class="row">
      <div class="col-md-1">Year:</div>
      <div class="col-md-3">
        <select name="year" id="year">
          <option value=0>Select Year</option>
          <?php for($i=2010; $i<=date("Y"); $i++){?>
            <option value=<?=$i?>><?=$i?></option>
          <?php } ?>
        </select>
      </div><br/>
      <div class="col-md-1">Month (Optional):</div>
      <div class="col-md-3">
        <select name="month">
          <option value=0<?php echo ($sel_mo==0)?" selected":"";?>>Select Month</option>
          <option value=1<?php echo ($sel_mo==1)?" selected":"";?>>January</option>
          <option value=2<?php echo ($sel_mo==2)?" selected":"";?>>February</option>
          <option value=3<?php echo ($sel_mo==3)?" selected":"";?>>March</option>
          <option value=4<?php echo ($sel_mo==4)?" selected":"";?>>April</option>
          <option value=5<?php echo ($sel_mo==5)?" selected":"";?>>May</option>
          <option value=6<?php echo ($sel_mo==6)?" selected":"";?>>June</option>
          <option value=7<?php echo ($sel_mo==7)?" selected":"";?>>July</option>
          <option value=8<?php echo ($sel_mo==8)?" selected":"";?>>August</option>
          <option value=9<?php echo ($sel_mo==9)?" selected":"";?>>September</option>
          <option value=10<?php echo ($sel_mo==10)?" selected":"";?>>October</option>
          <option value=11<?php echo ($sel_mo==11)?" selected":"";?>>November</option>
          <option value=12<?php echo ($sel_mo==12)?" selected":"";?>>December</option>
        </select>
      </div>
    </div><br/>
    <button class="btn btn-default" type="submit">Submit</button>
  </div>
</form>
<?php if (count($spec_list)){ ?>
<img id="big_image" src="/app/webroot<?=current($spec_list)['fam_dir'].current($spec_list)['filename']?>">
<br/><br/>
<div class="well" style="max-height:500px; overflow:auto; border:1px solid #000000;" >
  <br/><br/>
  <div class="row">
<?php foreach($spec_list as $val){ ?>
    <div class="col-xs-6 col-md-3">
      <div class="thumbnail" style="width:200px; height:200px; border-color:<?=($val['is_wild'])?'red':'green'?>">
        <img style="max-width:190px; max-height:145px" src="/app/webroot<?=$val['fam_dir'].$val['filename']?>" onclick="large_image(this)">
        <div class="caption">
          <span style="font-size:12px">
            <a href="/search/view?dir=<?=APP.'webroot'.$val['fam_dir'].$val['filename']?>"><?=$val['genus']." ".$val['species']?></a>
            <br/><?=date("M d, Y",strtotime($val['date_found']))?>
          </span>
        </div>
      </div>
    </div>
<?php } ?>
  </div>
</div>
<?php } ?>

<script>
  function large_image(image){
    $("#big_image").attr('src',image.getAttribute("src"));
  }
</script>
