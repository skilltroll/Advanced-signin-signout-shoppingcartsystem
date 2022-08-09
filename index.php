<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};
// when clicking on add to cart btn 
if(isset($_POST['add_to_cart'])){
   //put post names in vars
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
         //this query check if the product name and user id in cart table
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
// if it is there run
   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'product already added to cart!';
   }else{ //if not then insert the values to cart table
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart - test</title>
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <script src="https://kit.fontawesome.com/ed510e5ec9.js" crossorigin="anonymous"></script>


</head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
   }
}
?>

<div class="container">

<div class="user-profile"style="border:1px solid #fff;border-radius:10px;">

   <?php
      $select_user = mysqli_query($conn, "SELECT * FROM `user_info` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_user) > 0){
         $fetch_user = mysqli_fetch_assoc($select_user);
      };
   ?>

   <p> <i class="fa-solid fa-user"></i> : <span><?php echo $fetch_user['name']; ?></span> </p>
   <p> <i class="fa-solid fa-envelope"></i> : <span><?php echo $fetch_user['email']; ?></span> </p>
   <div class="flex">
      <!--<a href="login.php" class="btn">login</a> -->
    <!--  <a href="register.php" class="option-btn">register</a> -->
    <i class="fa-solid fa-cart-arrow-down" id="cartico" onclick="location.href='cart.php'" style="cursor:pointer;font-size:20px;display:flex;justify-content:center;align-items:center;color:#a1c4fd;"  onMouseOut="this.style.color='#a1c4fd'" onMouseOver="this.style.color='#333'"></i>
    <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>
   </div>

</div>

<div class="products">

   <h1 class="heading">latest products</h1>

   <div class="box-container">

   <?php
      $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
      if(mysqli_num_rows($select_product) > 0){
         while($fetch_product = mysqli_fetch_assoc($select_product)){
   ?>
   <!--THESE ARE THE PRODUCTS DATA TAKEN FROM products table -->
      <form method="post" class="box" action="" style="border:1px solid #fff;">
         <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_product['name']; ?> <p style="font-size:18px;" name="dec"><?php echo $fetch_product['dec']; ?></p></div>
         <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
   <?php
      };
   };
   ?>

   </div>

</div>      
</body>
</html>