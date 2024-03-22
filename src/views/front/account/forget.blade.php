@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">
<section class="" >
    <div class="forget container">
        <form method="post" data-fn="forget_res" action="/account/forget?redirect={{urlencode(request()->query('redirect',''))}}" id="forget" class="account_form form_request">
            @csrf
            <div class="accountContent">
                <h3 class="" style="margin-bottom: 20px;">
                    Forgot your password?
                </h3>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" name="id" class="form-control" placeholder="Email" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>
                <div id="code_img" class="form-group @if(config('base.seccode_forget')==1) @else none @endif">
                    <label>Captcha</label>
                    <div class="code_img">
                        <input type="text" name="code" class="form-control" value="" autocomplete="off" placeholder="Enter code">
                        <img src="/center/seccode" onclick="code_img(this)" >
                    </div>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="alert alert-warning d-none" id="msg"></div>

                <button class="btn btn-primary text-brand" type="submit">Reset Password</button>

            </div>
        </form>
    </div>

</section>
<script>
    function forget_res(res,_this) {
        _this.find('input.form-control').addClass('is-valid');
        if(!res.code) {
            location.href = res.data.redirect
        }else if(res.code===11000){
            form_err_11000(res,_this);
        }else{
            alert_msg(res);
        }
    }
</script>
@include(config('base.view_namespace_front_blade').'::common.footer')
