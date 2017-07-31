<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 	
 	<?php /* 
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-4 widget-left">
							<svg class="glyph stroked bag"><use xlink:href="#stroked-bag"/></svg>
						</div>
						<div class="col-sm-9 col-lg-8 widget-right">
							<div class="large">120</div>
							<div class="text-muted">Active Job Orders</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-4 widget-left">
							<svg class="glyph stroked empty-message"><use xlink:href="#stroked-empty-message"/></svg>
						</div>
						<div class="col-sm-9 col-lg-8 widget-right">
							<div class="large">52</div>
							<div class="text-muted">Recent Hires</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-ptk panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-4 widget-left">
							<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"/></svg>
						</div>
						<div class="col-sm-9 col-lg-8 widget-right">
							<div class="large">24</div>
							<div class="text-muted">Jobs Assigned</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-4 widget-left">
							<svg class="glyph stroked app-window-with-content"><use xlink:href="#stroked-app-window-with-content"/></svg>
						</div>
						<div class="col-sm-9 col-lg-8 widget-right">
							<div class="large">25.2k</div>
							<div class="text-muted">Jobs Assigned as Primary</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading">No. of Resumes is Added</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="line-chart" height="167" width="502" style="width: 502px; height: 167px;"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading">Job Orders</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="bar-chart" height="167" width="502" style="width: 502px; height: 167px;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12"><h3>My Recruiting Activities This Week</h3></div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Resumes Added This Week</h4>
						<div class="easypiechart" id="easypiechart-blue" data-percent="92"><span class="percent">92%</span>
						</div>
						<div class="week-goals"><i class="fa fa-arrow-left"></i> <span>27-Feb-2017 to 03-Mar-2017</span> <i class="fa fa-arrow-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Calls Made This Week</h4>
						<div class="easypiechart" id="easypiechart-orange" data-percent="65"><span class="percent">65%</span>
						</div>
						<div class="week-goals"><i class="fa fa-arrow-left"></i> <span>27-Feb-2017 to 03-Mar-2017</span> <i class="fa fa-arrow-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Interviews This Week</h4>
						<div class="easypiechart" id="easypiechart-teal" data-percent="56"><span class="percent">56%</span>
						</div>
						<div class="week-goals"><i class="fa fa-arrow-left"></i> <span>27-Feb-2017 to 03-Mar-2017</span> <i class="fa fa-arrow-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Hires This Week</h4>
						<div class="easypiechart" id="easypiechart-red" data-percent="27"><span class="percent">27%</span>
						</div>
						<div class="week-goals"><i class="fa fa-arrow-left"></i> <span>27-Feb-2017 to 03-Mar-2017</span> <i class="fa fa-arrow-right"></i></div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
								
		<div class="row">
			<div class="col-md-8">
			
				<div class="panel panel-default chat">
					<div class="panel-heading" id="accordion"><svg class="glyph stroked two-messages"><use xlink:href="#stroked-two-messages"/></svg> Chat</div>
					<div class="panel-body">
						<ul>
							<li class="left clearfix">
								<span class="chat-img pull-left">
									<img src="http://placehold.it/80/30a5ff/fff" alt="User Avatar" class="img-circle">
								</span>
								<div class="chat-body clearfix">
									<div class="header">
										<strong class="primary-font">John Doe</strong> <small class="text-muted">32 mins ago</small>
									</div>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc. Vivamus luctus convallis mauris, eu gravida tortor aliquam ultricies. 
									</p>
								</div>
							</li>
							<li class="right clearfix">
								<span class="chat-img pull-right">
									<img src="http://placehold.it/80/dde0e6/5f6468" alt="User Avatar" class="img-circle">
								</span>
								<div class="chat-body clearfix">
									<div class="header">
										<strong class="pull-left primary-font">Jane Doe</strong> <small class="text-muted">6 mins ago</small>
									</div>
									<p>
										Mauris dignissim porta enim, sed commodo sem blandit non. Ut scelerisque sapien eu mauris faucibus ultrices. Nulla ac odio nisl. Proin est metus, interdum scelerisque quam eu, eleifend pretium nunc. Suspendisse finibus auctor lectus, eu interdum sapien.
									</p>
								</div>
							</li>
							<li class="left clearfix">
								<span class="chat-img pull-left">
									<img src="http://placehold.it/80/30a5ff/fff" alt="User Avatar" class="img-circle">
								</span>
								<div class="chat-body clearfix">
									<div class="header">
										<strong class="primary-font">John Doe</strong> <small class="text-muted">32 mins ago</small>
									</div>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc. Vivamus luctus convallis mauris, eu gravida tortor aliquam ultricies. 
									</p>
								</div>
							</li>
						</ul>
					</div>
					
					<div class="panel-footer">
						<div class="input-group">
							<input id="btn-input" class="form-control input-md" placeholder="Type your message here..." type="text">
							<span class="input-group-btn">
								<button class="btn btn-success btn-md" id="btn-chat">Send</button>
							</span>
						</div>
					</div>
				</div>
				
			</div><!--/.col-->
			
			<div class="col-md-4">
			
				<div class="panel panel-blue">
					<div class="panel-heading dark-overlay"><svg class="glyph stroked clipboard-with-paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg>To-do List</div>
					<div class="panel-body">
						<ul class="todo-list">
						<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Make a plan for today</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Update Basecamp</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Send email to Jane</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Drink coffee</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Do some work</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
							<li class="todo-list-item">
								<div class="checkbox">
									<input id="checkbox" type="checkbox">
									<label for="checkbox">Tidy up workspace</label>
								</div>
								<div class="pull-right action-buttons">
									<a href="#"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
									<a href="#" class="flag"><svg class="glyph stroked flag"><use xlink:href="#stroked-flag"/></svg></a>
									<a href="#" class="trash"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
								</div>
							</li>
						</ul>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<input id="btn-input" class="form-control input-md" placeholder="Add new task" type="text">
							<span class="input-group-btn">
								<button class="btn btn-primary btn-md" id="btn-todo">Add</button>
							</span>
						</div>
					</div>
				</div>
								
			</div><!--/.col-->
		</div><!--/.row-->		
 */ ?>
 <div class="row">

						<!-- Paid Users -->
						<div class="col-xs-12 col-md-3">
							<div class="panel panel-blue panel-widget ">
								<div class="row no-padding">
									<div class="col-sm-3 col-lg-5 widget-left">
										<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>
									</div>
									<div class="col-sm-9 col-lg-7 widget-right">
										<div class="large">120</div>
										<div class="text-muted">Paid User</div>
									</div>
								</div>
							</div>

							<div class="panel panel-blue">
							<div class="panel-heading dark-overlay">
							Last Activities</div>
							<div class="panel-body">
								<ul class="todo-list">									
									<li class="todo-list-item">										
										<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
										<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
										<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
										<label for="checkbox">Make a plan for today</label>
									</li>									
								</ul>
							</div>							
							</div>

						</div>
						<!-- Paid Users -->

						<!-- Free Users -->
						<div class="col-xs-12 col-md-3">
							<div class="panel panel-orange panel-widget ">
								<div class="row no-padding">
									<div class="col-sm-3 col-lg-5 widget-left">
										<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>
									</div>
									<div class="col-sm-9 col-lg-7 widget-right">
										<div class="large">120</div>
										<div class="text-muted">Free User</div>
									</div>
								</div>
							</div>

							<div class="panel panel-orange">
							<div class="panel-heading dark-overlay">
							Last Activities</div>
							<div class="panel-body">
								<ul class="todo-list">
									<li class="todo-list-item">										
											<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
											<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
											<label for="checkbox">Make a plan for today</label>
									</li>
									<li class="todo-list-item">										
											<label for="checkbox">Make a plan for today</label>
									</li>
								</ul>
							</div>							
							</div>

						</div>
						<!-- Free Users -->

						<!-- Services -->
						<div class="col-xs-12 col-md-6 services">

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
								<a href="<?php echo base_url('/admin/feeds'); ?>">
								<div class="panel panel-ptk">
									<div class="panel-heading dark-overlay">
									<img src="<?php echo base_url('/themes/admin/images/dash/eleva-pencil.png'); ?>" height="60px">
										<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
									</div>
									<div class="panel-body">
										<p>Content</p>
									</div>
								</div>
								</a>
							</div>	
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

							<!-- Service -->
							<div class="col-xs-12 col-md-4">
							<div class="panel panel-lock-ptk">
								<div class="panel-heading dark-overlay">
								<img src="<?php echo base_url('/themes/admin/images/dash/eleva-lock.png'); ?>" height="60px">
								<!-- <svg class="glyph stroked app window with content"><use xlink:href="#stroked-app-window-with-content"></use></svg> -->
								</div>
								<div class="panel-body">
									<p>Lock</p>
								</div>
							</div>
							</div>
							<!-- Service -->

						</div>
						<!-- Services -->

					</div><!--/.row-->	
					<style type="text/css">
								/*.services > div > div > div.panel-body , 
								.services > div > a > div > div.panel-body{
								    padding: 5px;
								    text-align: center;
								}*/

								.services .panel-body{
								    padding: 5px;
								    text-align: center;
								}


								div.panel-body > p{								
    							margin: 0 0 0px;
								}
								.alert .glyph, .panel-heading .glyph {
								    width: 60px;
								    height: 50px;								 
								}
							</style>