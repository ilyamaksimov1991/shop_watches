<?php
/**
 * @var \app\models\AttributeGroupModel $group
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый фильтр
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN_URL;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?=ADMIN_URL;?>/filter/attribute">Список фильтров</a></li>
        <li class="active">Новый фильтр</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?=ADMIN_URL;?>/filter/attribute-add" method="post" data-toggle="validator" id="add">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="value">Наименование</label>
                            <input type="text" name="value" class="form-control" id="value" placeholder="Наименование" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Группа</label>
                            <select name="attr_group_id" id="category_id" class="form-control">
                                <option>Выберите группу</option>
                                <?php foreach($group as $k=>$item): ?>
                                    <option value="<?=$k;?>"><?=$item;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->