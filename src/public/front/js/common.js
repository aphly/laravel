let img_js_obj =  new img_js;
$(function () {
    $("body").on('submit','.form_request',function (){
        const that = $(this)
        let confirm_s = that.data('confirm')
        if(confirm_s){
            let msg = "Are you sure?";
            if (confirm(msg)!==true){
                return false;
            }
        }
        const fn = that.data('fn')
        if(that[0].checkValidity()===false){
        }else{
            let url = that.attr("action");
            let type = that.attr("method");
            if(url && type && fn){
                that.find('input.form-control').removeClass('is-valid').removeClass('is-invalid');
                let btn_html = that.find('button[type="submit"]').html();
                $.ajax({
                    type,url,
                    data: that.serialize(),
                    dataType: "json",
                    beforeSend:function () {
                        that.find('button[type="submit"]').attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
                    },
                    success: function(res){
                        window[fn](res,that);
                    },
                    complete:function(XMLHttpRequest,textStatus){
                        that.find('button[type="submit"]').removeAttr('disabled').html(btn_html);
                    }
                })
            }else{
                console.log('no action')
            }
        }
        return false;
    })

    $("body").on('submit','.form_request_file',function (){
        const that = $(this)
        let confirm_s = that.data('confirm')
        if(confirm_s){
            let msg = "Are you sure?";
            if (confirm(msg)!==true){
                return false;
            }
        }
        let formData = new FormData(that[0]);
        // form.find('input[type="file"]').each(function () {
        //     for (let i = 0; i < $(this)[0].files.length; i++) {
        //         formData.append($(this).attr('name'), $(this)[0].files[i]);
        //     }
        // })
        let url = that.attr("action");
        let type = that.attr("method");
        const fn = that.data('fn')
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

    $('body').on('click','.a_request',function (e) {
        e.preventDefault();
        let that = $(this)
        let confirm_s = that.data('confirm')
        if(confirm_s){
            let msg = "Are you sure?";
            if (confirm(msg)!==true){
                return false;
            }
        }
        let _token = that.data('_token')
        let fn = that.data('fn')
        let url = that.attr('href')
        debounce_fn(function () {
            if(_token){
                $.ajax({
                    url,
                    type:'post',
                    data:that.data(),
                    dataType: "json",
                    success:function (res) {
                        window[fn](res, that);
                    }
                })
            }
        },400)
    })

    $('.input_file_img').on("change" , function(){
        let files = this.files;
        let img_list = $(this).data('img_list')
        $('.'+img_list).html('')
        img_js_obj.handle(files,function (img) {
            $('.'+img_list).append(img)
        });
    })

    $("body").on('submit','.form_request_img_file',function (){
        let imgFileList = img_js_obj.imgFileList
        const that = $(this)
        let confirm_s = that.data('confirm')
        if(confirm_s){
            let msg = "Are you sure?";
            if (confirm(msg)!==true){
                return false;
            }
        }
        let formData = new FormData(that[0]);
        let image_length = that.data('image_length') || 0
        if(image_length && image_length<imgFileList.length){
            _alert_msg('Up to '+image_length+' images can be uploaded at once')
            return false;
        }
        let image_size = $(this).data('image_size') || 0
        for(let i in imgFileList){
            if(image_size && (imgFileList[i].size/1024/1024)>image_size){
                _alert_msg('Image size exceeds the limit of '+image_size+'M')
                return false;
            }
            formData.append('files[]', imgFileList[i]);
        }
        let url = that.attr("action");
        let type = that.attr("method");
        const fn = that.data('fn')
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
})

function isDel() {
    let msg = "Are you really sure you want to delete it?";
    if(confirm(msg)==true){
        return true;
    }else{
        return false;
    }
}

let currency = {
    _format(val,symbol_left='',symbol_right=''){
        let str='';
        val = parseFloat(val);
        if(symbol_left){
            str += symbol_left;
        }
        str +=val.toFixed(2);
        if(symbol_right){
            str += symbol_right;
        }
        return str;
    },
    format(val,code=''){
        for(let i in currency_data){
            if(currency_data[i].code==code){
                return this._format(val,currency_data[i].symbol_left,currency_data[i].symbol_right)
            }
        }
        return val;
    }
}
