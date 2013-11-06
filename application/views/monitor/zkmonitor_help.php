<div class="span8">
  
  <legend><span class="badge badge-info">1</span>  配置zookeeper集群</legend>
  <div>首先配置zookeeper集群，选择cluster config菜单，然后添加集群名称，集群各个节点ip与端口，节点之间用逗号分隔。</div>
  
  <legend><span class="badge badge-info">2</span>  查看zookeeper状态</legend>

  <div>选择Cluster Monitor菜单，从下拉菜单中选取配置好的zookeeper集群名称,即可看到各个节点的参数数值。点击各个节点的ip地址可以查看到
  该节点类似于文件系统的数据结构。点击图标 <i class='icon-zoom-in'></i> 可以查看该节点连接客户端详细信息。点击<i class='icon-signal'></i>
  图标可以查看该节点中的 参数watch、connection、sent的可视化图表，每隔5秒钟数据刷新一次。
  </div>
  <br>
  <legend><span class="badge badge-info">3</span>  zookeeper参数说明</legend>
  <div>
    <table class="table table-bordered table-striped table-hover table-condensed">
        <tr>
        	<td>字段名称</td><td>字段含义</td>
        </tr>
        <tr>
            <td>czxid</td><td>ZNode创建时的事物ID</td>
        </tr>
        <tr>
            <td>mzxid</td><td>ZNode最后修改时的事务ID </td>
        </tr>
        <tr>
            <td>ctime</td><td>节点创建时间（毫秒数） </td>
        </tr>
        <tr>           
            <td>mtime</td><td>节点修改时间（毫秒数）</td>                   
        </tr>
         <tr>           
            <td>version</td><td>节点数据的次数</td>                   
        </tr>
        <tr>           
            <td>cversion</td><td>子节点数据修改的次数</td>                              
        </tr>
        <tr>           
            <td>aversion</td><td>ZNode节点访问控制列表的修改次数</td>                                        
        </tr>
        <tr>           
            <td>ephemeralOwner</td><td>如果这个节点是临时节点， 该字段表示节点创建者的sessionId，否则为0</td> 
        </tr>
         <tr>           
            <td>Datalength</td><td>数据长度</td>                                                   
        </tr>
         <tr>           
            <td>numChildren</td><td>ZNode子节点的个数</td>
        </tr>
    </table>
  </div>
</div>