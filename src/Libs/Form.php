<?php

namespace Aphly\Laravel\Libs;

class Form
{
    static function radio($name,array $arr,$nv=false) {
        $html = '';
        foreach($arr as $k=>$v){
            if($v==$nv){
                $html.= '<div class="form-check"><label class="form-check-label" ><input checked="checked" class="form-check-input" type="radio" name="'.$name.'" value="'.$v['value'].'">'
                    .$v['name'].'</label></div>';
            }else{
                $html.= '<div class="form-check"><label class="form-check-label" ><input class="form-check-input" type="radio" name="'.$name.'" value="'.$v['value'].'">'
                    .$v['name'].'</label></div>';
            }
        }
        return $html;
    }

    static function checkbox($name,array $arr,$nv='') {
        $html = '';
        $nv = explode(',',$nv);
        foreach($arr as $k=>$v){
            if($nv && in_array($v['value'],$nv)){
                $html.= '<div class="form-check"><label class="form-check-label" ><input checked="checked" class="form-check-input" type="checkbox" name="'.$name.'[]" value="'.$v['value'].'">'
                        .$v['name'].'</label></div>';
            }else{
                $html.= '<div class="form-check"><label class="form-check-label" ><input class="form-check-input" type="checkbox" name="'.$name.'[]" value="'.$v['value'].'">'
                    .$v['name'].'</label></div>';
            }
        }
        return $html;
    }

    static function select($name,$nv,array $arr,string $id='') {
        if($id){
            $html= '<select class="form-control" name="'.$name.'" id="'.$id.'">';
        }else{
            $html= '<select class="form-control" name="'.$name.'">';
        }
        foreach($arr as $k=>$v){
            if($v==$nv){
                $html.='<option value="'.$v.'" selected = "selected">'.$k.'</option>';
            }else{
                $html.='<option value="'.$v.'">'.$k.'</option>';
            }
        }
        $html.= '</select>';
        return $html;
    }

    public static function dropDown($column,?string $value=null) {
        $dropDownList = array(
            'is_status'=> array(
                '1'=> '开启',
                '0'=> '关闭',
            ),
        );
        if ($value) {
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column][$value] : false;
        }else{
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column] : false;
        }
    }
}
