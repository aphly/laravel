
function processAjaxData(urlPath,title='',state={}){
    if(title){
        window.document.title = title;
    }
    window.history.pushState(state,title,urlPath);
}

function checkAll(_this) {
    $("input[type='checkbox']").prop("checked", $(_this).prop('checked'));
}

function _alert_msg(msg,reload=false,time=2000){
    $("#alert_msg").remove();
    let html = '<div id="alert_msg"><div class="alert_msg"><div class="alert_msg_header"><strong class="mr-auto">Tips</strong><small></small><span onclick="$(\'#alert_msg\').remove();">×</span></div><div class="alert_msg_body">'+msg+'</div></div></div>';
    let body = $('body');
    body.append(html);
    let alert_msg = $("#alert_msg");
    _autosize(alert_msg)
    setTimeout(function () {
        alert_msg.remove()
        if(reload){
            location.reload()
        }
    },time);
}

function alert_msg(res,redirect=false,time=2000){
    $('#loading').css('z-index',-1);
    $("#alert_msg").remove();
    let html = '<div id="alert_msg"><div class="alert_msg"><div class="alert_msg_header"><strong class="mr-auto">Tips</strong><small></small><span onclick="$(\'#alert_msg\').remove();">×</span></div><div class="alert_msg_body">'+res.msg+'</div></div></div>';
    let body = $('body');
    body.append(html);
    let alert_msg = $("#alert_msg");
    _autosize(alert_msg)
    setTimeout(function () {
        alert_msg.remove()
        if(redirect){
            if(!res.code && res.data.redirect){
                location.href = res.data.redirect
            }else{
                location.reload()
            }
        }
    },time);
}

function _autosize(ele){
    if(ele.height() <= $(window).height()) {
        ele.css("top", ($(window).height() - ele.height()) / 2);
    }
    if(ele.width() <= $(window).width()) {
        ele.css("left", ($(window).width() - ele.width()) / 2);
    }
}

function code_img(_this) {
    $(_this).attr('src','/center/seccode?t='+new Date().getTime())
}

function form_err_11000(res,_this) {
    for(let i in res.data){
        let str = ''
        res.data[i].forEach((elem, index)=>{
            str = str+elem+'<br>'
        })
        let obj = _this.find('input[name="'+i+'"]');
        obj.removeClass('is-valid').addClass('is-invalid');
        obj.closest('.form-group').find('.invalid-feedback').html(str);
    }
}

function toTree(data) {
    let result = []
    if(!Array.isArray(data)) {
        return result
    }
    data.forEach(item => {
        delete item.nodes;
    });
    let map = {};
    data.forEach(item => {
        map[item.id] = item;
    });
    data.forEach(item => {
        let parent = map[item.pid];
        if(parent) {
            (parent.nodes || (parent.nodes = [])).push(item);
        } else {
            result.push(item);
        }
    });
    return result;
}

function treeData(data,select_ids=0) {
    let new_array = []
    data.forEach((item,index) => {
        let selectable = item.is_leaf?true:false;
        if(select_ids){
            let selected=in_array(item.id,select_ids)?true:false;
            new_array.push({id:item.id,text:item.name,pid:item.pid,state:{selected},selectable})
        }else{
            new_array.push({id:item.id,text:item.name,pid:item.pid,selectable})
        }
        delete item.nodes;
    });
    return new_array;
}

function in_array(search,array){
    for(let i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}

function randomStr(n,all=false) {
    let str;
    if(all){
        str = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    }else{
        str = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    }
    let res = '';
    for (let i = 0; i < n; i++) {
        let id = Math.ceil(Math.random() * (str.length-1));
        res += str[id];
    }
    return res;
}

function urlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
    replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

window.onload = function() {
    const bowen = document.querySelectorAll(".bowen")
    bowen.forEach(btn => {
        btn.addEventListener('click', function (e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;

            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            this.appendChild(ripples);
            setTimeout(() => {
                ripples.remove();
            }, 400);
        })
    })
    $("body").on('click','[data-stopPropagation]',function (e) {
        e.stopPropagation();
    });
}

let urlOption={
    '_set':function (name,val,jump=false) {
        //str
        let url = ''
        let thisURL = String(document.location);
        let url_arr = thisURL.split('?');
        if(url_arr[1]){
            if(url_arr[1].indexOf(name+'=') !== -1){
                let url_arr_p = url_arr[1].split('&');
                let arr = [];
                url_arr_p.forEach(i=>{
                    if(i.indexOf(name+'=') !== -1){
                        arr.push(name+'='+val)
                    }else{
                        arr.push(i)
                    }
                })
                let url_p = arr.join('&');
                url = url_arr[0]+'?'+url_p;
            }else{
                url = url_arr[0]+'?'+url_arr[1]+'&'+name+'='+val;
            }
        }else{
            url = url_arr[0]+'?'+name+'='+val;
        }
        if(jump){
            location.href = url;
        }else{
            return url;
        }
    },
    '_get':function (name) {
        let reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        let r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    },
    '_del':function (name,jump=false) {
        //str
        let url = ''
        let thisURL = String(document.location);
        let url_arr = thisURL.split('?');
        if(url_arr[1]){
            if(url_arr[1].indexOf(name+'=') !== -1){
                let url_arr_p = url_arr[1].split('&');
                let arr = [];
                url_arr_p.forEach(i=>{
                    if(i.indexOf(name+'=') !== -1){
                    }else{
                        arr.push(i)
                    }
                })
                let url_p = arr.join('&');
                url = url_arr[0]+'?'+url_p;
            }else{
                url = url_arr[0]+'?'+url_arr[1];
            }
        }else{
            url = url_arr[0];
        }
        if(jump){
            location.href = url;
        }else{
            return url;
        }
    }
}

let mobileTouch = {
    getDirection(startX, startY, endX, endY) {
        let dy = startY - endY;
        let result = 0;
        if (dy > 0) { //向上滑动
            result = 1;
        } else if (dy < 0) {//向下滑动
            result = 2;
        } else {
            result = 0;
        }
        return result;
    },
    listentouch(callback) {
        let startX, startY;
        document.addEventListener(
            "touchstart",
            function(ev) {
                startX = ev.touches[0].pageX;
                startY = ev.touches[0].pageY;
            },
            false
        );
        let _this = this
        document.addEventListener(
            "touchend",
            function(ev) {
                let endX, endY;
                endX = ev.changedTouches[0].pageX;
                endY = ev.changedTouches[0].pageY;
                let direction = _this.getDirection(startX, startY, endX, endY);
                callback(direction);
            },
            false
        );
    }
}

let _session = {
    set(key,value){
        sessionStorage.setItem(key,JSON.stringify(value));
    },
    get(key){
        let value = sessionStorage.getItem(key);
        return JSON.parse(value);
    }
}

let getList = {
    isGet: true,
    page: 1,
    last_page:1,
    loading_div:'.loading',
    data_div:'#list',
    callback:'makeHtml',
    url:'/zone?page=',
    loading_nothing:'nothing',
    loading_html:'loading',
    loading_more:'more',
    timeout:200,
    timer:true,
    get() {
        if (!this.isGet || this.page>this.last_page) {
            if(this.page>this.last_page){
                $(this.loading_div).html(this.loading_nothing);
            }
            return;
        }
        this.isGet = false;
        let _this = this;
        $(_this.loading_div).html(_this.loading_html);
        clearTimeout(_this.timer);
        _this.timer = setTimeout(function() {
            $.ajax({
                url:_this.url+_this.page,
                success:function (res) {
                    window[_this.callback](res,_this);
                    _this.page++;
                },
                complete:function () {
                    _this.isGet = true;
                    $(_this.loading_div).html(_this.loading_more);
                }
            })
        }, _this.timeout);
    },
    init(params){
        for(let i in params){
            this[i] = params[i];
        }
        let _this = this
        $(window).scroll(function() {
            if (($(window).height() + $(window).scrollTop() + 60) >= $(document).height()) {
                _this.get()
            }
        });
    }
};

function debounce(func, delay=1000,...args) {
    let timer;
    return function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    }
}

let debounce_timer;
function debounce_fn(func,delay=1000,...args) {
    clearTimeout(debounce_timer);
    debounce_timer = setTimeout(() => {
        func.apply(args);
    }, delay);
}

class img_js {
    constructor(op={
        max_size:0.5,quality:0.8,scale_d:0.6,max_w:1000,max_h:1700
    }) {
        this.op = op
    }
    handle(files,callback){
        let _this = this
        _this.imgFileList = [];
        for( let i in files){
            let pettern = /^image/;
            if (pettern.test(files[i].type)) {
                let fr = new FileReader(),
                    img = document.createElement("img");
                fr.readAsDataURL(files[i]);
                fr.onload = function (res) {
                    img.src = this.result;
                    if (files[i].size > 1024*1024*_this.op.max_size) {
                        _this._handle(this.result, function (base64) {
                            _this.imgFileList[i] = _this.dataURLtoFile(base64, files[i].name)
                        },_this.op)
                    } else {
                        _this.imgFileList[i] = files[i];
                    }
                    callback(img);
                }
            }
        }
    }
    _handle(path,callback,op){
        let _this = this
        let img = new Image();
        img.src = path;
        img.onload = function() {
            let c_w,c_h;
            let arr = _this.get_wh(this.width,this.height,op)
            c_w = arr[0]
            c_h = arr[1]
            let canvas = document.createElement('canvas');
            let ctx = canvas.getContext('2d');
            let anw = document.createAttribute("width");
            anw.nodeValue = c_w;
            let anh = document.createAttribute("height");
            anh.nodeValue = c_h;
            canvas.setAttributeNode(anw);
            canvas.setAttributeNode(anh);
            ctx.drawImage(this, 0, 0, c_w, c_h);
            if (!(op.quality && op.quality <= 1 && op.quality > 0)) {
                op.quality=1;
            }
            let base64 = canvas.toDataURL('image/jpeg', op.quality);
            callback(base64);
        }
    }
    get_wh(i_w,i_h,op) {
        let scale = i_w / i_h;
        let c_w,c_h;
        if(scale>op.scale_d){
            if(i_w>op.max_w){
                c_w = op.max_w;
                c_h = parseInt(c_w / scale)
            }else{
                c_w = i_w
                c_h = i_h
            }
        }else{
            if(i_h>op.max_h){
                c_h = op.max_h
                c_w = parseInt(c_h * scale)
            }else{
                c_w = i_w
                c_h = i_h
            }
        }
        return [c_w,c_h];
    }
    dataURLtoFile(dataUrl, fileName){
        let arr = dataUrl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], fileName, {type:mime});
    }
}

function div_fly(obj1,obj2,callback){
    let scrollTop = $(window).scrollTop()
    let scrollLeft = $(window).scrollLeft()
    let cart_jq = obj2.offset()
    let left = cart_jq.left - scrollLeft
    let top = cart_jq.top - scrollTop
    let fly_id = 'fly'
    $('#'+fly_id).remove()
    let _offset = obj1.offset()
    let this_left = _offset.left+obj1.width()/2-8 - scrollLeft
    let this_top = _offset.top+obj1.height()/2-8 - scrollTop
    let html = '<div id="'+fly_id+'" style="position: fixed;left:'+this_left+'px;top:'+this_top+'px;background: '+obj1.css('background')+';' +
        'width: '+obj2.width()+'px;height: '+obj2.height()+'px;border-radius: 50%;z-index: 999999"></div>'
    $('body').append(html)
    $.easing.x = function (pro,t){
        return (t/900)*(t/900)
    }
    $.easing.y = function (pro){
        return pro
    }
    let obj = $('#'+fly_id)
    obj.stop(true,false).animate({
        left: [left,'x'],
        top: [top,'y']
    },900,'swing',function () {
        obj.remove()
        if (callback) { callback() }
    })
}
