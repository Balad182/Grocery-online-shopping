<?php add_user(); ?>                
				<!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Add User
                        </h1>
						<div class="col-md-6">
							<a href="#" data-toggle="modal" data-target="#photo-library"><img class="img-responsive" src="#" name="user_image"></a>
						</div>
					<form action="" method="post" enctype="multipart/form-data"> 
                        <div class="col-md-6">
                            <div class="form-group">
								<input type="file" name="user_image">
							</div>
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" class="form-control" value="">
							</div>
							
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" name="email" class="form-control" value="">
							</div>
														
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" name="password" class="form-control" value="">
							</div>
							<div class="form-group">
								<a id="user-id" href="delete_user.php?id=<?php //echo $user->id; ?>" rel="" class="btn btn-danger pull-left">Delete</a>
								<input type="submit" name="create" class="btn btn-primary pull-right" value="create">
							</div>
							
                        </div>
					</form>
						
						
                    </div>
                    
                </div>
                <!-- /.row -->
            