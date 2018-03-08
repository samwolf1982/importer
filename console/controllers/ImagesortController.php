<?php

namespace console\controllers;

use app\models\ProductTest;
use Yii;
use yii\db\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\console\Controller;


class ImagesortController extends Controller
{

    public $from;
    public $to;
    public $id;


    public $main_arr;
    public $img_list;

    public function options()
    {
        return ['from','to','id'];
    }

    public function actionSort(){
        $this->load('files/src/exportpresta.csv',1);
        $tmp_img_array=[];
        foreach ($this->main_arr as $item) {
            $img_list = explode(";", $item[56]);
            $img_paths=[];
            foreach ($img_list as $im) {
                $tmp_img_array[]=$im;
            }

            unset($img_list);
            unset($img_paths);

        } // end $this->main_arr forach

        // yii::error($tmp_prod_array);
        $this->img_list=$tmp_img_array;

        $counter=0;
        foreach ($this->img_list as $item) {
            if (($counter++) < $this->id){continue;}
            $this->copy($item,$counter);
//            if ($counter%10==0){
//                sleep(1);
//            }

        }

        echo 'end'.PHP_EOL;
    }

    private  function copy($url,$info){
        //    $img_paths[]=str_replace('http://brand-fashion.com.ua/','catalog/images/',$im);
        $pref_from='catalog';
        $pref_to='catalog/test';
        $path_dir=str_replace('http://brand-fashion.com.ua/','',$url);
        echo $path_dir.'_____'.$info.PHP_EOL;
        $p_i= pathinfo($path_dir);
        $p_i['dirname'];
        FileHelper::createDirectory($pref_to.'/'.$p_i['dirname']);
        usleep(50000);
        $from=$pref_from.'/'.$p_i['dirname'].'/'.$p_i['basename'];
        $to=$pref_to.'/'.$p_i['dirname'].'/'.$p_i['basename'];
//        echo $from.PHP_EOL;
//        echo $to.PHP_EOL;
        try{
            if (!copy($from, $to)) {
                echo "не удалось скопировать $from...\n";
            }
           // copy($from,$to);
            //$file   = file($url);
          //  $result = file_put_contents( $pref.'/'.$p_i['dirname'].'/'.$p_i['basename'],$file);
        }catch (yii\base\ErrorException $e){
            // write errorlist
            $fp = fopen('files/dest/errrurls.csv', 'a');
            fputcsv($fp, [$url]);
            fclose($fp);
        }

    }

    public function actionWriteimg()
    {


        $this->load('files/src/exportpresta.csv',1);
        $tmp_img_array=[];
        foreach ($this->main_arr as $item) {
            $img_list = explode(";", $item[56]);
            $img_paths=[];
                foreach ($img_list as $im) {
                    $tmp_img_array[]=$im;
                }

            unset($img_list);
            unset($img_paths);

        } // end $this->main_arr forach

        // yii::error($tmp_prod_array);
        $this->img_list=$tmp_img_array;



        $f=$this->from; // 3000
        $fe=$this->to; //4000;
        $cc=count($this->img_list);
        $error_filelist=[];
        foreach ( $this->img_list as $item) {

         $f++;
         if ($f>=0 && $f<$fe ){
             $this->write( trim($item) ,  $f  ." from ".$cc);
         }
//            echo $item[1].PHP_EOL;




//            break;
        }


        http://brand-fashion.com.ua/img/p/8/6/5/8/8658.jpg;
        //http://brand-fashion.com.ua/img/p/8/6/5/9/8659.jpg;http://brand-fashion.com.ua/img/p/8/6/6/0/8660.jpg;http://brand-fashion.com.ua/img/p/8/6/6/1/8661.jpg




    }

private function  write($url,$info){
//    $img_paths[]=str_replace('http://brand-fashion.com.ua/','catalog/images/',$im);
        $pref='catalog/images';
    $path_dir=str_replace('http://brand-fashion.com.ua/','',$url);
    echo $path_dir.'_____'.$info.PHP_EOL;
    $p_i= pathinfo($path_dir);
    $p_i['dirname'];
    FileHelper::createDirectory($pref.'/'.$p_i['dirname']);
    try{
        $file   = file($url);
        $result = file_put_contents( $pref.'/'.$p_i['dirname'].'/'.$p_i['basename'],$file);
    }catch (yii\base\ErrorException $e){
        // write errorlist
        $fp = fopen('files/dest/errrurls.csv', 'a');
            fputcsv($fp, [$url]);
        fclose($fp);
    }
}

    private function load($file_path,$clear_header_name=false)
    {
        $arr=[];
        $row = 1;
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                $num = count( $data);
                //  echo " $num полей в строке $row: ".PHP_EOL;
                $row++;
                $tmp=[];
                for ($c=0; $c < $num; $c++) {
                    $tmp[]=$data[$c];
//                    echo $data[$c] . PHP_EOL;
                }
                $arr[]=$tmp;
            }
            fclose($handle);
        }
        if ($clear_header_name){
            unset($arr[0]);
        }

        $this->main_arr=$arr;
    }

//    public function options($actionID)
//    {
//        return ['pages'];
//    }
//
//    public function optionAliases()
//    {
//        return ['p' => 'pages'];
//    }

}