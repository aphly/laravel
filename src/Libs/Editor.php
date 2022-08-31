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

    function subHtml2($html,$length,$sub='...') {
        $result = '';
        $tagStack = array();
        $len = 0;
        $contents = preg_split("~(<[^>]+?>)~si",$html, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
        foreach($contents as $tag) {
            if(trim($tag)=="") continue;
            if(preg_match("~<([a-z0-9]+)[^/>]*?/>~si",$tag)){
                $result .= $tag;
            }else if(preg_match("~</([a-z0-9]+)[^/>]*?>~si",$tag,$match)){
                if($tagStack[count($tagStack)-1] == $match[1]){
                    array_pop($tagStack);
                    $result .= $tag;
                }
            }else if(preg_match("~<([a-z0-9]+)[^/>]*?>~si",$tag,$match)){
                array_push($tagStack,$match[1]);
                $result .= $tag;
            }else if(preg_match("~<!--.*?-->~si",$tag)){
                $result .= $tag;
            }else{
                if($len + $this->mstrlen($tag) < $length){
                    $result .= $tag;
                    $len += $this->mstrlen($tag);
                }else {
                    $str = $this->msubstr($tag,0,$length-$len+1);
                    $result .= $str;
                    break;
                }
            }
        }
        while(!empty($tagStack)){
            $result .= $sub.'</'.array_pop($tagStack).'>';
        }
        return $result;
    }

    function msubstr($string, $start, $length,$dot='',$charset = 'UTF-8') {
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;','&nbsp;'), array('&', '"', '<', '>',' '), $string);
        if(strlen($string) <= $length) {
            return $string;
        }
        if(strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while($n < strlen($string)) {
                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2;
                } elseif(224 <= $t && $t <= 239) {
                    $tn = 3; $n += 3;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6;
                } else {
                    $n++;
                }
                $noc++;
                if($noc >= $length) {
                    break;
                }
            }
            if($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }

        return $strcut.$dot;
    }

    function mstrlen($str,$charset = 'UTF-8'){
        if (function_exists('mb_substr')) {
            $length=mb_strlen($str,$charset);
        } elseif (function_exists('iconv_substr')) {
            $length=iconv_strlen($str,$charset);
        } else {
            preg_match_all("/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|[xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/", $str, $ar);
            $length=count($ar[0]);
        }
        return $length;
    }

    function subHtml($str,$num,$more='...')
    {
        $leng=strlen($str);
        if($num>=$leng){
            return $str;
        }
        $word=0;
        $i=0;                        /** 字符串指针 **/
        $stag=array(array());        /** 存放开始HTML的标志 **/
        $etag=array(array());        /** 存放结束HTML的标志 **/
        $sp = 0;
        $ep = 0;
        while($word!=$num) {
            if(ord($str[$i])>128){
                $i+=3;
                $word++;
            }else if($str[$i]=='<'){
                if ($str[$i+1] == '!') {
                    $i++;
                    continue;
                }
                if ($str[$i+1]=='/'){
                    $ptag=&$etag ;
                    $k=&$ep;
                    $i+=2;
                } else {
                    $ptag=&$stag;
                    $i+=1;
                    $k=&$sp;
                }
                for(;$i<$leng;$i++)  {
                    if ($str[$i] == ' ') {
                        $ptag[$k] = implode('',$ptag[$k]);
                        $k++;
                        break;
                    }
                    if ($str[$i] != '>'){
                        $ptag[$k][]=$str[$i];
                        continue;
                    }else{
                        $ptag[$k] = implode('',$ptag[$k]);
                        $k++;
                        break;
                    }
                }
                $i++;
                continue;
            } else {
                //$re.=substr($str,$i,1);
                $word++;
                $i++;
            }
        }
        foreach ($etag as $val){
            $key=array_search($val,$stag);
            if ($key !== false){unset($stag[$key]); }
        }
        foreach ($stag as $key => $val) {
            if (in_array($val,array('br','img'))){unset($stag[$key]);}
        }
        //array_reverse($stag);
        return  substr($str,0,$i).$more.'</'.implode('></',$stag).'>';
    }
}

