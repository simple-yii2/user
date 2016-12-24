<?php

use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl([
	'/users/register/confirm',
	'token' => $user->confirmToken,
]);

echo Yii::t('users', "<p>Hello!</p>\n\n<p>You have been successfully registered on the site «{name}». To confirm your e-mail click the link:</p>\n\n<p>{link}</p>\n\n<p>Please disregard this letter if it fell to you by mistake.</p>\n\n<p>Yours faithfully,<br>\nSupport service «{name}»</p>", [
	'name' => Yii::$app->name,
	'link' => Html::a(Html::encode($link), $link),
]);
