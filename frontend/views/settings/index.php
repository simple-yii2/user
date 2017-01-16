<?php

use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use dkhlystov\uploadimage\widgets\UploadImage;
use cms\user\frontend\assets\SocialAsset;

SocialAsset::register($this);

$title = Yii::t('user', 'Settings');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

if ($model->confirmed) $template = '{input}';
else $template = '<div class="input-group">{input}<span class="input-group-btn">'.Html::a(Yii::t('user', 'Confirm e-mail'), ['confirm'], ['class' => 'btn btn-default']).'</span></div>';

$authItems = array_map(function($v) {
	return $v->source;
}, $model->getObject()->auth);

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<?= $form->field($model, 'email', ['inputTemplate' => $template])->textInput(['disabled' => true]) ?>

	<?= $form->field($model, 'firstName') ?>

	<?= $form->field($model, 'lastName') ?>

	<?= $form->field($model, 'image')->widget(UploadImage::className(), [
		'thumbAttribute' => 'thumb',
		'width' => 100,
		'height' => 100,
		'showPreview' => false,
	]) ?>

	<?php if (Yii::$app->has('authClientCollection')): ?>
	<div class="form-group">
		<label class="control-label col-sm-3"><?= Yii::t('user', 'Social networks') ?></label>
		<div class="col-sm-6">
			<?php 
				$authChoice = AuthChoice::begin([
					'baseAuthUrl' => ['auth/index'],
					'popupMode' => false,
				]);

				$clients = [];
				foreach ($authChoice->getClients() as $client) {
					if (!in_array($client->getId(), $authItems))
						$clients[] = $client;
				}
				$authChoice->setClients($clients);

				AuthChoice::end();
			?>
		</div>
	</div>
	<?php endif; ?>

	<?= $form->field($model, 'mailing')->checkbox() ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::a(Yii::t('user', 'Change password'), ['password/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
