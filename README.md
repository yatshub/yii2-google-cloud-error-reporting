<p align="center">
    <a href="http://www.yiiframework.com/" target="_blank">
        <img src="https://www.yiiframework.com/image/logo.svg" width="300" alt="Yii Framework" />
    </a>
</p>

Google Cloud Error Reporting for Yii2
------------

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yatshub/yii2-google-cloud-error-reporting "*"
```

or add

```
"yatshub/yii2-google-cloud-error-reporting": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply generate a service account  and configure your target as the following:

```php
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                ...
                'googleCloudErrorReporting' => [
                    'class' => 'yatshub\GoogleErrorReporting\ErrorReporting',
                    'levels' => ['error', 'warning'],
                    'except' => ['yii\web\HttpException:404'],
                    'projectId' => 'project-id',
                    'loggerInstance' => 'instance-log',
                    'clientSecretPath' => 'path/to/your/service/account/credentials.json',
                    'version' => 'dev or prod',
                    'service' => 'application name or any name for easy project tracking',
                ],
                ...
            ],
        ],
```