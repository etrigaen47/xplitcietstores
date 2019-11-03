<?php
//this page fires up an ajax request that when someone selects
// an option in the parent category, so as to return the options
// for the corresponding child category
    require_once $_SERVER['DOCUMENT_ROOT'].'/xplitcietstores/xp-basecontrol/xp-core/xp-init.php';
    $parentID = (int)$_POST['parentID'];
    $childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
    ob_start();//starts buffering
?>
    <option value=""></option>

<?php while($child = mysqli_fetch_assoc($childQuery)): ?>
    <option value="<?=$child['id'];?>"><?=$child['category'];?></option>
<?php endwhile; ?>
<?php
    echo ob_get_clean(); ?>
