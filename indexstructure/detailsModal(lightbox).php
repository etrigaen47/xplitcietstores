<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/5/2019
 * Time: 12:00 PM
 */
session_start();
require_once'../core/init.php';
$id = $_POST['id'];
$id = (int)$id;//passing the id variable to be an integer since our id values are integers
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);//takes the result of our sql query and makes it into an associative array
$brand_id = $product['brand'];
$sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
$brand_query = $db->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
$sizestring = $product['sizes'];
$sizestring = rtrim($sizestring,',');
$size_array = explode(',', $sizestring);

ob_start();//starts a buffer that reads up the entire content below
?>
<!---------------------------------Details Modal(light box)------------------------------->
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" onclick="closeModal()" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center"><?= $product['title']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="center-block">
                                <img src="<?= $product['images']; ?>" alt="<?= $product['title']; ?>" class="details img-responsive">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4>Details</h4>
                            <p><?= nl2br($product['description']); ?></p>
                            <hr>
                            <p>Price: $<?= $product['price']; ?></p>
                            <p>Brand: <?= $brand['brand']; ?></p>
                            <form action="add_cart.php" method="POST">
                                <div class="form-group">
                                    <div class="col-xs-3">
                                        <label for="quantity">Quantity:</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity">
                                    </div><div class="col-xs-9"></div>
                                </div><br><br>
                                <div class="form-group">
                                    <label for="size">Size:</label>
                                    <select name="size" id="size" class="form-control">
                                        <option value=""></option><?php
                                            foreach($size_array as $string) {
                                                $string_array = explode(':', $string);
                                                $size = $string_array[0];
                                                $quantity = $string_array[1];
                                                echo '<option value="'.$size.'">'.$size.' ('.$quantity.' Available)</option>';
                                            }
                                    ?></select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" onclick="closeModal()">Close</button>
                <button class="btn btn-warning" type="submit"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
            </div>
        </div>
    </div>
</div>
<script>
function  closeModal() {
  jQuery('#details-modal').modal('hide');
  setTimeout(function(){
      jQuery('#details-modal').remove();
      jQuery('.modal-backdrop').remove();
  },100);
}
</script>
<?php
echo ob_get_clean();
//cleans up the memory of the buffer when data come back from jquery
session_destroy();
?>