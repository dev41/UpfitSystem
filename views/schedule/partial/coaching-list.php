<?php
/**
 * @var \yii\web\View $this
 * @var array $coaching
 */
?>

<ul class="list-group coaching-list">
    <li class="list-group-item"><strong><?= Yii::t('app', 'Coaching'); ?></strong></li>
    <?php foreach ($coaching as $training): ?>
        <li class="list-group-item">
            <div class='fc-event js-coaching'
                 data-color="<?= $training['color'];?>"
                 data-id="<?= $training['id']; ?>"
            >
                <a target="_blank" href="/coaching/update?id=<?= $training['id']; ?>"><?= $training['name']; ?></a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>