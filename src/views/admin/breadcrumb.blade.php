
<section class="mybreadcrumb">
    <ul class="d-flex" >
        @foreach($res['arr'] as $val)
            @if(empty($val['href']))
                <li class="{{$val['class']}}">{{$val['name']}}</li>
            @else
                <li class="{{$val['class']}}"><a class="ajax_get" data-href="{{$val['href']}}">{{$val['name']}}</a></li>
            @endif
        @endforeach
    </ul>
</section>
