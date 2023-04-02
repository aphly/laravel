class MyTree{
    constructor(op) {
        this.op = op
    }
    tree_btn($role=false) {
        let _this = this
        $(_this.op.tree_form).hide();
        if(_this.op.select.length>0){
            if(_this.op.select[0].data.type===1 || _this.op.select[0].data.type===2){
                $('#tree_btn').html(`<div class="d-flex tree_form_btn justify-content-between">
                                            <div class="d-flex">
                                                <span class="tree_add" data-pid="${_this.op.select[0].data.id}">新增</span>
                                                <span class="tree_edit" data-id="${_this.op.select[0].data.id}">编辑</span>
                                            </div>
                                            <div class="tree_del" data-id="${_this.op.select[0].data.id}">删除</div>
                                        </div>`)
            }else{
                if($role){
                    $('#tree_btn').html(`<div class="d-flex tree_form_btn justify-content-between">
                                            <div class="d-flex">
                                                <span class="tree_edit" data-id="${_this.op.select[0].data.id}">编辑</span>
                                                <a class="badge badge-success ajax_get" data-href="/admin/role/${_this.op.select[0].data.id}/permission">授权</a>
                                                <a class="badge badge-success ajax_get" data-href="/admin/role/${_this.op.select[0].data.id}/menu">菜单</a>
                                            </div> <div class="tree_del" data-id="${_this.op.select[0].data.id}">删除</div>
                                        </div>`)
                }else{
                    $('#tree_btn').html(`<div class="d-flex tree_form_btn justify-content-between">
                                                <div class="d-flex"><span class="tree_edit" data-id="${_this.op.select[0].data.id}">编辑</span></div>
                                                <div class="tree_del" data-id="${_this.op.select[0].data.id}">删除</div>
                                            </div>`)
                }
            }
        }else{
            $('#tree_btn').html(`<div class="d-flex tree_form_btn"><span class="tree_add" data-pid="${_this.op.root}">新增</span></div>`)
        }
    }
    tree_form_add(pid) {
        let _this = this
        _this.op.type='add'
        $(_this.op.tree_form).show();
        if(pid){
            for(let i in _this.op.listById[pid]) {
                $(_this.op.tree_form + ' input[name="' + i + '"]').val('');
                $(_this.op.tree_form + ' select[name="' + i + '"]').val(1);
                $(_this.op.tree_form + ' textarea[name="' + i + '"]').val('');
            }
            $(_this.op.tree_form+' input[type="number"]').val(0);
            $(_this.op.tree_form+' input[name="pid"]').val(pid);
        }else{
            $(_this.op.tree_form+' form')[0].reset();
            $(_this.op.tree_form+' input[name="pid"]').val(0);
        }
    }
    tree_form_edit(id) {
        let _this = this
        _this.op.type='edit'
        $(_this.op.tree_form).show();
        if(id){
            for(let i in _this.op.listById[id]){
                $(_this.op.tree_form+' input[name="'+i+'"]').val(_this.op.listById[id][i]);
                $(_this.op.tree_form+' select[name="'+i+'"]').val(_this.op.listById[id][i]);
                $(_this.op.tree_form+' textarea[name="'+i+'"]').val(_this.op.listById[id][i]);
            }
        }
    }
    tree_save() {
        let _this = this
        let url = '';
        if(_this.op.type==='edit'){
            url = _this.op.tree_save_url+'/edit?id='+_this.op.select[0].data.id
        }else{
            url = _this.op.tree_save_url+'/add'
        }
        let btn_html = $(_this.op.tree_form+' button[type="submit"]').html();
        $.ajax({
            url,
            dataType:'json',
            type:'post',
            data:$(_this.op.tree_form+' form').serialize(),
            beforeSend:function () {
                $(_this.op.tree_form+' button[type="submit"]').attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
            },
            success:function (res) {
                if(!res.code) {
                    alert_msg(res)
                    $("#iload").load(res.data.redirect);
                }else if(res.code===11000){
                    form_err_11000(res,_this.op.tree_form);
                }else{
                    alert_msg(res)
                }
            },
            complete:function(XMLHttpRequest,textStatus){
                $(_this.op.tree_form+' button[type="submit"]').removeAttr('disabled').html(btn_html);
            }
        })
    }
    tree_del(id) {
        let _this = this
        if(confirm('确认删除吗') && id){
            $.ajax({
                url:_this.op.tree_del_url,
                dataType:'json',
                type:'post',
                data:{'delete[]':id,'_token':_this.op._token},
                success:function (res) {
                    alert_msg(res)
                    if(!res.code) {
                        $("#iload").load(_this.op.tree_del_url_return);
                    }
                }
            })
        }
    }
    treeFormat(data,select_ids=false){
        let new_array = [];
        data.forEach((item) => {
            let parent = this.op.root===item.pid?'#':item.pid;
            if(select_ids){
                let selected = in_array(item.id,select_ids)?true:false;
                new_array.push({id:item.id,text:item.name,parent,data:{type:item.type,pid:item.pid,id:item.id},state:{selected}})
            }else{
                new_array.push({id:item.id,text:item.name,parent,data:{type:item.type,pid:item.pid,id:item.id}})
            }
            delete item.nodes;
        });
        return new_array;
    }
    getSelectIds(data){
        let i, j, r = [];
        for(i = 0, j = data.selected.length; i < j; i++) {
            r.push(data.instance.get_node(data.selected[i]).id);
        }
        return r;
    }
    getSelectObj(data){
        let i, j, r = [];
        for(i = 0, j = data.selected.length; i < j; i++) {
            r.push(data.instance.get_node(data.selected[i]));
        }
        return r;
    }
}

function attr_addDiv() {
    let id = randomStr(8);
    let html = `<li class="d-flex" data-id="${id}">
                        <div class="attr1"><input type="text" name="json[${id}][name]"></div>
                        <div class="attr2"><input type="text" name="json[${id}][value]"></div>
                        <div class="attr3"><input type="text" name="json[${id}][img]"></div>
                        <div class="attr4"><input type="number" name="json[${id}][sort]"></div>
                        <div class="attr5" onclick="attr_delDiv(this)"><i class="uni app-lajitong"></i></div>
                    </li>`;
    $('.add_div ul').append(html);
}
function attr_delDiv(_this) {
    $(_this).parent().remove()
}
