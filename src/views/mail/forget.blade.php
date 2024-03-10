@include('laravel::mail.header')
<div>
    <div style="font-weight: 600;font-size: 22px;">Reset Password</div>
    <div style="padding:20px 0;">
        <div style="margin-top: 10px;">Dear {{$userAuth->id}}</div>
        <div style="margin-top: 20px;">
            <a href="{{$userAuth->siteUrl}}/account/forget-password/{{$userAuth->token}}" style="display: inline-block;padding: 10px 20px;
                    background: #089b0e;color: #ffff;text-decoration: none; border-radius: 4px;">Reset Password</a>
        </div>
        <div style="margin-top: 20px;">We've received your request to reset your password. If you don't want to change your password or if you didn't request it, please ignore this email.</div>
    </div>

</div>
@include('laravel::mail.footer')
