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
        $nameErr = $emailErr = $websiteErr = $productPErr = "";
        $name = $email = $website = $productP = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // if (empty($_POST["name"])) {
            //     $nameErr = "이름을 입력하여 주세요!";
            // } else {
            //     $name = form_input($_POST["name"]);
            //     // check if name only contains Korean and English letters
            //     if (!preg_match("/^[a-zA-Z가-힣 ]*$/",$name)) {
            //     $nameErr = "영문자와 한글만 가능합니다!";
            //     }
            // }
            
            if (empty($_POST["email"])) {
                $emailErr = "이메일을 입력하여 주세요!";
            } else {
                $emailErr = "";
                $email = form_input($_POST["email"]);
                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "이메일을 정확히 입력해 주세요!";
                }
            }
                
            if (empty($_POST["website"])) {
                $websiteErr = "URL 주소를 입력해 주세요!";
            } else {
                $website = form_input($_POST["website"]);
                // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
                if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                $websiteErr = "잘못된 URL 형식입니다!";
                }
            }

            if (empty($_POST["productP"])) {
                $productPErr = "제품가격을 입력해 주세요!";
              } else {
                $productP = form_input($_POST["productP"]);
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
            <!-- <h2 class="apply-title">서비스 신청하기</h2> -->
            <div class="form-wrapper">
                <p><span class="form-error">* 필수입력란 입니다.</span></p>
                <form id="apply-form" action="" method="post">
                    <!-- Start 이용약관 동의 -->
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="termsAgree" required>
                        <label class="form-check-label" for="termsAgree">이용약관 동의</label>
                    </div>
                    <div class="popup-termsAgree" style="display:none;">

                    </div>
                    <!-- End 이용약관 동의-->
                    <!-- Start 휴대폰 본인인증 -->

                    <!-- End 휴대폰 본인인증 -->
                    <div class="form-group">
                        <label for="email">이메일</label>
                        <input type="text" name="email" value="<?php echo $email;?>" id="email" required>
                        <span class="form-error"><?php echo $emailErr;?></span>
                    </div>
                    <div class="form-group">
                        <label for="file-studentid">학생증 업로드</label>
                        <input type="file" class="form-control-file" id="file-studentid" required>
                        <span class="form-error"><?php echo $fileErr;?></span>
                    </div>
                    <div class="form-group">
                        <label for="url-website">상품링크</label>
                        <input type="text" name="website" value="<?php echo $website;?>"
                            placeholder="예) www.jellydeal.io" id="url-website">
                        <span class="form-error"><?php echo $websiteErr;?></span>
                    </div>

                    <div class="form-group">
                        <label for="price-product">제품가격</label>
                        <input type="text" name="productP" value="<?php echo $productP;?>" id="price-product" numberOnly
                            required>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">신청하기</button>
                </form><!-- #apply-form -->
            </div><!-- .form-wrapper -->

            <!-- number only script with commas -->
            <script type="text/javascript">
                (function ($) {
                    // add commas every 3 digits
                    function addCommas(x) {
                        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    // remove all commas
                    function removeCommas(x) {
                        if (!x || x.length == 0) return "";
                        else return x.split(",").join("");
                    }
                    // initiate fx
                    $("input:text[numberOnly]").on("focus", function () {
                        var x = $(this).val();
                        x = removeCommas(x);
                        $(this).val(x);
                    }).on("focusout", function () {
                        var x = $(this).val();
                        if (x && x.length > 0) {
                            if (!$.isNumeric(x)) {
                                x = x.replace(/[^0-9]/g, "");
                            }
                            x = addCommas(x);
                            $(this).val(x);
                        }
                    }).on("keyup", function () {
                        $(this).val($(this).val().replace(/[^0-9]/g, ""));
                    });
                })(jQuery);
            </script>

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