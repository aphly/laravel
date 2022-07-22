<?php
namespace Aphly\Laravel\Libs;

class Editor
{
    public $tmp_path = '/editor_temp/';
    public $dir_path = '/editor/';

    public $pattern = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.jpeg|\.png]))[\'|\"].*?[\/]?>/i';

    public function add($content){
        if(!empty($content)){
            preg_match_all($this->pattern,$content,$res);
            foreach ($res[1] as $temp_img){
                $new_img = str_replace($this->tmp_path,$this->dir_path,$temp_img);
                $temp_img_path = public_path($temp_img);
                $new_img_path = str_replace($this->tmp_path,$this->dir_path,$temp_img_path);
                if(file_exists($temp_img_path) && rename($temp_img_path, $new_img_path)){
                    $content = str_replace($temp_img,$new_img,$content);
                }
            }
        }
        return $content;
    }

    public function edit($oldContent,$newContent){
        if(!empty($oldContent)){
            if(!empty($newContent)){
                preg_match_all($this->pattern,$oldContent,$old);
                preg_match_all($this->pattern,$newContent,$new);
                $intersect = array_intersect($old[1],$new[1]);
                $del_arr = array_diff($old[1],$intersect);
                foreach ($del_arr as $val){
                    @unlink(public_path($val));
                }
                $content = $this->add($newContent);
            }else{
                $content = $oldContent;
            }
        }else{
            $content = $this->add($newContent);
        }
        return $content;
    }
}

