Copy to common\modules.
This module made for multilanguage project.
Before executig migrations you should look at them - they have some custom values.
Important - for each input in form name attribute should be specified as section-key-type of korresponding model Setting
Run migrations
./yii migrate/up --migrationPath=@common/modules/settings/migrations

Add this to your main configuration's modules array

'modules' => [
    'settings' => [
        'class' => 'common\modules\settings\Module',
    ],
    ...
],

Add this to your main configuration's components array

'components' => [
    'settings' => [
        'class' => ''common\modules\settings\components\Settings'
    ],
    ...
]



Typical component usage


$settings = Yii::$app->settings;

$value = $settings->get('section.key');

$value = $settings->get('key', 'section');

$settings->set('section.key', 'value');

$settings->set('section.key', 'value', null, 'string');

$settings->set('key', 'value', 'section', 'integer');

// Automatically called on set();
$settings->clearCache();
