<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row"> 
            <div class="col-md-12 text-right">
				
				<?php //if($this->session->customerdata['logged_in']['access_level_id'] >= 3 ) : ?> 
			
                <a class="btn btn-success btn-sm tooltips" href="<?php echo base_url('admin/customers/add'); ?>" title="<?php echo lang('customers tooltip add_new_customer') ?>" data-toggle="tooltip"><span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('customers button add_new_customer'); ?></a>
				
				<?php //endif; ?>
				 
            </div>
        </div>
    </div>

    <table class="table table-striped table-hover-warning pad5">
        <thead>

            <?php // sortable headers ?>
            <tr>
                <td>
                    <a href="<?php echo current_url(); ?>?sort=customer_id&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col customer_id'); ?></a>
                    <?php if ($sort == 'customer_id') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
               
                <td>
                    <a href="<?php echo current_url(); ?>?sort=full_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col full_name'); ?></a>
                    <?php if ($sort == 'full_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
               <!--  <td>
                    <a href="<?php echo current_url(); ?>?sort=last_name&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers col last_name'); ?></a>
                    <?php if ($sort == 'last_name') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td> -->
				
				
				 <td>
                    <a href="<?php echo current_url(); ?>?sort=email_id&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers input email'); ?></a>
                    <?php if ($sort == 'email_id') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
				
				
				 <td>
                    <a href="<?php echo current_url(); ?>?sort=mobile_no&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('customers input mobile_no'); ?></a>
                    <?php if ($sort == 'mobile_no') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
				
				
				
				
                <td>
                    <a href="<?php echo current_url(); ?>?sort=is_active&dir=<?php echo (($dir == 'asc' ) ? 'desc' : 'asc'); ?>&limit=<?php echo $limit; ?>&offset=<?php echo $offset; ?><?php echo $filter; ?>"><?php echo lang('admin col status'); ?></a>
                    <?php if ($sort == 'is_active') : ?><span class="glyphicon glyphicon-arrow-<?php echo (($dir == 'asc') ? 'up' : 'down'); ?>"></span><?php endif; ?>
                </td>
               
                <td class="pull-right">
                    <?php echo lang('admin col actions'); ?>
                </td>
            </tr>

            <?php // search filters ?>
            <tr>
                <?php echo form_open("{$this_url}?sort={$sort}&dir={$dir}&limit={$limit}&offset=0{$filter}", array('role'=>'form', 'id'=>"filters")); ?>
                    <th>
                    </th>
                    
                    <th<?php echo ((isset($filters['full_name'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'full_name', 'id'=>'full_name', 'class'=>'form-control input-sm', 'placeholder'=>lang('customers input full_name'), 'value'=>set_value('full_name', ((isset($filters['full_name'])) ? $filters['full_name'] : '')))); ?>
                    </th>
                    <!-- <th<?php echo ((isset($filters['last_name'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'last_name', 'id'=>'customername', 'class'=>'form-control input-sm', 'placeholder'=>lang('customers input last_name'), 'value'=>set_value('last_name', ((isset($filters['last_name'])) ? $filters['last_name'] : '')))); ?>
                    </th> -->
					
					<th<?php echo ((isset($filters['email_id'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'email_id', 'id'=>'email_id', 'class'=>'form-control input-sm', 'placeholder'=>lang('customers input email'), 'value'=>set_value('email_id', ((isset($filters['email_id'])) ? $filters['email_id'] : '')))); ?>
                    </th>
					
					<th<?php echo ((isset($filters['mobile_no'])) ? ' class="has-success"' : ''); ?>>
                        <?php echo form_input(array('name'=>'mobile_no', 'id'=>'mobile_no', 'class'=>'form-control input-sm', 'placeholder'=>lang('customers input mobile_no'), 'value'=>set_value('mobile_no', ((isset($filters['mobile_no'])) ? $filters['mobile_no'] : '')))); ?>
                    </th>
					
					<th<?php echo ((isset($filters['is_active'])) ? ' class="has-success"' : ''); ?>>
					 <?php echo form_dropdown('is_active', $statuslist, isset($filters['is_active']) ? $filters['is_active'] : '' , 'class="form-control"'); ?>
                 </th> 
				 
				 
                    <th colspan="4" width="90px">
                        <div class="text-right">
							<div class="btn-group">
                            <a href="<?php echo $this_url; ?>" class="btn btn-sm btn-danger tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip filter_reset'); ?>"><span class="glyphicon glyphicon-refresh"></span></a>
                            <button type="submit" name="submit" value="<?php echo lang('core button filter'); ?>" class="btn btn-sm btn-success tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip filter'); ?>"><span class="glyphicon glyphicon-filter"></span></button>
                        </div>
						  </div>
                    </th>
                <?php echo form_close(); ?>
            </tr>

        </thead>
        <tbody>

            <?php // data rows ?>
            <?php if ($total) : ?>
                <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td<?php echo (($sort == 'customer_id') ? ' class="sorted"' : ''); ?>>
                            <?php echo $customer['customer_id']; ?>
                        </td>
                       
                        <td<?php echo (($sort == 'full_name') ? ' class="sorted"' : ''); ?>>
                            <?php echo $customer['full_name']; ?>
                        </td>
                       <!--  <td<?php echo (($sort == 'last_name') ? ' class="sorted"' : ''); ?>>
                            <?php echo $customer['last_name']; ?>
                        </td> -->
						 <td<?php echo (($sort == 'email_id') ? ' class="sorted"' : ''); ?>>
                            <?php echo $customer['email_id']; ?>
                        </td>
						
						 <td<?php echo (($sort == 'mobile_no') ? ' class="sorted"' : ''); ?>>
                            <?php echo $customer['mobile_no']; ?>
                        </td>
                        <td<?php echo (($sort == 'is_active') ? ' class="sorted"' : ''); ?>>
                            <?php echo ($customer['is_active']) ? '<span class="active text-success">' . lang('admin input active') . '</span>' : '<span class="inactive text-danger">' . lang('admin input inactive') . '</span>'; ?>
                        </td>
                        
                        <td>
                            <div class="text-right">
                                <div class="btn-group">
                                    
									<?php //if($this->session->customerdata['logged_in']['access_level_id'] >= 4 ) : ?> 
									<?php //if ($customer['customer_id'] > 1) : ?>
                                        <a href="#modal-<?php echo $customer['customer_id']; ?>" data-toggle="modal" class="btn btn-sm btn-danger" title="<?php echo lang('admin button delete'); ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                    <?php //endif; ?>
									<?php //endif; ?>
									
									<?php //if($this->session->customerdata['logged_in']['access_level_id'] >= 3 ) : ?> 
                                    <a href="<?php echo $this_url; ?>/edit/<?php echo $customer['customer_id']; ?>" class="btn btn-sm btn-warning" title="<?php echo lang('admin button edit'); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
									<?php //endif; ?>
									
									
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">
                        <?php echo lang('core error no_results'); ?>
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <?php // list tools ?>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-2 text-left">
                <label><?php echo sprintf(lang('admin label rows'), $total); ?></label>
            </div>
            <div class="col-md-2 text-left">
                <?php if ($total > 10) : ?>
                    <select id="limit" class="form-control">
                        <option value="10"<?php echo ($limit == 10 OR ($limit != 10 && $limit != 25 && $limit != 50 && $limit != 75 && $limit != 100)) ? ' selected' : ''; ?>>10 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="25"<?php echo ($limit == 25) ? ' selected' : ''; ?>>25 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="50"<?php echo ($limit == 50) ? ' selected' : ''; ?>>50 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="75"<?php echo ($limit == 75) ? ' selected' : ''; ?>>75 <?php echo lang('admin input items_per_page'); ?></option>
                        <option value="100"<?php echo ($limit == 100) ? ' selected' : ''; ?>>100 <?php echo lang('admin input items_per_page'); ?></option>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <?php echo $pagination; ?>
            </div>
            <div class="col-md-2 text-right">
                <?php if ($total) : ?>
                    <a href="<?php echo $this_url; ?>/export?sort=<?php echo $sort; ?>&dir=<?php echo $dir; ?><?php echo $filter; ?>" class="btn btn-sm btn-success tooltips" data-toggle="tooltip" title="<?php echo lang('admin tooltip csv_export'); ?>"><span class="glyphicon glyphicon-export"></span> <?php echo lang('admin button csv_export'); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php // delete modal ?>
<?php if ($total) : ?>
    <?php foreach ($customers as $customer) : ?>
        <div class="modal fade" id="modal-<?php echo $customer['customer_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-label-<?php echo $customer['customer_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 customer_id="modal-label-<?php echo $customer['customer_id']; ?>"><?php echo lang('customers title customer_delete');  ?></h4>
                    </div>
                    <div class="modal-body">
                        <p><?php echo sprintf(lang('customers msg delete_confirm'), $customer['full_name'] ); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('core button cancel'); ?></button>
                        <a href="<?php echo $this_url; ?>/delete/<?php echo $customer['customer_id']; ?>" class="btn btn-danger" title="<?php echo lang('admin button delete'); ?>"><?php echo lang('admin button delete'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
