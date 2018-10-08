<?php

namespace Netsnatch\Yii2GridColumns;

use Netsnatch\Yii2GridColumns\assets\GridColumnsAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\View;

class GridColumns extends Widget
{
    public $name;

    public $hideBeforeReady = false;

    public $buttonOptions;

    public $placeSelector;

    public $clientOptions = [];

    /** @var GridView */
    public $gridViewWidget;

    public function init()
    {
        parent::init();

        if (!$this->gridViewWidget) {
            throw new InvalidConfigException('The "gridViewWidget" option is required.');
        }

        if (!$this->placeSelector) {
            throw new InvalidConfigException('The "placeSelector" option is required.');
        }

        $this->validateTableName();
    }

    public function run()
    {
        $this->initOptions();

        return $this->render('widget', [
            'id' => $this->getId(),
            'buttonOptions' => (array) $this->buttonOptions,
        ]);
    }

    public function initOptions()
    {
        $this->clientOptions['id'] = $this->getId();
        $this->clientOptions['name'] = $this->name;
        $this->clientOptions['targetWidgetId'] = $this->gridViewWidget->getId();
        $this->clientOptions['placeSelector'] = $this->placeSelector;

        $this->gridViewWidget->tableOptions['class'] = $this->gridViewWidget->tableOptions['class'] ?? '';
        $this->gridViewWidget->tableOptions['class'] .= ' grid-columns__table';

        if ($this->hideBeforeReady) {
            $this->gridViewWidget->options['class'] = $this->gridViewWidget->options['class'] ?? '';
            $this->gridViewWidget->options['class'] .= ' grid-columns__hidden';
        }

        $columnsData = [];
        foreach ($this->gridViewWidget->columns as $column) {
            if (!$column instanceof \yii\grid\Column) {
                continue;
            }

            switch (get_class($column)) {
                case ActionColumn::class:
                    $attr = 'actions';
                    $label = 'Actions';
                    break;
                default:

                    if (isset($column->attribute)) {
                        $attr = $column->attribute;
                    } else {
                        $attr = Inflector::slug(strip_tags($column->renderHeaderCell())) ?: md5($column->renderHeaderCell());
                    }

                    if ($column instanceof \yii\grid\DataColumn && isset($column->label)) {
                        $label = $column->label;
                    } else {
                        $label = strip_tags($column->renderHeaderCell()) ?: '&nbsp;';
                    }
                    break;
            }

            $columnClass = $this->getId() . '-' . $attr;
            $column->headerOptions['class'] = $columnClass;

            $columnsData[] = [
                'class' => $columnClass,
                'label' => $label,
                'key' => $attr,
            ];
        }

        $this->clientOptions['columns'] = $columnsData;
    }

    public function registerScripts()
    {
        GridColumnsAsset::register($this->getView());

        $this->getView()->registerJs(
            "jQuery('#" . $this->getId() . "').yiiGridColumns(" . Json::htmlEncode($this->clientOptions) . ");",
            View::POS_END
        );
    }

    public function validateTableName()
    {
        static $names = [];

        if (!$this->name) {
            throw new InvalidConfigException('The "name" property must be set.');
        }

        if (array_key_exists($this->name, $names)) {
            throw new InvalidConfigException('The "name" "' . $this->name . '" already exists.');
        }

        $names[$this->name] = true;
    }
}
