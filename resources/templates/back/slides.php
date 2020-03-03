

  <div class="row">

    <h3 class="bg-success"><?php display_message(); ?></h3>

 <div class="col-xs-4">
 <?php add_slide(); ?>

 <form action="" method="post" enctype="multipart/form-data">
  
<div class="form-group">

<input type="file" name="file">

</div>

<div class="form-group">
<label for="title">Slide Title</label>
<input type="text" name="slide_title" class="form-control">

</div>

<div class="form-group">

<input type="submit" class="btn btn-primary" name="add_slide" value="Add Slide">

</div>

 </form>

 </div>


 <div class="col-xs-8">
	<!--<a href="#" data-toggle="modal" data-target="#photo-library"><img class="img-responsive" src="#" name="user_image"></a>-->
	<?php get_current_slide(); ?>					



 </div>

</div><!-- ROW-->

<hr>

<h1>Slides Available</h1>

<div class="row">
  
<?php get_thumbnails_in_admin(); ?>



</div>


