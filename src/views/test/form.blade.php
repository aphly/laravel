@include('laravel::common.header')
<style>
    .order span{min-width: 300px;display: inline-block;}
    .imglist img{width: 50px;height: 50px;}
</style>
<div style="margin-bottom: 50px;">
    <div id="imglist" class="imglist"></div>
    <label>
        点击这里添加图像
        <input type="file" class="fileUpload" accept="image/gif,image/jpeg,image/jpg,image/png"  multiple="multiple" style="display: none">
    </label>
    <button onclick="upload()">上传</button>
</div>
<script>
    $(function () {
        $(".fileUpload").change(function(){
            let img_html = ''
            let img_obj = $(this)[0];
            for (let i = 0; i < img_obj.files.length; i++) {
                let src = URL.createObjectURL(img_obj.files[i]);
                img_html += `<img data-file_id="i" src="${src}">`
            }
            $("#imglist").html(img_html);
        });
    })
    function upload() {
        let formData = new FormData();
        let img_obj = $('.fileUpload')[0];
        for (let i = 0; i < img_obj.files.length; i++) {
            formData.append("file[]", img_obj.files[i]);
        }
        formData.append('_token', '{{csrf_token()}}');
        $.ajax({
            url:'/upload',
            type:"post",
            data:formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(res){
                console.log(res);
            }
        })
    }

    // $('.express_send').click(function (e) {
    //     e.preventDefault();
    //     let url = $(this).attr('href');
    //     if(url){
    //
    //     }
    // })

    //图片类型验证
    function verificationPicFile(file) {
        var fileTypes = [".jpg", ".png"];
        var filePath = file.value;
        //当括号里面的值为0、空字符、false 、null 、undefined的时候就相当于false
        if(filePath){
            var isNext = false;
            var fileEnd = filePath.substring(filePath.indexOf("."));
            for (var i = 0; i < fileTypes.length; i++) {
                if (fileTypes[i] == fileEnd) {
                    isNext = true;
                    break;
                }
            }
            if (!isNext){
                alert('不接受此文件类型');
                file.value = "";
                return false;
            }
        }else {
            return false;
        }
    }

    //图片大小验证
    function verificationPicFile(file) {
        var fileSize = 0;
        var fileMaxSize = 1024;//1M
        var filePath = file.value;
        if(filePath){
            fileSize =file.files[0].size;
            var size = fileSize / 1024;
            if (size > fileMaxSize) {
                alert("文件大小不能大于1M！");
                file.value = "";
                return false;
            }else if (size <= 0) {
                alert("文件大小不能为0M！");
                file.value = "";
                return false;
            }
        }else{
            return false;
        }
    }

    //图片尺寸验证
    function verificationPicFile(file) {
        var filePath = file.value;
        if(filePath){
            //读取图片数据
            var filePic = file.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                var data = e.target.result;
                //加载图片获取图片真实宽度和高度
                var image = new Image();
                image.onload=function(){
                    var width = image.width;
                    var height = image.height;
                    if (width == 720 | height == 1280){
                        alert("文件尺寸符合！");
                    }else {
                        alert("文件尺寸应为：720*1280！");
                        file.value = "";
                        return false;
                    }
                };
                image.src= data;
            };
            reader.readAsDataURL(filePic);
        }else{
            return false;
        }
    }
</script>
@include('laravel::common.footer')
