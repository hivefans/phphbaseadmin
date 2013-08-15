<div class="span8">
<div id="container" style="height: 400px; margin: 0 auto"></div>
</div>

<script>
var connection=0;
var nodecount=0;
var outstand=0;
$(function () {
    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
    
        var chart;
        $('#container').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function() {
                        var connser = this.series[0];
                        var nodecountser=this.series[1];
                        var outstandser=this.series[2];
                        setInterval(function() {
                            var x1 = (new Date()).getTime(), // current time                                
                                y1 = connection;
                            var x2 = (new Date()).getTime(), // current time                                
                                y2 = nodecount;
                            var x3 = (new Date()).getTime(), // current time                                
                                y3 = outstand;                                 
                            connser.addPoint([x1, y1], true, true);
                            nodecountser.addPoint([x2, y2], true, true);
                            outstandser.addPoint([x3, y3], true, true);                            
                            $.getJSON("<?php echo $this->config->base_url();?>index.php/monitor/getserverinfo?qry=stat&server=192.168.205.6&port=2181&command=stat",function(data){
                               connection=parseInt(data.connection);
                               nodecount=parseInt(data.nodecount); 
                               outstand=parseInt(data.outstand);                              
                            });
                             
                        }, 1000);
                    }
                }
            },
            title: {
                text: 'zookeeper monitor'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
           yAxis: {
				title: {
					text: 'value'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}
				]
			},
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: true
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: 'connection',                                 
                data: (function() {                  
                    var conndata = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -9; i <= 0; i++) {                        
                        conndata.push({
                            x: time + i * 1000,
                            y: 0
                        });
                    }
                    return conndata;                    
                })()
               },
               {
                name: 'nodecount',
                data: (function() {                  
                    var nodecountdata = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -9; i <= 0; i++) {                        
                        nodecountdata.push({
                            x: time + i * 1000,
                            y: 0
                        });
                    }
                    return nodecountdata;                    
                })()
               },
               {
                name: 'outstand',
                data: (function() {                  
                    var outstanddata = [],
                        time = (new Date()).getTime(),
                        i;
    
                    for (i = -9; i <= 0; i++) {                        
                        outstanddata.push({
                            x: time + i * 1000,
                            y: 0
                        });
                    }
                    return outstanddata;                    
                })()
               }
            ]
        });
    });
    
});
</script>