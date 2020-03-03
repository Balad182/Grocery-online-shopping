<?php
$upload_directory = "uploads";

function set_message($msg){
	if(!empty($msg)){
		$_SESSION['message'] = $msg;
	}else{
		$_SESSION['message'] = "";
	}
}

function display_message(){
	if(isset($_SESSION['message'])){
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function redirect($location){
	return header("Location: $location");
}

function query($sql){
	global $connection;
	
	return mysqli_query($connection, $sql);
}

function confirm($result){
	global $connection;
	
	if(!$result){
		die("Query Failed" . mysqli_error($connection));
	}
}

function last_id(){
	global $connection;
	return mysqli_insert_id($connection);
}

function escape_string($string){
	global $connection;
	
	return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result){
	return mysqli_fetch_array($result);
}

function get_product(){
	$query = query("SELECT * FROM products WHERE product_quantity >= 1");
	confirm($query);
	
	$rows = mysqli_num_rows($query);
	
	if(isset($_GET['page'])){
		$page = preg_replace('#[^0-9]#', '', $_GET['page']);
	}else{
		$page = 1;
	}
	
	$perPage = 3;
	$lasPage = ceil($rows / $perPage);
	
	if($page < 1){
		$page = 1;
	}elseif($page > $lasPage){
		$page = $lasPage;
	}
	
	$middleNumbers = '';
	
	$sub1 = $page - 1;
	$sub2 = $page - 2;
	$add1 = $page + 1;
	$add2 = $page + 2;
	
	if($page == 1){
	$middleNumbers .= '<li class="page-item active"><a>'.$page.'</a></li>';
	$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">'.$add1.'</a></li>';
	
	}elseif($page == $lasPage){
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">'.$sub1.'</a></li>';
		$middleNumbers .= '<li class="page-item active"><a>'.$page.'</a></li>';
	}elseif($page > 2 && $page < ($lasPage - 1)){
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">'.$sub2.'</a></li>';
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">'.$sub1.'</a></li>';
		$middleNumbers .= '<li class="page-item active"><a>'.$page.'</a></li>';
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">'.$add1.'</a></li>';
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">'.$add2.'</a></li>';
		
	}elseif($page > 1 && $page < $lasPage){
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">'.$sub1.'</a></li>';
		$middleNumbers .= '<li class="page-item active"><a>'.$page.'</a></li>';
		$middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">'.$add1.'</a></li>';
		
	}
	
	$limit = 'LIMIT ' . ($page - 1) * $perPage . ',' . $perPage;
	
	$query2 = query("SELECT * FROM products {$limit}");
	confirm($query2);
	/*if($lasPage != 1){
	echo "Page {$page} of {$lasPage}";
	}*/
	
	$outputPagination = "";
	if($page != 1){
		$prev = $page - 1;
		$outputPagination .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$prev.'">Back</a></li>';
	}
	$outputPagination .= $middleNumbers;
	if($page != $lasPage){
		$next = $page + 1;
		$outputPagination .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a></li>';
	}
	
	while($row = fetch_array($query2)){
		$product_image = display_image($row['product_image']);
		$product = <<<DELIMETER
<div class="col-sm-4 col-lg-4 col-md-4">
	<div class="thumbnail">
		<a href="item.php?id={$row['product_id']}"><img style="height: 90px" src="../resources/{$product_image}" alt=""></a>
		<div class="caption">
			<h4 class="pull-right">AED{$row['product_price']}</h4>
			<h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
			</h4>
			<p>{$row['short_desc']}</p>
			<a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to Cart</a>
		</div>
		
	</div>
</div>
DELIMETER;
		echo $product;
		
	}
	echo "<div class='row text-center'><ul class='pagination'>{$outputPagination}</ul></div>";
}

function get_categories(){
	$query = query("SELECT * FROM categories");
	confirm($query);
	while($row = fetch_array($query)){
		$categories_links = <<<DELIMETER
	<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;
		echo $categories_links;
	}
}

function get_product_in_cat_page(){
	$query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " AND product_quantity >= 1");
	confirm($query);
	while($row = fetch_array($query)){
		$product_image = display_image($row['product_image']);
		$product = <<<DELIMETER
	<div class="col-md-3 col-sm-6 hero-feature">
		<div class="thumbnail">
			<img src="../resources/{$product_image}" alt="">
			<div class="caption">
				<h3>{$row['product_title']}</h3>
				<p>it is good to be good</p>
				<p>
					<a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
				</p>
			</div>
		</div>
	</div>
DELIMETER;
echo $product;
	}
}

function get_product_in_shop_page(){
	$query = query("SELECT * FROM products WHERE product_quantity >= 1");
	confirm($query);
	while($row = fetch_array($query)){
		$product_image = display_image($row['product_image']);
		$product = <<<DELIMETER
	<div class="col-md-3 col-sm-6 hero-feature">
		<div class="thumbnail">
			<img src="../resources/{$product_image}" alt="">
			<div class="caption">
				<h3>{$row['product_title']}</h3>
				<p>it is good to be good</p>
				<p>
					<a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
				</p>
			</div>
		</div>
	</div>
DELIMETER;
echo $product;
	}
}

function login_user(){
	if(isset($_POST['submit'])){
		$username = escape_string($_POST['username']);
		$user_password = escape_string($_POST['user_password']);
		
		$query = query("SELECT * FROM users WHERE username = '{$username}' AND user_password = '{$user_password}'");
		confirm($query);
		if(mysqli_num_rows($query) == 0){
			set_message("Your username or password are wrong");
			redirect("login.php");
		}else{
			$_SESSION['username'] = $username;
			redirect("admin");
		}
	}
}

function send_message(){
	if(isset($_POST['submit'])){
		$to      = "balad182@gmail.com";
		$from_name    = $_POST['name'];
		$email   = $_POST['email'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
		
		$headers = "From: {$from_name} {$email}";
		
		$result = mail($to, $subject, $message, $headers);
		if(!$result){
			set_message("Sorry your mesaage is not sent");
			redirect("contact.php");
		}else{
			set_message("Your message is sent");
			redirect("contact.php");
		}
	}
}

function display_orders(){
	$query = query("SELECT * FROM orders");
	confirm($query);
	while($row = fetch_array($query)){
	$orders = <<<DELIMETER
	<tr>
		<td>{$row['order_id']}</td>
		<td>{$row['order_amount']}</td>
		<td>{$row['order_transaction']}</td>
		<td>{$row['order_currency']}</td>
		<td>{$row['order_status']}</td>
		<td><a class="btn btn-danger" href="index.php?delete_order_id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
	</tr>
DELIMETER;
	echo $orders;
	}
}

/*****Admin products page *******/

function display_image($picture){
	global $upload_directory;
	return $upload_directory . DS . $picture;
}

function get_product_in_admin(){
	$query = query("SELECT * FROM products");
	confirm($query);
	while($row = fetch_array($query)){
		$product_image = display_image($row['product_image']);
		$sub_category_title = show_subcategory_title($row['product_subcat_id']);
		$category_title = show_category_title($row['product_category_id']);
		$product = <<<DELIMETER
<tr>
	<td>{$row['product_id']}</td>
	<td>{$row['product_title']}<br>
	<a href="index.php?edit_product&id={$row['product_id']}"><img width="100" src="../../resources/{$product_image}" alt="image"></a>
	</td>
	<td>{$category_title}</td>
	<td>{$sub_category_title}</td>
	<td>AED{$row['product_price']}</td>
	<td>{$row['product_quantity']}</td>
	<td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
DELIMETER;
		echo $product;
	}
}

function add_product(){
	if(isset($_POST['publish'])){
		$product_title 		 = escape_string($_POST['product_title']);
		$product_category_id = escape_string($_POST['product_category_id']);
		$product_subcat_id 	 = escape_string($_POST['product_subcat_id']);
		$product_description = escape_string($_POST['product_description']);
		$product_price 		 = escape_string($_POST['product_price']);
		$product_quantity	 = escape_string($_POST['product_quantity']);
		$short_desc 		 = escape_string($_POST['short_desc']);
		$product_image 		 = $_FILES['image']['name'];
		$image_temp_location = $_FILES['image']['tmp_name'];
		
		move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY . DS . $product_image);
					
		$query = query("INSERT INTO products(product_title, product_category_id, product_subcat_id, product_description, product_price, product_quantity, short_desc, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_subcat_id}', '{$product_description}', '{$product_price}', '{$product_quantity}', '{$short_desc}', '{$product_image}')");
		$last_id = last_id();
		confirm($query);
		set_message("New product with id {$last_id} was just added");
		redirect("index.php?products");
	}
}

function display_categories(){
	$query = query("SELECT * FROM categories");
	confirm($query);
	while($row = fetch_array($query)){
		$categories_display = <<<DELIMETER
	<option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;
		echo $categories_display;
	}
}

function display_subcategories_in_add_page(){
	$query = query("SELECT * FROM sub_categories");
	confirm($query);
	while($row = fetch_array($query)){
		$subcategories_display = <<<DELIMETER
	<option value="{$row['subcat_id']}">{$row['subcat_title']}</option>
DELIMETER;
		echo $subcategories_display;
	}
}

function show_categories_menu(){
	$category_menu_query = query("SELECT * FROM categories");
	confirm($category_menu_query);
	while($category_row = fetch_array($category_menu_query)){
		$cat_menu = <<<DELIMETER
			<li>
				<a href="index.php?cat_id={$category_row['cat_id']}">{$category_row['cat_title']}</a>
			</li>
DELIMETER;
echo $cat_menu;
}
}

function show_subcategories($product_category_id){
	$category_query = query("SELECT * FROM sub_categories WHERE subcat_cat_id = {$product_category_id}");
	confirm($category_query);
	while($category_row = fetch_array($category_query)){
		$subcategory = <<<DELIMETER
		<tr>
            <td>{$category_row['subcat_id']}</td>
			<td>{$category_row['subcat_cat_id']}</td>
            <td>{$category_row['subcat_title']}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$category_row['subcat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
echo $subcategory;		
		
	}
}

function show_category_title($product_category_id){
	$category_query = query("SELECT * FROM categories WHERE cat_id = {$product_category_id}");
	confirm($category_query);
	while($category_row = fetch_array($category_query)){
		return $category_row['cat_title'];
	}
}

function show_subcategory_title($product_category_id){
	$subcategory_query = query("SELECT * FROM sub_categories WHERE subcat_id = {$product_category_id}");
	confirm($subcategory_query);
	while($subcategory_row = fetch_array($subcategory_query)){
		return $subcategory_row['subcat_title'];
	}
}

function update_product(){
	if(isset($_POST['update'])){
		$product_title 		 = escape_string($_POST['product_title']);
		$product_category_id = escape_string($_POST['product_category_id']);
		$product_description = escape_string($_POST['product_description']);
		$product_price 		 = escape_string($_POST['product_price']);
		$product_quantity	 = escape_string($_POST['product_quantity']);
		$short_desc 		 = escape_string($_POST['short_desc']);
		$product_image 		 = $_FILES['image']['name'];
		$image_temp_location = $_FILES['image']['tmp_name'];
		
		if(empty($product_image)){
			$pic_query = query("SELECT product_image FROM products WHERE product_id = " . escape_string($_GET['id']) . " ");
			confirm($pic_query);
			while($pic = fetch_array($pic_query)){
				$product_image = $pic['product_image'];
			}
		}
		move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY . DS . $product_image);
					
		$query = "UPDATE products SET ";
		$query .= "product_title = '{$product_title}', ";
		$query .= "product_category_id = '{$product_category_id}', ";
		$query .= "product_description = '{$product_description}', ";
		$query .= "product_price = '{$product_price}', ";
		$query .= "product_quantity = '{$product_quantity}', ";
		$query .= "short_desc = '{$short_desc}', ";
		$query .= "product_image = '{$product_image}' ";
		$query .= "WHERE product_id = " . escape_string($_GET['id']);
		$query = query($query);
		confirm($query);
		set_message("the product has been updated");
		redirect("index.php?products");
	}
}

function get_report_in_admin(){
	$query = query("SELECT * FROM reports");
	confirm($query);
	while($row = fetch_array($query)){
		$report = <<<DELIMETER
<tr>
	<td>{$row['report_id']}</td>
	<td>{$row['product_id']}</td>
	<td>{$row['order_id']}</td>
	<td>{$row['product_title']}<br>
	<a href="#"><img width="100" src="../../resources/" alt="image"></a>
	</td>
	<td>{$row['product_price']}</td>
	<td>{$row['product_quantity']}</td>
	<td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>
DELIMETER;
		echo $report;
	}
}

function show_categories_in_admin(){
	$category_query = query("SELECT * FROM categories");
	confirm($category_query);
	while($row = fetch_array($category_query)){
		$cat_id = $row['cat_id'];
		$cat_title = $row['cat_title'];
		
		$category = <<<DELIMETER
		<tr>
            <td>{$cat_id}</td>
            <td>{$cat_title}</td>
			<td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
echo $category;
	}
}

function add_subcategory(){
	if(isset($_POST['add_subcategory'])){
		$subcat_cat_id = escape_string($_POST['subcat_cat_id']);
		$subcat_title = escape_string($_POST['subcat_title']);
		if(empty($subcat_title) || $subcat_title == " "){
			echo "<p class='text-center bg-danger'>pls category space can not be empty</p>";
		}else{
		$query = query("INSERT INTO sub_categories (subcat_cat_id, subcat_title) VALUES ('{$subcat_cat_id}', '{$subcat_title}')");
		confirm($query);
		set_message("Subcategory Added Successfully");
		
		}
	}
}

function add_category(){
	if(isset($_POST['add_category'])){
		$cat_title = escape_string($_POST['cat_title']);
		if(empty($cat_title) || $cat_title == " "){
			echo "<p class='text-center bg-danger'>pls category space can not be empty</p>";
		}else{
		$query = query("INSERT INTO categories (cat_title) VALUES ('{$cat_title}')");
		confirm($query);
		set_message("Category Added Successfully");
		
		}
	}
}

function display_users(){
	$query = query("SELECT * FROM users");
	confirm($query);
	while($row = fetch_array($query)){
		$user = <<<DELIMETER
	<tr>
		<td>{$row['user_id']}</td>
		<td>{$row['username']}</td>
		<td>{$row['user_email']}</td>
	   <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
	</tr>	
DELIMETER;
echo $user;
	}
				
}

function add_user(){
	if(isset($_POST['create'])){
		$username = escape_string($_POST['username']);
		$user_email = escape_string($_POST['user_email']);
		$user_password = escape_string($_POST['user_password']);
		$file = $_FILES['user_image'];
		$user_photo = $file['name'];
		$photo_temp = $file['tmp_name'];
		
		move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);
		
		if(empty($username) || $username == " "){
			echo "<p class='text-center bg-danger'>pls username can not be empty</p>";
		}else{
		$query = query("INSERT INTO users (username, email, password, user_photo) VALUES('{$username}', '{$email}', '{$password}', '{$user_photo}')");
		confirm($query);
		set_message("User Added Successfully");
		redirect("index.php?users");
		
		}
	}
}

/******** Slides Functions *******/
function add_slide(){
	if(isset($_POST['add_slide'])){
		$slide_title = escape_string($_POST['slide_title']);
		$slide_image = $_FILES['file']['name'];
		$slide_image_loc = $_FILES['file']['tmp_name'];
		
		if(empty($slide_title) || empty($slide_image)){
			echo "<h3 class='text-center bg-danger'>The field can not be empty</h3>";
		}else{
			move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);
			
			$query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}', '{$slide_image}')");
			confirm($query);
			set_message("Slide added successfully");
			redirect("index.php?slides");
		}
	}
}

function get_current_slide(){
	$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
	confirm($query);
	while($row = fetch_array($query)){
		$slide_image = display_image($row['slide_image']);
		$current_slide = <<<DELIMETER
		
			<img class="img-responsive" src="../../resources/{$slide_image}" alt="">
		
DELIMETER;
echo $current_slide;
	}
}

function get_active_slide(){
	$query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
	confirm($query);
	while($row = fetch_array($query)){
		$slide_image = display_image($row['slide_image']);
		$active_slide = <<<DELIMETER
		<div class="item active">
			<img class="slide-image" src="../resources/{$slide_image}" alt="">
		</div>
DELIMETER;
echo $active_slide;
	}
}

function get_slides(){
	$query = query("SELECT * FROM slides");
	confirm($query);
	while($row = fetch_array($query)){
		$slide_image = display_image($row['slide_image']);
		$slides = <<<DELIMETER
		<div class="item">
			<img class="slide-image" src="../resources/{$slide_image}" alt="">
		</div>
DELIMETER;
echo $slides;
	}
}

function get_thumbnails_in_admin(){
	$query = query("SELECT * FROM slides");
	confirm($query);
	while($row = fetch_array($query)){
		$slide_image = display_image($row['slide_image']);
		$slide_thumbnails = <<<DELIMETER
		<div class="col-xs-6 col-md-3 image_container">
			<a href="index.php?delete_slide_id={$row['slide_id']}">
			<img class="img-responsive slide_image" src="../../resources/{$slide_image}" alt="image">
			</a>
			<p>{$row['slide_title']}</p>
		</div>
DELIMETER;
echo $slide_thumbnails;
	}
}
?>