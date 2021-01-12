<?php
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // If necessary, modify the path in the require statement below to refer to the
    // location of your Composer autoload.php file.
    require '/usr/local/bin/vendor/autoload.php';

    session_start();
    require_once( $_SERVER["DOCUMENT_ROOT"] . "/wp-load.php" );

    $_SESSION['termsreqErr'] = $termsreqErr;
    $_SESSION['phoneValErr'] = $phoneValErr;
    $_SESSION['phoneValSuccess'] = $phoneValSuccess;
    $_SESSION['emailErr'] = $emailErr;
    $_SESSION['attachmentErr'] = $attachmentErr;
    $_SESSION['websiteErr'] = $websiteErr;
    $_SESSION['prdNameErr'] = $prdNameErr;
    $_SESSION['prdPriceErr'] = $prdPriceErr;
    $_SESSION['delTypeErr'] = $delTypeErr;
    $_SESSION['delCostErr'] = $delCostErr;
    $_SESSION['payDayErr'] = $payDayErr;
    $_SESSION['$addErr'] = $$addErr;

    // // validate attachment https://www.w3schools.com/php/php_file_upload.asp
    // $target_dir = '/wp-content/uploads/applicants_id/';
    // $target_file = $target_dir . basename($_FILES["IDUpload"]["name"]);
    // $uploadOk = 1;
    // $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


    // init service terms variables
    $allterms = $terms = $privacyterms = $consignterms = $paylaterterms = $marketingterms = $termsreqErr = "";
    // init phone identification variables

    // init form variables
    $phoneValErr = $emailErr = $attachmentErr = $websiteErr = $prdNameErr = $prdPriceErr = $delTypeErr = $delCostErr = $payDayErr = $phoneValSuccess = "";
    $phoneVal = $email = $website = $prdName = $prdPrice = $delType = $delCost = $payDay = $payDayArr = "";
    // init Danal Certification variables
    $appname = $gender = $birth = $message = "";
    // init Daum Address variables
    $postCode = $addErr = $roadAdd = $jibunAdd = $detAdd = $extAdd = "";

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

    // input fields sanitation and validation  
    if (isset($_POST['submit'])) {

        // validate required terms
        if (empty($_POST["terms"]) || empty($_POST["privacyterms"]) || empty($_POST["consignterms"]) || empty($_POST["paylaterterms"])) {
            $termsreqErr = "필수 약관에 동의해 주세요.";
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

        // $check = getimagesize($_FILES["IDUpload"]["tmp_name"]);
        // if($check !== false) {
        //     // echo "업로드한 파일은 " . $check["mime"] . " 파일입니다.";
        //     $uploadOk = 1;
        // } else {
        //     $attachmentErr = "이미지 파일만 업로드 가능합니다.";
        //     $uploadOk = 0;
        // }

        // // Check if file already exists
        // if (file_exists($target_file)) {
        //     $attachmentErr = "Sorry, file already exists.";
        //     $uploadOk = 0;
        // }
        
        // // Check file size
        // if ($_FILES["fileToUpload"]["size"] > 500000) {
        //     $attachmentErr = "Sorry, your file is too large.";
        //     $uploadOk = 0;
        // }

        // // Allow certain file formats
        // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        //     $attachmentErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        //     $uploadOk = 0;
        // }

        // // Check if $uploadOk is set to 0 by an error
        // if ($uploadOk == 0) {
        //     $attachmentErr = "Sorry, your file was not uploaded.";
        // // if everything is ok, try to upload file
        // } elseif (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //     $attachmentErr = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        // } else {
        //     $attachmentErr = "Sorry, there was an error uploading your file.";
        // }

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
        if (empty($_POST["prdPrice"])) {
            $prdPriceErr = "제품가격을 입력해 주세요.";
        } else {
            $prdPrice = form_input($_POST["prdPrice"]);
        }

        // model name (sanitize only)
        if (!empty($_POST["modelName"])) {
            $modelName = form_input($_POST["modelName"]);
        }

        // option name (sanitize only)
        if (!empty($_POST["optName"])) {
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
        if (empty($_POST["delCost"])) {
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

        // validate error before proceed
        if ($termsErr !='' || $phoneValErr !='' || $emailErr !='' || $attachmentErr !='' || $websiteErr !='' || $prdNameErr !='' || $prdPriceErr !='' || $delTypeErr !='' || $delCostErr !='' || $payDayErr !='') {
        } else {

            // 본인인증 조회 https://github.com/iamport/iamport-rest-client-php/blob/master/example/example_get_certification_by_imp_uid.php

            require_once( get_stylesheet_directory() . '/inc/iamport.php' );

            date_default_timezone_set('Asia/Seoul');

            $iamport = new Iamport('9758574948164308', 'nisAEwvDi2uSPYPgO0WekY5JVfwQ52Stlk30QzD9C82r6RIVSEacDfVyvroBug4PB8PsItBkBEMIvvDo');
        
            #1. imp_uid 로 주문정보 찾기(아임포트에서 생성된 거래고유번호)
            $result = $iamport -> findCertificationByImpUID($impField); //IamportResult 를 반환(success, data, error)

            // "From" address verified with Amazon SES.
            $sender = 'support@jellydeal.io';
            $senderName = 'JellyDeal Webmaster';

            // Replace recipient@example.com with a "To" address. If your account
            // is still in the sandbox, this address must be verified.
            $recipient = 'apply@jellydeal.io';

            // Amazon SES SMTP user name.
            $usernameSmtp = 'AKIA3S52DHLPMB53A2QC';

            // Amazon SES SMTP password.
            $passwordSmtp = 'BMsupEFshXQPfhW/MuMLEIF/FZLYmpmAHdIWmstaGJ+3';

            // Specify a configuration set. If you do not want to use a configuration
            // set, comment or remove the next line.
            // $configurationSet = 'ConfigSet';

            // If you're using Amazon SES in a region other than US West (Oregon),
            // replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
            // endpoint in the appropriate region.
            $host = 'email-smtp.ap-northeast-2.amazonaws.com';
            $port = 587;

            // The subject line of the email
            $subject = '서비스 신청서' . '';

                if ( $result->success ) {
                    /**
                    *	IamportPayment 를 가리킵니다. __get을 통해 API의 Payment Model의 값들을 모두 property처럼 접근할 수 있습니다.
                    *	참고 : https://api.iamport.kr/#!/payments/getPaymentByImpUid 의 Response Model
                    */
                    $certification = $result->data;

                    # certified 필드를 통해 인증여부를 판단합니다.
                    if($certification->certified) {

                        // The plain-text body of the email
                        $bodyText =  "Email Test\r\nThis email was sent through the
                            Amazon SES SMTP interface using the PHPMailer class.";

                        // The HTML-formatted body of the email
                        $bodyHtml = '
                        <h1>서비스 신청서</h1>
                        <br>
                        <br>
                        <h2>이용동의</h2>
                        <p>마케팅수신약관 : '.$marketingterms.'</p>
                        <p>군필여부 : '.'</p>
                        <br>
                        <h2>신상명세</h2>
                        <p>이름 : '.$certification->name.'</p>
                        <p>성별 : '.$certification->gender.'</p>
                        <p>생년월일 : '.$certification->birth.'</p>
                        <p>이메일 : '.$email.'</p>
                        <br>
                        <h2>구매제품정보</h2>
                        <p>상품링크 : '.$website.'</p>
                        <p>제품이름 : '.$prdName .'</p>
                        <p>제품가격 : '.$prdPrice.'</p>
                        <p>모델명 : '.$modelName.'</p>
                        <p>옵션이름 : '.$optName.'</p>
                        <p>옵션가격 : '.$optPrice .'</p>
                        <p>배송옵션 : '.$delType.'</p>
                        <p>배송비 : '.$delCost.'</p>
                        <br>
                        <h2>배송주소</h2>
                        <p>우편번호 : '.$postCode.'</p>
                        <p>도로명주소 : '.$roadAdd.'</p>
                        <p>지번주소 : '.$jibunAdd.'</p>
                        <p>상세주소 : '.$detAdd.'</p>
                        <p>참고항목 : '.$extAdd.'</p> 
                        <br>
                        <p>Pay Day : '.$payDay.'</p>';

                        $mail = new PHPMailer(true);

                        try {
                            // Specify the SMTP settings.
                            $mail->isSMTP();
                            $mail->setFrom($sender, $senderName);
                            $mail->Username   = $usernameSmtp;
                            $mail->Password   = $passwordSmtp;
                            $mail->Host       = $host;
                            $mail->Port       = $port;
                            $mail->SMTPAuth   = true;
                            $mail->SMTPSecure = 'tls';
                            $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

                            // Specify the message recipients.
                            $mail->addAddress($recipient);
                            // You can also add CC, BCC, and additional To recipients here.

                            // Specify the content of the message.
                            $mail->isHTML(true);
                            $mail->Subject    = $subject;
                            $mail->Body       = $bodyHtml;
                            $mail->AltBody    = $bodyText;
                            $mail->Send();
                            echo "이메일이 발송되었습니다!" , PHP_EOL;
                            // redirect to apply-complete page
                            header('Location:https://www.jellydeal.io/apply-complete/');
                        } catch (phpmailerException $e) {
                            echo "오류가 발생했습니다. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
                        } catch (Exception $e) {
                            echo "이메일이 발송되지 않았습니다. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
                        }
                    }
                } else {
                    error_log($result->error['code']);
                    error_log($result->error['message']);
                }
        }
    }