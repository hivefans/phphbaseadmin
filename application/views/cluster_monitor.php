<div class="span8">
    <legend>Cluster Monitor</legend>
    <div class="controls">
              <select id="clustersel">
                <option>choose cluster</option>                
              </select>
    </div>
   <table id="clustertb" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Node IP</th>
          <th>Online</th>
          <th>Mode</th>
          <th>connections</th>
          <th>sent</th>
          <th>received</th>
          <th>outstanding</th>
          <th>Node count</th>
          <th>zxid</th>
          <th>Latency</th>
        </tr>
      </thead>
      <tbody>
       
      </tbody>
    
    
    </table>
</div>


<script>
var clusterinfo=<?php echo $clusterinfo;?>;
var selhtml="";
$.each(clusterinfo,function(index,content){
    selhtml+="<option value="+content.serverlist+">"+content.clustername+"</option>";        
})
$("#clustersel").append(selhtml);

function getstat()
{
   var checkText=$("#clustersel").find("option:selected").text();
    var serverlist=$("#clustersel").val();
    var serverarr=new Array();
    var tbhtml="";
    serverarr=serverlist.split(",");    
    for(i=0;i<serverarr.length;i++)
    {
        serverip=serverarr[i].split(":")[0];
        serverport=serverarr[i].split(":")[1];
        var okurl="<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=stat&server="+serverip+"&port="+serverport+"&command=ruok";        
        var serveronline="";
        $.ajax({
           url:okurl,
           async:false,
           type:"GET",
           success:function(msg){ 
            serveronline = msg;
            }   
        });
        if(serveronline=="imok")
         {
            onlinehtml='<button class="btn btn-mini btn-success" type="button">online</button>';
         }
         else
         {
            onlinehtml='<button class="btn btn-mini btn-danger" type="button">offline</button>';
         }
         var staturl="<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=stat&server="+serverip+"&port="+serverport+"&command=stat"; 
         var serverstat="";
         $.ajax({
           url:staturl,
           async:false,
           type:"GET",
           success:function(msg){ 
              var result=eval ("(" + msg + ")");
              serverstat="<td>"+result.mode+"</td><td>"+result.connection+"</td><td>"+result.sent+"</td><td>"+result.received;
              serverstat+="</td><td>"+result.outstand+"</td><td>"+result.nodecount+"</td><td>"+result.zxid+"</td><td>"+result.latency+"</td>";
              
            }   
        });
         var zoodataurl="<?php echo $this->config->base_url();?>index.php/monitor/nodestat";
         tbhtml+="<tr><td><a href='"+zoodataurl+"'>"+serverip+"</a></td>"+"<td>"+onlinehtml+"</td>"+serverstat+"</tr>";
    }
    $("#clustertb tbody").html(tbhtml);
}
$("#clustersel").change(function(){
    getstat();
})
//setInterval(function()
//	{
//	  getstat();	
//	},5000);

</script>