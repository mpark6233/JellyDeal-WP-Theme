<?php /* Template Name: Apply Page */ ?>

<?php get_header(); ?>
<?php
    require_once( get_stylesheet_directory() . '/inc/phone-cert.php' );
?>

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
            // init service terms variables
            $allterms = $terms = $privacyterms = $consignterms = $paylaterterms = $marketingterms = $termsreqErr = "";
            // init phone identification variables

            // init form variables, omitted number inputs (prdPrice, optPrice, delCost) for validations
            $phoneValErr = $emailErr = $attachmentErr = $websiteErr = $prdNameErr = $prdPriceErr = $delTypeErr = $delCostErr = $payDayErr = $phoneValSuccess = "";
            $phoneVal = $email = $website = $prdName = $optName = $delType = $payDay = $payDayArr = "";
            // init Danal Certification variables
            $appname = $gender = $birth = $message = "";
            // init Daum Address variables
            $postCode = $addErr = $roadAdd = $jibunAdd = $detAdd = $extAdd = "";

            // 학생증 업로드 변수 선언
            $target_dir = "wp-content/uploads/applicants_id/";
            $target_file = $target_dir . basename($_FILES["IDUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // input fields sanitation and validation  
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // validate required terms
                if (empty($_POST["terms"]) || empty($_POST["privacyterms"]) || empty($_POST["consignterms"]) || empty($_POST["paylaterterms"])) {
                    $termsreqErr = "필수 약관에 동의해 주세요.";
                } elseif (isset($_POST["marketingterms"])) {
                    $marketingterms = "동의";
                }

                // validate phone identification
                $impField = $_POST['impField'];
                if (empty($impField)) {
                    $phoneValErr = "휴대폰 본인인증을 진행해 주세요.";
                } else {
                    $phoneValSuccess = "휴대폰 본인인증이 성공적으로 완료되었습니다.";
                }

                // validate email
                if (empty($_POST["email"])) {
                    $emailErr = "재학중인 대학교 이메일 주소를 입력해 주세요.";
                } else {
                    $email = form_input($_POST["email"]);
                    // check if e-mail address is well-formed
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "올바르지 않은 이메일 형식입니다. 다시 입력해 주세요.";
                    }
                }

                // validate attachments - reference: https://stackoverflow.com/questions/35997961/file-attachment-with-phpmailer
                $errors= array();
                $file_name = $_FILES['IDUpload']['name'];
                $file_size = $_FILES['IDUpload']['size'];
                $file_tmp = $_FILES['IDUpload']['tmp_name'];
                $file_type = $_FILES['IDUpload']['type'];
                $file_ext=strtolower(end(explode('.',$_FILES['IDUpload']['name'])));
                
                $expensions= array("jpeg","jpg","png","pdf");

                if(in_array($file_ext,$expensions)=== false) {
                    $errors[]="PDF, JPEG 또는 PNG 이미지 파일만 업로드 가능합니다.";
                }
                 
                if($file_size > 100000000) {
                    $errors[]='이미지 파일 크기가 10MB 이상입니다. 최적화 후 다시 업로드해 주세요.';
                }
                 
                if(empty($errors)==true) {
                    move_uploaded_file($file_tmp,"wp-content/uploads/applicants_id/".$file_name); //The folder where you would like your file to be saved
                    echo "업로드 성공";
                } else {
                    print_r($errors);
                }
                
                // validate product url
                if (empty($_POST["website"])) {
                    $websiteErr = "구매 상품의 URL 주소를 입력해 주세요.";
                } else {
                    $website = form_input($_POST["website"]);
                    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
                    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
                    $websiteErr = "올바르지 않은 URL 형식입니다. 다시 입력해 주세요.";
                    }
                }

                // validate product name
                if (empty($_POST["prdName"])) {
                    $prdNameErr = "제품이름을 입력해 주세요.";
                } else {
                    $prdName = form_input($_POST["prdName"]);
                }

                // validate product price
                if (empty($_POST["prdPrice"]) && strlen($_POST["prdPrice"]) == 0) {
                    $prdPriceErr = "제품가격을 입력해 주세요.";
                } else {
                    $prdPrice = form_input($_POST["prdPrice"]);
                }

                // model name (sanitize only)
                if (!empty($_POST["modelName"])) {
                    $modelName = form_input($_POST["modelName"]);
                }

                // option name (sanitize only)
                if (isset($_POST["optName"])) {
                    $optName = form_inputarr($_POST["optName"]);
                }

                // option price (sanitize only)
                if (!empty($_POST["optPrice"])) {
                    $optPrice = form_inputarr($_POST["optPrice"]);
                }

                // validate delivery type
                if (empty($_POST["delType"])) {
                    $delTypeErr = "배송옵션을 선택해 주세요.";
                } else {
                    $delType = form_input($_POST["delType"]);
                }

                // validate delivery cost
                if (empty($_POST["delCost"]) && strlen($_POST["delCost"]) == 0) {
                    $delCostErr = "배송비를 입력해 주세요.";
                } else {
                    $delCost = form_input($_POST["delCost"]);
                }

                // validate delivery address
                if (empty($_POST["postCode"] || empty($_POST["roadAdd"] || empty($_POST["jibunAdd"])))) {
                    $addErr = "우편번호와 도로명/지번주소는 필수 입력사항 입니다.";
                } else {
                    $postCode = form_input($_POST["postCode"]);
                    $roadAdd = form_input($_POST["roadAdd"]);
                    $jibunAdd = form_input($_POST["jibunAdd"]);
                    $detAdd = form_input($_POST["detAdd"]);
                    $extAdd = form_input($_POST["extAdd"]);
                }

                // validate payday date
                if (empty($_POST["payDay"])) {
                    $payDayErr = "희망 Pay Day를 입력해 주세요.";
                } else {
                    $payDay = form_input($_POST["payDay"]);
                    $payDayArr = explode('/', $payDay);
                    // check if date syntax is valid
                    if (!checkdate($payDayArr[0], $payDayArr[1], $payDayArr[2])) {
                    $payDayErr = "올바르지 않은 날짜 형식입니다. 다시 입력해 주세요.";
                    }
                }

                // validate required fields
                if (!empty($termsErr) || !empty($phoneValErr) || !empty($emailErr) || !empty($attachmentErr) || !empty($websiteErr) || !empty($prdNameErr) || !empty($prdPriceErr) || !empty($delTypeErr) || !empty($delCostErr) || !empty($payDayErr)) {

                } else {

                    // 본인인증 조회 https://github.com/iamport/iamport-rest-client-php/blob/master/example/example_get_certification_by_imp_uid.php
                    require_once( get_stylesheet_directory() . '/inc/iamport.php' );

                    date_default_timezone_set('Asia/Seoul');
                
                    $iamport = new Iamport('9758574948164308', 'nisAEwvDi2uSPYPgO0WekY5JVfwQ52Stlk30QzD9C82r6RIVSEacDfVyvroBug4PB8PsItBkBEMIvvDo');

                    #1. imp_uid 로 주문정보 찾기(아임포트에서 생성된 거래고유번호)
                    $result = $iamport -> findCertificationByImpUID($impField); //IamportResult 를 반환(success, data, error)

                    if ( $result->success ) {
                        /**
                         *	IamportPayment 를 가리킵니다. __get을 통해 API의 Payment Model의 값들을 모두 property처럼 접근할 수 있습니다.
                        *	참고 : https://api.iamport.kr/#!/payments/getPaymentByImpUid 의 Response Model
                        */
                        $certification = $result->data;

                        # certified 필드를 통해 인증여부를 판단합니다.
                        if($certification->certified) {

                            // 성별값 번역
                            if ($certification->gender == "male") {
                                $genderTr = "남성";
                            } elseif ($certification->gender == "female") {
                                $genderTr = "여성";
                            }

                            $optLoop = '';
                            foreach( $optName as $key => $n ) {
                                $optLoop .= '<p>옵션이름: '.$n.'</p>';
                                $optLoop .= '<p>옵션가격: '.$optPrice[$key].' 원</p>';
                            }

                            require_once( get_stylesheet_directory() . '/inc/jellydeal-ses-smtp.php');

                        }
                    }
                }
            }
            // sanitize input data
            function form_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            // sanitize input array
            function form_inputarr($array) {
                $array = array_map('trim', $array);
            foreach ( $array as $key=>$value) {
                $array[$key] = stripslashes($value);
            }
                $array = array_map('htmlspecialchars', $array);
                return $array;
            }
        ?>
        <div class="container">
            <!-- <h2 class="apply-title">서비스 신청하기</h2> -->
            <div class="form-wrapper justify-content-md-center">
            <!-- /wp-content/themes/Pentamint-WP-Theme/jellydeal-ses-smtp.php -->
                <form action="" method="post" id="apply-form" enctype="multipart/form-data">
                    <!-- Start 이용동의 -->
                    <!-- 서비스 이용약관 동의 -->
                    <div class="form-group">
                        <label for="service-terms" class="mb-2">이용동의</label>
                        <div class="form-block">
                            <div class="row">
                                <div class="col-auto mr-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="terms" class="form-check-input" id="serviceTerms"
                                            value="동의">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label" for="terms">서비스 이용약관 동의 <span
                                                class="form-required">(필수)</span></label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target=".terms-modal">보기</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="modal fade terms-modal" tabindex="-1" role="dialog"
                                aria-labelledby="termsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="termsModalLabel">서비스 이용약관</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                    // query for the terms-conditions page
                                                    $your_query = new WP_Query( 'pagename=terms-conditions' );
                                                    // "loop" through query (even though it's just one page) 
                                                    while ( $your_query->have_posts() ) : $your_query->the_post();
                                                        the_content();
                                                    endwhile;
                                                    // reset post data (important!)
                                                    wp_reset_postdata();
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 개인정보 수집 및 이용 동의 -->
                    <div class="form-group">
                        <div class="form-block">
                            <div class="row">
                                <div class="col-auto mr-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="privacyterms" class="form-check-input"
                                            id="privacyTerms" value="동의">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label" for="privacyterms">개인정보수집 및 이용동의 <span
                                                class="form-required">(필수)</span></label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target=".privacy-modal">보기</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="modal fade privacy-modal" tabindex="-1" role="dialog"
                                aria-labelledby="privacyModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="privacyModalLabel">개인정보수집 및 이용약관</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                    // query for the privacy-policy page
                                                    $your_query = new WP_Query( 'pagename=privacy-policy' );
                                                    // "loop" through query (even though it's just one page) 
                                                    while ( $your_query->have_posts() ) : $your_query->the_post();
                                                        the_content();
                                                    endwhile;
                                                    // reset post data (important!)
                                                    wp_reset_postdata();
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 구매위탁약관 동의 -->
                    <div class="form-group">
                        <div class="form-block">
                            <div class="row">
                                <div class="col-auto mr-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="consignterms" class="form-check-input"
                                            id="consignTerms" value="동의">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label" for="consignterms">구매위탁 약관동의 <span
                                                class="form-required">(필수)</span></label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target=".consign-modal">보기</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="modal fade consign-modal" tabindex="-1" role="dialog"
                                aria-labelledby="consignModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="consignModalLabel">구매위탁 및 구매대행에 대한 약관</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                    // query for the privacy-policy page
                                                    $your_query = new WP_Query( 'pagename=consign-terms' );
                                                    // "loop" through query (even though it's just one page) 
                                                    while ( $your_query->have_posts() ) : $your_query->the_post();
                                                        the_content();
                                                    endwhile;
                                                    // reset post data (important!)
                                                    wp_reset_postdata();
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 할부결제약관 동의 -->
                    <div class="form-group">
                        <div class="form-block">
                            <div class="row">
                                <div class="col-auto mr-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="paylaterterms" class="form-check-input"
                                            id="paylaterTerms" value="동의">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label" for="paylaterterms">분할결제 약관동의 <span
                                                class="form-required">(필수)</span></label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target=".paylater-modal">보기</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="modal fade paylater-modal" tabindex="-1" role="dialog"
                                aria-labelledby="paylaterModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="paylaterModalLabel">분할결제 약관</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                    // query for the privacy-policy page
                                                    $your_query = new WP_Query( 'pagename=paylater-terms' );
                                                    // "loop" through query (even though it's just one page) 
                                                    while ( $your_query->have_posts() ) : $your_query->the_post();
                                                        the_content();
                                                    endwhile;
                                                    // reset post data (important!)
                                                    wp_reset_postdata();
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 마케팅수신약관 동의 -->
                    <div class="form-group">
                        <div class="form-block">
                            <div class="row">
                                <div class="col-auto mr-auto">
                                    <div class="form-check">
                                        <input type="checkbox" name="marketingterms" class="form-check-input"
                                            id="marketingTerms" value="동의">
                                        <span class="checkmark"></span>
                                        <label class="form-check-label" for="marketingterms">마케팅수신약관 동의 <span
                                                class="form-opt">(선택)</span></label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target=".marketing-modal">보기</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-block">
                            <div class="modal fade marketing-modal" tabindex="-1" role="dialog"
                                aria-labelledby="marketingModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="marketingModalLabel">마케팅수신약관</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                                    // query for the privacy-policy page
                                                    $your_query = new WP_Query( 'pagename=marketing-terms' );
                                                    // "loop" through query (even though it's just one page) 
                                                    while ( $your_query->have_posts() ) : $your_query->the_post();
                                                        the_content();
                                                    endwhile;
                                                    // reset post data (important!)
                                                    wp_reset_postdata();
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 모두 확인 동의 -->
                    <div class="form-group mb-5">
                        <div class="form-check">
                            <input type="checkbox" name="allterms" class="form-check-input" id="allterms"
                                aria-describedby="alltermsHelpBlock">
                            <label class="form-check-label" for="allterms">모두 확인, 동의합니다</label>
                        </div>
                        <small id="alltermsHelpBlock" class="form-text text-muted mb-2">
                            <ul class="disclaimer-list">
                                <li class="disclaimer-listitem" style="list-style-type: '- ';">전체동의는 필수 및 선택정보에 대한 동의도
                                    포함되어 있으며, 개별적으로도 동의를 선택하실 수 있습니다.</li>
                                <li class="disclaimer-listitem" style="list-style-type: '- ';">선택항목에 대한 동의를 거부하시는 경우에도
                                    서비스는 이용이 가능합니다.</li>
                            </ul>
                        </small>
                        <span class="form-error"><?php echo $termsreqErr; ?></span>
                    </div>
                    <!-- End 이용약관 동의-->

                    <span class="form-required">* 필수입력란 입니다.</span>
                    <!-- 휴대폰 본인인증 410*660px -->
                    <div class="form-group">
                        <label for="phoneValBtn">실명확인</label>
                        <span class="form-required">*</span>
                        <input type="button" name="phoneVal" value="휴대폰 본인인증" id="phoneValBtn"
                            class="btn btn-primary100" aria-describedby="phoneValHelpBlock">
                        <input type="hidden" name="impField" id="impField" value="" />
                        <span class="form-error"><?php echo $phoneValErr;?></span>
                        <small id="phoneValHelpBlock" class="form-text text-muted">
                            <ul class="disclaimer-list">
                                <li class="disclaimer-listitem" style="list-style-type: '- ';">만 19세 이하는 본 서비스의 이용이
                                    제한됩니다.</li>
                            </ul>
                        </small>
                        <span class="form-success"><?php echo $phoneValSuccess;?></span>
                    </div>
                    <!-- 이메일 주소 -->
                    <div class="form-group">
                        <label for="email">대학 이메일 주소</label>
                        <span class="form-required">*</span>
                        <input type="text" name="email" id="email" class="form-control"
                            aria-describedby="emailHelpBlock">
                        <span class="form-error"><?php echo $emailErr;?></span>
                        <small id="emailHelpBlock" class="form-text text-muted">
                            <ul class="disclaimer-list">
                                <li class="disclaimer-listitem" style="list-style-type: '- ';">학생 인증을 위해 상시 수취 가능한 학교의 이메일 주소를 입력해주세요. 추후 결제정보가 발송되며, 접속이 불가능한 이메일 계정을 입력할 경우 젤리딜 서비스의 이용이 제한됩니다. 이메일이 없을 경우, 서비스 신청 전 고객센터에 문의 바랍니다.</li>
                            </ul>
                        </small>
                    </div>
                    <!-- 신분증 업로드 -->
                    <div class="form-group">
                        <label for="file-studentid">학생증 업로드</label>
                        <span class="form-required">*</span>
                        <input type="file" name="IDUpload" class="form-control-file" id="file-studentid">
                        <span class="form-error"><?php echo $attachmentErr;?></span>
                    </div>
                    <!-- 상품링크 -->
                    <div class="form-group">
                        <label for="url-website">상품링크</label>
                        <span class="form-required">*</span>
                        <input type="text" name="website" id="url-website" class="form-control"
                            placeholder="예: www.jellydeal.io" aria-describedby="siteHelpBlock">
                        <span class="form-error"><?php echo $websiteErr;?></span>
                        <small id="siteHelpBlock" class="form-text text-muted">
                            <ul class="disclaimer-list">
                                <li class="disclaimer-listitem" style="list-style-type: '- ';">할인쿠폰 적용, 통관번호가 필요한 해외주문 건
                                    등은 젤리딜의 내부 사정에 따라 서비스가 어려울 수 있습니다.</li>
                            </ul>
                        </small>
                    </div>
                    <!-- 제품정보 -->
                    <div id="product-group" class="form-group">
                        <div class="product-wrapper row">
                            <div class="product-group col">
                                <label for="product-name">제품이름</label>
                                <span class="form-required">*</span>
                                <input type="text" name="prdName" id="product-name" class="form-control">
                                <span class="form-error"><?php echo $prdNameErr;?></span>
                            </div>
                            <div class="product-group col">
                                <label for="product-price">제품가격</label>
                                <span class="form-required">*</span>
                                <input type="text" name="prdPrice" id="product-price" class="form-control" numberOnly>
                                <span class="form-error"><?php echo $prdPriceErr;?></span>
                            </div>
                        </div>
                    </div>
                    <!-- 모델명 -->
                    <div class="form-group">
                        <label for="model-name">모델명</label>
                        <input type="text" name="modelName" id="model-name" class="form-control"
                            placeholder="예: MGN63xx/A">
                    </div>
                    <!-- 옵션정보 -->
                    <div id="opt-group" class="form-group">
                        <div class="option-wrapper row">
                            <div class="option-group col">
                                <label for="option-name">옵션이름</label>
                                <input type="text" name="optName[]" id="option-name" class="form-control">
                            </div>
                            <div class="option-group col">
                                <label for="option-price">옵션가격</label>
                                <input type="text" name="optPrice[]" id="option-price" class="form-control" numberOnly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="button" onclick="addInput()" value="옵션 추가하기" id="optBtn"
                            class="btn btn-primary100">
                    </div>
                    <div class="form-group-h">
                        <label for="delivery-type">배송옵션</label>
                        <span class="form-required">*</span>
                    </div>
                    <div class="form-group">
                        <select id="delivery-type" name="delType" class="form-control">
                            <option value="선불">선불</option>
                            <option value="착불">착불</option>
                            <option value="퀵">퀵</option>
                            <option value="직접픽업">직접픽업</option>
                        </select>
                        <span class="form-error"><?php echo $delTypeErr; ?></span>
                    </div>
                    <div class="form-group-h">
                        <label for="delivery-cost">배송비</label>
                        <span class="form-required">*</span>
                    </div>
                    <div class="form-group">
                        <input type="text" name="delCost" id="delivery-cost" class="form-control" numberOnly>
                        <span class="form-error"><?php echo $delCostErr; ?></span>
                    </div>
                    <!-- Start 다음주소 입력 -->
                    <div class="form-group">
                        <div class="form-group-h">
                            <label for="delivery-address">배송주소</label>
                            <span class="form-required">*</span>
                        </div>
                        <input type="text" name="postCode" id="jellydeal_postcode" class="form-control"
                            placeholder="우편번호">
                        <span class="form-error"><?php echo $addErr; ?></span>
                        <input type="button" onclick="jellydeal_execDaumPostcode()" value="우편번호 찾기"
                            class="btn btn-primary100">
                        <input type="text" name="roadAdd" id="jellydeal_roadAddress" class="form-control"
                            placeholder="도로명주소">
                        <input type="text" name="jibunAdd" id="jellydeal_jibunAddress" class="form-control"
                            placeholder="지번주소">
                        <span id="guide" style="color:#999;display:none"></span>
                        <input type="text" name="detAdd" id="jellydeal_detailAddress" class="form-control"
                            placeholder="상세주소">
                        <input type="text" name="extAdd" id="jellydeal_extraAddress" class="form-control"
                            placeholder="참고항목">
                    </div>
                    <!-- End 다음주소 입력 -->
                    <div class="form-group-h">
                        <label for="pay-day">희망 Pay Day</label>
                        <span class="input-desc"><em>(신청일부터 100일 이내 설정)</em></span>
                    </div>
                    <div class="form-group" id="datepicker-container">
                        <input type="text" name="payDay" class="form-control" id="pay-day">
                        <span class="form-error"><?php echo $payDayErr; ?></span>
                    </div>
                    <!-- Start 총 금액 계산하기 -->
                    <!-- <div class="form-group">
                        <div id="total-calculator">
                            <span class="total-cost"><?php echo $totalCost; ?></span>
                        </div>
                    </div> -->
                    <!-- End 총 금액 계산하기 -->
                    <input type='submit' name='submit' value='신청하기' class='btn btn-primary100'>
                    <br><br>
                </form><!-- #apply-form -->
            </div><!-- .form-wrapper -->

            <script type="text/javascript">
                // 약관동의 전체 동의 스크립트
                (function ($) {
                    $(document).ready(function () {
                        $("#allterms").on('change', function () {
                            if (this.checked) {
                                $("#serviceTerms").prop('checked', true);
                                $("#privacyTerms").prop('checked', true);
                                $("#consignTerms").prop('checked', true);
                                $("#paylaterTerms").prop('checked', true);
                                $("#marketingTerms").prop('checked', true);
                            } else {
                                $("#serviceTerms").prop('checked', false);
                                $("#privacyTerms").prop('checked', false);
                                $("#consignTerms").prop('checked', false);
                                $("#paylaterTerms").prop('checked', false);
                                $("#marketingTerms").prop('checked', false);
                            }
                        })
                    });
                })(jQuery);
            </script>
            <script type="text/javascript">
                // Number only and add commas script
                (function ($) {
                    $(document).ready(function () {
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
                    });
                })(jQuery);

                // Bootstrap-datepicker init script
                (function ($) {
                    $(document).ready(function () {
                        $("#datepicker-container input").datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            orientation: 'top left'
                        });
                    });
                })(jQuery);

                // Add more options script
                function addInput() {
                    var opt = document.getElementById("opt-group");
                    opt.insertAdjacentHTML('beforeend',
                        '<div class="option-wrapper row"><div class="option-group col"><label for="option-name">옵션이름</label> <input type="text" name="optName[]" id="option-name" class="form-control"></div><div class="option-group col"><label for="option-price">옵션가격</label> <input type="text" name="optPrice[]" id="option-price" class="form-control" numberOnly></div></div>'
                    );
                }
            </script>
            <!-- Start 다음주소 script -->
            <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
            <script>
                function jellydeal_execDaumPostcode() {
                    new daum.Postcode({
                        oncomplete: function (data) {
                            // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
                            // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                            var roadAddr = data.roadAddress; // 도로명 주소 변수
                            var extraRoadAddr = ''; // 참고 항목 변수

                            // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                            // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                            if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
                                extraRoadAddr += data.bname;
                            }
                            // 건물명이 있고, 공동주택일 경우 추가한다.
                            if (data.buildingName !== '' && data.apartment === 'Y') {
                                extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data
                                    .buildingName);
                            }
                            // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                            if (extraRoadAddr !== '') {
                                extraRoadAddr = ' (' + extraRoadAddr + ')';
                            }

                            // 우편번호와 주소 정보를 해당 필드에 넣는다.
                            document.getElementById('jellydeal_postcode').value = data.zonecode;
                            document.getElementById("jellydeal_roadAddress").value = roadAddr;
                            document.getElementById("jellydeal_jibunAddress").value = data.jibunAddress;

                            // 참고항목 문자열이 있을 경우 해당 필드에 넣는다.
                            if (roadAddr !== '') {
                                document.getElementById("jellydeal_extraAddress").value = extraRoadAddr;
                            } else {
                                document.getElementById("jellydeal_extraAddress").value = '';
                            }

                            var guideTextBox = document.getElementById("guide");
                            // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                            if (data.autoRoadAddress) {
                                var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                                guideTextBox.innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';
                                guideTextBox.style.display = 'block';

                            } else if (data.autoJibunAddress) {
                                var expJibunAddr = data.autoJibunAddress;
                                guideTextBox.innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';
                                guideTextBox.style.display = 'block';
                            } else {
                                guideTextBox.innerHTML = '';
                                guideTextBox.style.display = 'none';
                            }
                        }
                    }).open();
                }
            </script>
            <!-- End 다음주소 script -->
            <style>
                .form-error,
                .form-required {
                    color: #FF0000;
                }

                .form-success {
                    color: #00cc00;    
                }

                form ul {
                    padding-inline-start: 1.25rem;
                }
            </style>
        </div><!-- .container -->
        <!-- End Custom Form -->

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>