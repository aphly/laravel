
    <div style="border-top: 1px solid #C6C6C6;margin: 20px 0;"></div>
    <div style="font-size: 12px;">
        <ul style="display: flex;padding: 0 0px;font-size: 12px;">
            <li style="list-style: none;"><a style="" href="{{url('/information/1')}}">About Us</a></li>
            <li style="list-style: none;margin: 0 10px;color: #999;">|</li>
            <li style="list-style: none;"><a style="" href="{{url('/account/order')}}">My Order</a></li>
            <li style="list-style: none;margin: 0 10px;color: #999;">|</li>
            <li style="list-style: none;"><a style="" href="{{url('/account/service')}}">My Service</a></li>
        </ul>
        <div style="margin-top: 10px;">
            If you have any questions, please <a href="{{url('/contact_us')}}" style="color: #333;">contact us</a> or <a
                href="mailto:{{config('base.email')}}" style="color: #333;">{{config('base.email')}}</a> through our customer support page.
        </div>

        <div style="margin-top: 10px;">
            © {{date('Y')}} <a href="{{url('')}}" style="color: #333;">{{config('base.name')}}</a> All Rights Reserved.
        </div>
    </div>
</div>
</div>
