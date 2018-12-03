<?php
/**
 * @var \app\models\AttributeValueModel $attributes
 * @var \app\models\AttributeGroupModel $attributesGroup
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование фильтра <?=h($attributes->value);?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN_URL;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?=ADMIN_URL;?>/filter/attribute">Список фильтров</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?=ADMIN_URL;?>/filter/attribute-edit" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="value">Наименование</label>
                            <input type="text" name="value" class="form-control" id="value" placeholder="Наименование" required value="<?=h($attributes->value);?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Группа</label>
                            <select name="attr_group_id" id="category_id" class="form-control">
                                <?php foreach($attributesGroup as $k=>$item): ?>
                                    <option value="<?=$k;?>"<?php if($k == $attributes->attr_group_id) echo ' selected'; ?>><?=$item;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="id" value="<?=$attributes->id;?>">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->