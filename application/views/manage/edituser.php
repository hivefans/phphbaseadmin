<link href="<?php echo $this->config->base_url();?>css/layer.css" rel="stylesheet"> 

 <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> user info edit with success.';
          echo '</div>';       
        }
      }
      ?>
<?php echo validation_errors(); ?>
<div id="content" class='container_12'>
  	
	<div id="title">
        <div class="grid_6" id="title-header"><a href="<?php echo site_url("manage/userinfo")?>">User Manage</a> / Edit</div>
        
        <ul class="" id="title-menu-holder"></ul>
    </div>

<div id="sensors" class='boxit'>
	
<form action="<?php echo site_url("manage/edit/".$this->uri->segment(3))?>" method="post" class="form-horizontal">  
      <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">username</label>
            <div class="controls">
              <input type="text" id="username" name="username" value="<?php echo $userinfo[0]['user_name']; ?>" />
             
            </div>
          </div>
           <div class="control-group">
             <label for="manufacture_id" class="control-label">groupname</label>
             <div class="controls">
               <select name="groupname" class="span2">                
                 <?php
                  foreach($usergroup as $row)
                  {
                    if($row['name']==$userinfo[0]['group'])
                     {
                         echo '<option value='.$row['name'].' selected="selected">'.$row['name'].'</option>'; 
                     }
                     else
                     {
                         echo '<option value='.$row['name'].'>'.$row['name'].'</option>';
                     }
                   
                  }
                  ?>
                </select>
             </div> 
          </div>          
          <div class="control-group">
            <label for="inputError" class="control-label">password</label>
            <div class="controls">
              <input type="password" name="password" value="" />  
               <input type="hidden"  name="password2"  value="<?php echo $userinfo[0]['pass_word']; ?>" />          
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" name="email" value="<?php echo $userinfo[0]['email_address']; ?>" />             
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">grant</label>
            <div class="controls">
               <input type="text" name="grant" id="grant" value="<?php echo $userinfo[0]['grant']; ?>" onclick="addgrant()" readonly="readonly" />
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