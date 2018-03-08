<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class LoadCsvPresta extends Model
{

      public $file;
      private $main_arr;
      private $main_arr_category_list; // cписок категорий
      private  $prod_id_to_colection; // [ ['p_id'=[3]]  ]
      private  $prod_arr_list; //
      private  $atr_list; //  список атрибутов
      private  $add_img_list; //  список add images


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
    public function getMain_arr()
    {
             return $this->main_arr;
    }

    public function load($file_path,$clear_header_name=false)
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

    /**
     * генераторо категорий здесь одноуровневая вложеность  // корень-главная - категория
     * для следующих сделать мгогоуровенвое вложение категорий.
     */
    public  function generate_category_presta(){
        $tmp_cat_array=[];
        $cat_counter_id=1;
        foreach ($this->main_arr as $item) {
            $cats_list = explode("||", $item[52]);
            foreach ($cats_list as $c_l) {
                $cat_list= explode("|", $c_l);
                 $cat_name=array_pop($cat_list);
                 if ($cat_name!='Корень' && $cat_name!='Главная'){
                     $gate=true;
                     foreach ($tmp_cat_array as $tmp_cat){
                          if ($tmp_cat['name']==$cat_name){
                              $gate=false;
                              break;
                          }
                     }
                     //---------
                     if ($gate){
                         $tmp_cat_array[]=['name'=>$cat_name,'parent_id'=>0,'id'=>$cat_counter_id++,'seo'=>$this->category_seo_presta($cat_name)];
                     }

                 }

              //  yii::error( $cat_list);
                unset($cat_list);
            }
            unset($cats_list);

            foreach ($item as $key=> $el) {
                  $t[]=['k'=>$key,'val'=>$el];
            }
            break;

        }
      //  yii::error( $t);       //значение ключ
        $this->main_arr_category_list=$tmp_cat_array;
        return $this->create_category_csv_presta();

//        yii::error( $this->main_arr_category_list)  ;

    }
    /**
     * @param $file_name     имя  создаваемого файла с путем  files/dest/category.csv
     *
     */
    private function create_category_csv_presta($file_name='files/dest/category.csv'){
//        category_id	parent_id	name(ru-ru)	top	columns	sort_order	image_name	date_added	date_modified	seo_keyword	description(ru-ru)	meta_title(ru-ru)	meta_description(ru-ru)	meta_keywords(ru-ru)	store_ids	layout	status

        $list_atr_group=[];
        $list_atr_group[]=['category_id','parent_id','name(ru-ru)','top','columns','sort_order','image_name','date_added','date_modified','seo_keyword','description(ru-ru)','meta_title(ru-ru)','meta_description(ru-ru)','meta_keywords(ru-ru)','store_ids','layout','status'];
        $i=0;
        foreach ($this->main_arr_category_list as $v){
            $list_atr_group[]=[ $v['id'],$v['parent_id'],$v['name'],'true',1,0,'',// image_name
                '2009-01-03 21:08:57','2011-05-30 12:15:11',$v['seo'], //seo_keyword
                $v['name'],$v['name'],$v['name'],$v['name'],0,'','true'];
        }
        $fp = fopen($file_name, 'w');
        foreach ($list_atr_group as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return $file_name;

    }

    private  function  category_seo_presta($name){
        $arr=['Одежда '=>'odezhda','Обувь'=>'obuv-brand','Аксессуары'=>'aksessuary','Верхняя Одежда'=>'verkhnyaya-odezhda','Сумки'=>'sumki','Украшения'=>'ukrasheniya','Мужское'=>'muzhskoe'];
        return $arr[$name];
    }




    public  function generate_product_presta(){
        $tmp_prod_array=[];
        foreach ($this->main_arr as $item) {
            $t_prod=[];
            $t_prod['id']=$item[0];$t_prod['name']=$item[47]; // id name

            $cats_list = explode("||", $item[52]);
            $cat_id_colect=[];
            foreach ($cats_list as $c_l) {
                $cat_list= explode("|", $c_l);
//                yii::error($cat_list);
                $cat_name=array_pop($cat_list);
                if ($cat_name =='Корень' || $cat_name=='Главная'){
                    $cat_id_colect[]=0;
                }else{
                    foreach ($this->main_arr_category_list as $cl) {
                        if ($cl['name']==$cat_name){
                            $cat_id_colect[]= $cl['id'];
                        }  //  $tmp_cat_array[]=['name'=>$cat_name,'parent_id'=>0,'id'=>$cat_counter_id++,'seo'=>$this->category_seo_presta($cat_name)];
                    }

                }
                unset($cat_list);
            }
            unset($cats_list);
//                            yii::error($cat_id_colect);
//            ----------------------------
//            yii::error($cat_id_colect);
            $cat_id_colect_string=implode(',',$cat_id_colect);
            $t_prod['cat']= $cat_id_colect_string;
            unset($cat_id_colect);
//            -----------
            $t_prod['manuf']= $item[54];

            $img_list = explode(";", $item[56]);
            $img_path='';
            if(isset($img_list[0])){
                $img_path=str_replace('http://brand-fashion.com.ua/','catalog/images/',$img_list[0]);
            }
            $t_prod['img']= $img_path;
            $t_prod['price']= $item[8];
            $t_prod['seo']= $item[43];
            $t_prod['des']= $item[41];
            $t_prod['meta_title']= $item[46];
            $t_prod['meta_desc']= $item[44];
            $t_prod['meta_key']= $item[46];
//            $t_prod['meta_key']= $item[46];
            $t_prod['tags']= $item[50];

            $tmp_prod_array[]= $t_prod;
           // yii::error($tmp_prod_array);
            unset($t_prod);
        } // end $this->main_arr forach


       // yii::error($tmp_prod_array);
        $this->prod_arr_list=$tmp_prod_array;
       // $this->main_arr_category_list=$tmp_cat_array;
        return $this->create_product_csv_presta();

//        yii::error( $this->main_arr_category_list)  ;

    }

    private function create_product_csv_presta($file_name='files/dest/product.csv'){
        //   product_id	name(ru-ru)	categories	sku	upc	ean	jan	isbn	mpn	location
        //	quantity	model	manufacturer	image_name	shipping	price	points	date_added	date_modified	date_available
        //	weight	weight_unit	length	width	height	length_unit	status	tax_class_id	seo_keyword
        //  description(ru-ru)	meta_title(ru-ru)	meta_description(ru-ru)	meta_keywords(ru-ru)	stock_status_id	store_ids	layout	related_ids	tags(ru-ru)	sort_order	subtract	minimum

        $list_atr_prod=[];
    // $list_atr_prod[]=['product_id','name(ru-ru)','categories','sku','upc','ean','jan','isbn','mpn','location','quantity','model','manufacturer','image_name','shipping','price','points','date_added','date_modified','date_available','weight','weight_unit','length','width','height','length_unit','status','tax_class_id','seo_keyword','description(ru-ru)','meta_title(ru-ru)','meta_description(ru-ru)','meta_keywords(ru-ru)','stock_status_id','store_ids','layout','related_ids','tags(ru-ru)','sort_order','subtract','minimum'];

        foreach ($this->prod_arr_list as $v){
            $list_atr_prod[]=[ $v['id'],$v['name'],$v['cat'],'','','','','','','',// location
                  100,$v['id'],$v['manuf'],$v['img'],'yes',$v['price'],'0','2009-02-03 16:59:00','2009-02-03 16:59:00','2009-02-03', //date_available
                   '0','кг','0','0','0','см','true','9',$v['seo'],//seo_keyword
               $v['des'],$v['meta_title'],$v['meta_desc'],$v['meta_key'],6,0,'','',$v['tags'],0,'true',1];
        }
        $fp = fopen($file_name, 'w');
        foreach ($list_atr_prod as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return $file_name;

    }


    /**
     * генераторо атрибутов
     * @return string
     */
    public  function generate_atribute_presta(){
        $tmp_atr_array=[];
        $list_name_atr=[77=>'Пол',78=>'Размер Обуви',79=>'Размер Одежды',80=>'Состояние',81=>'Тип Аксессуара',82=>'Тип Верхней Одежды',83=>'Тип Обуви',84=>'Тип Одежды',85=>'Тип Сумки',86=>'Тип Украшений'];



        foreach ($this->main_arr as $item) {
            $l_temp=[];
            for($i=77;$i<=86;$i++){
                if(!empty($item[$i])){
                    $l_temp[]= ['name_attr'=>$list_name_atr[$i],'val' =>$item[$i]];
                }
            }
            if (!empty($l_temp)){
                $tmp_atr_array[]=['id'=>$item[0],'attr'=>$l_temp];
            }
            unset($l_temp);

        } // end $this->main_arr forach

        // yii::error($tmp_prod_array);
        $this->atr_list=$tmp_atr_array;
          return $this->create_atribute_csv_presta();

    }

    /**
     * генератор атрибутов сейчас все в одну групу
     * @param string $file_name
     * @return string   file name
     */
    private function create_atribute_csv_presta($file_name='files/dest/atribute.csv'){
 // product_id	attribute_group	attribute	text(ru-ru)
        $list_atr_prod=[];
        foreach ($this->atr_list as $v){
            foreach ($v['attr'] as $a) {
                $list_atr_prod[]=[$v['id'],'Дополнительно',$a['name_attr'],$a['val']];
            }

        }
        $fp = fopen($file_name, 'w');
        foreach ($list_atr_prod as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return $file_name;

    }




    /**
     * генераторо картинок
     * @return string
     */
    public  function generate_addimage_presta(){
        $tmp_img_array=[];
        foreach ($this->main_arr as $item) {
            $img_list = explode(";", $item[56]);
            $img_paths=[];
            if (count($img_list)>1){
                array_shift($img_list);
                foreach ($img_list as $im) {
                    $img_paths[]=str_replace('http://brand-fashion.com.ua/','catalog/images/',$im);
                }
                $tmp_img_array[]=['id'=>$item[0],'imglist'=>$img_paths];
            }


            unset($img_list);
            unset($img_paths);

        } // end $this->main_arr forach

        // yii::error($tmp_prod_array);
        $this->add_img_list=$tmp_img_array;
        return $this->create_addimage_csv_presta();

    }

    /**
     * генератор атрибутов сейчас все в одну групу
     * @param string $file_name
     * @return string   file name
     */
    private function create_addimage_csv_presta($file_name='files/dest/addimages.csv'){
        // product_id	image	sort_order
        $list_elements=[];
        foreach ( $this->add_img_list as $v){
            foreach ($v['imglist'] as $a) {
                $list_elements[]=[$v['id'],$a,0];
            }

        }
        $fp = fopen($file_name, 'w');
        foreach ($list_elements as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        return $file_name;

    }



}