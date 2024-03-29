<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('admin.name')}}</title>
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/c.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/iconfont.css') }}">
    <script src='{{ URL::asset('static/base/js/jquery.min.js') }}' type='text/javascript'></script>
    <script src='{{ URL::asset('static/base/js/c.js') }}' type='text/javascript'></script>
    <script src='{{ URL::asset('static/base/js/admin.js') }}' type='text/javascript'></script>
    <link rel="stylesheet" href="{{ URL::asset('static/base/editor/style.css') }}">
    <script src="{{ URL::asset('static/base/editor/index.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/links.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/jstree.css') }}">
    <script src='{{ URL::asset('static/base/js/jstree.js') }}' type='text/javascript'></script>
    <link rel="stylesheet" href="{{ URL::asset('static/base/css/video-js.min.css') }}">
    <script src='{{ URL::asset('static/base/js/video.min.js') }}' type='text/javascript'></script>
</head>
<body>
<style>
    #editor—wrapper{border: 1px solid #ced4da;border-radius: 2px;}
    #editor-toolbar{border-bottom: 1px solid #ced4da;}
    #editor-container{border-bottom: 1px solid #ced4da;min-height: 400px;}
</style>
