<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\sortinput\SortableInput;

/* @var $this yii\web\View */
/* @var $model frontend\modules\phonebook\models\PhoneForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contacts-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> Телефони</h4></div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.sortable', // required: css class selector
                'widgetItem' => '.sortable-item', // required: css class
                'limit' => 50, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $model->modelsPhones[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'phone',
                    'id_type'
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->

                <?= SortableInput::widget([
                    'name' => 'phone-order',
                    'items' => $model->getPhoneElements($form,$model),
                    'hideInput' => true,
                    'sortableOptions' => [
                        'connected' => true,
                    ],
                    'options' => ['class' => 'form-control', 'readonly' => true]
                ]) ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->contact->isNewRecord ? 'Запиши' : 'Запиши', ['class' => $model->contact->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
            if(!$model->contact->isNewRecord) {
                echo Html::a('Изтрий','#', [
                    'title' => 'Изтрий',
                    'onclick'=>"
                        var del = confirm('Наистина ли искате да изтриете записа?');
                        if(del) {
                            $.ajax({
                                type     :'POST',
                                cache    : false,
                                url  : '".Url::to(['contacts/delete','id'=>$model->contact->id])."',
                            });
                        }

                    return false;",
                    'class'=>'btn btn-danger'
                ]);

            }
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
