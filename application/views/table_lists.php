<script>
$.getJSON('<?php echo $this->config->base_url();?>index.php/tables/tablelist/', function(json){
	var names = json.table_names;
	var html="";
	$('#tablenames').empty();
	$.each(names,function(index, content){
		html += '<tr><td id="'+content+'"> <i class="icon-list-alt"></i> ';
		html += '<a href="<?php echo $this->config->base_url();?>index.php/tables/listtablerecords/' + content + '">'+ content +'</a>';
		html += '</td></tr>';
	});
	$('#tablenames').append(html);   
     
});


</script>
<div class="span2" style="height: 600px;">
	<table class="table table-hover" id="tablenames">		
	</table>   
</div>
