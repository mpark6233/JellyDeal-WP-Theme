<?php
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // If necessary, modify the path in the require statement below to refer to the
    // location of your Composer autoload.php file.
    require '/usr/local/bin/vendor/autoload.php';

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
    // "=?UTF-8?B?".base64_encode("서비스 신청서")."?=";
    $subject = '서비스 신청서 : ' . $certification->name;

    // The plain-text body of the email
    $bodyText =  "Email Test\r\nThis email was sent through the
        Amazon SES SMTP interface using the PHPMailer class.";

    // The HTML-formatted body of the email

    $bodyHtml = '
    <h1>서비스 신청서</h1>
    <br>
    <h2>신청인정보</h2>
    <p>이름 : '.$certification->name.'</p>
    <p>성별 : '.$genderTr.'</p>
    <p>생년월일 : '.date('Y-m-d', $certification->birth).'</p>
    <p>이메일 : '.$email.'</p>
    <br>
    <h2>이용동의</h2>
    <p>마케팅수신약관 : '.$marketingterms.'</p>
    <p>군필여부 : '.'</p>
    <br>
    <h2>구매제품정보</h2>
    <p>상품링크 : '.$website.'</p>
    <p>제품이름 : '.$prdName .'</p>
    <p>제품가격 : '.$prdPrice.' 원</p>
    <p>모델명 : '.$modelName.'</p>
    '.$optLoop.'
    <p>배송옵션 : '.$delType.'</p>
    <p>배송비 : '.$delCost.' 원</p>
    <br>
    <h2>배송주소</h2>
    <p>우편번호 : '.$postCode.'</p>
    <p>도로명주소 : '.$roadAdd.'</p>
    <p>지번주소 : '.$jibunAdd.'</p>
    <p>상세주소 : '.$detAdd.'</p>
    <p>참고항목 : '.$extAdd.'</p> 

    <p>Pay Day : '.$payDay.'</p>';

    $mail = new PHPMailer(true);

    try {
        // Specify the SMTP settings.
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->addAttachment("wp-content/uploads/applicants_id/".$file_name);
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

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
        header('Location: https://www.jellydeal.io/apply-complete/');

    } catch (phpmailerException $e) {
        echo "오류가 발생했습니다. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "이메일이 발송되지 않았습니다. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }

?>

