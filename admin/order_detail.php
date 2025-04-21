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
                    <table class="table table-bordered">   
                    <thead class="thead-light"> 
                        <tr>
                       
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                    
                        
                            <tr>
                            <td><?php echo escape($orderResult['id']); ?></td>
                            <td>
                                <?php 
                                $userStmt=$pdo->prepare ("SELECT * FROM users WHERE id=".$orderResult['user_id']);
                                $userStmt->execute();
                                $userResult=$userStmt->fetch();
                                echo escape($userResult['name']);
                                ?>
                            </td>
                            <td>
                                <?php 
                                $productStmt=$pdo->prepare ("SELECT * FROM products WHERE id=".$result['product_id']);
                                $productStmt->execute();
                                $productResult=$productStmt->fetch();
                                echo escape($productResult['name']);
                                ?>
                            </td>
                            <td><?php echo escape($result['quantity'])?></td>
                            <td><?php echo escape($orderResult['total_price'])?></td>
                            <td><?php echo escape($result['order_date'])?></td>
                        
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
 