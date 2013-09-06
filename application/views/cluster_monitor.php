
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
          <th>Role</th>
          <th>Watches</th>
          <th>connections</th>
          <th>sent</th>
          <th>received</th>         
          <th>Node count</th>
          <th>zxid</th>
          <th>Latency</th>
          <th>Trend</th>
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
function showchart(ip,port)
{
   $('#zktrenddiv').modal({
         backdrop:true
      });     
           var chart;
           var chartData = [];            
                // generate some random data first
                generateChartData();

                // SERIAL CHART    
                chart = new AmCharts.AmSerialChart();
                chart.pathToImages = "<?php echo $this->config->base_url();?>js/images/";
                chart.zoomOutButton = {
                    backgroundColor: '#000000',
                    backgroundAlpha: 0.15
                };
                chart.dataProvider = chartData;
                chart.categoryField = "date";

                // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
                chart.addListener("dataUpdated", zoomChart);

                // AXES
                // category                
                var categoryAxis = chart.categoryAxis;
                categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
                categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
                categoryAxis.dashLength = 2;
                categoryAxis.gridAlpha = 0.15;
                categoryAxis.axisColor = "#DADADA";

                // first value axis (on the left)
                var valueAxis1 = new AmCharts.ValueAxis();
                valueAxis1.axisColor = "#FF6600";
                valueAxis1.axisThickness = 2;
                valueAxis1.gridAlpha = 0;
                chart.addValueAxis(valueAxis1);

                // second value axis (on the right) 
                var valueAxis2 = new AmCharts.ValueAxis();
                valueAxis2.position = "right"; // this line makes the axis to appear on the right
                valueAxis2.axisColor = "#FCD202";
                valueAxis2.gridAlpha = 0;
                valueAxis2.axisThickness = 2;
                chart.addValueAxis(valueAxis2);

                // third value axis (on the left, detached)
                valueAxis3 = new AmCharts.ValueAxis();
                valueAxis3.offset = 50; // this line makes the axis to appear detached from plot area
                valueAxis3.gridAlpha = 0;
                valueAxis3.axisColor = "#B0DE09";
                valueAxis3.axisThickness = 2;
                chart.addValueAxis(valueAxis3);

                // GRAPHS
                // first graph
                var graph1 = new AmCharts.AmGraph();
                graph1.valueAxis = valueAxis1; // we have to indicate which value axis should be used
                graph1.title = "red line";
                graph1.valueField = "visits";
                graph1.bullet = "round";
                graph1.hideBulletsCount = 30;
                chart.addGraph(graph1);

                // second graph                
                var graph2 = new AmCharts.AmGraph();
                graph2.valueAxis = valueAxis2; // we have to indicate which value axis should be used
                graph2.title = "yellow line";
                graph2.valueField = "hits";
                graph2.bullet = "square";
                graph2.hideBulletsCount = 30;
                chart.addGraph(graph2);

                // third graph
                var graph3 = new AmCharts.AmGraph();
                graph3.valueAxis = valueAxis3; // we have to indicate which value axis should be used
                graph3.valueField = "views";
                graph3.title = "green line";
                graph3.bullet = "triangleUp";
                graph3.hideBulletsCount = 30;
                chart.addGraph(graph3);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorPosition = "mouse";
                chart.addChartCursor(chartCursor);

                // SCROLLBAR
                var chartScrollbar = new AmCharts.ChartScrollbar();
                chart.addChartScrollbar(chartScrollbar);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.marginLeft = 110;
                chart.addLegend(legend);

                // WRITE
                chart.write("zkchartdiv");
           

            // generate some random data, quite different range
            function generateChartData() {
                var firstDate = new Date();
                firstDate.setDate(firstDate.getDate() - 50);

                for (var i = 0; i < 50; i++) {
                    var newDate = new Date(firstDate);
                    newDate.setDate(newDate.getDate() + i);

                    var visits = Math.round(Math.random() * 40) + 100;
                    var hits = Math.round(Math.random() * 80) + 500;
                    var views = Math.round(Math.random() * 6000);

                    chartData.push({
                        date: newDate,
                        visits: visits,
                        hits: hits,
                        views: views
                    });
                }
            }

            // this method is called when chart is first inited as we listen for "dataUpdated" event
            function zoomChart() {
                // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                chart.zoomToIndexes(10, 20);
            }
               
}

</script>
<div class="modal hide" id="zktrenddiv" style="width:800px;">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>zookeeper stat trend</h3>
  </div>
  <div class="modal-body">
     <div id="zkchartdiv" style="width: 100%; height: 400px;"></div>
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
                      serverstat="<td>"+result.mode+"</td><td>"+result.watches+"</td><td>"+result.connection+"</td><td>"+result.sent+"</td><td>"+result.received;
                      serverstat+="</td><td>"+result.nodecount+"</td><td>"+result.zxid+"</td><td>"+result.latency+"</td>";
                      
                    }   
                });
                 
                tbhtml+="<tr><td><a href='javascript:;' onclick=getchild('"+serverip+"','"+serverport+"','/');>"+serverip+" <i class='icon-zoom-in'></i></a></td>"+"<td>"+onlinehtml+"</td>"+serverstat;
                tbhtml+="<td><a href='javascript:;' onclick=showchart('"+serverip+"','"+serverport+"');><i class='icon-signal'></i></a></td></tr>";           
              }
             else
             {
                onlinehtml='<button class="btn btn-mini btn-danger" type="button">offline</button>';
                serverstat="<td>no mode</td><td>0</td><td>0</td><td>";
                serverstat+="0</td><td>0</td><td>0</td><td>no result</td><td>0</td>";
                tbhtml+="<tr><td>"+serverip+"</td>"+"<td>"+onlinehtml+"</td>"+serverstat;
                tbhtml+="<td><i class='icon-signal'></i></td></tr>";
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
//setInterval(function()
//	{
//	  getstat();	
//	},5000);
//
</script>