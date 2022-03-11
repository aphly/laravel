function checkAll(_this) {
    $("input[type='checkbox']").prop("checked", $(_this).prop('checked'));
}

function alert_msg(res,time=2000){
    $("#alert_msg").remove();
    let html = '<div id="alert_msg"><div class="alert_msg"><div class="alert_msg_header"><strong class="mr-auto">提示</strong><small></small><span onclick="$(\'#alert_msg\').remove();">×</span></div><div class="alert_msg_body">'+res.msg+'</div></div></div>';
    let body = $('body');
    body.append(html);
    _autosize($('#alert_msg'))
    setTimeout('$("#alert_msg").remove()',time);
}

function _autosize(ele){
    if(ele.height() <= $(window).height()) {
        ele.css("top", ($(window).height() - ele.height()) / 2-20);
    }
    if(ele.width() <= $(window).width()) {
        ele.css("left", ($(window).width() - ele.width()) / 2-40);
    }
}

var urlOption={
    '_set':function (name,val,jump=false) {
        let thisURL = String(document.location);
        if(thisURL.indexOf(name+'=') > 0){
            let v = this._get(name);
            if(v != null) {
                thisURL = thisURL.replace(name + '=' + v, name + '=' + val);
            }else{
                thisURL = thisURL.replace(name + '=', name + '=' + val);
            }
        }else{
            if(thisURL.indexOf("?") > 0){
                thisURL = thisURL + "&" + name + "=" + val;
            }else {
                thisURL = thisURL + "?" + name + "=" + val;
            }
        }
        if(jump){
            location.href = thisURL;
        }else{
            return thisURL;
        }
    },
    '_get':function (name) {
        let reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        let r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    },
    '_del':function (name) {
        let thisURL = String(document.location);
        if (thisURL.indexOf(name+'=') > 0){
            let arr_url = thisURL.split('?');
            let base = arr_url[0];
            let arr_param = arr_url[1].split('&');
            let index = -1;
            for (let i = 0; i < arr_param.length; i++) {
                let paired = arr_param[i].split('=');
                if (paired[0] === name) {
                    index = i;
                    break;
                }
            }
            if (index === -1) {
                return thisURL;
            }else{
                arr_param.splice(index, 1);
                if(arr_param.length){
                    return base + "?" + arr_param.join('&');
                }else{
                    return base ;
                }
            }
        }else{
            return thisURL;
        }
    }
}

function toTree(data) {
    let result = []
    if(!Array.isArray(data)) {
        return result
    }
    data.forEach(item => {
        delete item.nodes;
    });
    let map = {};
    data.forEach(item => {
        map[item.id] = item;
    });
    data.forEach(item => {
        let parent = map[item.pid];
        if(parent) {
            (parent.nodes || (parent.nodes = [])).push(item);
        } else {
            result.push(item);
        }
    });
    return result;
}

function treeData(data,select_ids=0) {
    let new_array = []
    data.forEach((item,index) => {
        let selectable = item.is_leaf?true:false;
        if(select_ids){
            let selected=in_array(item.id,select_ids)?true:false;
            new_array.push({id:item.id,text:item.name,pid:item.pid,state:{selected},selectable})
        }else{
            new_array.push({id:item.id,text:item.name,pid:item.pid,selectable})
        }
        delete item.nodes;
    });
    return new_array;
}

function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
