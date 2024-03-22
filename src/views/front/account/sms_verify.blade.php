@include(config('base.view_namespace_front_blade').'::common.header')
<link rel="stylesheet" href="{{ URL::asset('static/base/front/css/account.css') }}">

<div class="container">
    <div class="uiInterstitial">
        <div class="interstitialHeader">
            <p>Enter the code from your email</p>
        </div>
        <div class="uiInterstitialContent">
            <div class="mts mbl _1v_-">Let us know this email belongs to you. Enter the code in the email sent to <span class="">test1@apixn.com</span>.</div>
        </div>
        <form action="" class="confirmemail">
            <div class="_8n2_" >
                <div style="margin-bottom: 10px;">
                    <input type="text" class="_8n1_ code" autofocus="1" id="code" name="code" size="5" maxlength="5">
                </div>
                <a class="ajax_request _8n3_" href="/account/mail-verify/send">Send email again</a>
            </div>
            <div class="_8iu5" >
                <button type="submit" class="_8iu6" disabled>Continue</button>
            </div>
        </form>
    </div>
</div>
<style>
    .uiInterstitial{border:none;border-radius:8px;box-shadow:0 2px 4px rgb(0 0 0 / 10%);box-sizing:border-box;overflow:hidden;padding:0;width:500px;margin:40px auto}
    .uiInterstitial .interstitialHeader{border-bottom:1px solid rgba(0,0,0,.1);margin:0;padding:18px 16px}
    .interstitialHeader p{font-weight: bold;font-size: 28px;}
    .uiInterstitial .uiInterstitialContent{padding:16px 16px 0;margin-bottom:20px;}

    .uiInterstitial ._8n1_{background-color:transparent;border:1px solid #ccd0d5;border-radius:6px;box-sizing:border-box;color:#1c1e21;font-size:16px;font-weight:normal;line-height:1.25;padding:20px 40px;text-align:inherit;width:100%}
    .uiInterstitial ._8n1_{width: 30%;}
    .uiInterstitial ._8n2_{margin-bottom: 20px;padding: 0 16px;}
    .uiInterstitial ._8iu5{padding: 12px 16px;box-shadow: 0 0 4px rgb(0 0 0 / 10%), 0 0 1px rgb(0 0 0 / 10%);display: flex;justify-content: end;}
    .uiInterstitial ._8iu6{background-color:#ebedf0;color:#1c1e21;font-weight:600;padding-left:40px;padding-right:40px;border:none;height:36px;line-height:36px;border-radius:6px}
    .uiInterstitial ._8n3_{color: var(--a-hover);margin-left: 5px;}
    .uiInterstitial ._8iu6[disabled]{color: #bec3c9;}
</style>
<script>
    $(function () {
        let form_class = '.confirmemail';
        $(form_class).on('input','.code',function () {
            if($(this).val()){
                $(form_class+' button[type="submit"]').removeAttr('disabled')
            }else{
                $(form_class+' button[type="submit"]').attr('disabled',true)
            }
        })

    })
</script>
@include(config('base.view_namespace_front_blade').'::common.footer')
