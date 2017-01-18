<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$title = Yii::t('user', 'Password reset');

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<p><?= Yii::t('user', 'Enter your e-mail. On it will be sent a link to reset your password.') ?></p>
		</div>
	</div>

	<fieldset>
		<?= $form->field($model, 'email') ?>
		<?= $form->field($model, 'verificationCode')->widget(Captcha::className(), [
			'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
			'captchaAction' => ['/site/captcha'],
			'template' => '<div class="captcha">{image}<div>{input}</div></div>',
		]) ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Yii::t('user', 'You know your password?') ?> <?= Html::a(Yii::t('user', 'Login'), ['/user/login/index']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
