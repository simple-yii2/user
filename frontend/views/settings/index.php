<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = Yii::t('users', 'Settings');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

if ($model->confirmed) $template = '{input}';
else $template = '<div class="input-group">{input}<span class="input-group-btn">'.Html::a(Yii::t('users', 'Confirm e-mail'), ['confirm'], ['class' => 'btn btn-default']).'</span></div>';

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<fieldset>
		<?= $form->field($model, 'email', ['inputTemplate' => $template])->textInput(['disabled' => true]) ?>
		<?= $form->field($model, 'firstName') ?>
		<?= $form->field($model, 'lastName') ?>
		<?= $form->field($model, 'mailing')->checkbox() ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::a(Yii::t('users', 'Change password'), ['password/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('users', 'Save'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
