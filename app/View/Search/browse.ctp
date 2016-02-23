<h3>Folder Browse</h3>
<form method="POST" id="folder_form">
  <table class="table table-condensed">
<?php foreach($list as $val){ ?>
<?php if (substr($val,0,1) != "." && substr($val,0,3) != "aa "){ ?>
    <tr>
      <td><span class="clickable" style="color:blue" onclick="form_submit('<?=$val?>')"><?=array_shift(explode(".",$val))?></span></td>
    </tr>
<?php } } ?>
  </table>
  <input type="hidden" name="old_dir" id="old_dir" value="<?=$dir?>">
  <input type="hidden" name="new_dir" id="new_dir" value="">
</form>

<script>
  
  function form_submit(folder_name){
    if (folder_name == "plants" || folder_name == "fungi" || folder_name == "animals")
      folder_name = "zz "+folder_name;
    $("#new_dir").val(folder_name);
    if (!(<?=$is_image?>))
      $("#folder_form").submit();
  }
  
  $(".clickable").hover(function(){
    $(this).css("text-decoration","underline");
    $(this).css("cursor","pointer");
  }, function(){
    $(this).css("text-decoration","none");
    $(this).css("cursor","default");
  });
  
</script>