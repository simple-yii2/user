<?php

use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use cms\user\frontend\assets\AuthChoiceAsset;

AuthChoiceAsset::register($this);

$title = Yii::t('user', 'Login');

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<?php if (Yii::$app->has('authClientCollection')): ?>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= AuthChoice::widget([
				'baseAuthUrl' => ['auth/index'],
				'popupMode' => false,
			]) ?>
		</div>
	</div>
	<?php endif; ?>

	<?= $form->field($model, 'email')->textInput() ?>

	<?= $form->field($model, 'password')->passwordInput() ?>

	<?= $form->field($model, 'rememberMe')->checkbox() ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::a(Yii::t('user', 'Forgot your password?'), ['reset/request']) ?>
		</div>
	</div>

<?php ActiveForm::end() ?>
