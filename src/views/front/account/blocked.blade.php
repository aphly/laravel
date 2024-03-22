@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">

<div class=" container">
    <div class="account_msg">
        Account blocked
    </div>

</div>
@include(config('base.view_namespace_front_blade').'::common.footer')
