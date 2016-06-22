<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$title = $this->name = Yii::t('user', 'Password reset');
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
			<p><?= Yii::t('user', 'Set a new password for your account.') ?></p>
		</div>
	</div>

	<fieldset>
		<?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'confirm')->passwordInput() ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
