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
<ul class="breadcrumb span8" id="serverip"></ul>
<div class="span8">
    
    <div class="span3">       
       <table id="zookeepertb" class="table table-striped table-bordered">
          <thead>
            <tr><th>Children</th></tr>
          </thead> 
          <tbody>
          </tbody>         
       </table>    
    </div>
   
    <div class="span5">       
       <table id="nodestattb" class="table table-striped table-bordered">
          <thead>
            <tr><th>Node Stat</th><th>Value</th></tr>
          </thead> 
          <tbody>
          </tbody>         
       </table>    
    </div>

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
   if(checkText!="choose cluster")
     {   
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
                 
                tbhtml+="<tr><td><a href='javascript:;' onclick=getchild('"+serverip+"','"+serverport+"','/');>"+serverip+" <i class='icon-zoom-in'></i></a></td>"+"<td>"+onlinehtml+"</td>"+serverstat+"</tr>";           
              }
             else
             {
                onlinehtml='<button class="btn btn-mini btn-danger" type="button">offline</button>';
                serverstat="<td>no mode</td><td>0</td><td>0</td><td>";
                serverstat+="0</td><td>0</td><td>0</td><td>no result</td><td>0</td>";
                tbhtml+="<tr><td>"+serverip+"</td>"+"<td>"+onlinehtml+"</td>"+serverstat+"</tr>";
             }
             
        }
        $("#clustertb tbody").html(tbhtml);
     }
     else
      {
         $("#clustertb tbody").html("");
      }   
}
function getchild(serverip,serverport,path)
{
    var childpath="";
    var zootbhtml="";
    getchildurl="<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=getchild&server="+serverip+"&port="+serverport+"&path="+path;
    $.ajax({
           url:getchildurl,
           async:false,
           type:"GET",
           success:function(msg){ 
            paths = msg;
            }   
        });
    childpath=paths.split(",");        
    for(data in childpath)
     {
        childurl=path+childpath[data];
        zootbhtml+="<tr><td><a href='javascript:;' onclick=getchild('"+serverip+"','"+serverport+"','"+path+childpath[data]+"/"+"');>"+childpath[data]+"</a></td></tr>";
     }    
    serverhtml="<li><a href='javascript:;' onclick=getchild('"+serverip+"','"+serverport+"','/');>"+serverip+"</a></li>";
    patharr=path.split("/");
    var pathurl="";
    var pathhtml="";
    for (index in patharr)
      {
         pathurl+=patharr[index]+"/";
         pathhtml+='<li><i class="icon-chevron-right"></i><a href="javascript:;" onclick=getchild("'+serverip+'","'+serverport+'","'+pathurl+'");>'+patharr[index]+'</a></li>'
      }
    
    $("#serverip").html(serverhtml+pathhtml);
    $("#zookeepertb tbody").html(zootbhtml);
    
    nodedataurl="<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=get&server="+serverip+"&port="+serverport+"&path="+path;
    $.ajax({
           url:nodedataurl,
           async:false,
           type:"GET",
           success:function(msg){ 
            nodedata = msg;
            }   
        });
     nodestathtml=""; 
     nodedatajson=eval('('+nodedata+')');  
     $.each(nodedatajson,function(key,value){
         nodestathtml+="<tr><td>"+key+"</td><td>"+value+"</td><tr>";
     });  
     $("#nodestattb tbody").html(nodestathtml);
    
}
$("#clustersel").change(function(){
    getstat();
    $("#serverip").html("");
    $("#zookeepertb tbody").html("");
    $("#nodestattb tbody").html("");
})
setInterval(function()
	{
	  getstat();	
	},5000);

</script>