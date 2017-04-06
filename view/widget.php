<?php

use yii\helpers\Html;

/**
 * @var string $menuElId
 * @var string $modalElId
 * @var array $columns [ ['id' => .., 'label' => ..], ]
 */

?>
<button class="btn btn-sm btn-info" id="<?= Html::encode($menuElId) ?>" style="margin-top: 6px">
    Columns&nbsp;<span class="caret"></span>
</button>

<div class="modal" tabindex="-1" role="dialog" id="<?= Html::encode($modalElId) ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Columns settings</h4>
            </div>
            <div class="modal-body">
                <table width="100%">
                    <tr>
                        <th class="text-center col-md-6">SHOW</th>
                        <th class="text-center col-md-6">HIDE</th>
                    </tr>
                    <tr>
                        <td class="admin-grid__left-col admin-grid__list-col"></td>
                        <td class="admin-grid__right-col admin-grid__list-col"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
