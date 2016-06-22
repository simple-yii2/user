<?php

use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl([
	'/user/reset/password',
	'token' => $user->passwordResetToken,
]);

echo Yii::t('user', "<p>Hello!</p>\n\n<p>You have sent a request for password recovery from account {email}. To set a new password, click the link:</p>\n\n<p>{link}</p>\n\n<p>Please disregard this letter if it fell to you by mistake.</p>\n\n<p>Yours faithfully,<br>\nSupport service «{name}»</p>", [
	'name' => Yii::$app->name,
	'email' => $user->email,
	'link' => Html::a(Html::encode($link), $link),
]);
