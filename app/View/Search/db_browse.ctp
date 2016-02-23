<h2><b><?=$type?></b></h2>
<table class="table table-condensed">
<?php foreach($list as $val){ ?>
<tr><td><a href="/search/browse/<?=$val['division']?>/<?=$val['id']?>"><?=$val['name']?></a></td></tr>
<?php } ?>
</table>