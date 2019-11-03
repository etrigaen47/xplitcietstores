<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/xplitcietstores/xp-basecontrol/xp-core/xp-init.php';
    include 'xp-includes/xp-head.php';
    include 'xp-includes/xp-navigation.php';
        if(isset($_GET['add']) || isset($_GET['edit'])) {
            $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
            $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
            $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']): '');
            $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
            //$categories = ((isset($_POST['categories']) && $_POST['categories'] != '')?sanitize($_POST['child']):'');
            if (isset($_GET['edit'])){
                $edit_id = (int)$_GET['edit'];
                $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
                $product = mysqli_fetch_assoc($productResults);
                $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
                $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
            }
            if ($_POST) {
                $price = sanitize($_POST['price']);
                $list_price = sanitize($_POST['list_price']);
                $sizes = sanitize($_POST['sizes']);
                $description = sanitize($_POST['description']);
                $errors = array();
                if (!empty($_POST['sizes'])) {
                    $sizeString = sanitize($_POST['sizes']);
                    $sizeString = rtrim($sizeString, ',');
                    $sizesArray = explode(',', $sizeString);
                    $sArray = array();
                    $qArray = array();
                    foreach ($sizesArray as $ss) {
                        $s = explode(':', $ss);
                        $sArray[] = $s[0];
                        $qArray[] = $s[1];
                    }
                } else {
                    $sizesArray = array();
                }
                $required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
                foreach ($required as $field) {
                    if ($_POST[$field] == '') {
                        $errors[] = 'All Fields With Asterisk are required';
                        break;
                    }
                }
                if (!empty($_FILES)) {
                   // var_dump($_FILES);
                    $photo = $_FILES['photo'];
                    $name = $photo['name'];
                    $nameArray = explode('.', $name);
                    $filename = $nameArray[0];
                    $fileExt = $nameArray[1];
                    $mime = explode('/', $photo['type']);
                    $mimeType = $mime[0];
                    $mimeExt = $mime[1];
                    $tmpLocation = $photo['tmp_name'];
                    $fileSize = $photo['size'];
                    $allowed = array('png', 'jpg', 'jpeg', 'gif');
                    $uploadName = md5(microtime()) . '.' . $fileExt;
                    $uploadPath = BASEURL . 'media/products-images/' . $uploadName;
                    $dbpath = '/xplitcietstores/media/products-images/' . $uploadName;
                    $allowed_format = $allowed[0] . ', ' .
                        $allowed_format = $allowed[1] . ', ' .
                            $allowed_format = $allowed[2] . ', ' .
                                $allowed_format = $allowed[3];
                    if ($mimeType != 'image') {
                        $errors[] = 'The FIle must Be an Image.';
                    }
                    if (!in_array($fileExt, $allowed)) {
                        $errors[] = 'The File Extension must be in either ' . $allowed_format . ' file format.';
                    }
                    if ($fileSize > 25000000) {
                        $errors[] = 'The File Size must be Under 25MB.';
                    }
                    if ($fileExt != $mimeExt && ($mimeExt == 'jpg' && $fileExt != 'jpg')) {
                        // OR ($mimeExt == 'jpg' && $fileExt != 'jpg') OR ($mimeExt == 'png' && $fileExt != 'png') OR ($mimeExt == 'gif' && $fileExt != 'gif')
                        $errors[] = 'File Extension Does not match the File.';
                    }
                }
                if (!empty($errors)) {
                    echo display_errors($errors);
                } else {
                    //upload file and insert into database
                    move_uploaded_file($tmpLocation, $uploadPath);
                    $sql_insert_db = "INSERT into products (`title`,`price`,`list_price`,`brand`,`categories`,`sizes`,`images`,`description`) 
                    VALUE ('$title','$price','$list_price','$brand','$categories','$sizes','$dbpath','$description')";
                    $db->query($sql_insert_db);
                    header("Location: xp-products?Product%20Added%20Successfully");
                }
            }
            ?>
            <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Product</h2>
            <a href="xp-products.php" class="btn btn-dark pull-left" id="add-product-btn" style="background: #000000;color: #ffffff;">Cancel Edit</a>
            <hr />
            <form action="xp-products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" method="POST" enctype="multipart/form-data">
                <div class="form-group col-md-3">
                    <label for="title">*Title</label>
                    <input type="text" name="title" class="form-control" autocomplete="off" id="title" value="<?=$title;?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="brand">*Brand</label>
                    <select class="form-control" id="brand" name="brand">
                        <option value=""<?=(($brand == '')?' selected':'');?>></option>
                        <?php while($brd_brand = mysqli_fetch_assoc($brandQuery)): ?>
                            <option value="<?=$brd_brand['id'];?>"<?=(($brand == $brd_brand['id'])?' selected':'');?>><?=$brd_brand['brand'];?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="parent">*Parent Category</label>
                    <select class="form-control" id="parent" name="parent">
                        <option value=""<?=((isset($_POST['parent']) && $_POST['parent'] == '')?' selected':'');?>></option>
                        <?php while($parent = mysqli_fetch_assoc($parentQuery)): ?>
                        <option value="<?=$parent['id'];?>"<?=((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])?' select':'');?>><?=$parent['category'];?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="child">*Child Category</label>
                    <select id="child" name="child" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="price">*Price</label>
                    <input type="text" id="price" name="price" class="form-control" value="<?=((isset($_POST['price']))?sanitize($_POST['price']):'');?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="price">List Price</label>
                    <input type="text" id="list_price" name="list_price" class="form-control" value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']):'');?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="quantity and sizes">*Quantity and Sizes</label>
                    <button class="btn btn-info form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity and Sizes</button>
                </div>
                <div class="form-group col-md-3">
                    <label for="sizes">*Sizes and Qty Preview</label>
                    <input type="text" class="form-control" readonly name="sizes" id="sizes" value="<?=((isset($_POST['sizes']))?$_POST['sizes']:'');?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="photo">Product Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="description">*Product Description</label>
                    <textarea id="description" name="description" class="form-control" rows="6"><?=((isset($_POST['description']))?sanitize($_POST['description']):'');?></textarea>
                </div>
                <div class="col-md-5 text-md-center">
                    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="btn btn-success form-control" style="height: 50px;">
                </div>
            </form>
            <!-- MODAL FOR ADDING SIZES AND QUANTITY OF PRODUCT -->
            <div class="modal fade bs-example-modal-lg col-lg-12" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sizesModalLabel">SIZES AND QUANTITY</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <?php for($i=1;$i <= 12;$i++): ?>
                                    <div class="form-group col-md-4">
                                        <label for="size<?=$i;?>">Size</label>
                                        <input type="text" class="form-control" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="qty<?=$i;?>">Quantity</label>
                                        <input type="number" class="form-control" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0">
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }else{
            $sql_product = "SELECT * FROM products WHERE deleted = 0";
            $product_result = $db->query($sql_product);
            if (isset($_GET['featured'])) {
                $id = (int)$_GET['id'];
                $featured = (int)$_GET['featured'];
                $sql_featured = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
                $db->query($sql_featured);
                header("Location: xp-products.php?Update Successful");
            }
        ?>
        <h2 class="text-center">Products</h2>
        <a href="xp-products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Products</a>
        <div class="clearfix"></div>
        <hr/>
        <table class="table table-bordered table-condensed table-striped">
            <thead>
            <th></th>
            <th>Products</th>
            <th>Price</th>
            <th>Categories</th>
            <th>Featured</th>
            <th>Sold</th>
            </thead>
            <tbody>
            <?php while ($product = mysqli_fetch_assoc($product_result)):
                $childID = $product['categories'];
                $sql_category = "SELECT * FROM categories WHERE id = '$childID'";
                $result_cat = $db->query($sql_category);
                $child_category = mysqli_fetch_assoc($result_cat);
                $parentID = $child_category['parent'];
                $sql_parent = "SELECT * FROM categories WHERE id = '$parentID'";
                $result_parent = $db->query($sql_parent);
                $parent = mysqli_fetch_assoc($result_parent);
                $category = $parent['category'] . '~' . $child_category['category'];
                ?>
                <tr>
                    <td>
                        <a href="xp-products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><i class="fa fa-pen"></i></a>
                        <a href="?delete=<?=$product['id'];?>" class="btn btn-xs btn-danger"><i class="fa fa-remove-format"></i></a>
                    </td>
                    <td><?=$product['title'];?></td>
                    <td><?=money($product['price']);?></td>
                    <td><?=$category;?></td>
                    <td>
                        <a href="xp-products.php?featured=<?=(($product['featured'] == 0) ? '1' : '0');?>&id=<?=$product['id'];?>"
                           class="btn btn-xs btn-default">
                            <i class="fa fa-<?=(($product['featured'] == 1) ? 'minus' : 'plus');?>"></i>
                        </a>
                        &nbsp; <?=(($product['featured'] == 1) ? 'Featured Product' : '');?>
                    </td>
                    <td>0</td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php
    }       include 'xp-includes/xp-footer.php';
