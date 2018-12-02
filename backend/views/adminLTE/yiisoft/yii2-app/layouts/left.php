<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/<?php
                        if(Yii::$app->user->identity->administrador) echo "administrador";
                        else if(Yii::$app->user->identity->coordenador) echo "coordenador";
                        else if(Yii::$app->user->identity->professor) echo "professor";
                        else if(Yii::$app->user->identity->secretaria) echo "secretaria";
                        else echo "aluno";
                    ?>.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info" style="white-space:normal;padding-right:10px;">
                <?= Yii::$app->user->identity->nome ?>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                ['label' => 'Início','icon' => 'fa fa-home', 'url' => ['site/index'],],
                ['label' => 'Administração', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('administrador')],
                [
                    'label' => 'Usuários',
                    'icon' => 'fa fa-users',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')),
                    'items' => [
                        ['label' => 'Adicionar Usuário', 'icon' => 'fa fa-user-plus', 'url' => ['site/signup'],],
                        ['label' => 'Listar Usuários', 'icon' => 'fa fa-list', 'url' => ['user/index'], 'active' => $this->context->route == 'user/update' || $this->context->route == 'user/view' || $this->context->route == 'user/index' ],
                    ],
                ],
                ['label' => 'Financeiro', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')],
                [
                    'label' => 'Fornecedores',
                    'icon' => 'fa fa-truck',
                    'url' => ['fornecedor/index'],
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')),
                ],
                [
                    'label' => 'Despesas',
                    'icon' => 'fa fa-list-alt',
                    'url' => ['despesa/index'],
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')),
                ],
                [
                    'label' => 'Receitas',
                    'icon' => 'fa fa-list-alt',
                    'url' => ['receita/index'],
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')),
                ],
            ]
        ]) ?>
    </section>

</aside>
