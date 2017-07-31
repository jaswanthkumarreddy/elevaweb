<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('', array('role'=>'form')); ?>
<?php // hidden id ?>
<?php if (isset($customer_id)) : ?>
<?php echo form_hidden('id', $customer_id); ?>
<?php endif; ?>

<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('full_name') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input full_name'), 'full_name', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'full_name', 'value'=>set_value('full_name', (isset($customer['full_name']) ? $customer['full_name'] : '')), 'class'=>'form-control')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('email_id') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input email'), 'email_id', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'email_id', 'value'=>set_value('email_id', (isset($customer['email_id']) ? $customer['email_id'] : '')), 'class'=>'form-control')); ?> </div>

  
</div>
<div class="row">
 <!-- <div class="form-group col-sm-6<?php echo form_error('last_name') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input last_name'), 'last_name', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'last_name', 'value'=>set_value('last_name', (isset($customer['last_name']) ? $customer['last_name'] : '')), 'class'=>'form-control')); ?> </div> --> 

</div>

<div class="row">
<!-- <div class="form-group col-sm-6<?php echo form_error('birthdate') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input birthdate'), 'birthdate', array('class'=>'control-label')); ?>
    <div class='input-group date'> <?php echo form_input(array('name'=>'birthdate', 'value'=>set_value('birthdate', (isset($customer['birthdate']) ? date_format(date_create($customer['birthdate']),'Y-m-d') : '')), 'class'=>'form-control')); ?> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> </div>
  </div> -->
  
  
  <div class="form-group col-sm-6<?php echo form_error('website') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input website'), 'website', array('class'=>'control-label')); ?>   <?php echo form_input(array('name'=>'website', 'value'=>set_value('website', (isset($customer['website']) ? $customer['website'] : '')), 'class'=>'form-control')); ?> </div>

  <div class="form-group col-sm-6<?php echo form_error('mobile_no') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input mobile_no'), 'mobile_no', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'mobile_no', 'value'=>set_value('mobile_no', (isset($customer['mobile_no']) ? $customer['mobile_no'] : '')), 'class'=>'form-control')); ?> </div>
  
</div>


<div class="row">
  <div class="form-group col-sm-6<?php echo form_error('password') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input password'), 'password', array('class'=>'control-label')); ?>
    <?php if ($password_required) : ?>
    <span class="required">*</span>
    <?php endif; ?>
    <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?> </div>
  <div class="form-group col-sm-6<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
    <?php if ($password_required) : ?>
    <span class="required">*</span>
    <?php endif; ?>
    <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?> </div>
  <?php if ( ! $password_required) : ?>
  <span class="help-block"><br />
  <?php echo lang('customers help passwords'); ?></span>
  <?php endif; ?>
</div>
<div class="row radio-block">
  <?php // is_active ?>
  <div class="form-group col-sm-6<?php echo form_error('is_active') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input is_active'), '', array('class'=>'control-label')); ?> <span class="required">*</span>
    <div class="radio">
      <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-1', 'value'=>'1', 'checked'=>(( ! isset($customer['is_active']) OR (isset($customer['is_active']) && (int)$customer['is_active'] == 1)) ? 'checked' : FALSE))); ?> <?php echo lang('admin input active'); ?> </label>
    </div>
    <div class="radio">
      <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-2', 'value'=>'0', 'checked'=>((isset($customer['is_active']) && (int)$customer['is_active'] == 0) ? 'checked' : FALSE))); ?> <?php echo lang('admin input inactive'); ?> </label>
    </div>
  </div>
  
    <div class="form-group col-sm-6<?php echo form_error('payment_status') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('customers input payment_status'), 'payment_status', array('class'=>'control-label')); ?> <span class="required">*</span>   <?php echo form_dropdown('payment_status', $paymentlist , set_value('payment_status', (isset($customer['payment_status']) ? $customer['payment_status'] : '')), 'class="form-control"'); ?> </div>
  
  
</div>
<?php // buttons ?>
<div class="row">
  <div class="col-md-12"> <a class="btn btn-default" href="<?php echo $cancel_url; ?>" data-toggle="tooltip" data-original-title="Cancel"> <?php echo lang('core button cancel'); ?> </a>
    <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core button save'); ?> </button>
  </div>
</div>
<?php echo form_close(); ?>

<div class="row">
	<div class="col-md-12">
		<h4>Website Installation Log</h4>
		<?php
			$user_websites = $customer['customer_websites'];
			if(!empty( $user_websites )){
				$sites = unserialize( $user_websites );
				if(!empty( $sites ) && is_array( $sites )){
					$count = 1;
					$html = '';
					foreach( $sites as $site ){
						$html .= ' '.$site;
						if($count%2 == 0){
							echo '<p>'.$html.'</p>';
							$html = '';
						}
						$count++;
					}
				}
			}
		?>
	</div>
</div>