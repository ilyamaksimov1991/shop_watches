<?php
/**
 * @var int $id
 * @var array $category
 * 'tpl' => WIDGETS . '/menu/menu_tpl/menu_widget.php',
 */
?>
<li>
    <a href="?id=<?= $id; ?>"><?= $category['title']; ?></a>
    <?php if (isset($category['childs'])): ?>
        <ul>
            <?= $this->getMenuHtml($category['childs']); ?>
        </ul>
    <?php endif; ?>
</li>