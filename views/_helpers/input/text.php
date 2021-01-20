<?php
/**
 * @var array $before
 * @var array $after
 */

use app\src\helpers\InputHelper;

?>

<div class="col-sm-3 col-xs-12">{label}</div>

<div class="col-sm-9 col-xs-12">
    <div class="input-group">
        <?php if (!empty($before)): ?>
            <span class="input-group-addon">
                <?php if ($before['type'] === InputHelper::WRAPPER_TYPE_ICON): ?>
                    <i class="fa fa-<?= $before['value'] ?>"></i>
                <?php endif; ?>

                <?php if ($before['type'] === InputHelper::WRAPPER_TYPE_HTML): ?>
                    <?= $before['value'] ?>
                <?php endif; ?>
            </span>
        <?php endif; ?>
        {input}
        <?php if (!empty($after)): ?>
            <span class="input-group-addon">
                <?php if ($after['type'] === InputHelper::WRAPPER_TYPE_ICON): ?>
                    <i class="fa fa-<?= $after['value'] ?>"></i>
                <?php endif; ?>

                <?php if ($after['type'] === InputHelper::WRAPPER_TYPE_HTML): ?>
                    <?= $after['value'] ?>
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
    {error}
</div>