<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$title = Yii::t('user', 'Registration');

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<fieldset>
		<?= $form->field($model, 'email') ?>
		<?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'confirm')->passwordInput() ?>
		<?= $form->field($model, 'firstName') ?>
		<?= $form->field($model, 'lastName') ?>
		<?= $form->field($model, 'verificationCode')->widget(Captcha::className(), [
			'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
			'captchaAction' => ['/site/captcha'],
			'template' => '<div class="captcha">{image}<div>{input}</div></div>',
		]) ?>
		<?= $form->field($model, 'mailing')->checkbox() ?>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-6">
				<p><?= Yii::t('user', 'By clicking "Register", you agree to the terms of use.') ?></p>
			</div>
		</div>
	</fieldset>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Register'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
