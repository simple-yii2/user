<?php

use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use dkhlystov\uploadimage\widgets\UploadImage;
use cms\user\frontend\assets\AuthChoiceAsset;

AuthChoiceAsset::register($this);

$title = Yii::t('user', 'Settings');

$this->title = $title . ' | ' . Yii::$app->name;

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

	<?php if (!empty($model->email)) echo $form->field($model, 'email', ['inputTemplate' => $template])->textInput(['disabled' => true]); ?>

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
			<?php $authChoice = AuthChoice::begin([
				'baseAuthUrl' => ['auth/index'],
				'popupMode' => false,
			]); ?>
			<ul class="auth-clients">
				<?php
				foreach ($authChoice->getClients() as $client) {
					$name = $client->getName();
					$isLinked = in_array($name, $authItems);

					$options = ['class' => 'auth-icon ' . $name];
					if ($isLinked)
						Html::addCssClass($options, 'linked');

					$text = Html::tag('span', '', $options);
					
					$clientLink = $authChoice->clientLink($client, $text);

					echo Html::tag('li', $isLinked ? $text : $clientLink);
				}
				?>
			</ul>
			<?php AuthChoice::end(); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if (!empty($model->email)) echo $form->field($model, 'mailing')->checkbox(); ?>

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
