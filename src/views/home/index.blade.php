@include('laravel::common.header')
<div class="top-bar">
   <h5 class="nav-title">首页</h5>
</div>
<div>
    你好
    @if($res['user'])
        {{$res['user']['nickname']}}

        <a href="/logout">注销</a>

        {{$res['user']['identifier']??''}}
        {{$res['user']['identity_type']??''}}
        <a href="/email/verify">校验邮箱</a>
    @endif


</div>
@include('laravel::common.footer')
