@include('laravel::admin.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/css/admin.css') }}">
<section class="admin container-fluid">
    <div class="row">
        <div class="ad_left d-none d-lg-block">
            <div class="navbar_brand_box">
                <span class="logo">管理中心</span>
            </div>
            <div class="menu">
                <dl class="accordion" id="s_nav">
                    @if(isset($res['menu_tree']))
                        @foreach($res['menu_tree'] as $val)
                            <dd class="">
                                @if(isset($val['child']))
                                    <a class="s_nav_t text-left" data-toggle="collapse" data-target="#collapse{{$val['id']}}" aria-expanded="true" aria-controls="collapse{{$val['id']}}">
                                        <i class="{{$val['icon']}}"></i> {{$val['name']}} <i class="uni app-qianjin_r y"></i>
                                    </a>
                                    <div id="collapse{{$val['id']}}" class="collapse show">
                                        <ul class="card-body">
                                            @foreach($val['child'] as $v)
                                            <li class="">
                                                @if(isset($v['child']))
                                                    <a class="s_nav_t text-left" data-toggle="collapse" data-target="#collapse{{$v['id']}}" aria-expanded="true" aria-controls="collapse{{$v['id']}}">{{$v['name']}}<i class="uni app-qianjin_r y"></i></a>
                                                    <div id="collapse{{$v['id']}}" class="collapse ">
                                                        <ul class="card-body">
                                                            @foreach($v['child'] as $v1)
                                                                <li class="third_menu"><a class="dj" data-title="{{$v1['name']}}" data-href="/{{$v1['route']}}">{{$v1['name']}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <a class="dj" data-title="{{$v['name']}}" data-href="/{{$v['route']}}">{{$v['name']}}</a>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <a class="s_nav_t text-left dj active" data-title="{{$val['name']}}" data-href="/{{$val['route']}}">
                                        <i class="{{$val['icon']}}"></i> {{$val['name']}}
                                    </a>
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
                        <a style="display: block" href="/admin_client/notice/index" class="linkage_nav">
                            <div class="setting">
                                <i class="uni app-gonggao" style="font-size: 20px"></i>
                            </div>
                        </a>
                    </div>

                    <div class="dropdown">
                        <a style="display: block" href="/admin_client/msg/index" class="linkage_nav">
                            <div class="setting">
                                <i class="uni app-lingdang" style="font-size: 20px"></i>
                            </div>
                        </a>
                    </div>

                    <div class="dropdown">
                        <a style="display: block" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user_dropdown">
                                <img class="lazy user_avatar" @if($res['user']['gender']==1) src="{{url('static/base/img/man.png')}}" @else src="{{url('static/base/img/woman.png')}}" @endif data-original="">
                                <span class="user_name wenzi">{{$res['user']['username']}}</span>
                                <i class="uni app-qianjin_xia" style="position: relative;top: 3px;"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="/admin/role">角色选择</a>
                            <a class="dropdown-item layout_ajax" href="/admin/cache">清空缓存</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger layout_ajax " href="/admin/logout">退出</a>
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
    function ajax_complete() {
        $('.ajax_modal').modal('hide');
    }

    function iload(url,data='') {
        if(url){
            $('#loading').css('z-index',100);
            $.ajax({
                url,data,
                dataType: "html",
                success: function(res){
                    $("#iload").html(res)
                    //processAjaxData(url)
                },
                complete:function (){
                    $('#loading').css('z-index',-1);
                    closeMenu()
                }
            })
        }
    }

    function save_form_res(res,_this) {
        if(!res.code) {
            iload(res.data.redirect)
            alert_msg(res)
        }else if(res.code===11000){
            form_err_11000(res,_this);
        }else{
            alert_msg(res)
        }
    }

    function save_form_file_res(res, that) {
        save_form_res(res,that)
    }

    $(function (){
        iload('/admin/home/index');
        $('.layout_ajax').click(function (e) {
            e.preventDefault();
            const that = $(this)
            let confirm_s = that.data('confirm')
            if(confirm_s){
                let msg = "确定吗？";
                if (confirm(msg)!==true){
                    return false;
                }
            }
            let url = that.attr('href');
            if(url){
               $.ajax({
                    url,dataType: "json",
                    success: function(res){
                        if(!res.code && res.data.redirect) {
                            location.href=res.data.redirect
                        }
                        alert_msg(res)
                    }
                })
            }
        })

        $("#iload").on('click','.ajax_request',function (e){
            e.preventDefault()
            e.stopPropagation()
            const that = $(this)
            let confirm_s = that.data('confirm')
            if(confirm_s){
                let msg = "确定吗？";
                if (confirm(msg)!==true){
                    return false;
                }
            }
            let url = that.attr('data-href');
            let load = that.attr('data-load');
            let btn_html = that.html();
            if(url){
                $.ajax({
                    url,dataType: "json",
                    beforeSend:function () {
                        that.attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
                    },
                    success: function(res){
                        if(load){
                            iload(load);
                        }else{
                            iload(res.data.redirect);
                        }
                        alert_msg(res)
                    },
                    complete:function(XMLHttpRequest,textStatus){
                        that.removeAttr('disabled').html(btn_html);
                    }
                })

            }
        })

        $("#iload").on('submit','.select_form',function (){
            const form = $(this)
            //console.log(form.serialize())
            let url = form.attr("action");
            if(url.indexOf('?') !== -1){
                iload(url+'&'+form.serialize());
            }else{
                iload(url+'?'+form.serialize());
            }
            return false;
        })

        $("#iload").on('submit','.save_form',function (){
            const that = $(this)
            let confirm_s = that.data('confirm')
            if(confirm_s){
                let msg = "确定吗？";
                if (confirm(msg)!==true){
                    return false;
                }
            }
            const fn = that.data('fn') || 'save_form_res'
            if(that[0].checkValidity()===false){
            }else{
                let url = that.attr("action");
                let type = that.attr("method");
                if(url && type){
                    that.find('input.form-control').removeClass('is-valid').removeClass('is-invalid');
                    let btn_html = that.find('button[type="submit"]').html();
                    $.ajax({
                        type,url,data: that.serialize(),
                        dataType: "json",
                        beforeSend:function () {
                            that.find('button[type="submit"]').attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
                        },
                        success: function(res){
                            window[fn](res,that);
                        },
                        complete:function(XMLHttpRequest,textStatus){
                            that.find('button[type="submit"]').removeAttr('disabled').html(btn_html);
                            ajax_complete()
                        }
                    })
                }else{
                    console.log('no action')
                }
            }
            return false;
        })

        $("#iload").on('click','.xls_download',function (e){
            e.preventDefault();
            let url = $(this).attr('data-href');
            let form = $("<form></form>").attr("action", url).attr("method", "post");
            form.append('@csrf');
            form.appendTo('body').submit().remove();
        })

        $("#iload").on('submit','.save_form_file',function (){
            const that = $(this)
            let confirm_s = that.data('confirm')
            if(confirm_s){
                let msg = "确定吗？";
                if (confirm(msg)!==true){
                    return false;
                }
            }
            let formData = new FormData(that[0]);
            let url = that.attr("action");
            let type = that.attr("method");
            const fn = that.data('fn') || 'save_form_file_res'
            if(that[0].checkValidity()===false){
            }else {
                if (url && type) {
                    that.find('input.form-control').removeClass('is-valid').removeClass('is-invalid');
                    let btn_html = that.find('button[type="submit"]').html();
                    $.ajax({
                        type, url,
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        beforeSend: function () {
                            that.find('button[type="submit"]').attr('disabled', true).html('<i class="btn_loading app-jiazai uni"></i>');
                        },
                        success: function (res) {
                            window[fn](res, that);
                        },
                        complete: function (XMLHttpRequest, textStatus) {
                            that.find('button[type="submit"]').removeAttr('disabled').html(btn_html);
                        }
                    })
                } else {
                    console.log('no action' + url + type)
                }
            }
            return false;
        })

        $("#iload").on('submit','.del_form',function (){
            let msg = "您真的确定要删除吗？";
            if (confirm(msg)!==true){
                return false;
            }
            const form = $(this)
            if(form[0].checkValidity()===false){
            }else{
                let url = form.attr("action");
                let type = form.attr("method");
                let btn_html = form.find('button[type="submit"]').html();
                if(url && type){
                    $.ajax({
                        type,url,data: form.serialize(),
                        dataType: "json",
                        beforeSend:function () {
                            form.find('button[type="submit"]').attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
                        },
                        success: function(res){
                            if(!res.code) {
                                iload(res.data.redirect)
                            }
                            alert_msg(res)
                        },
                        complete:function(XMLHttpRequest,textStatus){
                            form.find('button[type="submit"]').removeAttr('disabled').html(btn_html);
                        }
                    })
                }else{
                    console.log('no action')
                }
            }
            return false;
        })

        $("#iload").on('click','a.ajax_html,a.page-link',function (e){
            $('.modal-backdrop').remove()
            e.preventDefault();
            let ajax = $("#iload");
            ajax.html('');
            $('#loading').css('z-index',100);
            let obj = $(this);
            if(obj && obj.data('href')){
                obj.addClass('active');
                iload(obj.data('href'))
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
                iload(obj.data('href'))
            }else{
                console.log('error');
            }
        });

        $(".linkage_nav").on('click',function (e){
            e.preventDefault();
            let href = $(this).attr('href')
            $('#s_nav .dj[data-href="'+href+'"]').click();
        });

        $('.accordion .s_nav_t').on('click', function () {
            let obj = $(this).children().filter(".y");
            if(obj.hasClass('app-qianjin_r')){
                obj.removeClass('app-qianjin_r').addClass('app-qianjin_xia');
            }else{
                obj.removeClass('app-qianjin_xia').addClass('app-qianjin_r');
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

    var aphly_viewerjs = document.querySelectorAll('.aphly_viewer_js');
    if(aphly_viewerjs){
        aphly_viewerjs.forEach(function (item,index) {
            new Viewer(item,{
                url: 'data-original',
                toolbar:false,
                title:false,
                rotatable:false,
                scalable:false,
                keyboard:false,
                filter(image) {
                    if(image.className.indexOf("aphly_viewer") !== -1){
                        return true;
                    }else{
                        return false;
                    }
                }
            });
        })

    }
</script>
@include('laravel::admin.footer')
