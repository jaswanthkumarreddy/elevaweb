 
	
	 <div class="row">
		<div class="form-group col-sm-6">
            <?php echo form_label('Menu', 'name', array('class'=>'control-label')); ?>
            <span class="required">*</span>   
        </div> 
<div class="form-group col-sm-1">
             <?php echo form_label('View', 'name', array('class'=>'control-label')); ?>
        </div> 		
		<div class="form-group col-sm-1">
            <?php echo form_label('Add', 'name', array('class'=>'control-label')); ?>
        </div>
<div class="form-group col-sm-1">
             <?php echo form_label('Edit', 'name', array('class'=>'control-label')); ?>
        </div>
<div class="form-group col-sm-1">
             <?php echo form_label('Delete', 'name', array('class'=>'control-label')); ?>
        </div> 
     </div> 
	 
	 
	 <?php if(isset($permissionList) && !empty($permissionList)) { ?>
		
		
	<?php
	   foreach($meenuList as $ml=>$val) :
  	
		$u_view = isset($permissionList[$ml]['u_view']) ? $permissionList[$ml]['u_view'] : '';
		$u_add = isset($permissionList[$ml]['u_add']) ? $permissionList[$ml]['u_add'] : '';
		$u_edit = isset($permissionList[$ml]['u_edit']) ? $permissionList[$ml]['u_edit'] : '';
		$u_delete = isset($permissionList[$ml]['u_delete']) ? $permissionList[$ml]['u_delete']:'';
	?>
	
	  <div class="row">
		<div class="form-group col-sm-6">
            <?php echo $val; ?>
        </div>
		<div class="form-group col-sm-1">
		<?php echo form_checkbox(array('name'=>'view-'.$ml, 'id'=>'view-'.$ml, 'value'=>'1','checked'=>(( $u_view == 1) ? 'checked' : FALSE))); ?>
        </div>
		<div class="form-group col-sm-1">
		<?php echo form_checkbox(array('name'=>'add-'.$ml, 'id'=>'add-'.$ml, 'value'=>'1','checked'=>(( $u_add == 1) ? 'checked' : FALSE))); ?>
        </div>
<div class="form-group col-sm-1">
      <?php echo form_checkbox(array('name'=>'edit-'.$ml, 'id'=>'edit-'.$ml, 'value'=>'1','checked'=>(( $u_edit == 1) ? 'checked' : FALSE))); ?>
        </div>
<div class="form-group col-sm-1">
      <?php echo form_checkbox(array('name'=>'delete-'.$ml, 'id'=>'delete-'.$ml, 'value'=>'1','checked'=>(( $u_delete == 1) ? 'checked' : FALSE))); ?>
        </div> 
		
     </div> 
	
     
	 <?php endforeach;?>
	

	<?php } else { ?>
		 
		 <?php foreach($meenuList as $ml=>$val) : ?>
	 
	  <div class="row">
		<div class="form-group col-sm-6">
            <?php echo $val; ?>
        </div>
		<div class="form-group col-sm-1">
		<?php echo form_checkbox(array('name'=>'view-'.$ml, 'id'=>'view-'.$ml, 'value'=>'1')); ?>
        </div>
		<div class="form-group col-sm-1">
		<?php echo form_checkbox(array('name'=>'add-'.$ml, 'id'=>'add-'.$ml, 'value'=>'1')); ?>
        </div>
<div class="form-group col-sm-1">
      <?php echo form_checkbox(array('name'=>'edit-'.$ml, 'id'=>'edit-'.$ml, 'value'=>'1')); ?>
        </div>
<div class="form-group col-sm-1">
      <?php echo form_checkbox(array('name'=>'delete-'.$ml, 'id'=>'delete-'.$ml, 'value'=>'1')); ?>
        </div> 
		
     </div> 
	 <?php endforeach; ?>
	 
	 
	 <?php } ?>
	 
	 
	 
    
	 </div>
	  
	  
	 