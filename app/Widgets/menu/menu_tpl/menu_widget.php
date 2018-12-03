<?php
/**
 * @var array $category
 */
?>
<li>
    <a href="category/<?= isset($category['alias']) ? $category['alias'] : false; ?>"><?= isset($category['title']) ? $category['title'] : false; ?></a>
    <?php if (isset($category['childs'])): ?>
        <ul>
            <?= $this->getMenuHtml($category['childs']); ?>
        </ul>
    <?php endif; ?>
</li>
