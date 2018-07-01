<?php

namespace cms\user\backend;

use Yii;
use yii\helpers\Html;

use cms\components\BackendModule;
use cms\user\common\components\AuthorRule;
use cms\user\common\models\User;

/**
 * User backend module
 */
class Module extends BackendModule
{

    /**
     * @inheritdoc
     */
    public static function moduleName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    protected static function cmsDatabase()
    {
        parent::cmsDatabase();

        if (User::find()->where(['email' => 'admin'])->count() == 0) {
            $model = new User([
                'admin' => true,
                'active' => true,
                'email' => 'admin',
                'mailing' => false,
                'passwordChange' => true,
            ]);
            $model->setPassword('admin');
            $model->save();
        }
    }

    /**
     * @inheritdoc
     */
    protected static function cmsSecurity()
    {
        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole('author') === null) {
            //author role
            $author = $auth->createRole('author');
            $auth->add($author);
            //author rule
            $rule = new AuthorRule;
            $auth->add($rule);
            //author permission
            $own = $auth->createPermission('own');
            $own->ruleName = $rule->name;
            $auth->add($own);
            //add permission with rule to role
            $auth->addChild($author, $own);
        }
    }

    /**
     * @inheritdoc
     */
    public function cmsMenu()
    {
        if (!Yii::$app->user->can('admin')) {
            return [];
        }

        return [
            'label' => Yii::t('user', 'Security'), 
            'items' => [
                ['label' => Yii::t('user', 'Permissions'), 'url' => ['/user/permission/index']],
                ['label' => Yii::t('user', 'Roles'), 'url' => ['/user/role/index']],
                ['label' => Yii::t('user', 'Users'), 'url' => ['/user/user/index']],
            ],
        ];
    }

    /**
     * Making user module menu
     * @return array
     */
    public function cmsUserMenu()
    {
        self::cmsTranslation();

        if (Yii::$app->user->isGuest) {
            return [];
        }

        $username = Html::encode(Yii::$app->getUser()->getIdentity()->getUsername());

        return [
            ['label' => '<span class="glyphicon glyphicon-user"></span><span class="cms-username"> ' . $username . '</span>', 'encode' => false, 'items' => [
                ['label' => Yii::t('user', 'Settings'), 'url' => ['/user/settings/index']],
                ['label' => Yii::t('user', 'Change password'), 'url' => ['/user/password/index']],
                '<li class="divider"></li>',
                ['label' => Yii::t('user', 'Logout'), 'url' => ['/user/logout/index']],
            ]],
        ];
    }

}
