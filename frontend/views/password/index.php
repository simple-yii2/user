<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = Yii::t('user', 'Change password');

$this->title = $title . ' | ' . Yii::$app->name;

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
		<?= $form->field($model, 'oldPassword')->passwordInput() ?>
		<?= $form->field($model, 'password')->passwordInput() ?>
		<?= $form->field($model, 'confirm')->passwordInput() ?>
	</fieldset>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
