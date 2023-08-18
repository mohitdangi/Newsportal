<!-- <div class="card mb-4">
            <h5 class="card-header">Search</h5>
            <div class="card-body"> -->
                   <!-- <form name="search" action="search.php" method="post">
              <div class="input-group">
           
        <input type="text" name="searchtitle" class="form-control" placeholder="Search for..." required>
                <span class="input-group-btn">
                  <button class="btn btn-secondary" type="submit">Go!</button>
                </span>
              </form> -->
              <!-- </div>
            </div>
          </div> -->
          <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="about-us.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact-us.php">Contact us</a>
                </li>
                <li class="nav-item">
                    <div class="col-md-4">
                        <!-- Search Widget -->
                        <form name="search" action="search.php" method="post" class="form-inline my-2 my-lg-0">
                            <input type="text" name="searchtitle" class="form-control mr-sm-2"
                                   placeholder="Search for..." required>
                            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Go!</button>
                        </form>
                    </div>
                </li>
                <?php
                $query = mysqli_query($con, "select id,CategoryName from tblcategory");
                while ($row = mysqli_fetch_array($query)) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="category.php?catid=<?php echo htmlentities($row['id']) ?>"><?php echo htmlentities($row['CategoryName']); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
