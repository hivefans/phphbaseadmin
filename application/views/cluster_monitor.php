
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
//get ajax result
function getstatresult(url)
{  
   var result="";
   $.ajax({
         url:url,
         async:false,
         type:"GET",
         success:function(msg){ 
           result=msg;     
         }   
    });
   return result; 
}

//get zookeeper stat 
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
                 var result=getstatresult(staturl);                 
                 result=eval ("(" + result + ")");
                 serverstat="<td>"+result.mode+"</td><td>"+result.watches+"</td><td>"+result.connection+"</td><td>"+result.sent+"</td><td>"+result.received;
                 serverstat+="</td><td>"+result.nodecount+"</td><td>"+result.zxid+"</td><td>"+result.latency+"</td>";                 
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

//create zookeeper stat chart
function showchart(serverip,serverport)
{
   $('#zktrenddiv').modal({
         backdrop:true
      });     
//zookeeper stat chart

var chart;
var chartData = [];
var chartCursor;
var second = 0;
var firstDate = new Date();
firstDate.setSeconds(firstDate.getSeconds() - 30);
 

// generate some random data, quite different range
function generateChartData() {
    for (second = 0; second < 30; second++) {
        var newDate = new Date(firstDate);
        newDate.setSeconds(newDate.getSeconds() + second);                     
        var visits = 1;;
        chartData.push({
            date: newDate,
            visits: visits
        });
    }
}

// create chart
    generateChartData();
    // SERIAL CHART    
    chart = new AmCharts.AmSerialChart();
    chart.pathToImages = "http://www.amcharts.com/lib/images/";
    chart.marginTop = 0;
    chart.marginRight = 10;
    chart.autoMarginOffset = 5;
    chart.zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
    };
    chart.dataProvider = chartData;
    chart.categoryField = "date";

    // AXES
    // category
    var categoryAxis = chart.categoryAxis;
    categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
    categoryAxis.minPeriod = "ss"; // our data is daily, so we set minPeriod to DD
    categoryAxis.dashLength = 1;
    categoryAxis.gridAlpha = 0.15;
    categoryAxis.axisColor = "#DADADA";

    // value                
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.axisAlpha = 0.2;
    valueAxis.dashLength = 1;
    chart.addValueAxis(valueAxis);

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.title = "red line";
    graph.valueField = "visits";
    graph.bullet = "round";
    graph.bulletBorderColor = "#FFFFFF";
    graph.bulletBorderThickness = 2;
    graph.lineThickness = 2;
    graph.lineColor = "#b5030d";
    graph.negativeLineColor = "#0352b5";
    graph.hideBulletsCount = 50; // this makes the chart to hide bullets when there are more than 50 series in selection
    chart.addGraph(graph);

    // CURSOR
    chartCursor = new AmCharts.ChartCursor();
    chartCursor.cursorPosition = "mouse";
    chart.addChartCursor(chartCursor);

    // SCROLLBAR
    var chartScrollbar = new AmCharts.ChartScrollbar();
    chartScrollbar.graph = graph;
    chartScrollbar.scrollbarHeight = 40;
    chartScrollbar.color = "#FFFFFF";
    chartScrollbar.autoGridCount = true;
    chart.addChartScrollbar(chartScrollbar);

    // WRITE
    chart.write("zkchartdiv");
    
    // set up the chart to update every second
    setInterval(function () {
        // normally you would load new datapoints here,
        // but we will just generate some random values
        // and remove the value from the beginning so that
        // we get nice sliding graph feeling
        
        // remove datapoint from the beginning
        chart.dataProvider.shift();
        
        // add new one at the end
        second++;
        var newDate = new Date(firstDate);
        newDate.setSeconds(newDate.getSeconds() + second);
        var staturl="<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=stat&server="+serverip+"&port="+serverport+"&command=stat";
        var result=getstatresult(staturl);                 
        result=eval ("(" + result + ")");
        var visits = result.watches;
        chart.dataProvider.push({
            date: newDate,
            visits: visits
        });
        chart.validateData();
    }, 3000);
  
   
   
  
}



//chart


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