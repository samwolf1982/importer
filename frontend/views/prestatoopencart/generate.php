<?php

/* @var $this yii\web\View */

use frontend\assets\PrestatoopencartAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;


$this->title = 'Сервис переноса';
PrestatoopencartAsset::register($this);
?>
<div class="site-index">

    <div class="jumbotron">

        <p class="lead">
          Генератор
        </p>

        <hr>
    </div>

    <div class="body-content">
        <div class="row">

            <div class="col-sm-12 text-center">
                <h2 class="text-center">Категория</h2>


                <div class="wrapdrop">
                    <?php Pjax::begin(['id' => 'some-id', 'clientOptions' => ['method' => 'POST']]); ?>
                    <h1>Сейчас: <?= $time ?></h1>
                    <?= Html::a("Генерировать категории", ['prestatoopencart/generate'], ['class' => 'btn btn-lg btn-primary']);?>
                    <?php
                    if (!empty( $url_csv)){ ?>
                        <a href="<?=$url_csv?>" type="application/file" target="_blank" data-pjax="0" download>Скачать категории</a>
                        <br>
                        <a href="<?=$url_prod_csv?>" type="application/file" target="_blank" data-pjax="0" download>Скачать продукты</a>
                        <br>
                        <a href="<?=$url_attr_csv?>" type="application/file" target="_blank" data-pjax="0" download>Скачать атрибуты</a>

                        <br>
                        <a href="<?=$url_img_csv?>" type="application/file" target="_blank" data-pjax="0" download>Скачать дополнительные изображения</a>


                <?php }else{ ?>

                        <?php   } ?>



                    <?php Pjax::end(); ?>

                </div>
            </div>




        </div>

    </div>
</div>
