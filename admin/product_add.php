<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['loggged_in'])){
    header('Location: login.php');
}

if($_SESSION['role']!=1){
    header('Location: login.php');
}
// validation
if ($_POST){
if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category_id']) ||
 empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image']['name'])){
    if (empty($_POST['name'])){
        $nameError= 'Category name is required';
    }
    if (empty($_POST['description'])){
        $descError= 'Category description is required';
    }
    if (empty($_POST['category_id'])){
        $catError= 'Category is required';
    }
    if (empty($_POST['quantity'])){
        $qtyError= 'Quantity is required';
    }elseif ($_POST['quantity'] < 0 || (is_numeric($_POST['quantity'])!=1)){
        $qtyError= 'Quantity must be greater than 0 and a number';
    }
    if (empty($_POST['price'])){
        $priceError= 'Price is required';
    } elseif ($_POST['price'] < 0 || (is_numeric($_POST['price'])!=1)){
        $priceError= 'Price must be greater than 0 and a number';
    }

    if (empty($_FILES['image']['name'])){
        $imgError= 'Image is required';
    }else{
        $file='images/'.($_FILES['image']['name']);
        $fileType= pathinfo($file,PATHINFO_EXTENSION);
        if ($fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg'){
            $imgError= 'Image must be jpg, png or jpeg';
    }
    } 
 }

//Insert Into Database
else{
    $name= $_POST['name'];
    $description=$_POST['description'];
    $category_id=$_POST['category_id'];
    $quantity=$_POST['quantity'];
    $price=$_POST['price'];
    $image=$_FILES['image']['name'];


    $stmt= $pdo->prepare("INSERT INTO products (name,description,category_id,quantity,price,image) VALUES (:name,:description,:category_id,:quantity,:price,:image)");
    $results= $stmt->execute([
    ':name' => $name, 
    ':description' => $description,
     ':category_id' => $category_id,
      ':quantity' => $quantity, 
      ':price' => $price, 
      ':image' => $image]);
      if ($results){
        $file='images/'.($_FILES['image']['name']); 
        move_uploaded_file($_FILES['image']['tmp_name'], $file);
      }
      
    if ($results){
        echo "<script>alert('Product added successfully!'); window.location.href='../admin/index.php';</script>";
    }

}
}
?>

<?php include('header.php'); ?>
    <!-- Main content --> 
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Category</h3>
                </div>  
              <div class="card-body">
                <form class="" action="product_add.php" method="post" enctype="multipart/form-data">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token'];?>"> 
                  <div class="form-group">
                    <label for="">Name</label><p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><p style="color:red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                    <textarea class="form-control" name="description" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                  <label for="">Category</label><p style="color:red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                  <select class="form-control" name="category_id">
                    <option value="">Select Category</option>
                    <?php
                      $stmt = $pdo->prepare("SELECT * FROM categories");
                      $stmt->execute();
                      $results = $stmt->fetchAll();
                      if ($results) {
                        foreach ($results as $result) {
                          echo '<option value="'.$result['id'].'">'.$result['name'].'</option>';
                        }
                      }
                    ?> 
                  </select>
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label><p style="color:red"><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
                    <input type="number" class="form-control" name="quantity" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Price</label><p style="color:red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input type="number" class="form-control" name="price" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><p style="color:red"><?php echo empty($imgError) ? '' : '*'.$imgError; ?></p>
                    <input type="file" class="form-control" name="image" value="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="category.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php include('footer.html')?>
