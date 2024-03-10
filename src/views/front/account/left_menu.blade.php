
<div class="sidebar-menu">
    <div class="sidebar-menu-list">
        @php
        $uri = request()->path()
        @endphp
        <div class="sidebar-menu-title">My Account Information</div>
        <ul class="sidebar-menu-content">
            <li><a href="/account/index" @if('account/index'==$uri) class="active" @endif>Dashboard</a></li>
            <li><a href="/account/subscribe" @if('account/subscribe'==$uri) class="active" @endif>My Subscribe</a></li>
        </ul>

        <div class="sidebar-menu-title">Customer Service</div>
        <ul class="sidebar-menu-content">
            <li><a href="/account/logout" @if('account/logout'==$uri) class="active" @endif>Logout</a></li>
        </ul>
    </div>
</div>
<style>
    .sidebar-menu{width:280px;overflow:hidden;background: #fff;border-radius: 4px;padding: 20px}
    .sidebar-menu .sidebar-menu-title{padding-top:5px;padding-bottom:7px;margin:0 auto 10px;font-size:18px;font-weight:500}
    .sidebar-menu ul li{list-style:inside;color:#d8d8d8;padding-bottom:5px}
    .sidebar-menu ul li a{font-size:15px;color:#333}
    .sidebar-menu ul li a.active{color: #0da9c4;}
    .sidebar-menu-content{margin-bottom: 15px;}

    .account-main-section{width: calc(100% - 300px);margin-left: 20px;background: #fff;border-radius: 4px;padding: 20px}
    .account_info{margin-top: 10px;display: flex;justify-content: space-between;}
    .top-desc{margin-bottom: 10px;}
    .top-desc a{color:#06b4d1;font-size: 16px;}
    .list_index{}
    .list_index li{margin-bottom: 20px;}

    .form_request .form-group{margin-bottom: 20px;}
    .form_request .form-group p{margin-bottom: 10px;}
    .form_request .form-group p b{color: darkred;}

    @media (max-width: 1199.98px) {
        .account_info{flex-wrap: wrap;}
        .sidebar-menu,.account-main-section{width: 100%;margin-left: 0;margin-bottom: 20px;}
    }
</style>
