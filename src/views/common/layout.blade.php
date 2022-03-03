@include('laravel::common.header')
<link rel="stylesheet" href="{{ URL::asset('vendor/laravel-admin/css/admin.css') }}">
<section class="admin container-fluid">
    <div class="row">
        <div class="ad_left d-none d-lg-block">
            <div class="navbar_brand_box">
                <span class="logo">管理中心</span>
            </div>
            <div class="menu">
                <dl class="accordion" id="s_nav">
                    @if(isset($res['menu_tree']['child']))
                        @foreach($res['menu_tree']['child'] as $val)
                            <dd class="">
                                <a class="s_nav_t text-left" data-toggle="collapse" data-target="#collapse{{$val['id']}}" aria-expanded="true" aria-controls="collapse{{$val['id']}}">
                                    <i class="{{$val['icon']}}"></i> {{$val['name']}} <i class="uni app-caret-right-copy y"></i>
                                </a>
                                @if(isset($val['child']))
                                <div id="collapse{{$val['id']}}" class="collapse ">
                                    <ul class="card-body">
                                        @foreach($val['child'] as $v)
                                        <li class=""><a class="dj" data-title="{{$v['name']}}" data-href="{{$v['url']}}">{{$v['name']}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </dd>
                        @endforeach
                    @endif
                </dl>
            </div>
            <div class="menuclose d-lg-none" onclick="closeMenu()"></div>
        </div>
        <div class="ad_right">
            <div class="topbar d-flex justify-content-between">
                <div class="d-flex">
                    <div id="showmenu" class="uni app-px1 d-lg-none"></div>
                    <a href="/" class="portal" target="_blank">网站首页</a>
                </div>
                <div class="d-flex">
                    <div class="dropdown">
                        <a style="display: block" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user_dropdown">
                                <img class="lazy user_avatar" @if($res['user']['gender']==1) src="{{url('vendor/laravel-admin/img/man.png')}}" @else src="{{url('vendor/laravel-admin/img/woman.png')}}" @endif data-original="">
                                <span class="user_name wenzi">{{$res['user']['username']}}</span>
                                <i class="uni app-xia" style="position: relative;top: 3px;"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item " href="#" style="display: none;">Action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger layout_ajax_post " href="/admin/logout">退出</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a style="display: block" href="#" role="button" data-target=”#dropdownsettingbox” id="dropdownsetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="setting">
                                <i class="uni app-xitong"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" id="dropdownsettingbox" aria-labelledby="dropdownsetting">
                            <a class="dropdown-item layout_ajax_post" href="/admin/cache">清空缓存</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iload_box">
                <div class="d-none alert alert-danger" id="error_msg" role="alert"></div>
                <div class="loading" id="loading">
                    <div class="loading-pointer-inner"><div class="pointer"></div> <div class="pointer"></div> <div class="pointer"></div></div>
                </div>
                <div id="iload"></div>
            </div>
        </div>
    </div>
</section>

<script>
    function iload(url,data='') {
        $('#loading').css('z-index',100);
        $("#iload").load(url,data,function () {
            $('#loading').css('z-index',-1);
        });
    }
    $(function (){
        iload('/admin/index/index');

        $('.layout_ajax_post').click(function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            if(url){
               $.ajax({
                    url,
                    dataType: "json",
                    success: function(res){
                        alert_msg(res)
                        if(!res.code && res.data.redirect) {
                            location.href=res.data.redirect
                        }
                    }
                })
            }
        })

        $("#iload").on('submit','.select_form',function (e){
            e.preventDefault()
            e.stopPropagation()
            const form = $(this)
            //console.log(form.serialize())
            let url = form.attr("action");
            if(url.indexOf('?') !== -1){
                iload(url+'&'+form.serialize());
            }else{
                iload(url+'?'+form.serialize());
            }
        })

        $("#iload").on('submit','.save_form',function (e){
            e.preventDefault()
            e.stopPropagation()
            const form = $(this)
            if(form[0].checkValidity()===false){
            }else{
                let url = form.attr("action");
                let type = form.attr("method");
                if(url && type){
                    $('.save_form input.form-control').removeClass('is-valid').removeClass('is-invalid');
                    $.ajax({
                        type,url,
                        data: form.serialize(),
                        dataType: "json",
                        success: function(res){
                            $('.save_form input.form-control').addClass('is-valid');
                            if(!res.code) {
                                $("#iload").load(res.data.redirect);
                                alert_msg(res)
                            }else if(res.code===11000){
                                for(var item in res.data){
                                    let str = ''
                                    res.data[item].forEach((elem, index)=>{
                                        str = str+elem+'<br>'
                                    })
                                    let obj = $('.save_form input[name="'+item+'"]');
                                    obj.removeClass('is-valid').addClass('is-invalid');
                                    obj.next('.invalid-feedback').html(str);
                                }
                            }else{
                                alert_msg(res)
                            }
                        },
                        complete:function(XMLHttpRequest,textStatus){
                            //console.log(XMLHttpRequest,textStatus)
                        }
                    })
                }else{
                    console.log('no action')
                }
            }
        })

        $("#iload").on('submit','.del_form',function (e){
            e.preventDefault()
            e.stopPropagation()
            let msg = "您真的确定要删除吗？";
            if (confirm(msg)!==true){
               return;
            }
            const form = $(this)
            if(form[0].checkValidity()===false){
            }else{
                let url = form.attr("action");
                let type = form.attr("method");
                if(url && type){
                    $('.save_form input.form-control').removeClass('is-valid').removeClass('is-invalid');
                    $.ajax({
                        type,url,
                        data: form.serialize(),
                        dataType: "json",
                        success: function(res){
                            $('.save_form input.form-control').addClass('is-valid');
                            if(!res.code) {
                                alert_msg(res)
                                $("#iload").load(res.data.redirect);
                            }else{
                                alert_msg(res)
                            }
                        },
                        complete:function(XMLHttpRequest,textStatus){
                            //console.log(XMLHttpRequest,textStatus)
                        }
                    })
                }else{
                    console.log('no action')
                }
            }
        })

        $("#iload").on('click','a.ajax_get,a.page-link',function (e){
            e.preventDefault();
            let ajax = $("#iload");
            ajax.html('');
            $('#loading').css('z-index',100);
            let obj = $(this);
            if(obj && obj.data('href')){
                obj.addClass('active');
                ajax.load(obj.data('href'),function(responseTxt,statusTxt,xhr){
                    if(statusTxt=="success"){}
                    $('#loading').css('z-index',-1);
                });
            }else{
                console.log('error',obj,obj.data('href'));
            }
        });


        $("#s_nav .dj").on('click',function (e){
            e.preventDefault();
            let ajax = $("#iload");
            ajax.html('');
            $('#loading').css('z-index',100);
            $("#s_nav .dj").removeClass('active');
            let obj = $(this);
            if(obj && obj.data('href')){
                obj.addClass('active');
                ajax.load(obj.data('href'),function(responseTxt,statusTxt,xhr){
                    $('title').html(obj.data('title'));
                    if(statusTxt=="success"){}
                    $('#loading').css('z-index',-1);
                    closeMenu();
                });
            }else{
                console.log('error');
            }
        });

        $('.accordion .s_nav_t').on('click', function () {
            let obj = $(this).children().filter(".y");
            if(obj.hasClass('app-caret-right-copy')){
                obj.removeClass('app-caret-right-copy').addClass('app-xia');
            }else{
                obj.removeClass('app-xia').addClass('app-caret-right-copy');
            }
        });
        $('#showmenu').on('click',function (){
            let obj = $('.ad_left');
            if(obj.hasClass('d-none')){
                obj.removeClass('d-none d-lg-block')
                $(this).removeClass('app-px1').addClass('app-px')
            }else{
                obj.addClass('d-none d-lg-block')
                $(this).removeClass('app-px').addClass('app-px1')
            }
        })
        //$("img.lazy").lazyload({effect : "fadeIn",threshold :50});
    });

    function closeMenu(){
        $('.ad_left').addClass('d-none d-lg-block')
        $('#showmenu').removeClass('app-px').addClass('app-px1')
    }

    var aphly_viewerjs = document.getElementById('aphly_viewerjs');
    if(aphly_viewerjs){
        var aphly_viewer = new Viewer(aphly_viewerjs,{
            url: 'data-original',
            toolbar:false,
            title:false,
            rotatable:false,
            scalable:false,
            keyboard:false,
            filter(image) {
                if(image.className.indexOf("aphly_viewer") != -1){
                    return true;
                }else{
                    return false;
                }
            },
        });
    }
</script>
@include('laravel::common.footer')
