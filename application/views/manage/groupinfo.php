<div id="content" class='container_12 rmonitor'>
  	
	<div id="title">
        <div class="grid_6" id="title-header">Group List</div>
        <div class="rightgrid"><a class="btn btn-success" href="<?php echo site_url("manage/addgroup")?>">Add a new</a></div>
        <ul class="" id="title-menu-holder"></ul>
    </div>
   

   <div id="sensors" class='boxit'>
	
	<table class='default' border="0" cellspacing="0" cellpadding="0">
		<tr>		
			<th>ID</th>		
			<th>Group</th>				
			<th class="admin-sensor-actions"></th>
		</tr>
		<tbody class='sensors'>
        
        <?php
              foreach($groups as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['name'].'</td>';                              
                echo '<td class="crud-actions">
                  <a href="'.site_url("manage").'/groupedit/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a href="javascript:void(0);" class="btn btn-danger" onclick="showdelgroup('.$row["id"].');">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>
		</tbody>
	</table>
	
  </div>
</div>  

<div class="modal hide" id="delgroupdiv">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>delete group</h3>
  </div>
  <div class="modal-body">
    Are you sure delete this group?
  </div>
  <div class="modal-footer">    
    <a href="#" id="delgroup" class="btn btn-danger">delete</a>
    <a href="#" class="btn" data-dismiss="modal">close</a>
  </div>
</div> 
<script>
function showdelgroup(id){
     $('#delgroupdiv').modal({
         backdrop:true
      });
    url="<?php site_url("manage")?>"+"deletegroup/"+id;
    $("#delgroup").attr("href",url);  
    
}
$(".grant").each(function(){
  if($(this).text().length>=50)
    {
      $(this).html("<a href='#' title='"+$(this).text()+"'>"+$(this).text().substr(0,50)+"...</a>");  
    }
});
</script>