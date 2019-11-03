 <?php
require_once $_SERVER['DOCUMENT_ROOT'].'/xplitcietstores/xp-basecontrol/xp-core/xp-init.php';
include 'xp-includes/xp-head.php';
include 'xp-includes/xp-navigation.php';

$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $db->query($sql);
$errors = array();
$category = '';
$post_parent = '';

//edit category
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'";
    $edit_result = $db->query($edit_sql);
    $edit_category = mysqli_fetch_assoc($edit_result);
}

//delete category
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "SELECT * FROM categories WHERE id = '$delete_id'";
    $result = $db->query($sql);
    $category = mysqli_fetch_assoc($result);
    if($category['parent'] == 0){
            $sql = "DELETE FROM categories WHERE parent = '$delete_id'";
            $db->query($sql);
    }
    $delete_sql = "DELETE FROM categories WHERE id = '$delete_id'";
    $db->query($delete_sql);
    header("Location: xp-categories?Delete%20Successful");
}

//Process Form
if(isset($_POST) && !empty($_POST)){
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
    if(isset($_GET['edit'])){
        $id = $edit_category['id'];
        $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";
    }
    $form_result = $db->query($sqlform);
    $count = mysqli_num_rows($form_result);
    //if category is blank
    if($category == ''){
        $errors[] .= 'Please fill up the BLANK Category';
    }

    //if category exists in the database
    if($count > 0){
        $errors[] .= strtoupper($category). ' already exists. Please select a new Category';
    }

    //dislay errors or Update Databse
    if(!empty($errors)){
        //display errors
         $display = display_errors($errors); ?>
        <script>
            jQuery('document').ready(function(){
                jQuery('#errors').html('<?=$display; ?>');
            });
        </script>
    <?php }else{
        //update database
        $updatesql = "INSERT INTO categories (category, parent) VALUES ('$category', '$post_parent')";
        if(isset($_GET['edit'])){
            $updatesql = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
        }
        $db->query($updatesql);
        header("Location: xp-categories?Update%20Successful");
    }
}

$category_value = '';
$parent_value = 0;
if(isset($_GET['edit'])){
    $category_value = $edit_category['category'];
    $parent_value = $edit_category['parent'];
}else{
    if(isset($_POST)){
        $category_value = $category;
        $parent_value = $post_parent;
    }
}
?> 
<h2 class="text-center">Categories</h2>
<hr />
<div class="row">
<!-------------  FORM  ------------>
<div class="col-md-6">
   <form class="form" action="xp-categories<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="POST">
   <legend><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> Category</legend>
   <div id="errors"></div>
        <div class="form-group">
            <label for="parent">Parent</label>
            <select class="form-control" name="parent" id="parent">
                <option value="0"<?=(($parent_value == 0)?' selected="selected"':'');?>>Parent</option>
                <?php while($parent = mysqli_fetch_assoc($result)) : ?>
                <option value="<?=$parent['id']; ?>"<?=(($parent_value == $parent['id'])?' selected="selected"':''); ?>><?=$parent['category']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?=$category_value; ?>">
        </div>
        <div class="form-group">
            <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Category" class="btn btn-success">
        </div>
   </form> 
</div>
<!------------------------ TABLE ---------------------->
<div class="col-md-6">
    <table class="table table-bordered">
        <thead>
            <th>Category</th>
            <th>Parent</th>
            <th></th>
        </thead>
        <tbody>
        <?php 
        $sql = "SELECT * FROM categories WHERE parent = 0";
        $result = $db->query($sql);
        while($parent = mysqli_fetch_assoc($result)): 
                $parent_id = (int)$parent['id'];
                $sqlpid = "SELECT * FROM categories WHERE parent = '$parent_id'";
                $result_pid = $db->query($sqlpid);
            ?>
            <tr class="bg-primary">
                <td><?=$parent['category']; ?></td>
                <td>Parent</td>
                <td>
                    <a href="xp-categories?edit=<?=$parent['id']; ?>" class="btn btn-xs btn-default">
                        <i class="fa fa-pen"></i>
                    </a>
                    <a href="xp-categories?delete=<?=$parent['id']; ?>" class="btn btn-xs btn-danger">
                        <i class="fa fa-remove-format"></i>
                    </a>
                </td>
            </tr>
            <?php while($child = mysqli_fetch_assoc($result_pid)): ?>
            <tr class="bg-info">
                <td><?=$child['category']; ?></td>
                <td><?=$parent['category']?></td>
                <td>
                    <a href="xp-categories?edit=<?=$child['id']; ?>" class="btn btn-xs btn-default">
                        <i class="fa fa-pen"></i>
                    </a>
                    <a href="xp-categories?delete=<?=$child['id']; ?>" class="btn btn-xs btn-danger">
                        <i class="fa fa-remove-format"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
<?php
include 'xp-includes/xp-footer.php';
?>