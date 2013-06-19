<div class="row">
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
        <button id="addcluster" type="button" class="btn btn-primary">Add Cluster</button>        
    </div>
</form>
</div>
</div>
<script>
   $("#addcluster").click(function(){
         $.ajax({
                   url:"<?php echo $this->config->base_url();?>index.php/monitor/addcluster",
                   type: "POST",                   
                   data: ({clustername:$("#clustername").val(),serverlist:$("#serverlist").val()}),
                   beforeSend:Request,
                   success:Response,
                }); 
                function Request(){
                  //alert(column);
               $('#addcluster').attr('disabled',true);   
               $('#addcluster').html('<img src=<?php echo $this->config->base_url();?>/img/loading.gif /> Add Cluster');
                }
                function Response(data){
                    $('#addcluster').attr('disabled',false);
                   $('#addcluster').html("Add Cluster");
                   alert(data);                  
                }  
    
   })

</script>
