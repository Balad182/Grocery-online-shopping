<?php add_subcategory(); ?>
<h1 class="page-header">
  <?php echo show_category_title($_GET['cat_id'])?> Subcategories

</h1>
<h3 class="text-center bg-success"><?php display_message(); ?></h3>


<div class="col-md-4">
    
    <form action="" method="post">
    
		<div class="form-group">
            <label for="category-title">Category</label>
            <select name="subcat_cat_id" id="" class="form-control">
                <option>Select Category</option>
				<?php display_categories(); ?>
			</select>
        </div>
        <div class="form-group">
            <label for="subcategory-title">Title</label>
            <input type="text" name="subcat_title" class="form-control">
        </div>
		
        <div class="form-group">
            
            <input type="submit" name="add_subcategory" class="btn btn-primary" value="Add SubCategory">
        </div>      


    </form>


</div>


<div class="col-md-8">

    <table class="table">
            <thead>

        <tr>
            <th>Subcat Id</th>
			<th>Cat Id</th>
            <th>Subcat Title</th>
        </tr>
            </thead>


    <tbody>
        <?php show_subcategories($_GET['cat_id']); ?>
    </tbody>

        </table>

</div>



                













            