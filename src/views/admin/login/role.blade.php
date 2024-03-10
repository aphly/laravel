@include('laravel::admin.header')

<div class=" container">
    <div class="d-flex justify-content-center" style="margin-top: 20%;">
        <div style="">
            @if($res['roleData']->count())
                <ul class="role_choose">
                @foreach($res['roleData'] as $val)
                    <li><a href="{{route('adminChooseRole')}}?role_id={{$val->role_id}}">{{$val->name}}</a></li>
                @endforeach
                </ul>
            @else
                请联系管理员
            @endif
        </div>
    </div>
</div>
<style>
    .role_choose li{padding:5px 10px;font-size:16px;text-align:center;border:1px solid #007bff;border-radius:4px;margin-bottom:10px}
</style>
@include('laravel::admin.footer')
