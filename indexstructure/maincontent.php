<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/5/2019
 * Time: 11:56 AM
 */

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $db->query($sql);
?>
    <!--------------------Main Content-------------------------->
    <div class="col-md-8">
        <div class="row">
            <h2 class="text-center">Featured Products</h2>
            <?php while($product = mysqli_fetch_assoc($featured)) : ?>
            <div class="col-lg-3">
                    <h4><?=$product['title'];?></h4>
                    <img src="<?=$product['images'];?>" alt="<?=$product['title'];?>" class="img-thumbnail" style="width: 240px;height: 240px"/>
                    <p class="list-price text-danger">List Price: <s>$<?=$product['list_price'];?></s></p>
                    <p class="price">Our Price: $<?=$product['price'];?></p>
                    <button class="btn btn-sm btn-success" type="button" onclick="detailsModal(<?=$product['id'];?>)";>Details</button>
                  </div>
            <?php endwhile; ?>
        </div>
        <br>
    </div>