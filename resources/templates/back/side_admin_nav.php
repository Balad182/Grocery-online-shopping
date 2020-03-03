<div class="collapse navbar-collapse navbar-ex1-collapse">
	<ul class="nav navbar-nav side-nav">
		<li class="active">
			<a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
		</li>
		<li>
			<a href="index.php?orders"><i class="fa fa-fw fa-bar-chart-o"></i> Orders</a>
		</li>
		<li>
			<a href="javascript:;" data-toggle="collapse" data-target="#catalog_dropdown"><i class="fa fa-fw fa-desktop"></i> CATALOG <i class="fa fa-fw fa-caret-down"></i></a>
			<ul id="catalog_dropdown" class="collapse">
				<li>
					<a href="javascript:;" data-toggle="collapse" data-target="#products_dropdown">Products <i class="fa fa-fw fa-caret-down"></i></a>
					<ul id="products_dropdown" class="collapse">
						<li>
							<a href="index.php?products"><i class="fa fa-fw fa-wrench"></i>View Products</a>
						</li>
						<li>
							<a href="index.php?add_product"><i class="fa fa-fw fa-wrench"></i>Add Products</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:;" data-toggle="collapse" data-target="#cats_dropdown">Categories <i class="fa fa-fw fa-caret-down"></i></a>
					<ul id="cats_dropdown" class="collapse">
						<?php show_categories_menu(); ?>
						<li>
							<a href="index.php?categories">Add Category</a>
						</li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="index.php?users"><i class="fa fa-fw fa-user"></i>Users</a>
		</li>
		<li>
			<a href="index.php?reports"><i class="fa fa-fw fa-arrows-v"></i>Reports</a>
		</li>
		<li>
			<a href="index.php?slides"><i class="fa fa-fw fa-wrench"></i>Slides</a>
		</li>

	</ul>
</div>