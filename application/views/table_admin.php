<div id="content">
<div class="row">  
  <div class="span4"></div>
  
  <div class="span4">
   <h2>phphbaseadmin</h2>
  <img src="<?php echo $this->config->base_url();?>/img/hbase.png" />   
  </div>
  
</div>
<div>
<table class="table table-striped table-bordered table-condensed">
     <thead>
    <tr>
      <th>Attribute Name</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>ServerName</td>
      <td><?php echo $ServerName[0]?> ,<?php echo $ServerName[1]?></td>
    </tr>
    <tr>
      <td>version</td>
      <td><?php echo($version)?></td>
    </tr>
    <tr>
      <td>zookeeper_quorum</td>
      <td><?php echo($ZookeeperQuorum)?></td>
    </tr>
    <tr>
      <td>DeadRegionServers</td>
      <td><?php echo implode('',$DeadRegionServers)?> </td>
    </tr>
    <tr>
      <td>AverageLoad</td>
      <td><?php echo $AverageLoad?> </td>
    </tr>
    <tr>
      <td>MasterStartTime</td>
      <td><?php echo date('Y-m-d H:i:s',$MasterStartTime)?> </td>
    </tr>
    <tr>
      <td>live_regionservers</td>
      <td><?php echo $live_regionservers?> </td>
    </tr>
    <tr>
      <td>Coprocessors</td>
      <td><?php echo implode('',$Coprocessors)?> </td>
    </tr>
  </tbody>   
   </table>
</div>
</div>
</div>