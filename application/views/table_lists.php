<script>
$.getJSON('<?php echo $this->config->base_url();?>index.php/tables/tablelist/', function(json){
	var names = json.table_names;
	var html="";
	$('#tablenames').empty();
	$.each(names,function(index, content){
	   var tablename="";
	   if(content.length>22)
        {
           tablename=content.substr(0,22)+"...";
        }
        else tablename=content;
       
       
		html += '<tr><td id="'+content+'"> <i class="icon-list-alt"></i> ';
		html += '<a href="<?php echo $this->config->base_url();?>index.php/tables/listtablerecords/' + content + '" title="'+content+'">'+ tablename +'</a>';
		html += '</td></tr>';
	});
	$('#tablenames').append(html);   
     
});


</script>
<div class="span2" style="height: 600px;">
	<table class="table table-hover" id="tablenames">		
	</table>   
</div>
