@include('laravel::common.header')
<div class="top-bar">
   <h5 class="nav-title">首页</h5>
</div>
<div>
    你好
    @if($res['user'])
        {{$res['user']->nickname}}
    @endif
</div>
@include('laravel::common.footer')
