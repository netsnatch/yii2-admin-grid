<?php

namespace Netsnatch\Yii2GridColumns;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\WidgetEvent;
use yii\grid\GridView;
use yii\web\View;

class GridColumnsBehavior extends Behavior
{
    public $name;
    public $placeSelector;
    public $hideBeforeReady = false;
    public $buttonOptions = [];

    public function events()
    {
        return [
            GridView::EVENT_BEFORE_RUN => function (WidgetEvent $event) {
                $this->run($event);
            }
        ];
    }

    public function run(WidgetEvent $event)
    {
        /** @var GridView $widget */
        $widget = $event->sender;

        if (!$widget instanceof GridView) {
            throw new InvalidCallException('Widget must be an instance of yii\grid\GridView');
        }

        $content = GridColumns::widget([
            'name' => $this->name,
            'hideBeforeReady' => $this->hideBeforeReady,
            'gridViewWidget' => $widget,
            'buttonOptions' => $this->buttonOptions,
            'placeSelector' => $this->placeSelector,
        ]);

        Yii::$app->getView()->on(View::EVENT_END_BODY, function () use ($content) {
            echo $content;
        });
    }
}
