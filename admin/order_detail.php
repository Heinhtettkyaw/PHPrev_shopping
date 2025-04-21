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
    ?>


    <?php include('header.php'); ?>
        <!-- Main content -->
        <div class="content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Details</h3>
                </div>
                <?php
                

                    

                  
                    $stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=:id");
                    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetch();

                    $ostmt=$pdo->prepare("SELECT * FROM sale_orders WHERE id=".$_GET['id']);
                    $ostmt->execute();
                    $orderResult=$ostmt->fetch();
                    

                ?>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <br>
                   <a href="order.php" class="btn btn-primary">Back</a>
                   <br><br> 
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Order ID</th>
                                <td><?php echo escape($orderResult['id']); ?></td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>
                                    <?php 
                                    $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$orderResult['user_id']);
                                    $userStmt->execute();
                                    $userResult = $userStmt->fetch();
                                    echo escape($userResult['name']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <td>
                                    <?php 
                                    $productStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$result['product_id']);
                                    $productStmt->execute();
                                    $productResult = $productStmt->fetch();
                                    echo escape($productResult['name']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td><?php echo escape($result['quantity']); ?></td>
                            </tr>
                            <tr>
                                <th>Total Price</th>
                                <td><?php echo escape($orderResult['total_price']); ?></td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td><?php echo escape($result['order_date']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    
                   
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
