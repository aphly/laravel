@Linclude('laravel-front::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">
<section class="container">
    <style>
        .avatar{width: 200px;height: 200px;margin-top: 20px;}
        .avatar img{width: 100%;height: 100%;border-radius: 50%;}
        .avatar i{font-size: 100px;}
    </style>
    <div class="account_info">
        @Linclude('laravel-front::account.left_menu')
        <div class="account-main-section">
            <div class="top-desc d-flex justify-content-between">
                <h2>Dashboard</h2>
            </div>
            <div class="avatar">
                @if($user->avatar_src)
                    <img class="lazy " src="{{$user->avatar_src}}" />
                @else
                    <i class="common-iconfont icon-touxiang"></i>
                @endif
            </div>
            <form action="/account/index" method="post" class="upload_form" onsubmit="uploadImage(event,this)" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">avatar</label>
                    <input type="file" accept="image/gif,image/jpeg,image/jpg,image/png" id="image" name="image" class="form-control-file " >
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="">nickname</label>
                    <input type="text" name="nickname" class="form-control " value="{{$user->nickname}}">
                    <div class="invalid-feedback"></div>
                </div>

                @foreach($user->userAuth as $val)
                    <div class="form-group">
                        <label for="">{{$val->id_type}}</label>
                        <input type="text" name="{{$val->id_type}}" class="form-control " readonly value="{{$val->id}}">
                        <div class="invalid-feedback"></div>
                    </div>
                @endforeach
                <button class="btn btn-primary" type="submit">save</button>
            </form>
        </div>
    </div>
</section>
<script>
    function uploadImage(e,_this){
        e.preventDefault()
        e.stopPropagation()
        const form = $(_this)
        let formData = new FormData();
        formData.append('image', $("#image")[0].files[0]);
        formData.append('_token', '{{csrf_token()}}');
        formData.append('nickname', $('input[name="nickname"]').val());
        let url = form.attr("action");
        let type = form.attr("method");
        if(url && type){
            $.ajax({
                type,url,
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res){
                    alert_msg(res,true)
                },
                complete:function(XMLHttpRequest,textStatus){
                    //console.log(XMLHttpRequest,textStatus)
                }
            })
        }else{
            console.log('no action'+url+type)
        }
    }
</script>
@Linclude('laravel-front::common.footer')
