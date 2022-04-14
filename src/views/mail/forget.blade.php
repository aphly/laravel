<div>
    <div>Hi {{$userAuth->identifier}}!</div>
    <div>Help us secure your account by verifying your email address ({{$userAuth->identifier}}). </div>
    <div><a href="{{$userAuth->siteUrl}}/forget-password/{{$userAuth->token}}">Verify email address</a></div>
</div>
