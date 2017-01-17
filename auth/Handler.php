<?php

namespace cms\user\auth;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

use cms\user\common\models\User;
use cms\user\common\models\UserAuth;

/**
 * Auth handler handles successful authentication via Yii auth component
 */
class Handler
{

	/**
	 * @var ClientInterface
	 */
	private $_client;

	/**
	 * @inheritdoc
	 */
	public function __construct(ClientInterface $client)
	{
		$this->_client = $client;
	}

	/**
	 * Handling auth response
	 * @return void
	 */
	public function handle()
	{
		$client = $this->_client;

		$attributes = $client->getUserAttributes();
		$id = ArrayHelper::getValue($attributes, 'id');
		if ($id === null)
			throw new \Exception("Identifier is not defined.");

		$auth = UserAuth::find()->where([
			'source' => $client->getId(),
			'source_id' => $id,
		])->one();

		if (Yii::$app->getUser()->getIsGuest()) {
			if ($auth === null) {
				$this->register($attributes);
			} else {
				$auth->user->login();
			}
		} else {
			if ($auth === null) {
				$this->link($attributes);
			} else {
				Yii::$app->getSession()->setFlash('error', Yii::t('user', 'Unable to link {client} account. There is another user using it.', ['client' => $client->getTitle()]));
			}
		}
	}

	/**
	 * Register new user linked to auth client and login
	 * @param array $attributes 
	 * @return void
	 */
	private function register($attributes)
	{
		$client = $this->_client;
		$email = ArrayHelper::getValue($attributes, 'email');

		if ($email !== null && User::find()->where(['email' => $email])->exists()) {
			Yii::$app->getSession()->setFlash('error', Yii::t('user', 'User with the same email as in {client} account already exists but isn\'t linked to it. Login using email first to link it.', ['client' => $client->getTitle()]));
			return;
		}

		$user = new User([
			'email' => $email,
			'lastName' => ArrayHelper::getValue($attributes, 'lastName'),
			'firstName' => ArrayHelper::getValue($attributes, 'firstName'),
			'mailing' => false,
		]);
		$this->setUserPic($user, $attributes);
		$user->setPassword(Yii::$app->security->generateRandomString(8));

		if ($user->save(false)) {
			$auth = new UserAuth([
				'user_id' => $user->id,
				'source' => $client->getId(),
				'source_id' => (string) $attributes['id'],
			]);
			if ($auth->save())
				$user->login();
		}
	}

	/**
	 * Link auth client account to current user
	 * @param array $attributes 
	 * @return void
	 */
	private function link($attributes)
	{
		$auth = new UserAuth([
			'user_id' => Yii::$app->getUser()->getId(),
			'source' => $this->_client->getId(),
			'source_id' => (string) $attributes['id'],
		]);
		if ($auth->save()) {
			$user = $auth->user;
			if ($user->firstName === null && $user->lastName === null) {
				$user->setAttributes([
					'firstName' => ArrayHelper::getValue($attributes, 'firstName'),
					'lastName' => ArrayHelper::getValue($attributes, 'lastName'),
				], false);
			}
			$this->setUserPic($user, $attributes);

			$user->save(false);

			Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Linked {client} account.', ['client' => $this->_client->getTitle()]));
		}
	}

	/**
	 * Sets user pic if needed
	 * @param User $user 
	 * @param array $attributes 
	 * @return void
	 */
	private function setUserPic(User $user, $attributes)
	{
		if ($user->pic === null) {
			$pic = ArrayHelper::getValue($attributes, 'pic');
			if ($pic !== null) {
				$info = parse_url($pic);
				$filename = Yii::$app->storage->generateTmpName($info['path']);
				if (@copy($pic, Yii::getAlias('@webroot') . $filename)) {
					$user->pic = $filename;
					Yii::$app->storage->storeObject($user);
				}
			}				
		}
	}

}
