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
           Виберите версию Prestashop и Опенкарта
        </p>

        <hr>
    </div>

    <div class="body-content">

        <div class="row">
         <?php   $form = ActiveForm::begin(); ?>
            <div class="col-sm-6 text-center">
                <h2 class="text-center">Prestashop</h2>

                <div class="wrapdrop">
                    <?php
                    echo $form->field($prerstatver, 'name')->dropDownList($prestaver_list,array_merge($prestaver_list_param,['class'=>'form-control']));
                    ?>
                </div>

            </div>
            <div class="col-sm-6 text-center">
                <h2 class="text-center">Opencart</h2>
                <div class="wrapdrop">
                    <?php
                    echo $form->field($opencartver, 'name')->dropDownList($opencartver_list,array_merge($opencartver_list_param,['class'=>'form-control']));
                    ?>
                </div>
            </div>
            <div class="col-md-12 right">
                <?=  Html::submitButton('Продолжить', ['class' => 'btn btn-success waves-effect waves-light m-r-10 pull-right']); ?>
            </div>
         <?php   ActiveForm::end(); ?>


            <div class="col-sm-12 text-center">
                <p>Настройки импорта</p>
                <img class="img-responsive" src="/images/example_presta_import.png" alt="example_presta_import">

            </div>


        </div>

    </div>
</div>
