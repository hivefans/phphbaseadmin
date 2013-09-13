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
        html += '<a href="javascript:;" onclick=getrecord("'+content+'") title="'+content+'">'+ tablename + '</a>';		
		html += '</td></tr>';
	});
	$('#tablenames').append(html);   
    $("div.holder").jPages({
      containerID : "tablenames",
      previous : "←", 
      next : "→",
      perPage : 15,
      delay : 20 
    });
   
});

$.get("<?php echo $this->config->base_url();?>index.php/tables/gethbaseinfo",function(data){
    $('#rcontent').html(data);
});
function getrecord(tablename)
{
    $.get("<?php echo $this->config->base_url();?>index.php/tables/listtablerecords/"+tablename,function(data){
       $('#rcontent').html(data); 
    });
}


</script>

<div class="span2 tbdiv">
	<table class="table table-hover">
     <tbody id="tablenames">
     </tbody>		
	</table>  
    <div class="holder"></div>
</div>

<div id="rcontent" class="span9 rcontent">
<div id="loading" align="center"><img src="<?php echo $this->config->base_url();?>img/ajax-loader.gif" ></div>
</div>
