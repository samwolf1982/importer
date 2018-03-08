<?php

namespace frontend\controllers;

use app\models\LoadCsv;
use app\models\LoadCsvPresta;
use app\models\Opencartver;
use app\models\Prestaver;
use app\models\UploadForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class PrestatoopencartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $opencartver=new  Opencartver();
        $prerstatver=new  Prestaver();


        if (Yii::$app->request->post()) {
            return $this->redirect(['prestatoopencart/load']);
        } else {
//            return $this->render('contact', [
//                'model' => $model,
//            ]);
        }
        $opencartver_list =   ArrayHelper::map(Opencartver::find()->all(), 'id', 'name');
        $prestaver_list =   ArrayHelper::map(Prestaver::find()->all(), 'id', 'name');
        $opencartver_list_param=['prompt' => 'Виберите версию Опенкарт...'];
        $prestaver_list_param=['prompt' => 'Виберите версию Prestashop...'];
        return $this->render('index',compact('opencartver','opencartver_list','opencartver_list_param',
                                                            'prerstatver','prestaver_list','prestaver_list_param'));
    }


    public function actionGenerate()
    {
        $session = Yii::$app->session;
        $filename= $session->get('filenamecsv');
        $time = date('H:i:s');
        $url_csv='';
        $url_prod_csv='';
        if ( Yii::$app->request->isPjax) {

            $loadcsv=new LoadCsvPresta();
            $loadcsv->load($filename,true);
            $url_csv='/'.   $loadcsv->generate_category_presta();

            $url_prod_csv='/'.   $loadcsv->generate_product_presta();

            $url_attr_csv='/'.    $loadcsv->generate_atribute_presta();
            $url_img_csv='/'.    $loadcsv->generate_addimage_presta();

        }
        return $this->render('generate', compact('time','url_csv','url_prod_csv','url_attr_csv','url_img_csv' ));
    }

    public function actionLoad()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $filename='uploads/' . $model->file->baseName . '.' . $model->file->extension;
                $session = Yii::$app->session;
                $session->set('filenamecsv',$filename);
                $model->file->saveAs($filename);
                return $this->redirect(['prestatoopencart/generate']);
            }
        }

        return $this->render('upload', compact('model' ));
    }


}
