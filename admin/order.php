<?php
    session_start();
    require '../config/config.php';
    require '../config/common.php';


    if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    }
    if ($_SESSION['role'] != 1) {
    header('Location: login.php');
    }

    if (isset($_POST['search']) && $_POST['search'] !== '') {
        setcookie(
            'search',
            $_POST['search'] ?? '',
            time() + (86400 * 30),
            "/"
          );          
    }else{
    if (empty($_GET['pageno'])) {  
        unset($_COOKIE['search']); 
        setcookie('search', '', time() - 3600, '/'); 
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
                    <h3 class="card-title">Order Listings</h3>
                </div>
                <?php
                    if (!empty($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                    }else{
                    $pageno = 1;
                    }

                    $numOfrecs = 5;
                    $offset = ($pageno - 1) * $numOfrecs;

                    if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();

                    $total_pages = ceil(count($rawResult) / $numOfrecs);

                    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    }else{
                    $searchKey = $_POST['search'];
                    $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();

                    $total_pages = ceil(count($rawResult) / $numOfrecs);

                    $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    }

                ?>
                <!-- /.card-header -->
                <div class="card-body">
                    <div>
                    <a href="order_add.php" type="button" class="btn btn-success">New Order</a>
                    </div>
                    <br>
                    <table class="table table-bordered">   
                    <thead>
                        <tr>
                        <th style="width: 10px">#</th>
                        <th>Customer</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th style="width: 40px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($result) {
                        $i = 1;
                        foreach ($result as $value) { ?>
                            <tr>
                            <td><?php echo $i;?></td>
                            <td>
                                <?php 
                                $userStmt=$pdo->prepare ("SELECT * FROM users WHERE id=".$value['user_id']);
                                $userStmt->execute();
                                $userResult=$userStmt->fetch();
                                echo escape($userResult['name']);
                                ?>
                            </td>
                            <td><?php echo escape($value['total_price'])?></td>
                            <td><?php echo escape($value['order_date'])?></td>
                             
                            <td>
                                <div class="btn-group">
                                <div class="container">
                                    <a href="order_detail.php?id=<?php echo $value['id']?>" type="button" class="btn btn-warning"> Details</a>
                                </div>
                                
                                </div>
                            </td>
                            </tr>
                        <?php
                        $i++;
                        }
                        }
                        ?>
                    </tbody>
                    </table><br>
                    <nav aria-label="Page navigation example" style="float:right">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                        <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
                        <a class="page-link" href="<?php if($pageno <= 1) {echo '#';}else{ echo "?pageno=".($pageno-1);}?>">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                        <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                        <a class="page-link" href="<?php if($pageno >= $total_pages) {echo '#';}else{ echo "?pageno=".($pageno+1);}?>">Next</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
                    </ul>
                    </nav>
                </div>
                <!-- /.card-body -->

                </div>
                <!-- /.card -->
            </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    <?php include('footer.html')?>
 