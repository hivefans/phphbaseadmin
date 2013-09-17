<link href="<?php echo $this->config->base_url();?>css/layer.css" rel="stylesheet"> 

 <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success rmonitor">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new user created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error rmonitor">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Username already taken</strong>';
          echo '</div>';          
        }
      }
      ?>
<?php echo validation_errors(); ?>
<div id="content" class='container_12 rmonitor'>
  	
	<div id="title">
        <div class="grid_6" id="title-header"><a href="<?php echo site_url("manage/userinfo")?>">User Manage</a> / Add</div>
        
        <ul class="" id="title-menu-holder"></ul>
    </div>

<div id="sensors" class='boxit'>
	
<form action="<?php echo site_url("manage/adduser")?>" method="post" class="form-horizontal">  
      <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">username</label>
            <div class="controls">
              <input type="text" id="username" name="username" value="" />
             
            </div>
          </div>
           <div class="control-group">
             <label for="manufacture_id" class="control-label">groupname</label>
             <div class="controls">
               <select name="groupname" class="span2">                
                 <?php
                  foreach($usergroup as $row)
                  {
                    echo '<option value='.$row['name'].'>'.$row['name'].'</option>';
                  }
                  ?>
                </select>
             </div> 
          </div>          
          <div class="control-group">
            <label for="inputError" class="control-label">password</label>
            <div class="controls">
              <input type="password" id="" name="password" value="" />            
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" name="email" value="" />             
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">grant</label>
            <div class="controls">
               <input type="text" name="grant" id="grant" value="" onclick="addgrant()" readonly="readonly" />
            </div>
          </div>
                  <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save </button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      </form>
	 
</div> 
    
<script>

function addgrant()
{ 
   var data =[<?php echo $result;?>];
			$("#grant").mulitselector({
				title:"choose tables",
				data:data,
				perPage:200,
				curPage:1
			});        
}

</script>
