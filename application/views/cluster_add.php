<div class="span8">
<form class="form-horizontal">
    <legend>ZooKeeper Cluster</legend>
    <div class="control-group">
        <label class="control-label" for="focusedInput">Cluster Name</label>
        <div class="controls">
        <input class="input-xlarge focused" id="clustername" name="clustername" type="text" value="">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Server List</label>
        <div class="controls">
        <input class="input-xlarge" id="serverlist" name="serverlist" type="text" value="">
        </div>
    </div>
    <div class="control-group">
        <div class="span3"></div>
        <button id="addcluster" type="button" class="btn btn-primary">submit</button>        
    </div>
</form>

<table id="clustertb" class="table table-striped table-bordered">
          <thead>
            <tr><th>cluster name</th><th>serverlist</th><th>operation</th></tr>
          </thead> 
          <tbody>
          </tbody>         
</table> 


<div class="modal hide" id="delmodal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>delete cluster</h3>
  </div>
  <div class="modal-body">
    Are you sure delete <Strong id="cluname"></Strong> ?
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">cancel</a>
    <a href="#" class="btn btn-primary" id="delbtn">delete</a>
  </div>
</div>

</div>


<script>
var clusterinfo=<?php echo $clusterinfo;?>;
var clustertbhtml="";
$.each(clusterinfo,function(index,content){
    clustertbhtml+="<tr><td>"+content.clustername+"</td><td>"+content.serverlist+"</td>"; 
    clustertbhtml+="<td><button class='btn btn-primary btn-small' type='button' onclick=modcluster('"+content.clustername+"','"+content.serverlist+"');>modify</button>"; 
    clustertbhtml+="&nbsp;&nbsp;&nbsp;&nbsp;<button class='btn btn-danger btn-small' type='button' onclick=delconfirm('"+content.clustername+"');>delete</button></td></tr>"      
})
$("#clustertb tbody").html(clustertbhtml);

function modcluster(clustername,serverlist)
{
    $("#clustername").val(clustername);
    $("#serverlist").val(serverlist);
    
}
function delconfirm(clustername)
{
    $('#delmodal').modal('show');
    $("#cluname").html(clustername);
}
$("#delbtn").click(function(){    
               $.ajax({
                   url:"<?php echo $this->config->base_url();?>index.php/monitor/delcluster",
                   type: "POST",                   
                   data: ({clustername:$("#cluname").html()}),
                   beforeSend:Request,
                   success:Response,
                }); 
                function Request(){                  
               $(this).attr('disabled',true);   
               $(this).html('<img src=<?php echo $this->config->base_url();?>/img/loading.gif /> delete');
                }
                function Response(data){
                    $(this).attr('disabled',false);
                   $(this).html("delete");
                   alert(data);
                   location.reload();                  
                }  
    
})  




   $("#addcluster").click(function(){
         $.ajax({
                   url:"<?php echo $this->config->base_url();?>index.php/monitor/addcluster",
                   type: "POST",                   
                   data: ({clustername:$("#clustername").val(),serverlist:$("#serverlist").val()}),
                   beforeSend:Request,
                   success:Response,
                }); 
                function Request(){                 
               $('#addcluster').attr('disabled',true);   
               $('#addcluster').html('<img src=<?php echo $this->config->base_url();?>/img/loading.gif />submit');
                }
                function Response(data){
                    $('#addcluster').attr('disabled',false);
                   $('#addcluster').html("submit");
                   alert(data);
                   location.reload();                  
                }  
    
   })

</script>
