@include('laravel::common.header')
<style>
    .order span{min-width: 300px;display: inline-block;}
</style>
<div style="margin-bottom: 50px;">
    <div class="d-flex justify-content-between" style="margin-bottom: 20px;">
        <div>
            <a href="/import_order" style="margin-right: 20px" target="_blank">导入order</a>
            <a href="/import_express" style="margin-right: 20px" target="_blank">导入express</a>
        </div>
        <a href="/express_send" class="express_send" target="_blank">发送邮件</a>
    </div>
    <ul class="order">
        <li>
            <span style="margin-right: 20px">订单号</span>
            <span style="margin-right: 20px">Email</span>
            <span style="margin-right: 20px">快递号</span>
            <span style="margin-right: 20px">网站</span>
            <span style="">状态</span>
        </li>
        @foreach($res['order'] as $val)
        <li>
            <span style="margin-right: 20px">{{$val->order_id}}</span>
            <span style="margin-right: 20px">{{$val->email}}</span>
            <span style="margin-right: 20px">{{$val->express_id}}</span>
            <span style="margin-right: 20px">{{$val->site}}</span>
            @if($val->status)
                <span style="color: green">已发送</span>
            @else
                <span style="color: red">未发送</span>
            @endif
        </li>
        @endforeach
</ul>

</div>
<script>
    // $('.express_send').click(function (e) {
    //     e.preventDefault();
    //     let url = $(this).attr('href');
    //     if(url){
    //         $.ajax({
    //             url,
    //             dataType: "json",
    //             success: function(res){
    //                 alert_msg('邮件发送中...');
    //             }
    //         })
    //     }
    // })
</script>
@include('laravel::common.footer')
