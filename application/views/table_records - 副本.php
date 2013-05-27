<link href="<?php echo $this->config->base_url();?>css/kendo.common.min.css" rel="stylesheet">
<link href="<?php echo $this->config->base_url();?>css/kendo.default.min.css" rel="stylesheet">

<script src="<?php echo $this->config->base_url();?>js/kendo.web.min.js"></script>
<script>
var rurl="<?php echo $this->config->base_url();?>index.php/tables/gettablerecords/<?php echo $tablename?>";




function showmeta()
{
    $('#metadata').modal({
         backdrop:true
      })
    $("#tab").empty();
    $("#myTabContent").empty();  
    $.getJSON('<?php echo $this->config->base_url();?>index.php/tables/getdescriptors/<?php echo $tablename?>', function(json){
         var content="";
         var i=1;
         var ultab='';
         $.each(json,function(key,value){
             key=key.replace(":","");
             if(i==1)
             {
                ultab=ultab+'<li class="active"><a href="#'+key+'" data-toggle="tab">'+key+'</a></li>';
                content=content+'<div class="tab-pane fade in active" id="'+key+'">';
             }
             else
             {
                ultab=ultab+'<li><a href="#'+key+'" data-toggle="tab">'+key+'</a></li>';
                content=content+'<div class="tab-pane fade" id="'+key+'">';
             }             
             content=content+'<table class="table table-bordered table-striped  table-hover" id="metadata">';             
             content=content+"<tr><td>maxVersions</td><td>"+value["maxVersions"]+"</td><tr>";
             content=content+"<tr><td>compression</td><td>"+value["compression"]+"</td><tr>";
             content=content+"<tr><td>inMemory</td><td>"+value["inMemory"]+"</td><tr>";
             content=content+"<tr><td>bloomFilterType</td><td>"+value["bloomFilterType"]+"</td><tr>";
             content=content+"<tr><td>bloomFilterVectorSize</td><td>"+value["bloomFilterVectorSize"]+"</td><tr>";
             content=content+"<tr><td>bloomFilterNbHashes</td><td>"+value["bloomFilterNbHashes"]+"</td><tr>";
             content=content+"<tr><td>blockCacheEnabled</td><td>"+value["blockCacheEnabled"]+"</td><tr>";
             content=content+"<tr><td>timeToLive</td><td>"+value["timeToLive"]+"</td><tr>";
             content=content+"</table></div>";
             i=i+1;             
         });
          $("#tab").append(ultab); 
          $("#myTabContent").append(content);                
     });
    
      
}
function trundialog()
{
   $('#truntable').attr('disabled',false);
   $('#trun').modal({
         backdrop:true
      }) 
}
function truntable()
{
   var url='<?php echo $this->config->base_url();?>index.php/tables/truncatetable/<?php echo $tablename?>'; 
   $('#truntable').text("loading");
   $('#truntable').attr('disabled',true);
   $('#truntable').load(url,function(response,status){
       if (status=="success")
       {
          $('#truntable').text("Truncate");
          $('#truntable').attr('disabled',false);          
          alert('truncate table success');
          closemodal("trun");
          location.reload();
       }    
    
   }) 
}
function deldialog()
{
   $('#deltable').attr('disabled',false);
   $('#deltab').modal({
         backdrop:true
      })  
}
function deltable()
{
  var url='<?php echo $this->config->base_url();?>index.php/tables/deltable/<?php echo $tablename?>';
  $('#deltable').text("loading");
  $('#deltable').attr('disabled',true);
  $('#truntable').load(url,function(response,status){
       if (status=="success")
       {
          $('#deltable').text("Delete");
          $('#truntable').attr('disabled',false);          
          alert('delete table success');
          closemodal("deltab");
          window.location= ('<?php echo $this->config->base_url();?>index.php'); 
       }    
    
   }) 
     
}
function closemodal(divid)
{
    $('#'+divid).modal('hide');
}
</script>
<div class="span8" style="margin-left: 120px;">
    <h2><?php echo $tablename?></h2>
</div>

<div class="span8" style="margin-left: 120px;margin-bottom: 20px;">
   <div class="btn-group">
      <a class="btn btn-primary" href="javascript:;" onclick="showmeta();"><i class="icon-check icon-white"></i> show metadata</a>         
   </div>   
   <div class="btn-group">
      <a class="btn btn-danger" href="javascript:;" onclick="trundialog();"><i class="icon-ban-circle icon-white"></i> truncate table</a>            
   </div>
    <div class="btn-group">
      <a class="btn btn-danger" href="javascript:;" onclick="deldialog();"><i class="icon-remove icon-white"></i> delete table</a>            
   </div>
    
</div>

<div class="modal hide" id="metadata">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo $tablename?> talbe metadata</h3>
  </div>
  <div class="modal-body">
     <ul id="tab" class="nav nav-tabs">
     </ul>  
     <div id="myTabContent" class="tab-content">
         
     </div>    
  </div> 
</div>

<div class="modal hide" id="trun">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>truncate table <?php echo $tablename?> </h3>
  </div>
  <div class="modal-body">
    <p>Are you sure truncate table <?php echo $tablename?>? </p>
  </div>
  <div class="modal-footer"> 
    <button type="button" id="truntable" class="btn btn-danger" onclick="truntable();">Truncate</button>
    <a href="javascript:;" class="btn" onclick="closemodal('trun');">Cancel</a>
  </div>
</div>

<div class="modal hide" id="deltab">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>delete table <?php echo $tablename?> </h3>
  </div>
  <div class="modal-body">
    <p>Are you sure delete table <?php echo $tablename?>? </p>
  </div>
  <div class="modal-footer"> 
    <button type="button" id="deltable" class="btn btn-danger" onclick="deltable();">Delete</button>
    <a href="javascript:;" class="btn" onclick="closemodal('deltab');">Cancel</a>
  </div>
</div>

<div class="span8" style="margin-left: 120px;">
  <div id="grid"></div>
  <div id="details"></div>  
</div>

<script>
  var wnd,detailsTemplate;
  $(document).ready(function () {
                    var crudServiceBaseUrl = "<?php echo $this->config->base_url();?>index.php/tables",
                        dataSource = new kendo.data.DataSource({
                            transport: {
                                read:  {
                                    url: crudServiceBaseUrl+"/gettablerecords/<?php echo $tablename?>",
                                    dataType: "json"
                                }
                            },
                            batch: true,
                            pageSize: 20,
                            schema: {
                                model: {                                    
                                    fields: {                                        
                                        row: { validation: { required: true } },
                                        column: { type: "string", validation: { required: true} },
                                        timestamp: { type: "string" },
                                        value: { type: "string", validation: { required: true } }
                                    }
                                }
                            }
                        });

                    $("#grid").kendoGrid({
                        dataSource: dataSource,
                        navigatable: true,
                        pageable: true,
                        height: 430,
                        toolbar: ["create", "save", "cancel"],
                        columns: [
                            "row",
                            { field: "column", title: "column", width: 110 },
                            { field: "timestamp", title: "timestamp" },
                            { field: "value", width: 110 },
                            { command: { text: "View Details", click: showDetails }, title: " ", width: "140px" }],
                        editable: "inline"
                    });
                    
  wnd = $("#details")
                        .kendoWindow({
                            title: "Customer Details",
                            modal: true,
                            visible: false,
                            resizable: false,
                            width: 300
                        }).data("kendoWindow");

                    detailsTemplate = kendo.template($("#template").html());
                });
              
 function showDetails(e) {
                    e.preventDefault();

                    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
                    wnd.content(detailsTemplate(dataItem));
                    wnd.center().open();
                }                
            
</script>

<script type="text/x-kendo-template" id="template">
                <div id="details-container">
                    <h2>#= row # #= column #</h2>
                    <em>#= timestamp #</em>
                    <dl>
                        <dt>value: #= value #</dt>
                      
                    </dl>
                </div>
</script>