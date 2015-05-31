<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\phonebook\models\PhoneForm */

$this->title = $model->first_name . ' '.$model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Контакти', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contacts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        /*'modelsPhones' => $modelsPhones,
        'modelPhoneType' => $modelPhoneType,*/
    ]) ?>

</div>
