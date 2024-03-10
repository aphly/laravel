@include('laravel::mail.header')
<div>
    <div style="font-weight: 600;font-size: 22px;">Thank you for creating your Account.</div>
    <div style="padding:20px 0;">
        <div style="margin-top: 10px;">Dear {{$userAuth->id}}</div>
        <div style="margin-top: 20px;">
            <a href="{{$userAuth->siteUrl}}/account/email-verify/{{$userAuth->token}}" style="display: inline-block;padding: 10px 20px;
                    background: #089b0e;color: #ffff;text-decoration: none; border-radius: 4px;">Verify email address</a>
        </div>
        <div style="margin-top: 20px;"> Help us secure your account by verifying your email address.</div>
    </div>

</div>
@include('laravel::mail.footer')
