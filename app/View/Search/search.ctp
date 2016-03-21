<?php if (count($post_info) || count($get_info)){ ?>
<h2>Results</h2>
<div class="well">
<?php if (!count($results)){ ?>
No matches found.
<?php }else{ ?>
Click on a column to sort:
<table class="sortable" style="width:100%; text-align:center; background-color:white; border: 1px solid black;">
  <tr class="active">
    <td><b>Name</b></td><td><b>Common Name</b></td>
    <td><b>Division</b></td><td><b>Family</b></td>
    <td><b>Found</b></td><td><b>State</b></td>
    <td><b>Country</b></td><td><b>Wild</b></td>
  </tr>
<?php foreach ($results as $key=>$val){ ?>
  <tr>
    <td><a href="/search/view?dir=<?=$val['filename']?>"><?=$key?></a></td>
    <td><?=$val['common_name']?></td>
    <td><?=$val['phydiv']?></td>
    <td><?=$val['family_name']?></td>
    <td><?=$val['date']?></td>
    <td><?=($val['state']=="XX")?"--":$val['state']?></td>
    <td><?=$val['iso']?></td>
    <td><?=$val['is_wild']?></td>
  </tr>
<?php } ?>
</table>
<?php } ?>
</div>
<?php } ?>

<h1>Search Database:</h1>
<form id="search_form" method="post" action="/search/search">
  <div class="well">
    <h4>Species Information</h4>
    <table class="table">
      <tr>
        <td><b>Scientific Name:</b></td>
        <td><input type="text" id="name" name="name"></td>
      </tr><tr>
        <td><b>Common Name:</b></td>
        <td><input type="text" id="common_name" name="common_name"></td>
      </tr><tr>
        <td><b>Wild?</b></td>
        <td>
          <input type="radio" name="is_wild" value=1> Yes<br/>
          <input type="radio" name="is_wild" value=0> No<br/>
          <input type="radio" name="is_wild" value=2 checked> Either
        </td>
      </tr><tr>
        <td><b>Division:</b></td>
        <td>
<?php foreach ($all_divisions as $val){ ?>
          <input class="plant_check" type="checkbox" name="division_<?=$val['Division']['id']?>" value=<?=$val['Division']['id']?>> <?=$val['Division']['name']?><br/>
<?php } ?>
          <br/><input class="plant_check" id="plant_checkall" type="checkbox" onchange="toggle_plants()"> <b>Check All</b>
        </td>
        <td><b>Phylum:</b></td>
        <td>
<?php foreach ($all_phyla as $val){ ?>
          <input class="animal_check" type="checkbox" name="phylum_<?=$val['Phylum']['id']?>" value=<?=$val['Phylum']['id']?>> <?=$val['Phylum']['name']?><br/>
<?php } ?>
          <br/><input class="animal_check" id="animal_checkall" type="checkbox" onchange="toggle_animals()"> <b>Check All</b>
        </td>
      </tr>
    </table>
    <br/><br/>
    <table class="table">
      <h4>Picture Information</h4>
      <tr>
        <td>From:</td>
        <td><input type="text" id="start_date" name="start_date"></td>
      </tr>
      <tr>
        <td>To:</td>
        <td><input type="text" id="end_date" name="end_date"</td>
      </tr>
    </table>
    <br/><br/>
    <table class="table">
      <span style="font-size:18px; font-weight:500; margin-top:10px; margin-bottom:10px">Location   </span>
      <select name="location" id="location">
        <option value="usa">United States</option>
        <option value="world">World</option>
      </select><br/><br/>
      <button type="button" class="btn btn-default" onclick="refresh_map()">Clear Selections</button>
      <br/><br/>
      <div id="usa_map">
        <div id="map" style="width: 600px; height: 400px"></div>
        <div id="chosen_locs"></div>
      </div>
      <br/><br/>
      <div id="world_map">
        <input type="checkbox" name="AFR" value="1"> Africa<br/>
        <input type="checkbox" name="ASI" value="1"> Asia<br>
        <input type="checkbox" name="OCE" value="1" checked> Oceania<br/>
        <input type="checkbox" name="EUR" value="1"> Europe<br/>
        <input type="checkbox" name="NAM" value="1" checked> North America<br>
        <input type="checkbox" name="SAM" value="1"> South America<br>
      </div>
    </table>
    <button type="button" class="btn btn-default" onclick="form_submit()">Submit</button>
  </div>
</form>

<script>

$(function() {
  $("#start_date").datepicker();
  $("#end_date").datepicker();
  refresh_map();
});

function refresh_map(){
  $("#map").html("");
  var map = 'us_merc_en';
  $('#map').vectorMap({
    map: map,
    zoomOnScroll: false,
    regionsSelectable: true,
    regionStyle: {
      selected: {
        fill: '#00CC66'
      }
    }
  });
}

function toggle_animals(){
  if ($('#animal_checkall').is(':checked')) {
    $(".animal_check").prop('checked', true);
  }else{
    $(".animal_check").prop('checked', false);
  }
}

function toggle_plants(){
  if ($('#plant_checkall').is(':checked')) {
    $(".plant_check").prop('checked', true);
  }else{
    $(".plant_check").prop('checked', false);
  }
}

function form_submit(){
  var html = "";
  $(".jvectormap-region").each(function (index){
    $loc_arr = $(this).data("code").split("-");
    html += '<input type=hidden name="'+$loc_arr[1]+'" value="';
    if ($(this).attr("fill") == "white"){
      html += '0">';
    }else{
      html += '1">';
    }
  });
  $("#chosen_locs").html(html);
  $("#search_form").submit();
}

</script>


