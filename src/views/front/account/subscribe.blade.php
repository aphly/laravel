@Linclude('laravel-front::common.header')
<div class="container">
    <style>

    </style>
    <div class="d-flex justify-content-between account_info">
        @Linclude('laravel-front::account.left_menu')
        <div class="account-main-section">
            <div class="">
                <div class="top-desc d-flex justify-content-between">
                    <h2>Subscribe</h2>
                </div>
                <form method="post" action="/account/subscribe" data-fn="subscribe_res" class="form_request">
                    @csrf
                    @if(!empty($res['info']))
                        <div>
                            <input type="radio" value="1" name="status" id="status1" @if($res['info']->status==1) checked @endif> <label for="status1">Yes</label>
                            <input type="radio" value="2" name="status" id="status2" @if($res['info']->status==2) checked @endif> <label for="status2">No</label>
                        </div>
                    @else
                        <div>
                            <input type="radio" value="1" name="status" id="status1"> <label for="status1">Yes</label>
                            <input type="radio" value="2" name="status" id="status2" checked> <label for="status2">No</label>
                        </div>
                    @endif
                    <button type="submit" class="common_btn" style="padding:4px 8px; ">save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function subscribe_res(res,_this) {
        alert_msg(res)
    }
</script>
@Linclude('laravel-front::common.footer')
