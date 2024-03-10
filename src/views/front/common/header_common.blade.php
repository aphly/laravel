<!DOCTYPE html>
<html style="font-size: 14px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{$res['title']??''}} {{config('base.title')}}</title>
    <link rel="stylesheet" href="{{ URL::asset('static/base/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('static/base/admin/css/c.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('static/base/admin/css/iconfont.css') }}">
    <script src='{{ URL::asset('static/base/admin/js/jquery.min.js') }}' type='text/javascript'></script>
    <script src="{{ URL::asset('static/base/admin/js/jquery.lazyload.min.js') }}" type="text/javascript"></script>
    <script src='{{ URL::asset('static/base/admin/js/c.js') }}' type='text/javascript'></script>
    <script src='{{ URL::asset('static/base/front/js/common.js') }}' type='text/javascript'></script>
    <link rel="stylesheet" href="{{ URL::asset('static/base/admin/css/viewer.min.css') }}">
    <script src="{{ URL::asset('static/base/admin/js/viewer.min.js') }}" type="text/javascript"></script>
    <meta name="description" content="{{$res['description']??''}}" />
    <script src='{{ URL::asset('static/base/admin/js/decimal.js') }}' type='text/javascript'></script>
</head>
<body>
<style>

</style>
<script>
    $(function () {
        $('input.form-control').attr("autocomplete","off")
    })
</script>
