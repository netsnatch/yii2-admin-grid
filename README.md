Instalation
---
Either run

`$ php composer.phar require netsnatch/yii2-grid-columns:"~1.0.4"`

or add

`"netsnatch/yii2-grid-columns": "~1.0.3"`

to the require section of your composer.json file.


Usage
---

```
  
  <div id="example-table-columns"></div>
  
  <?= GridView::widget([
        'as gridColumns' => [
            'class' => \Netsnatch\Yii2GridColumns\GridColumnsBehavior::class,
            'name' => 'example-table',
            'placeSelector' => '#example-table-columns',
            'buttonOptions' => [
                'class' => 'btn btn-default'
            ]
        ],
        ...
  ]); ?>

```
