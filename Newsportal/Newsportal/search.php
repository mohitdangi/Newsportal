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

    <title>News Portal | Search  Page</title>

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<?php
include('includes/header.php');

$apiKey = 'AIzaSyCugAKra7Tmj_iynhWD2w-KwGxBIYUXM8k';
$searchEngineId = 'f5100a0d5989f4f7e';
$searchQuery = isset($_GET['searchtitle']) ? $_GET['searchtitle'] : '';
$apiUrl = "https://www.googleapis.com/customsearch/v1?key=$apiKey&cx=$searchEngineId&q=$searchQuery";

// Perform API request
$data = @file_get_contents($apiUrl);

if ($data === false) {
    echo 'Failed to retrieve search results.';
} else {
    $data = json_decode($data, true);

    if (isset($data['items']) && is_array($data['items'])) {
        // Create an array to store the news items with ratings based on sources
        $newsItems = array();

        foreach ($data['items'] as $item) {
            $newsItem = array(
                'link' => $item['link'],
                'title' => $item['title'],
                'snippet' => isset($item['snippet']) ? $item['snippet'] : '',
                'rating' => '', // Placeholder for rating
                'source' => $item['displayLink']
            );

            // Check if the source already exists in the array
            $sourceIndex = array_search($newsItem['source'], array_column($newsItems, 'source'));
            if ($sourceIndex !== false) {
                // Assign the same rating as the existing item from the same source
                $newsItem['rating'] = $newsItems[$sourceIndex]['rating'];
            } else {
                // Generate a random rating between 1 and 10 for a new source
                $newsItem['rating'] = rand(1, 10);
            }

            // Add the news item to the array
            $newsItems[] = $newsItem;
        }

        // Display the news items in separate cards
        echo '<div class="container news-card">';
        foreach ($newsItems as $item) {
            echo '<div class="card mb-3">';
            echo '<div class="card-header"><strong>News</strong></div>';
            echo '<div class="card-body">';
            echo "<h3><a href='{$item['link']}' class='card-title'>{$item['title']}</a></h3>";

            if (!empty($item['snippet'])) {
                echo "<p class='card-text'>{$item['snippet']}</p>";
            }

            echo "<p class='card-source'>Source: {$item['source']}</p>";

            echo '<div class="card-rating">';
            // Convert the rating number to star icons
            for ($i = 1; $i <= 10; $i++) {
                if ($i <= $item['rating']) {
                    echo '<i class="fas fa-star"></i>'; // Filled star icon
                } else {
                    echo '<i class="far fa-star"></i>'; // Empty star icon
                }
            }
            echo '</div>';

            $ratingPercentage = round(($item['rating'] / 10) * 100);
            echo "<p class='card-rating-percentage'>Trust: {$ratingPercentage}%</p>";

            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo 'No search results found.';
    }

}

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
}if (isset($_GET['searchtitle']) && $_GET['searchtitle'] != '') {
    $searchtitle = $_GET['searchtitle'];

    // Get the category ID from the URL if available
    $categoryID = isset($_GET['category']) ? $_GET['category'] : '';

    // Pagination logic
    $pageno = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
    $no_of_records_per_page = 8;
    $offset = ($pageno - 1) * $no_of_records_per_page;

    // Build the SQL query based on the search title and category
    $sql = "SELECT tblposts.id as pid, tblposts.PostTitle as posttitle, tblposts.PostImage, tblcategory.CategoryName as category, tblsubcategory.Subcategory as subcategory, tblposts.PostDetails as postdetails, tblposts.PostingDate as postingdate, tblposts.PostUrl as url, tblposts.rating as rating 
    FROM tblposts 
    LEFT JOIN tblcategory ON tblcategory.id = tblposts.CategoryId 
    LEFT JOIN tblsubcategory ON tblsubcategory.SubCategoryId = tblposts.SubCategoryId 
    WHERE (tblposts.PostTitle LIKE '%$searchtitle%' OR tblcategory.CategoryName LIKE '%$searchtitle%')
    AND tblposts.Is_Active = 1";

    // Append category matching to the SQL query if a category is specified
    if (!empty($categoryID)) {
        $sql .= " AND tblcategory.id = $categoryID";
    }

    $sql .= " ORDER BY tblposts.id DESC";

    // Query to fetch search results
    $query = mysqli_query($con, $sql);
    $rowcount = mysqli_num_rows($query);

    if ($rowcount == 0) {
        echo "No record found";
    } else {
        $total_pages = ceil($rowcount / $no_of_records_per_page);

        // Adjust the current page number if it exceeds the total pages
        if ($pageno > $total_pages) {
            $pageno = $total_pages;
        }

        $sql .= " LIMIT $offset, $no_of_records_per_page";
        $query = mysqli_query($con, $sql);

        $count = 0;
        echo '<div class="row">';
        while ($row = mysqli_fetch_array($query)) {
            if ($count % 2 == 0 && $count > 0) {
                echo '</div><div class="row mt-4">';
            }

            // Display the blog post with image and rating
            ?>
            <div class="card mb-4">
                <img src="admin/postimages/<?php echo htmlentities($row['PostImage']); ?>" class="card-img-top" alt="Post Image">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlentities($row['posttitle']); ?></h2>
                    <a href="news-details.php?nid=<?php echo htmlentities($row['pid']); ?>" class="btn btn-primary">Read More &rarr;</a>
                    <?php
                    $rating = $row['rating'];
                    if ($rating == 0) {
                        $rating = rand(1, 10); // Generate a random rating if it's zero
                    }
                    ?>
                    <p><b>Rating:</b> <?php echo convertToStars($rating); ?></p>
                </div>
                <div class="card-footer text-muted">
                    Posted on <?php echo htmlentities($row['postingdate']); ?>
                </div>
            </div>
            <?php
            $count++;
        }
        echo '</div>'; // Close the last row

        // Pagination links
        ?>
        <ul class="pagination justify-content-center mb-4">
    <?php if ($pageno > 1) { ?>
        <li class="page-item"><a href="?searchtitle=<?php echo urlencode($searchtitle); ?>&category=<?php echo $categoryID; ?>&pageno=1" class="page-link">First</a></li>
        <li class="page-item"><a href="?searchtitle=<?php echo urlencode($searchtitle); ?>&category=<?php echo $categoryID; ?>&pageno=<?php echo $pageno - 1; ?>" class="page-link">Prev</a></li>
    <?php } ?>

    <?php
    $start = max(1, $pageno - 2);
    $end = min($pageno + 2, $total_pages);

    for ($i = $start; $i <= $end; $i++) {
        if ($i == $pageno) {
            echo '<li class="page-item active"><a href="#" class="page-link">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item"><a href="?searchtitle=' . urlencode($searchtitle) . '&category=' . $categoryID . '&pageno=' . $i . '" class="page-link">' . $i . '</a></li>';
        }
    }
    ?>

    <?php if ($pageno < $total_pages) { ?>
        <li class="page-item"><a href="?searchtitle=<?php echo urlencode($searchtitle); ?>&category=<?php echo $categoryID; ?>&pageno=<?php echo $pageno + 1; ?>" class="page-link">Next</a></li>
        <li class="page-item"><a href="?searchtitle=<?php echo urlencode($searchtitle); ?>&category=<?php echo $categoryID; ?>&pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
    <?php } ?>
</ul>

        <?php
    }
}
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
