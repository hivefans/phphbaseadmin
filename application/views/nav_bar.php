<!--Nav bar area-->

<div class="navbar">
	<div class="navbar-inner">
		<a class="brand" href="<?php echo $this->config->base_url();?>"><?php echo $common_title;?></a>
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="nav-collapse collapse  navbar-responsive-collapse">
				<ul class="nav">
				<li class="dropdown" <?php if($this->router->class == "tables"){ echo "class=\"active\"";}?>>
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $common_table_list;?> <b class="caret"></b></a>
				   <ul class="dropdown-menu">
            	    <li><a href="<?php echo $this->config->base_url();?>index.php/tables/index/"><?php echo $common_table_view;?></a></li>
                    <li><a href="javascript:;" onclick="showaddtable();"><?php echo $common_create_table;?></a></li>
                    <li><a href="javascript:;" onclick="showdeltable();"><?php echo $common_table_deltable;?></a></li>   
                      </ul>
                </li>
				<li class="dropdown" <?php if($this->router->class == "monitor"){ echo "class=\"active\"";}?>>
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $common_monitor;?>  <b class="caret"></b></a> 
				   <ul class="dropdown-menu bottom-up pull-right">
            	    <li><a href="<?php echo $this->config->base_url();?>index.php/monitor/zookeeper/">ZooKeeper Monitor</a></li>
                    <li><a href="<?php echo $this->config->base_url();?>index.php/monitor/hbase/">Hbase Monitor</a></li>
                       
                      </ul>
				</li>
                
                	<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">System  <b class="caret"></b></a> 
				   <ul class="dropdown-menu bottom-up pull-right">
                   <li><a href="<?php echo $this->config->base_url();?>index.php/manage/userinfo">user manage</a></li> 
                   
            	    <li><a href="<?php echo $this->config->base_url();?>index.php/manage/logout">logout</a></li>                   
                       
                      </ul>
				</li>
                
				</ul>
			</div>
		</div>
	</div>
</div>
<!--Nav bar area end-->

<!--add table div -->
<script>
function showaddtable()
{
   $('#addtablediv').modal({
         backdrop:true
      }) 
}
function showdeltable()
{
   $('#deltabdiv').modal({
         backdrop:true
      });
   $('#chkAll').attr("checked",false); 
}

$(function() {   
     var scntDiv = $('#cols');
     var i = $('#cols').size() + 1;
     colhtml='<li>';
     colhtml+='<label for="columnfamily" class="required">column family</label>';
     colhtml+='<input type="text" id="column" name="column[]" class="input-medium" placeholder="column family" required/>';
     colhtml+=' <a class="btn btn-small" href="#" id="remScnt"><i class="icon-minus-sign"></i>remove</a>';
     colhtml+='<br /><br /><label for="maxversions" class="required">maxversions</label>';
     colhtml+='<input type="text" id="maxversions" name="maxversions[]" min="0" value="3" class="input-medium maxversions" placeholder="maxversions"/>';
     colhtml+='<br /><br /><label for="compression" class="required">compression</label>';          
     colhtml+='<select name="compression[]" id="compression" class="input-medium">';         
     colhtml+='<option>NONE</option><option>SNAPPY</option><option>LZO</option></select></li>';            
        
     $('#addinput').live('click', function() {
                    $(colhtml).appendTo(scntDiv);
                    i++;
                    return false;
            });
            
            $('#remScnt').live('click', function() { 
                    if( i > 2 ) {
                            $(this).parents('li').remove();
                            i--;
                    }
                    return false;
            });
    
     $("#bt_form").validate({
        submitHandler: function(form) 
           {
              var column="";
              $("input[name='column\\[\\]']").each(function(){
                 column+=$(this).val()+",";
               });
              var maxversions="";
               $("input[name='maxversions\\[\\]']").each(function(){
                 maxversions+=$(this).val()+",";
               });
              var compression="";
               $("select[name='compression\\[\\]']").each(function(){
                 compression+=$(this).val()+",";
               });
              column=column.substr(0,column.length-1);
              maxversions=maxversions.substr(0,maxversions.length-1); 
              compression=compression.substr(0,compression.length-1);                
              $.ajax({
                   url:"<?php echo $this->config->base_url();?>index.php/tables/addtable",
                   type: "POST",                   
                   data: ({tablename:$("#tbname").val(),column:column,maxversions:maxversions,compression:compression}),
                   beforeSend:Request,
                   success:Response,
                }); 
                function Request(){
                  //alert(column);
               $('#addtable').attr('disabled',true);   
               $('#addtable').html('<img src=<?php echo $this->config->base_url();?>/img/loading.gif /> create');
                }
                function Response(data){
                    $('#addtable').attr('disabled',false);
                   $('#addtable').html("create");
                   alert(data);
                   location.reload();
                }                 
           }
     });    
     
}); 

$.getJSON('<?php echo $this->config->base_url();?>index.php/tables/tablelist/', function(json){
	var names = json.table_names;
	var html="";
	$('#tbnames tbody').empty();
	$.each(names,function(index, content){
		html += '<tr><td id="'+content+'">';
		html += content;
		html += '</td><td><input type="checkbox" value="'+content+'" name="selected_table_name[]"> </td></tr>';
	});
	$('#tbnames tbody').append(html);
         
     
});

function CheckUnCheckAll()
	{
		var checkAll = document.getElementById('chkAll');
		var checkBox = document.getElementsByName("selected_table_name[]");
		var arr = checkBox;
		if(checkAll.checked==true){
			checkAll.value = "";
			for(i=0; i<arr.length; i++){
				arr[i].checked = true;
				checkAll.value += arr[i].value + ';';
			}
			var json = '{"tables":"' + checkAll.value.substring(0, (checkAll.value.length-1)) + '"}';            			
		}
		if(checkAll.checked==false){
			for(i=0; i<arr.length; i++){
				if(arr[i].checked == true){
					arr[i].checked = false;
				}else {
					arr[i].checked = true ;
				}
			}
		}
	}
    
function ConfirmDropTables()
{
	var checkBox = document.getElementsByName("selected_table_name[]");
	var checked = "";
	for (i=0; i<checkBox.length; i++)
	{
		if(checkBox[i].checked == true)
		{
			checked += checkBox[i].value + ';';
		}
	}
	var html = checked.substring(0, (checked.length-1));
    var url="<?php echo $this->config->base_url();?>index.php/tables/delalltable/";
	$.ajax({
      type: 'POST',
      url: url,
      data: ({tables:html}),
      beforeSend:Request,
      success: success      
    });
    function Request()
    {
       $('#delall').attr('disabled',true);   
       $('#delall').html('<img src=<?php echo $this->config->base_url();?>/img/loading.gif /> delete');   
    }
    function success(data)
    {
      $('#delall').attr('disabled',false);
      $('#delall').html('delete');
      alert(data);
      location.reload();  
    }
    
    
    
}    
</script>
<style scoped>
#modalbody ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
 }
 #modalbody li {
     margin: 10px 0 0 0;
 }
 #modalbody label {
    display: inline-block;
    width: 120px;
     text-align: right;
  }
 </style>
<div class="modal hide " id="addtablediv">
     <form class="form-horizontal" id="bt_form">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $common_create_table;?></h3>
      </div>
      
      <div class="modal-body" id="modalbody">
          <ul>
                <li>
                    <label for="tablename" class="required">table name</label>
                    <input type="text" id="tbname" name="tbname" class="input-medium" placeholder="table name" required/>
                </li>
                <div id="cols">
                
                <li>
                    <label for="columnfamily" class="required">column family</label>
                    <input type="text" id="column" name="column[]" class="input-medium" placeholder="column family" required/>
                    <a class="btn btn-small" id="addinput"><i class="icon-plus-sign"></i>add</a>
                    <br /><br />
                     <label for="maxversions" class="required">maxversions</label>
                    <input type="text" id="maxversions" name="maxversions[]" min="0" value="3" class="input-medium maxversions" placeholder="maxversions" required/>
                    <br /><br />
                    <label for="compression" class="required">compression</label>
                   <select name="compression[]" id="compression" class="input-medium">
                        <option>NONE</option>
                        <option>SNAPPY</option>
                        <option>LZO</option>                       
                    </select> 
                </li>
          </ul>
      </div>
      <div class="modal-footer"> 
        <button type="btn" id="addtable" class="btn btn-primary">Create</button>         
        <a href="javascript:;" class="btn" data-dismiss="modal">Cancel</a>
      </div> 
      </form>
</div>
<!--add table div end -->

<!-- delete table div-->
<div class="modal hide" id="deltabdiv">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>delete table</h3>
  </div>
  <div class="modal-body">
    <table id="tbnames" class="table table-hover table-bordered">
       <thead>
       <tr>
      <th>table name</th>
      <th><input type="checkbox" onclick="CheckUnCheckAll()" id="chkAll" value=""> All</th>
      </tr>
      </thead>
     <tbody>
     </tbody>
    </table>
  </div>
  <div class="modal-footer">    
    <a href="#" id="delall" class="btn btn-danger" onclick="ConfirmDropTables();">delete</a>
    <a href="#" class="btn" data-dismiss="modal">close</a>
  </div>
</div>
<!-- delete table div end-->