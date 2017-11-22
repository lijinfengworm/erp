<?php
//ini_set('memory_limit', '-1'); // 如果csv比较大的话，可以添加。  
/* 
 *  $file : csv file 
 *  $csvDataArr : header of csv table, eg: arary('name','sex','age') or array(0,1,2) 
 *  $specialhtml : whether do you want to convert special characters to html entities ? 
 *  $removechar : which type do you want to remove special characters in array keys, manual or automatical ? 
 */
class csv_to_array
{
    private $counter;
    private $handler;
    private $length;
    private $file;
    private $seprator;
    private $specialhtml;
    private $removechar = 'manual';
    private $csvDataArr;
    private $csvData = array();

    function __construct($file = '', $csvDataArr = '', $specialhtml = true, $length = 1000, $seprator = ',')
    {
        $this->counter = 0;
        $this->length = $length;
        $this->file = $file;
        $this->seprator =  $seprator;
        $this->specialhtml =  $specialhtml;
        $this->csvDataArr = is_array($csvDataArr) ? $csvDataArr : array();
        $this->handler = fopen($this->file, "r");
    }

    function get_array()
    {
        $getCsvArr = array();
        $csvDataArr = array();
        while(($data = fgetcsv($this->handler, $this->length, $this->seprator)) != FALSE)
        {
            $num = count($data);
            $getCsvArr[$this->counter] = $data;
            $this->counter++;
        }
        if(count($getCsvArr) > 0)
        {
            $csvDataArr = array_shift($getCsvArr);
            if($this->csvDataArr) $csvDataArr = $this->csvDataArr;

            $counter = 0;
            foreach($getCsvArr as $csvValue)
            {
                $totalRec = count($csvValue);
                for($i = 0; $i < $totalRec ; $i++)
                {
                    $key = $this->csvDataArr ? $csvDataArr[$i] : $this->remove_char($csvDataArr[$i]);
                    if($csvValue[$i]) $this->csvData[$counter][$key] = $this->put_special_char($csvValue[$i]);
                }
                $counter++;
            }
        }
        return $this->csvData;
    }

    function put_special_char($value)
    {
        return $this->specialhtml ? str_replace(array('&','" ','\'','<','>'),array('&amp;','&quot;','&#039;','&lt;','&gt;'),$value) : $value;
    }

    function remove_char($value)
    {
        $result = $this->removechar == 'manual' ? $this->remove_char_manual($value) : $this->remove_char_auto($value);
        return str_replace(' ','_',trim($result));
    }

    private function remove_char_manual($value)
    {
        return str_replace(array('&','"','\'','<','>','(',')','%'),'',trim($value));
    }

    private function remove_char_auto($str,$x=0)
    {
        $x==0 ? $str=$this->make_semiangle($str) : '' ;
        eregi('[[:punct:]]',$str,$arr);
        $str = str_replace($arr[0],'',$str);

        return eregi('[[:punct:]]',$str) ? $this->remove_char_auto($str,1) : $str;
    }

    private function make_semiangle($str)
    {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ','＄'=>'$','＠'=>'@','＃'=>'#','＾'=>'^','＆'=>'&','＊'=>'*');

        return strtr($str, $arr);
    }

    function __destruct(){
        fclose($this->handler);
    }
}  