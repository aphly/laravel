@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">
<section class="">
    <div class="container">
        @if(in_array('email',config('base.id_type')))
        <form class="account_form form_request" data-fn="login_res" method="post" action="/account/login?redirect={{urlencode(request()->query('redirect',''))}}">
            @csrf
            <div class="accountContent">
                <div class="text-center" style="margin-bottom: 24px;">
                    <h1 class="title ">Log In</h1>
                    <span class="descrip">Log in with email</span>
                </div>
                <input type="hidden" name="id_type" class="form-control" value="email">
                <div class=" ">
                    <div class="form-group ">
                        <label>Email</label>
                        <input type="email" name="id" class="form-control" value="" autocomplete="off" required placeholder="you@example.com">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group ">
                        <label class="d-flex justify-content-between"><span>Password</span>
                            <a href="/account/forget?redirect={{urlencode(request()->query('redirect',''))}}" class="color-link-defaut">
                                <span class="forget">Forgot your password?</span>
                            </a>
                        </label>
                        <input type="password" name="password" class="form-control" value="" required autocomplete="off" placeholder="Enter 6 characters or more">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div id="code_img" class="form-group @if(config('base.seccode_login')==1 || (config('base.seccode_login')==2 && $res['seccode'])) @else none @endif">
                        <label>Captcha</label>
                        <div class="code_img">
                            <input type="text" name="code" class="form-control" value="" autocomplete="off" placeholder="Enter code">
                            <img src="/center/seccode" onclick="code_img(this)" >
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn loginBtn text-brand">Login</button>
                </div>

            </div>
        </form>
        @elseif(in_array('mobile',config('base.id_type')))
        <form class="account_form form_request" data-fn="login_res" method="post" action="/account/login?redirect={{urlencode(request()->query('redirect',''))}}">
            @csrf
            <div class="accountContent">
                <div class="text-center" style="margin-bottom: 24px;">
                    <h1 class="title ">Log In</h1>
                    <span class="descrip">Log in with Mobile</span>
                </div>
                <input type="hidden" name="id_type" class="form-control" value="mobile">
                <div class=" ">
                    <div class="form-group ">
                        <label>Mobile</label>
                        <input type="text" name="id" class="form-control" value="" autocomplete="off" required placeholder="Enter mobile">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group ">
                        <label class="d-flex justify-content-between"><span>Password</span>
                            <a href="/account/forget?redirect={{urlencode(request()->query('redirect',''))}}" class="color-link-defaut">
                                <span class="forget">Forgot your password?</span>
                            </a>
                        </label>
                        <input type="password" name="password" class="form-control" value="" required autocomplete="off" placeholder="Enter 6 characters or more">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div id="code_img" class="form-group @if(config('base.seccode_login')==1 || (config('base.seccode_login')==2 && $res['seccode'])) @else none @endif">
                        <label>Captcha</label>
                        <div class="code_img">
                            <input type="text" name="code" class="form-control" value="" autocomplete="off" placeholder="Enter code">
                            <img src="/center/seccode" onclick="code_img(this)" >
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn loginBtn text-brand">Login</button>
                </div>
            </div>
        </form>
        @endif
        <div class="accountContent">
            <div class="split-line ">
                <p class="text-center">
                    <span class="">Don't have an account? </span>
                    <a class="" href="/account/register?redirect={{urlencode(request()->query('redirect',''))}}">Register</a>
                </p>
            </div>
            <div class="line-between">
                <span>Or</span>
            </div>
            <div class="ext_login">
                <a class="google" href="/oauth/google">
                    <div class="d-flex justify-content-between">
                        <div class="ext_icon">
                            <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <path d="M20.6 11.227c0-.709-.064-1.39-.182-2.045H11v3.868h5.382a4.6 4.6 0 0 1-1.996 3.018v2.51h3.232c1.891-1.742 2.982-4.305 2.982-7.35z" fill="#4285F4"></path>
                                    <path d="M11 21c2.7 0 4.964-.895 6.618-2.423l-3.232-2.509c-.895.6-2.04.955-3.386.955-2.605 0-4.81-1.76-5.595-4.123H2.064v2.59A9.996 9.996 0 0 0 11 21z" fill="#34A853"></path>
                                    <path d="M5.405 12.9c-.2-.6-.314-1.24-.314-1.9 0-.66.114-1.3.314-1.9V6.51H2.064A9.996 9.996 0 0 0 1 11c0 1.614.386 3.14 1.064 4.49l3.34-2.59z" fill="#FBBC05"></path>
                                    <path d="M11 4.977c1.468 0 2.786.505 3.823 1.496l2.868-2.868C15.959 1.99 13.695 1 11 1 7.09 1 3.71 3.24 2.064 6.51l3.34 2.59C6.192 6.736 8.396 4.977 11 4.977z" fill="#EA4335"></path>
                                </g>
                            </svg>
                        </div>
                        <div class="">Continue with Google</div>
                        <div></div>
                    </div>
                </a>
                <a class="facebook" href="/oauth/facebook">
                    <div class="d-flex justify-content-between">
                        <div class="ext_icon">
                            <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.122 11.061C21.122 5.505 16.618 1 11.062 1 5.504 1 1 5.505 1 11.061 1 16.083 4.68 20.245 9.49 21v-7.03H6.933V11.06H9.49V8.845c0-2.522 1.502-3.915 3.8-3.915 1.101 0 2.252.197 2.252.197v2.476h-1.268c-1.25 0-1.64.775-1.64 1.57v1.888h2.79l-.446 2.908h-2.344V21c4.81-.755 8.49-4.917 8.49-9.939z" fill="#FFF" fill-rule="nonzero"></path>
                            </svg>
                        </div>
                        <div class="">Continue with Facebook</div>
                        <div></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<style>

</style>
<script>
    function login_res(res,_this) {
        if(!res.code) {
            location.href = res.data.redirect
        }else if(res.code===11000){
            form_err_11000(res,_this);
        }else if(res.code===2){
            $('#code_img').show()
            alert_msg(res);
        }else{
            alert_msg(res);
        }
    }
</script>
@include(config('base.view_namespace_front_blade').'::common.footer')
