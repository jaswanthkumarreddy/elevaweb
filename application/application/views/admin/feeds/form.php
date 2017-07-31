<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('', array('role'=>'form')); ?>
<?php // hidden id ?>
<?php if (isset($feed_id)) : ?>
<?php echo form_hidden('id', $feed_id); ?>
<?php endif; ?>

<div class="col-sm-6">
  <div class="row">
    <div class="form-group col-sm-12<?php echo form_error('feed_name') ? ' has-error' : ''; ?>"> <?php echo form_label('Feed Name', 'feed_name', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'feed_name', 'value'=>set_value('feed_name', (isset($feed['feed_name']) ? $feed['feed_name'] : '')), 'class'=>'form-control')); ?> </div>
    <div class="form-group col-sm-12<?php echo form_error('website_url') ? ' has-error' : ''; ?>"> <?php echo form_label('Website Url', 'website_url', array('class'=>'control-label')); ?> <?php echo form_input(array('name'=>'website_url', 'value'=>set_value('website_url', (isset($feed['website_url']) ? $feed['website_url'] : '')), 'class'=>'form-control')); ?> </div>
  </div>
  <div class="row">
    <div class="form-group col-sm-offset-1 col-sm-11<?php echo form_error('feed_path') ? ' has-error' : ''; ?>"> <?php echo form_label('Feed Path', 'feed_path', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'feed_path', 'value'=>set_value('feed_path', (isset($feed['feed_path']) ? $feed['feed_path'] : '')), 'class'=>'form-control')); ?> </div>
    <div class="form-group col-sm-offset-1 col-sm-11<?php echo form_error('feed_category') ? ' has-error' : ''; ?>"> <?php echo form_label('Feed Category', 'feed_category', array('class'=>'control-label')); ?> <span class="required">*</span> <?php echo form_input(array('name'=>'feed_category', 'value'=>set_value('feed_category', (isset($feed['feed_category']) ? $feed['feed_category'] : '')), 'class'=>'form-control')); ?> </div>
  </div>
  <div class="row">
    <div class="form-group col-sm-12">
      <button type="button" name="add_category" class="btn btn-default pull-right" id="add_category">Add Category </button>
      <!--<button type="button" name="test_feed" class="btn btn-default pull-right">Test Feed </button>-->
    </div>
  </div>
  <div class="row">
    <div class="form-group col-sm-12">
      <table class="table">
        <tr>
          <td>SrNo</td>
          <td>Path</td>
          <td>Category</td>
          <td></td>
        </tr>
        <tbody class="feed_path_category_list">        
        <?php  
			$count=1;
			if($this->session->userdata('cart_feeds')){
			$cart_feeds = $this->session->userdata('cart_feeds'); 	 
			foreach($cart_feeds as $fKey=>$fVal){		
		?>	
         <tr>
         <td><?php echo $count++; ?></td>
          <td><?php echo $cart_feeds[$fKey]['feed_path']; ?></td>
          <td><?php echo $cart_feeds[$fKey]['feed_category']; ?></td>
         <td> <a class="btn btn-default" href="javascript://" onclick="deleteFeed(<?php echo $fKey; ?>)"> Delete </a> <!--<a class="btn btn-default" href="#"> Test Feed </a>--> </td>
        </tr>
        <?php } } else { ?>
		<tr>
        	<td colspan="4" align="center"> No record found.! </td>
        </tr>
		
		<?php } ?>
            
        </tbody>
      </table>
    </div>
  </div>
  <div class="row radio-block">
    <?php // is_active ?>
    <div class="form-group col-sm-12<?php echo form_error('is_active') ? ' has-error' : ''; ?>"> <?php echo form_label('Status', '', array('class'=>'control-label')); ?> <span class="required">*</span>
      <div class="radio">
        <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-1', 'value'=>'1', 'checked'=>(( ! isset($feed['is_active']) OR (isset($feed['is_active']) && (int)$feed['is_active'] == 1)) ? 'checked' : FALSE))); ?> <?php echo lang('admin input active'); ?> </label>
      </div>
      <div class="radio">
        <label> <?php echo form_radio(array('name'=>'is_active', 'id'=>'radio-is_active-2', 'value'=>'0', 'checked'=>((isset($feed['is_active']) && (int)$feed['is_active'] == 0) ? 'checked' : FALSE))); ?> <?php echo lang('admin input inactive'); ?> </label>
      </div>
    </div>
    <!-- <div class="form-group col-sm-6<?php echo form_error('payment_status') ? ' has-error' : ''; ?>"> <?php echo form_label(lang('feeds input payment_status'), 'payment_status', array('class'=>'control-label')); ?> <span class="required">*</span>   <?php echo form_dropdown('payment_status', $paymentlist , set_value('payment_status', (isset($feed['payment_status']) ? $feed['payment_status'] : '')), 'class="form-control"'); ?> </div> -->
  </div>
  <?php // buttons ?>
  <div class="row">
    <div class="col-md-12"> <a class="btn btn-default" href="<?php echo $cancel_url; ?>" data-toggle="tooltip" data-original-title="Cancel"> <?php echo lang('core button cancel'); ?> </a>
      <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core button save'); ?> </button>
    </div>
  </div>
</div>
<div class="col-sm-6">
  <div class="row">
    <label>Division ID or Class or XPath (ADVANCED)</label>
    <table class="table">
      <tr>
        <td>Rule 1</td>
        <td><?php echo form_dropdown('rule1_type', $rule1_type ,isset($feed['rule1_type']) ? $feed['rule1_type'] : '', 'class="form-control"'); ?></td>
        
        <td> 
        
        <?php echo form_input(array('name'=>'rule1_type_value', 'value'=>set_value('rule1_type_value', (isset($feed['rule1_type_value']) ? $feed['rule1_type_value'] : '')), 'class'=>'form-control')); ?>
        
        </td> 
        
        <td><?php echo form_checkbox(array('name'=>'rule1_is_single', 'id'=>'rule1_is_single', 'value'=>'1', 'checked'=>(( 1 == $feed['rule1_is_single']) ? 'checked' : FALSE))); ?>Single?</td>
        <td><?php echo form_checkbox(array('name'=>'rule1_is_inner', 'id'=>'rule1_is_inner', 'value'=>'1', 'checked'=>(( 1 == $feed['rule1_is_inner']) ? 'checked' : FALSE))); ?>
		inner?
		 </td>
      </tr>
      <tr>
        <td>Rule 2</td>
        <td><?php echo form_dropdown('rule2_type', $rule2_type ,isset($feed['rule2_type']) ? $feed['rule2_type'] : '', 'class="form-control"'); ?></td>
        <td> 
        
           <?php echo form_input(array('name'=>'rule2_type_value', 'value'=>set_value('rule2_type_value', (isset($feed['rule2_type_value']) ? $feed['rule2_type_value'] : '')), 'class'=>'form-control')); ?>
        
        </td>
       <td><?php echo form_checkbox(array('name'=>'rule2_is_single', 'id'=>'rule2_is_single', 'value'=>'1', 'checked'=>(( 1 == $feed['rule2_is_single']) ? 'checked' : FALSE))); ?>Single?</td>
        <td><?php echo form_checkbox(array('name'=>'rule2_is_inner', 'id'=>'rule2_is_inner', 'value'=>'1', 'checked'=>(( 1 == $feed['rule2_is_inner']) ? 'checked' : FALSE))); ?>
		inner?
		 </td>
      </tr>
    </table>
  </div>
  <div class="row"> <?php echo form_checkbox('is_strip_parts', 'rule_chk', FALSE); ?> Strip parts after extracting content using id or class
    <table class="table">
      <tr>
        <td>Rule 1</td>
        <td><?php echo form_dropdown('strip1_type', $strip1_type ,isset($feed['strip1_type']) ? $feed['strip1_type'] : '', 'class="form-control"'); ?></td>
        <td> 
        
        
         <?php echo form_input(array('name'=>'strip1_value', 'value'=>set_value('strip1_value', (isset($feed['strip1_value']) ? $feed['strip1_value'] : '')), 'class'=>'form-control')); ?>
        
        </td>
      </tr>
      <tr>
        <td>Rule 2</td>
        <td><?php echo form_dropdown('strip2_type', $strip2_type ,isset($feed['strip2_type']) ? $feed['strip2_type'] : '', 'class="form-control"'); ?></td>
        <td> 
        
          <?php echo form_input(array('name'=>'strip2_value', 'value'=>set_value('strip2_value', (isset($feed['strip2_value']) ? $feed['strip2_value'] : '')), 'class'=>'form-control')); ?>
        </td>
      </tr>
    </table>
  </div>
  <div class="row" style="display:none">
    <label>Post text template (spintax enabled like {Awsome|Amazing|Grate})</label>
  
     
          <?php echo form_textarea(array('name'=>'feed_template', 'value'=>set_value('feed_template', (isset($feed['feed_template']) ? $feed['feed_template'] : '')), 'class'=>'form-control', 'rows' => '4',
    'cols' => '50')); ?>
    
    
    
    <label>Post text template (spintax enabled like {Awsome|Amazing|Grate})</label>
  </div>
</div>
<?php echo form_close(); ?>