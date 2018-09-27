<?php

use yii\helpers\Html;

/**
 * @var string $id
 * @var array $buttonOptions
 */

if (isset($buttonOptions['class'])) {
    $buttonOptions['class'] .= ' grid-columns__button';
} else {
    $buttonOptions['class'] = 'grid-columns__button';
}
?>

<div id="<?= Html::encode($id) ?>" class="grid-columns">
    <?= Html::button('Columns&nbsp;<span class="caret"></span>', $buttonOptions) ?>

    <div class="modal grid-columns__modal" tabindex="-1" role="dialog">
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
                            <td class="grid-columns__left-col grid-columns__list-col"></td>
                            <td class="grid-columns__right-col grid-columns__list-col"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="grid-columns__bottom">
                                <?= Html::button('Reset', [
                                    'class' => 'btn btn-sm btn-warning grid-columns__reset'
                                ]) ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
