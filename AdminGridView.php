<?php

namespace app\components\grid;

use yii\base\InvalidConfigException;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\web\View;

class AdminGridView extends GridView
{
    public $layout = "{summary}\n{menu}\n{items}\n{pager}";
    public $dataColumnClass = '\app\components\grid\AdminGridDataColumn';

    /**
     * Unique table name to store settings
     * @var string $tableName
     */
    public $tableName;

    private $clientOptions;

    public function init()
    {
        parent::init();

        if (!$this->tableName) {
            throw new InvalidConfigException('The "tableName" option is required.');
        }

        if (strpos($this->layout, '{menu}') === false) {
            $this->layout = '{menu}' . $this->layout;
        }

        $id = $this->options['id'];
        $this->clientOptions['id'] = $id;
        $this->clientOptions['menuElId'] = $id . '-menu';
        $this->clientOptions['modalElId'] = $id . '-modal';
        $this->clientOptions['tableName'] = $this->tableName;
        $this->tableOptions['class'] = (isset($this->tableOptions['class']) ? $this->tableOptions['class'] : '') . ' hidden';
        $columnsData = [];

        /** @var AdminGridDataColumn $column */
        foreach ($this->columns as $column) {

            if (!$column instanceof $this->dataColumnClass) {
                continue;
            }

            if (isset($column->attribute)) {
                $attr = $column->attribute;
            } else {
                $attr = $column->getHeaderCellLabelSlug();
            }

            $columnId = $this->options['id'] . '-' . $attr;
            $column->headerOptions['id'] = $columnId;

            $columnsData[] = [
                'id' => $columnId,
                'label' => $column->getHeaderCellLabelText(),
                'show' => true,
            ];
        }

        $this->clientOptions['columns'] = $columnsData;

        $view = $this->getView();
        AdminGridViewAsset::register($view);
        $view->registerJs("jQuery('#$id').adminGridView(" . Json::htmlEncode($this->clientOptions) . ");", View::POS_END);
    }

    public function renderSection($name)
    {
        switch ($name) {
            case '{menu}':
                return $this->renderMenu();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderMenu(){
        return $this->getView()->renderFile(__DIR__ . '/view/widget.php', $this->clientOptions);
    }

}
