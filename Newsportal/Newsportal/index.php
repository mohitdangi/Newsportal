<?php 
session_start();
include('includes/config.php');

    ?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


    <title>News Portal | Home Page</title>

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

    <?php

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default'; // Define the sorting option
?>
     
      <div class="row" style="margin-top: 4%">
     
        <!-- Blog Entries Column -->
        
        <div class="col-md-8">

         <form action="" method="GET">
         <label for="sort">Sort By:</label>
         <select name="sort" id="sort">
           <option value="default" <?php if ($sort === 'default') echo 'selected'; ?>>Default</option>
           <option value="rating" <?php if ($sort === 'rating') echo 'selected'; ?>>Rating</option>
         </select>
         <button type="submit">Apply</button>
       </form>
     
           <?php
       
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default'; // Define the sorting option

        // Define the ORDER BY clause based on the sorting option
        $order_by = ($sort === 'rating') ? 'tblposts.rating DESC' : 'tblposts.id DESC'; // Default sorting

        // Define the number of records per page
        $no_of_records_per_page = 2;
      
      
      // Function to convert rating to stars
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
        
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$no_of_records_per_page = 8;

// Calculate the offset
$offset = ($pageno - 1) * $no_of_records_per_page;


// Rest of the code...
$query = mysqli_query($con, "SELECT tblposts.id AS pid, tblposts.PostTitle AS posttitle, tblposts.PostImage, tblposts.rating, tblcategory.CategoryName AS category, tblsubcategory.Subcategory, tblposts.PostingDate AS postingdate
        FROM tblposts
        LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId
        LEFT JOIN tblsubcategory ON tblsubcategory.SubcategoryId = tblposts.SubcategoryId
        WHERE tblposts.Is_Active = 1
        ORDER BY $order_by
        LIMIT $offset, $no_of_records_per_page");

// $query = mysqli_query($con, "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, tblposts.PostImage, tblcategory.CategoryName as category, tblcategory.id as cid, tblsubcategory.Subcategory as subcategory, tblposts.PostDetails as postdetails, tblposts.PostingDate as postingdate, tblposts.PostUrl as url FROM tblposts LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId WHERE tblposts.Is_Active = 1 ORDER BY tblposts.id DESC LIMIT $offset, $no_of_records_per_page");

$count = 0;
echo '<div class="row">';


while ($row = mysqli_fetch_array($query)) {
    if ($count % 2 == 0 && $count > 0) {
        echo '</div><div class="row">';
    }
    ?>
    <div class="card mb-4 col-md-6">
        <img class="card-img-top" src="admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" alt="<?php echo htmlentities($row['posttitle']); ?>">
        <div class="card-body">
            <h2 class="card-title"><?php echo htmlentities($row['posttitle']); ?></h2>
            Category: <a href="category.php?catid=<?php echo htmlentities($row['category']); ?>"><?php echo htmlentities($row['category']); ?></a>
            <p><b>Rating:</b> <?php echo convertToStars($row['rating']); ?></p>

                  <a href="news-details.php?nid=' . htmlentities($row['pid']) . '" class="btn btn-primary">Read More</a> </div>
        <div class="card-footer text-muted">
            Posted on <?php echo htmlentities($row['postingdate']); ?>
           
        </div>
    </div>
    <?php
    $count++;
    
}
echo '</div>';

?>

<ul class="pagination justify-content-center mb-4">
    <li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>
    <li class="<?php if ($pageno <= 1) { echo 'disabled'; } ?> page-item">
        <a href="<?php if ($pageno <= 1) { echo '#'; } else { echo "?pageno=" . ($pageno - 1); } ?>" class="page-link">Prev</a>
    </li>
    <?php
    $total_pages_sql = "SELECT COUNT(*) FROM tblposts WHERE tblposts.Is_Active = 1";
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
    ?>
    <li class="<?php if ($pageno >= $total_pages) { echo 'disabled'; } ?> page-item">
        <a href="<?php if ($pageno >= $total_pages) { echo '#'; } else { echo "?pageno=" . ($pageno + 1); } ?> " class="page-link">Next</a>
    </li>
    <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
</ul>


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
