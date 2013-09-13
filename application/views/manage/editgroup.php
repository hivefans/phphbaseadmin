<?php      
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> group info edit with success.';
          echo '</div>';       
        }
        else{
           echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>group name already exists</strong></div>';
        }
      }
      ?>
<?php echo validation_errors(); ?>
<div id="content" class='container_12 rmonitor'>
  	
	<div id="title">
        <div class="grid_6" id="title-header"><a href="<?php echo site_url("manage/groupinfo")?>">Group Manage</a> / Edit</div>
        
        <ul class="" id="title-menu-holder"></ul>
    </div>

<div id="sensors" class='boxit'>
	
<form action="<?php echo site_url("manage/groupedit/".$this->uri->segment(3))?>" method="post" class="form-horizontal">  
      <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">group name</label>
            <div class="controls">
              <input type="text" id="groupname" name="groupname" value="<?php echo $groupname[0]['name']; ?>" />
             
            </div>
          </div>
          
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save </button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      </form>
	 
</div> 