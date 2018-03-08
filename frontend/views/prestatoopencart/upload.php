<?php

/* @var $this yii\web\View */

use frontend\assets\PrestatoopencartAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


$this->title = 'Сервис переноса';
PrestatoopencartAsset::register($this);
?>
<div class="site-index">

    <div class="jumbotron">

        <p class="lead">
           Виберите файл импорта сsv создан из Prestashop програмой eMagicOne Store Manager for PrestaShop
        </p>

        <hr>
    </div>

    <div class="body-content">
        <div class="row">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <div class="col-sm-12 text-center">
                <h2 class="text-center">Prestashop</h2>

                <div class="wrapdrop">
                    <?= $form->field($model, 'file')->fileInput() ?>
                </div>
            </div>
            <div class="col-md-12 right">
                <?=  Html::submitButton('Загрузить файл', ['class' => 'btn btn-success waves-effect waves-light m-r-10 pull-right']); ?>
            </div>
         <?php   ActiveForm::end(); ?>
            <div class="col-sm-12 text-center">
                <p>файл Б</p>
            </div>


        </div>

    </div>
</div>
