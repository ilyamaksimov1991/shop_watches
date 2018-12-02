<?php
/**
 * @var \app\models\CurrencyModel $currency
 */
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список валют
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список валют</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Наименование</th>
                                <th>Код</th>
                                <th>Значение</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($currencies as $currency=>$value): ?>
                                <tr>
                                    <td><?=$value['id'];?></td>
                                    <td><?=$value['title'];?></td>
                                    <td><?=$currency;?></td>
                                    <td><?=$value['value'];?></td>
                                    <td>
                                        <a href="<?=ADMIN;?>/currency/edit?id=<?=$value['id'];?>"><i class="fa fa-fw fa-pencil"></i></a>
                                        <a class="delete" href="<?=ADMIN;?>/currency/delete?id=<?=$value['id'];?>"><i class="fa fa-fw fa-close text-danger"></i></a>
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