@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">

<section class="" >
    <div class="container">
        <form method="post" data-fn="forget_res" action="/account/forget-password/{{$res['token']}}" id="forget-password-set" class="account_form form_request">
            @csrf
            <div class="accountContent">
                <h3 class="" style="margin-bottom: 20px;">
                    Reset password
                </h3>

                <div class="form-group">
                    <label for="">Email</label>
                    <div>{{$res['userAuth']->id}}</div>
                </div>

                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="new password" autocomplete="off">
                    <div class="invalid-feedback"></div>
                </div>

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
