<div class="uk-container">
    <div class="loginBox">
        <h1>LOGIN</h1>

        <form class="loginForm" id="loginForm" method="POST">
            <div class="input-form">
                <input type="text" name="userID" id="userID" class="uk-input" placeholder="이메일을 입력해주세요">
            </div>

            <div class="input-form">
                <input type="password" name="userPW" id="userPW" class="uk-input" placeholder="비밀번호를 입력해주세요">
            </div>

            <div class="input-form">
                <div class="loginSession">
                    <div class="loginSessionBox">
                        <input type="checkbox"> <span>로그인 상태 유지</span>
                    </div>

                    <div class="searchPW">
                        <a href="#">아이디 / 비밀번호 찾기</a>
                    </div>
                </div>
                <button type="button" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom loginBtn">로그인</button>
                <button type="button" class="uk-button uk-button-default uk-width-1-1 uk-margin-small-bottom" onclick="location.href='/register';">회원가입 하러 가기</button>
            </div>
        </form>

        <p id="snsLoginBar">----- SNS 계정으로 로그인 -----</p>

        <div class="SNSLogin">
            <div id="naverIdLogin" class="snsImg"></div>
            <p>네이버 아이디로 로그인</p>
        </div>

        <!-- <div class="input-form">
            <div class="SNSLogin">
                <div id="naverIdLogin" class="snsImg"></div>
                <p>카카오 아이디로 로그인</p>
            </div>
        </div> -->

        <!-- <div class="input-form">
            <div class="SNSLogin">
                <img src="/dev/dev_img/phone.png" alt="phone" class="snsImg" style="height: 70px;">
                <p>본인인증으로 로그인</p>
            </div>
        </div> -->

        <a id="kakao-login-btn"></a>
        <a href="http://developers.kakao.com/logout"></a>

        <!-- <script type='text/javascript'>
            //<![CDATA[
            // 사용할 앱의 JavaScript 키를 설정해 주세요.
            Kakao.init('YOUR APP KEY');

            // 카카오 로그인 버튼을 생성합니다.
            Kakao.Auth.createLoginButton({
                container: '#kakao-login-btn',
                success: function(authObj) {
                    alert(JSON.stringify(authObj));
                },
                fail: function(err) {
                    alert(JSON.stringify(err));
                }
            });
            //
        </script> -->
    </div>
</div>

<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>
<!-- <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script> -->
<script src="./dev/js/login.js"></script>

<script type="text/javascript">
    var naverLogin = new naver.LoginWithNaverId({
        clientId: "QSilNuZ7qHfKk7d1nVba",
        callbackUrl: "http://localhost/php/login_naver_callback_OK1.php",
        isPopup: false,
        loginButton: {
            color: "green",
            type: 1,
            height: 50
        }
    });
    naverLogin.init();
</script>