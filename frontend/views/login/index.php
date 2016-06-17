<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$title = $this->name = Yii::t('user', 'Login');

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout'=>'horizontal',
	'enableClientValidation'=>false,
]); ?>

	<fieldset>
		<?= $form->field($model, 'email')->textInput() ?>
		<?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'rememberMe')->checkbox() ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Login'), ['class'=>'btn btn-primary']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::a(Yii::t('user', 'Forgot your password?'), ['reset/request']) ?>
		</div>
	</div>

<?php ActiveForm::end() ?>
