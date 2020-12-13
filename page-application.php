<?php /* Template Name: CustomPageT1 */ ?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
 
            // Include the page content template.
            get_template_part( 'template-parts/content', 'page' );
 
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
 
            // End of the loop.
        endwhile;
        ?>

        <!-- Start Custom Form -->
        <?php

        // define variables and set to empty values
        $nameErr = $emailErr = $websiteErr = "";
        $name = $email = $website = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["name"])) {
                $nameErr = "이름을 입력하여 주세요!";
            } else {
                $name = form_input($_POST["name"]);
                // check if name only contains Korean and English letters
                if (!preg_match("/^[a-zA-Z가-힣 ]*$/",$name)) {
                $nameErr = "영문자와 한글만 가능합니다!";
                }
            }
            
            if (empty($_POST["email"])) {
                $emailErr = "이메일을 입력하여 주세요!";
            } else {
                $email = form_input($_POST["email"]);
                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "이메일을 정확히 입력해 주세요!";
                }
            }
                
            if (empty($_POST["website"])) {
                $website = "";
            } else {
                $website = form_input($_POST["website"]);
                // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
                if (!preg_match("/^[a-zA-Z가-힣 ]*$/",$name)) {
                $websiteErr = "URL 주소를 정확히 입력해 주세요!";
                }
            }

            if (empty($_POST["productp"])) {
                $productp = "";
            } else {
                $productp = form_input($_POST["productp"]);
                // check if input only contains numeric values
                if (!preg_match("/^[a-zA-Z가-힣 ]*$/",$productp)) {
                $productpErr = "URL 주소를 정확히 입력해 주세요!";
            }

            if (empty($_POST["optionp"])) {
                $optionp = "";
            } else {
                $optionp = form_input($_POST["optionp"]);
            }

            if (empty($_POST["deliveryp"])) {
                $deliveryp = "";
            } else {
                $deliveryp = form_input($_POST["deliveryp"]);
            }

            if (empty($_POST["gender"])) {
                $genderErr = "Gender is required";
            } else {
                $gender = form_input($_POST["gender"]);
            }
        }

        // sanitize input data
        function form_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        ?>
        <div class="container">
            <h2 class="apply-title">서비스 신청하기</h2>
            <div class="form-wrapper">
                <p><span class="form-error">* 필수입력란 입니다.</span></p>
                <form id="apply-form" action="" method="post">
                    <!-- Start 이용약관 동의 -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="termsAgree">
                        <label class="form-check-label" for="termsAgree">이용약관 동의</label>
                    </div>
                    <div class="popup-termsAgree" style="display:none;">

                    </div>
                    <!-- End 이용약관 동의-->
                    <!-- Start 휴대폰 본인인증 -->

                    <!-- End 휴대폰 본인인증 -->
                    <div class="form-group">
                        <label for="file-studentid">학생증 업로드</label>
                        <input type="file" class="form-control-file" id="file-studentid">
                    </div>
                    <div class="form-group">
                        <label for="url-website">구매하고 싶은 상품의 링크를 입력해 주세요.</label>
                        <input type="text" name="website" value="<?php echo $website;?>"
                            placeholder="예) www.jellydeal.io" id="url-website">
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">신청하기</button>
                </form><!-- #apply-form -->
            </div><!-- .form-wrapper -->
            <style>
                .form-error {
                    color: #FF0000;
                }
            </style>
        </div><!-- .container -->



        <!-- End Custom Form -->

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>