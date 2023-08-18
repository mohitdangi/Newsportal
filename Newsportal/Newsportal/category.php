<?php 
session_start();
error_reporting(0);
include('includes/config.php');

    ?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>News Portal | Category  Page</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
   <?php include('includes/header.php');?>

    <!-- Page Content -->
    <div class="container">


     
      <div class="row" style="margin-top: 4%">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

          <!-- Blog Post -->
        <!-- search-results.php -->
<?php
include('includes/header.php');
?>

<!-- Add the following line in the head section of your HTML file -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<?php
function convertToStars($rating) {
    $full_stars = floor($rating); // Number of full stars
    $half_stars = ceil($rating - $full_stars); // Number of half stars

    $stars = "";
    // Add full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }
    // Add half stars
    for ($i = 0; $i < $half_stars; $i++) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }

    return $stars;
}

if ($_GET['catid'] != '') {
    $_SESSION['catid'] = intval($_GET['catid']);
}

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 3;
$offset = ($pageno - 1) * $no_of_records_per_page;

$total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE tblposts.CategoryId = '" . $_SESSION['catid'] . "' AND tblposts.Is_Active = 1";
$result = mysqli_query($con, $total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];

$total_pages = ceil($total_rows / $no_of_records_per_page);


$query = mysqli_query($con, "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, tblposts.PostImage, tblcategory.CategoryName as category, tblsubcategory.Subcategory as subcategory, tblposts.PostDetails as postdetails, tblposts.PostingDate as postingdate, tblposts.PostUrl as url, tblposts.rating as rating FROM tblposts LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId WHERE tblposts.CategoryId = '" . $_SESSION['catid'] . "' AND tblposts.Is_Active = 1 ORDER BY tblposts.id DESC LIMIT $no_of_records_per_page OFFSET $offset");

$rowcount = mysqli_num_rows($query);
if ($rowcount == 0) {
    echo "No record found";
} else {
    $count = 0;
    echo '<div class="row">';
    while ($row = mysqli_fetch_array($query)) {
        if ($count % 2 == 0 && $count > 0) {
            echo '</div><div class="row mt-4">';
        }
        ?>
        <div class="col-md-6">
            <div class="card mb-4">
                <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" alt="<?php echo htmlentities($row['posttitle']); ?>">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlentities($row['posttitle']); ?></h2>
                    <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" class="btn btn-primary">Read More &rarr;</a>
                    <?php
                    if ($row['rating'] > 0) {
                        echo '<p><b>Rating:</b> ' . convertToStars($row['rating']) . '</p>';
                    } else {
                        echo '<p><b>Rating:</b> ' . convertToStars(rand(1, 10)) . '</p>'; // Show a random number if rating is zero
                    }
                    ?>
                </div>
                <div class="card-footer text-muted">
                    Posted on <?php echo htmlentities($row['postingdate']); ?>
                </div>
            </div>
        </div>
        <?php
        $count++;
    }
    echo '</div>';
}

echo '<ul class="pagination justify-content-center mb-4">';
if ($pageno == 1) {
    echo '<li class="page-item disabled"><a class="page-link">First</a></li>';
    echo '<li class="page-item disabled"><a class="page-link">Prev</a></li>';
} else {
    echo '<li class="page-item"><a href="?catid=' . $_SESSION['catid'] . '&pageno=1" class="page-link">First</a></li>';
    echo '<li class="page-item"><a href="?catid=' . $_SESSION['catid'] . '&pageno=' . ($pageno - 1) . '" class="page-link">Prev</a></li>';
}

$start = max(1, $pageno - 2);
$end = min($pageno + 2, $total_pages);

for ($i = $start; $i <= $end; $i++) {
    if ($i == $pageno) {
        echo '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
    } else {
        echo '<li class="page-item"><a href="?catid=' . $_SESSION['catid'] . '&pageno=' . $i . '" class="page-link">' . $i . '</a></li>';
    }
}

if ($pageno == $total_pages) {
    echo '<li class="page-item disabled"><a class="page-link">Next</a></li>';
    echo '<li class="page-item disabled"><a class="page-link">Last</a></li>';
} else {
    echo '<li class="page-item"><a href="?catid=' . $_SESSION['catid'] . '&pageno=' . ($pageno + 1) . '" class="page-link">Next</a></li>';
    echo '<li class="page-item"><a href="?catid=' . $_SESSION['catid'] . '&pageno=' . $total_pages . '" class="page-link">Last</a></li>';
}
echo '</ul>';
?>
          <!-- Pagination -->




        </div>

        <!-- Sidebar Widgets Column -->
      <?php include('includes/sidebar.php');?>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
      <?php include('includes/footer.php');?>


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

 
</head>
  </body>

</html>
