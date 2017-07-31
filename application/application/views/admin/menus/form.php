<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php //echo form_open('', array('role'=>'form')); ?>
 <?php echo form_open_multipart('', array('role'=>'form'));?>
 
    <?php // hidden id ?>
    <?php if (isset($menu_id)) : ?>
        <?php echo form_hidden('id', $menu_id); ?>
		<?php echo form_hidden('hmenu_name',$menu['name']); ?>
    <?php endif; ?> 
	<div class="bggray">
	 <div class="row">
		<div class="form-group col-sm-6<?php echo form_error('level') ? ' has-error' : ''; ?>">
            <?php echo form_label(lang('menus input level'), 'name', array('class'=>'control-label')); ?>
            <span class="required">*</span>  
			 <?php echo form_dropdown('level_id', $levelList, isset($menu['category_id']) ? $menu['level_id'] : '' , 'class="form-control levelClick"'); ?>
        </div>  
     </div> 
	</div>
	<div class="bggray" id="menu-list" style="display:none">
	
	
    
	 </div>
	  
	  
	<div class="bggray">  
		<div class="row">
		<div class="col-md-12">
        <a class="btn btn-default" href="<?php echo $cancel_url; ?>" data-toggle="tooltip" data-original-title="Cancel"><?php echo lang('core button cancel'); ?></a>
        <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core button save'); ?></button>
		</div>
   </div>
		 
	  

<?php echo form_close(); ?>


