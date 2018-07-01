<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$title = $model->getUsername();

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    ['label' => Yii::t('user', 'Users'), 'url' => ['index']],
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableClientValidation' => false,
]); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'confirm')->passwordInput() ?>
    <?= $form->field($model, 'passwordChange')->checkbox() ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('user', 'Cancel'), ['index'], ['class' => 'btn btn-link']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
