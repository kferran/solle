<div id="header">
    <div id="header_top"></div>
    <div id="header_center" class="cf">
        <a href="/home.php"><img id="header_logo" src="<?php echo $base_path; ?>images/solle_logo_header.png"/></a>
        <ul id="header_links_list" class="cf">
            <?php
                function AddCurrentPageClass($page)
                {
                    if(strpos($_SERVER['PHP_SELF'],$page) !== false)
                        echo 'class="current"';
                }
            ?>
          <li><a href="/home.php" <?php AddCurrentPageClass('home.php'); ?>>Home</a></li>
            <li>
                <a href="store.php" <?php AddCurrentPageClass('store.php'); ?>>Products</a>
                <div class="hover_menu" style="width:200px;left:-70px;display:none;">
                    <a href="/solle_quality.php">SolleCertain</a>
                    <a href="/products.php">View All Products</a>
                    <a href="/products.php">Solle Product Categories</a>
                </div>
            </li>
            <li><a href="/about_us.php" <?php AddCurrentPageClass('about_us.php'); ?>>About Us</a></li>
            <li><a href="/comp_plan.php" <?php AddCurrentPageClass('comp_plan.php'); ?>>Compensation Plan</a></li>
            <li><a href="/blog" <?php AddCurrentPageClass('blog'); ?>>Blog</a></li>
            <li><a id="contact_header_link" href="#">Contact</a></li>
        </ul>
    </div>
</div>
