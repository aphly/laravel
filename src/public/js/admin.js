function toMyTree(data,id=0) {
    let new_array = []
    data.forEach((item,index) => {
        if(item.id===id){
            new_array.push({id:item.id,text:item.name,pid:item.pid,state:{selected:true}})
        }else{
            new_array.push({id:item.id,text:item.name,pid:item.pid})
        }
        delete item.nodes;
    });
    return new_array;
}
function selectData(data,select_ids=false) {
    let new_array = []
    data.forEach((item,index) => {
        let color = item.status?'inherit':'#ddd';
        if(select_ids){
            let selected=in_array(item.id,select_ids)?true:false;
            new_array.push({id:item.id,text:item.name,pid:item.pid,state:{selected},color})
        }else{
            new_array.push({id:item.id,text:item.name,pid:item.pid,color})
        }
        delete item.nodes;
    });
    return new_array;
}

//fast_show_start
function fast_show_btn($role=false) {
    $(treeGlobal.fast_form).hide();
    if(treeGlobal.id || treeGlobal.pid){
        if(treeGlobal.listById[treeGlobal.pid].is_leaf){
            if($role){
                $('#show_btn').html(`<div class="d-flex fast_form_btn justify-content-between">
                                        <div class="d-flex">
                                            <span onclick="fast_show_make_form('edit')">编辑</span>
                                            <a class="badge badge-success ajax_get" data-href="/admin/role/${treeGlobal.id}/permission">授权</a>
                                            <a class="badge badge-success ajax_get" data-href="/admin/role/${treeGlobal.id}/menu">菜单</a>
                                        </div> <div class="fast_del" onclick="fast_del()">删除</div>
                                    </div>`)
            }else{
                $('#show_btn').html(`<div class="d-flex fast_form_btn justify-content-between"><div class="d-flex"><span onclick="fast_show_make_form('edit')">编辑</span></div> <div class="fast_del" onclick="fast_del()">删除</div></div>`)
            }
        }else{
            $('#show_btn').html(`<div class="d-flex fast_form_btn justify-content-between"><div class="d-flex"><span onclick="fast_show_make_form('add')">新增</span> <span onclick="fast_show_make_form('edit')">编辑</span></div> <div class="fast_del" onclick="fast_del()">删除</div></div>`)
        }
    }else{
        $('#show_btn').html(`<div class="d-flex fast_form_btn"><span onclick="fast_show_make_form('add')">新增</span></div>`)
    }
}
function fast_show_make_form(type) {
    $(treeGlobal.fast_form).show();
    $(treeGlobal.fast_form+' .module_div').hide()
    for (let i in treeGlobal.hide_id) {
        $(treeGlobal.hide_id[i]).show();
    }
    if(type==='add'){
        treeGlobal.id = 0;
        $(treeGlobal.fast_form+' select[name="is_leaf"]').removeAttr("disabled");
        if(treeGlobal.pid){
            for(let i in treeGlobal.listById[treeGlobal.pid]) {
                $(treeGlobal.fast_form + ' input[name="' + i + '"]').val('');
                $(treeGlobal.fast_form + ' select[name="' + i + '"]').val(1);
                $(treeGlobal.fast_form + ' textarea[name="' + i + '"]').val('');
            }
            $(treeGlobal.fast_form+' input[type="number"]').val(0);
            $(treeGlobal.fast_form+' input[name="pid"]').val(treeGlobal.listById[treeGlobal.pid]['id']);
            //$(fast_form+' .p_name').val(listById[id]['name']);
        }else{
            $(treeGlobal.fast_form+' form')[0].reset();
            $(treeGlobal.fast_form+' input[name="pid"]').val(0);
            $(treeGlobal.fast_form+' .module_div').show()
            $(treeGlobal.fast_form+' .module_div select').removeAttr("disabled");
        }
    }else{
        treeGlobal.pid = 0;
        $(treeGlobal.fast_form+' select[name="is_leaf"]').attr("disabled","disabled");
        if(treeGlobal.id){
            for(let i in treeGlobal.listById[treeGlobal.id]){
                $(treeGlobal.fast_form+' input[name="'+i+'"]').val(treeGlobal.listById[treeGlobal.id][i]);
                $(treeGlobal.fast_form+' select[name="'+i+'"]').val(treeGlobal.listById[treeGlobal.id][i]);
                $(treeGlobal.fast_form+' textarea[name="'+i+'"]').val(treeGlobal.listById[treeGlobal.id][i]);
                // if(i=='pid'){
                //     let pid = listById[id][i];
                //     $(fast_form+' .p_name').val(listById[pid]['name']);
                // }
            }
            if(!treeGlobal.listById[treeGlobal.id]['pid']){
                $(treeGlobal.fast_form+' .module_div').show()
                $(treeGlobal.fast_form+' .module_div select').attr("disabled","disabled");
            }
            if(!treeGlobal.listById[treeGlobal.id]['is_leaf']) {
                for (let i in treeGlobal.hide_id) {
                    $(treeGlobal.hide_id[i]).hide();
                }
            }
        }
    }
}
function fast_save() {
    let url = '';
    if(treeGlobal.id){
        url = treeGlobal.fast_save_url+'/edit?id='+treeGlobal.id
    }else{
        url = treeGlobal.fast_save_url+'/add'
    }
    let btn_html = $(treeGlobal.fast_form+' button[type="submit"]').html();
    $.ajax({
        url,
        dataType:'json',
        type:'post',
        data:$(treeGlobal.fast_form+' form').serialize(),
        beforeSend:function () {
            $(treeGlobal.fast_form+' button[type="submit"]').attr('disabled',true).html('<i class="btn_loading app-jiazai uni"></i>');
        },
        success:function (res) {
            if(!res.code) {
                alert_msg(res)
                $("#iload").load(res.data.redirect);
            }else if(res.code===11000){
                form_err_11000(res,treeGlobal.fast_form);
            }else{
                alert_msg(res)
            }
        },
        complete:function(XMLHttpRequest,textStatus){
            $(treeGlobal.fast_form+' button[type="submit"]').removeAttr('disabled').html(btn_html);
        }
    })
}

function fast_del() {
    if(confirm('确认删除吗') && treeGlobal.id){
        $.ajax({
            url:treeGlobal.fast_del_url,
            dataType:'json',
            type:'post',
            data:{'delete[]':treeGlobal.id,'_token':treeGlobal._token},
            success:function (res) {
                alert_msg(res)
                if(!res.code) {
                    $("#iload").load(treeGlobal.fast_del_url_return);
                }
            }
        })
    }
}
//fast_show_end

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
