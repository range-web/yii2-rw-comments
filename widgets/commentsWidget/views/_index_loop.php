<ul class="comments">
<?php
if ($models) {
    foreach ($models as $model) {
        echo $this->render('_index_item', [
            'model' => $model,
            'level' => $level,
            'maxLevel' => $maxLevel
        ]);
    }
}?>
</ul>