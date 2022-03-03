
<div class="top-bar">
    <h5 class="nav-title">修改头像 {{$res['info']['username']}}</h5>
</div>
<div class="imain">
    <form method="post" action="/admin/user/{{$res['info']['id']}}/avatar" class="userform" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="form-group">
                <label for="exampleInputEmail1">头像图片</label>
                <input type="file" accept="image/gif,image/jpeg,image/jpg,image/png,image/svg" name="avatar" class="form-control-file " value="">
                @error('avatar')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </form>
</div>


