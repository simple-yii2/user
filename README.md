User
=============
User module for Yii PHP Framework Version 2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require simpleyiicms/user:dev-master
```

or add

```
"simpleyiicms/user": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Make shure that auth manager is configured.

```php
return [
    // ...
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // ...
    ],
];
```

Add backend and frontend parts into your config  :

```php
return [
    // ...
    'modules' => [
        'user' => 'user\backend\Module',
        // ...
    ],
];
```

Use module User application component instead native.

```php
return [
    // ...
    'components' => [
        'user' => [
            'class' => 'user\common\components\User',
            'identityClass' => 'user\common\models\User',
            'loginUrl' => ['user/login/index'],
        ],
        // ...
    ],
];
```
