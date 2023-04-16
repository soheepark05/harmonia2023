<div class="uk-container">
    <div class="loginBox">
        <h1>회원가입</h1>

        <form method="POST" id="registerForm">
            <div class="input-form row">
                <div class="col">
                    <label for="">아이디(이메일)</label>
                    <input type="text" class="uk-input" id="email" name="email" placeholder="이메일을 입력해주세요">
                </div>
            </div>

            <div class="input-form row">
                <div class="col">
                    <label for="">비밀번호</label>
                    <input type="password" class="uk-input" id="password" name="password" placeholder="비밀번호를 입력해주세요">
                </div>
            </div>

            <div class="input-form row">
                <div class="col">
                    <label for="">비밀번호 확인</label>
                    <input type="password" class="uk-input" id="rePassword" name="rePassword" placeholder="비밀번호를 다시 입력해주세요">
                </div>
            </div>

            <div class="input-form row">
                <div class="col">
                    <label for="">닉네임(상점명)</label>
                    <input type="text" class="uk-input" id="nickname" name="nickname" placeholder="거래왕">
                </div>
            </div>

            <div class="input-form row">
                <div class="col-sm-8">
                    <label for="">휴대전화</label>
                    <input type="text" class="uk-input" id="phone" name="phone" placeholder="010-1234-5678">
                </div>
                <div class="col-sm-4" style="display: flex; justify-content: center; align-items: flex-end;">
                    <button type="button" class="btn phoneGuardBtn" onclick="alert('서비스 준비중으로 인증 없이 가입 가능합니다.');">인증번호 전송</button>
                </div>
            </div>

            <div class="input-form row regAgree">
                <p id="centerAddr">내위치 : <span></span></p>
                <div id="map" style="display: none;"></div>
                
                <div class="col">
                    <p>가입시 <a href="#">이용약관</a> 및 <a href="#">개인정보 취급방침</a>, <a href="#">위치정보제공</a>에 동의합니다.</p>
                </div>
            </div>

            <div class="input-form row">
                <div class="col">
                    <button type="button" id="registerBtn" class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom">회원가입</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="https://static.nid.naver.com/js/naveridlogin_js_sdk_2.0.0.js" charset="utf-8"></script>
<script src="//dapi.kakao.com/v2/maps/sdk.js?appkey=1e4f6947ffe0633761669d9089236567&libraries=services"></script>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="/dev/js/register.js"></script>