<?php

namespace app\components\grid;

use yii\grid\DataColumn;
use yii\helpers\Inflector;

class AdminGridDataColumn extends DataColumn
{

    public function getHeaderCellLabelSlug()
    {
        return Inflector::slug($this->getHeaderCellLabelText());
    }

    public function getHeaderCellLabelText()
    {
        return strip_tags($this->renderHeaderCellContent());
    }

}
