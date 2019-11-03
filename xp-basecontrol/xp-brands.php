<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/11/2019
 * Time: 12:54 PM
 */
require_once 'xp-core/xp-init.php';
include 'xp-includes/xp-head.php';
include 'xp-includes/xp-navigation.php';
//get brands from database
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql);
$errors = array();
//edit brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sqlEdit = "SELECT * FROM brand WHERE id = '$edit_id'";
    $editResult = $db->query($sqlEdit);
    $editBrand = mysqli_fetch_assoc($editResult);
    //header('Location: xp-brands?Brand Edited Successfully');
}
//delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "DELETE FROM brand WHERE id = '$delete_id'";
    $db->query($sql);
    header('Location: xp-brands?Brand Successfully Deleted');
}
//if add form is submitted
if(isset($_POST['add_submit'])){
    $brand = sanitize($_POST['brand']);
    //check if brand is blank
    if($_POST['brand'] == ''){
        $errors[] .= '<h3>YOU MUST ENTER A BRAND</h3>';
    }
    //check if brand exists in database
    $sql = "SELECT * FROM brand WHERE brand = '$brand'";
    if(isset($_GET['edit'])){
        $sql = "SELECT * FROM brand WHERE brand = '$brand' AND != '$edit_id'";
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if($count > 0){
        $errors[] .= '<h4><b>'.strtoupper($brand). '&nbsp; ALREADY EXISTS, PLEASE SELECT ANOTHER BRAND...</b></h4>';
    }

    //display errors
    if(!empty($errors)){
        echo display_errors($errors);
    }else{
        //Add brand to database
        $sql = "INSERT INTO brand (brand) VALUES ('$brand')";
        if(isset($_GET['edit'])){
            $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
        }
        $db->query($sql);
        header('Location: xp-brands?Brand Added Successfully');
    }
}?>
    <h2 class="text-center">Brands</h2>
    <!--Brands Form-->
    <div class="text-center">
        <form class="form-inline" action="xp-brands<?=((isset($_GET['edit']))?'?edit='.$edit_id:'') ;?>" method="POST">
            <div class="form-group">
                <?php
                $brand_value = '';
                    if(isset($_GET['edit'])){
                        $brand_value = $editBrand['brand'];
                    }else{
                        if(isset($_POST['brand'])){
                            $brand_value = sanitize($_POST['brand']);
                        }
                    }
                ?>
                <label for="brand"><?=((isset($_GET['edit']))?'Edit ':'Add A '); ?>Brand</label>
                <input type="text" name="brand" id="brand" class="form-control" autocomplete="off" value="<?=$brand_value;?>">
                <!--the tenary operator above is in two parts and the first part is for what happens when the below input field field is not-empty
                while the second part is what happens when the field is empty-->
                <?php if(isset($_GET['edit'])):
                    echo '<a href="xp-brands" class="btn btn-default">Cancel</a>';
                endif;?>
                <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?>Brand" class="btn btn-md btn-success">
            </div>
        </form>
        <hr />
    </div>
    <hr>
    <table class="table table-striped"><!--table-auto-->
        <thead>
        <th></th><th><h3><b>Brand</b></h3></th><th></th>
        </thead>
        <tbody>
        <?php
        while($brand = mysqli_fetch_assoc($results)) :?>
            <tr>
            <td class="text-center"><a href="xp-brands?edit=<?=$brand['id']; ?>" class="btn btn-sm btn-default"><i class="fa fa-pen"></i></a></td>
            <td class="text-center" style="font-size: 16px;"><b><?= $brand['brand']; ?></b></td>
            <td class="text-center"><a href="xp-brands?delete=<?=$brand['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-remove-format"></i></a></td>
            </tr><?php
        endwhile; ?>
        </tbody>
    </table>
<?php
include 'xp-includes/xp-footer.php';
?>