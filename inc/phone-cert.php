<?php
/**
 * File phone-cert.php
 *
 * Initialize phone cert
 *
 * Please see comments below.
 */
?>

<script type="text/javascript">
    (function ($) {

        $(document).ready(function () {
            $("#phoneValBtn").click(function () {

                var isMobile = {
                    Android: function () {
                        return navigator.userAgent.match(/Android/i) == null ? false : true;
                    },
                    IOS: function () {
                        return navigator.userAgent.match(/iPhone|iPad|iPod/i) == null ?
                            false :
                            true;
                    }
                };

                IMP.init("imp49179850"); // 가맹점 식별코드 imp97539348


                // IMP.certification(param, callback) 호출
                IMP.certification({ // param
                    merchant_uid: 'JD_' + new Date().getTime(),
                    min_age: 19
                }, function (rsp) {
                    if (rsp.success) {
                        // 인증성공
                        setInputValue('impField', rsp.imp_uid);
                        console.log(rsp.imp_uid);
                        console.log(rsp.merchant_uid);
                        $.ajax({
                            type: 'POST',
                            url: '<?php get_stylesheet_directory() . "/certifications/confirm"?>',
                            dataType: 'json',
                            data: {
                                imp_uid: rsp.imp_uid
                            }
                        }).done(function(rsp) {
                            // 이후 Business Logic 처리
                            takeResponseAndHandle(rsp);
                        });

                    } else {
                        // 인증취소 또는 인증실패
                        if (isMobile.IOS()) {
                            // 브릿지 연동 : authFail는 브릿지 네임 (프로젝트에 맞게 설정 필요)
                            webkit.messageHandlers.authFail.postMessage(JSON.stringify(rsp));
                        }
                        var msg = '인증에 실패하였습니다.';
                        msg += '에러내용 : ' + rsp.error_msg;

                        alert(msg);
                        alert(rsp.error_msg);
                    }

                });

                function takeResponseAndHandle(rsp) {
                    if (rsp.success) {
                        // 인증성공
                        if (isMobile.IOS()) {
                            // 브릿지 연동 : authSuccess는 브릿지 네임 (프로젝트에 맞게 설정 필요)
                            webkit.messageHandlers.authSuccess.postMessage(JSON.stringify(rsp));
                        }
                        console.log(rsp.imp_uid);
                        console.log(rsp.merchant_uid);
                    } else {
                        // 인증취소 또는 인증실패
                        if (isMobile.IOS()) {
                            // 브릿지 연동 : authFail는 브릿지 네임 (프로젝트에 맞게 설정 필요)
                            webkit.messageHandlers.authFail.postMessage(JSON.stringify(rsp));
                        }
                        var msg = '인증에 실패하였습니다.';
                        msg += '에러내용 : ' + rsp.error_msg;

                        alert(msg);
                    }
                }

                // Add value to hidden input field
                function setInputValue(impField, val) {
                    document.getElementById(impField).setAttribute('value', val);
                }

            });
        });
    })(jQuery);
</script>