<?php require_once 'inc/connection.php' ?>
<?php require_once 'inc/header.php' ?>
<?php require_once 'inc/functions.php' ?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="banner header-text">
    <div class="owl-banner owl-carousel">
        <div class="banner-item-01">
            <div class="text-content">
                <!-- <h4>Best Offer</h4> -->
                <!-- <h2>New Arrivals On Sale</h2> -->
            </div>
        </div>
        <div class="banner-item-02">
            <div class="text-content">
                <!-- <h4>Flash Deals</h4> -->
                <!-- <h2>Get your best products</h2> -->
            </div>
        </div>
        <div class="banner-item-03">
            <div class="text-content">
                <!-- <h4>Last Minute</h4> -->
                <!-- <h2>Grab last minute deals</h2> -->
            </div>
        </div>
    </div>
</div>
<!-- Banner Ends Here -->

<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$limit = 3;
$offset = ($page - 1) * $limit;

// Number Of Pages
$query = "select count(`id`) as total from posts";
$runQuery = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($runQuery);
$total = $result["total"];
$numOfPages = ceil($total / $limit);

if ($page > $numOfPages) {
    header("location:{$_SERVER['PHP_SELF']}?page=$numOfPages");
} elseif ($page < 1) {
    header("location:{$_SERVER['PHP_SELF']}?page=1");
}

$query = "SELECT id, title, substring(body,1,53) as body, created_at, image FROM posts order by id limit $limit offset $offset";
$runQuery = mysqli_query($conn, $query);
if (mysqli_num_rows($runQuery) > 0) {
    $posts = mysqli_fetch_all($runQuery, MYSQLI_ASSOC);
}
?>

<?php
if (!empty($posts)):
    ?>

<div class="latest-products">
    <div class="container">
        <?php require_once 'inc/success.php' ?>
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h2>Latest Posts</h2>
                </div>
            </div>

            <?php
                foreach ($posts as $post):
                    ?>
            <div class="col-md-4">
                <div class="product-item">
                    <a href="#"><img src="uploads/<?php echo $post['image'] ?>" alt=""></a>
                    <div class="down-content">
                        <a href="#">
                            <h4><?php echo $post['title'] ?></h4>
                        </a>
                        <h6 style="font-size:15px"><?php echo $post['created_at'] ?></h6>
                        <p><?php echo $post['body'] . "..." ?></p>
                        <div class="d-flex justify-content-end">
                            <a href="viewPost.php?id=<?php echo $post['id'] ?>" class="btn btn-info "> view</a>
                        </div>

                    </div>
                </div>
            </div>
            <?php
                endforeach;
                ?>

        </div>

    </div>
</div>

<?php
else:
    echo "No Posts Found";
endif;
?>

<div class="container d-flex justify-content-center">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item <?php if ($page == 1)
                echo "disabled" ?>"><a class="page-link text-danger"
                    href="<?php echo $_SERVER['PHP_SELF'] . "?page=" . $page - 1 ?>">Previous</a></li>
            <li class="page-item"><a class="page-link text-danger"><?php echo $page ?></a></li>
            <li class="page-item <?php if ($page == $numOfPages)
                echo "disabled" ?>"><a class="page-link text-danger"
                    href="<?php echo $_SERVER['PHP_SELF'] . "?page=" . $page + 1 ?>">Next</a></li>
        </ul>
    </nav>
</div>

<?php require_once 'inc/footer.php' ?>