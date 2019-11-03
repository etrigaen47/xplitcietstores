<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/5/2019
 * Time: 11:43 AM
 */
//include '../core/indexclasses/index__navigation.php';
$sql = "SELECT * FROM categories WHERE parent = 0";
//selects category options from the database where the parent id is 0
$parentQuery = $db->query($sql);
//the $db-> is the method of the object $db
?>
<!--------------------------------Top Nav Bar---------------------------------->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="index" class="navbar-brand">Xplitciet Stores</a>
        <ul class="nav navbar-nav">
        <?php while ($parent = mysqli_fetch_assoc($parentQuery)) :
            //the mysqli_fetch_assoc($parentQuery) is going to take
            //the $parent and make a associative array and store
            //it in the $parent in the db
            $parent__id = $parent['id'];
        //gets the nav name by id that will be used to assign individual nav titles each time loop runs
        $sqlnav = "SELECT * FROM categories WHERE parent = '$parent__id'";
        $childQuery = $db->query($sqlnav);
        ?>
        <!---------------------Menu Items------------------->
            <li class="dropdown">
                <a href="" class="dropdown-toggle" data-toggle="dropdown"><?=$parent['category'];//assign id per nav title each time loop runs e.g men, women etc?>
                <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">';
                <?php while ($child = mysqli_fetch_assoc($childQuery)) : ?>
                    <li><a href="#"><?=$child['category'];?></a></li>
                <?php endwhile; ?>
                </ul>
            </li>
        <?php endwhile; ?>
         </ul>
    </div>
</nav>