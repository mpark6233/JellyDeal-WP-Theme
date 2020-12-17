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
            $termsErr = $emailErr = $attachmentErr = $websiteErr = $prdNameErr = $prdPriceErr = $optNameErr = $optPriceErr = "";
            $terms = $email = $website = $prdName = $prdPrice = $optName = $optPrice = "";

            // input fields validation  
            if ($_SERVER["REQUEST_METHOD"] == "POST") {  

                // terms
                if (empty($_POST["terms"])) {
                    $termsErr = "이용약관에 동의해 주세요.";
                } else {
                    $terms = form_input($_POST["terms"]);
                }

                // attachment
                $allowed = array('gif', 'png', 'jpg');
                $filename = $_FILES["attachment"]["name"];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (empty($_FILES["attachment"])) {
                    $attachmentErr = "학생증 파일을 업로드 해주세요.";
                } else {
                    if (!in_array($ext, $allowed)) {
                    $attachmentErr = "파일은 GIF, PNG, JPG 이미지만 업로드 가능합니다.";
                    }
                }

                // school email
                if (empty($_POST["email"])) {
                    $emailErr = "재학중인 대학교 이메일 주소를 입력해 주세요.";
                } else {
                    $email = form_input($_POST["email"]);
                    // check if e-mail address is well-formed
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "올바르지 않은 이메일 형식입니다. 다시 입력해 주세요.";
                    }
                }

                // product url
                if (empty($_POST["website"])) {
                    $websiteErr = "구매 상품의 URL 주소를 입력해 주세요.";
                } else {
                    $website = form_input($_POST["website"]);
                    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
                    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                    $websiteErr = "올바르지 않은 URL 형식입니다. 다시 입력해 주세요.";
                    }
                }

                // product name
                if (empty($_POST["prdName"])) {
                    $prdNameErr = "제품이름을 입력해 주세요.";
                } else {
                    $prdName = form_input($_POST["prdName"]);
                }

                // product price
                if (empty($_POST["prdPrice"])) {
                    $prdPriceErr = "제품가격을 입력해 주세요.";
                } else {
                    $prdPrice = form_input($_POST["prdPrice"]);
                }

                // model name (sanitize only)
                if ($_POST["modelName"]) {
                    $modelName = form_input($_POST["modelName"]);
                }

                // option name
                if (empty($_POST["optName[]"])) {
                    $optNameErr = "옵션이름을 입력해 주세요.";
                } else {
                    foreach($_POST["optName"] as $optName) {
                        $optName = form_input($optName);
                    }
                }

                // option price
                if (empty($_POST["optPrice[]"])) {
                    $optPriceErr = "옵션가격을 입력해 주세요.";
                } else {
                    $optPrice = form_input($_POST["optPrice[]"]);
                }

                // delivery type


                // delivery cost


                // delivery address
                ?>
                <div class="container">
                    <?php
                    echo "<h2>Input Test:</h2>";
                    echo "<span>Terms: </span>" . $terms;
                    echo "<br>";
                    echo "<span>Email: </span>" . $email;
                    echo "<br>";
                    echo "<span>Website: </span>" . $website;
                    echo "<br>";
                    echo "<span>Product Name: </span>" . $prdName;
                    echo "<br>";
                    echo "<span>Product Price: </span>" . $prdPrice;
                    echo "<br>";
                    echo "<span>Option Name: </span>" . $optName;
                    echo "<br>";
                    echo "<span>Option Price: </span>" . $optPrice;
                    echo "<br>";
                    ?>
                </div>
                <?php
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
                <span class="form-error">* 필수입력란 입니다.</span>
                <form action="" method="post" id="apply-form">
                    <!-- Start 이용약관 동의 -->
                    <div class="form-check">
                        <input type="checkbox" name="terms" class="form-check-input" id="termsAgree">
                        <label class="form-check-label" for="termsAgree">이용약관 동의</label>
                        <span class="form-error"><?php echo $termsErr;?></span>
                    </div>
                    <div class="popup-termsAgree" style="display:none;">

                    </div>
                    <!-- End 이용약관 동의-->
                    <!-- Start 휴대폰 본인인증 -->

                    <!-- End 휴대폰 본인인증 -->
                    <div class="form-group">
                        <label for="email">이메일</label>
                        <input type="text" name="email" value="<?php echo $email;?>" id="email">
                        <span class="form-error"><?php echo $emailErr;?></span>
                    </div>
                    <!-- Start 다음주소 입력 -->

                    <!-- End 다음주소 입력 -->
                    <div class="form-group">
                        <label for="file-studentid">학생증 업로드</label>
                        <input type="file" name="attachment" class="form-control-file" id="file-studentid">
                        <span class="form-error"><?php echo $attachmentErr;?></span>
                    </div>
                    <div class="form-group">
                        <label for="url-website">상품링크</label>
                        <input type="text" name="website" value="<?php echo $website;?>"
                            placeholder="예) www.jellydeal.io" id="url-website">
                        <span class="form-error"><?php echo $websiteErr;?></span>
                    </div>
                    <div class="form-group">
                        <label for="product-name">제품이름</label>
                        <input type="text" name="prdName" value="<?php echo $prdName;?>" id="product-name">
                        <span class="form-error"><?php echo $prdNameErr;?></span>
                    </div>
                    <div class="form-group">
                        <label for="product-price">제품가격</label>
                        <input type="text" name="prdPrice" value="<?php echo $prdPrice;?>" id="product-price" numberOnly>
                        <span class="form-error"><?php echo $prdPriceErr;?></span>
                    </div>
                    <div class="form-group">
                        <div class="option-wrapper">
                            <div class="option-group">
                                <label for="option-name">옵션이름</label>
                                <input type="text" name="optName[]" value="<?php echo $optName;?>" id="option-name">
                                <span class="form-error"><?php echo $optNameErr;?></span>
                            </div>
                            <div class="option-group">
                                <label for="option-price">옵션가격</label>
                                <input type="text" name="optPrice[]" value="<?php echo $optPrice;?>" id="option-price" numberOnly>
                                <span class="form-error"><?php echo $optPriceErr;?></span>
                            </div>
                        </div>
                        <input type="button" onclick="addInput()" name="add" value="옵션 추가하기" id="optBtn" />
                    </div>
                    <input type='submit' name='submit' value='신청하기' class='btn btn-primary'>
                    <!-- <button type="submit" name="submit" class="btn btn-primary">신청하기</button> -->
                </form><!-- #apply-form -->
            </div><!-- .form-wrapper -->

            <script type="text/javascript">
                // number only script with commas
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

                // add more options function
                function addInput() {
                    var opt = document.getElementById("optBtn");
                    opt.insertAdjacentHTML('beforebegin',
                        '<br /><div class="option-wrapper"><div class="option-group"><label for="option-name">옵션이름</label><input type="text" name="optName[]" value="<?php echo $optName;?>" id="option-name" numberOnly><span class="form-error"><?php echo $optNameErr;?></span></div><div class="option-group"><label for="product-price">옵션가격</label><input type="text" name="prdPrice[]" value="<?php echo $prdPrice;?>" id="product-price" numberOnly><span class="form-error"><?php echo $prdPriceErr;?></span></div></div>'
                        );
                }
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