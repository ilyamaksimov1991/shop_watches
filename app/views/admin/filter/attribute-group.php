<?php
/**
 * @var \app\models\AttributeGroupModel $attributesGroup
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Группы фильтров
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Группы фильтров</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?=ADMIN;?>/filter/group-add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить группу</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($attributesGroup as $k=>$item): ?>
                            <tr>
                                <td><?=$item;?></td>
                                <td>
                                    <a href="<?=ADMIN;?>/filter/group-edit?id=<?=$k;?>"><i class="fa fa-fw fa-pencil"></i></a>
                                    <a class="delete text-danger" href="<?=ADMIN;?>/filter/group-delete?id=<?=$k;?>"><i class="fa fa-fw fa-close text-danger"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->