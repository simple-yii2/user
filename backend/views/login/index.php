<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$title = Yii::t('user', 'Login');

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<?= $form->field($model, 'email')->textInput() ?>

	<?= $form->field($model, 'password')->passwordInput() ?>

	<?= $form->field($model, 'rememberMe')->checkbox() ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end() ?>
