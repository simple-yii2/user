<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$title = Yii::t('users', 'Password reset');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<p><?= Yii::t('users', 'Enter your e-mail. On it will be sent a link to reset your password.') ?></p>
		</div>
	</div>

	<fieldset>
		<?= $form->field($model, 'email') ?>
		<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
			'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
			'captchaAction' => ['/site/captcha'],
			'template' => '<div class="captcha">{image}<div>{input}</div></div>',
		]) ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('users', 'Send'), ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Yii::t('users', 'You know your password?') ?> <?= Html::a(Yii::t('users', 'Login'), ['/users/login/index']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
