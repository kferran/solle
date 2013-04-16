    </div>
        <div id="footer">
            <div id="footer_center">
                <div id="footer_top" class="cf">
                    <div id="footer_contact">
                        <img src="/images/solle_logo_footer.png"/>
                        <br/>
                        <br/>
                        260 S. 2500 W.<br/>
                        Suite 102, Pleasant Grove, Utah 84062<br/>
                        <br/>
                        <br/>
                        Toll Free #: 888-787-0665<br/>
                        Email: info@sollenaturals.com<br/>
                        <br/>
                        <a href="http://fb.com/sollenaturals"><img src="/images/facebook_icon.png"/></a>&nbsp;
                        <a href="http://twitter.com/sollenaturals"><img src="/images/twitter_icon.png"/></a>
                    </div>
                    <div id="footer_blog_feed">
                        <h2>From the Solle Blog</h2>
                        <ul>
                            <?php
                                // require_once('../blog/wp-blog-header.php');
                                // $latest = new WP_Query('posts_per_page=4');
                                // while($latest->have_posts()): $latest->the_post();
                            ?>
                            <li><?php //the_title(); ?> - <b><?php //the_time('j M Y'); ?></b></li>
                            <?php //endwhile; ?>
                        </ul>
                    </div>
                </div>
                <div id="footer_bottom" class="cf">
                    <ul id="footer_links_list" class="cf">
                        <li><a href="/home.php">Home</a></li>
                        <li><a href="/store/store.php">Products</a></li>
                        <li><a href="/about_us.php">About Us</a></li>
                        <li><a href="/comp_plan.php">Compensation Plan</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a id="footer_contact_link" href="#">Contact</a></li>
                    </ul>
                    <span id="footer_copyright">
                        Copyright &copy; <?php echo date('Y'); ?> Solle Naturals All rights reserved.
                    </span>
                </div>
                <div id="footer_disclaimer">
                    <style>#legal_disclaimer:hover { color : white; cursor : pointer; }</style>
                    <span id="legal_disclaimer"><img src="/images/right_arrow.png"/> Legal Disclaimer</span>
                    <p style="display:none">
                    The content of this website is intended for education purposes only.<br/>
                    It is not intended to be a substitute for professional healthcare advice, diagnosis or treatment.<br/>
                    We encourage you to consult your healthcare professional if you have concerns about your physical or emotional well-being.
                    </p>
                </div>
            </div>
        </div>
        <?php include 'end.php'; ?>
        <?php include 'modals/login.php'; ?>
        <?php include 'modals/forgot_password.php'; ?>
        <?php include 'modals/become_member.php'; ?>
        <?php include 'modals/online_customer.php'; ?>
        <?php include 'modals/member.php'; ?>
        <?php include 'modals/success.php'; // create customer/member ?>
        <?php include '../contact_modal.php'; ?>
    </body>
</html>
