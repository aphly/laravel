@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">

<div class="container">
    <div class="account_msg" style="">
        <p>Forget password confirmation </p>
        <p>Please check your email to reset your password.</p>
    </div>
</div>
<style>

</style>
@include(config('base.view_namespace_front_blade').'::common.footer')
