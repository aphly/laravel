</main>
<footer>
    <div class="footer2">
        <div class="container">
            <div class="footer21">
                <div class="footer21a">
                    <div>
                        <i class="uni app-world"></i>
                    </div>
                </div>
                <div class="footer21b">
                    © {{date('Y')}} <a href="{{url('')}}">{{config('base.hostname')}}</a> All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer>
<style>
</style>
@if(in_array('Aphly\LaravelStatistics',$comm_module))
<script src="{{ URL::asset('static/statistics/js/statistics.js') }}" data-appid="{{config('base.statistics_appid')}}" id="statistics"></script>
@endif
<script src="{{ URL::asset('static/base/admin/js/bootstrap.bundle.min.js') }}"></script>
<script>
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
    function subscribe_res(res,_this) {
        alert_msg(res)
    }
    $(function() {
        $("img.lazy").lazyload({effect : "fadeIn",threshold :50});
    })
</script>
</body>
</html>
