<?php

namespace backend\controllers;

use Yii;
use backend\models\Despesa;
use backend\models\DespesaSearch;
use backend\models\Fornecedor;
use backend\models\FornecedorSearch;
use backend\models\Beneficiario;
use backend\models\Item;
use backend\models\DespesaPassagem;
use backend\models\DespesaDiaria;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//debugger para restrear erros se tiver
/*function dbg() {

    $debug_arr = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $line = $debug_arr[0]['line'];
    $file = $debug_arr[0]['file'];

    header('Content-Type: text/plain');

    echo "linha: $line\n";
    echo "arquivo: $file\n\n";
    print_r(array('GET' => $_GET, 'POST' => $_POST));
    exit;
}*/

/**
 * DespesaController implements the CRUD actions for Despesa model.
 */
class DespesaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Despesa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DespesaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Despesa model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $despesaModel = $this->findModel($id);
        if(isset($despesaModel)){
            $fornecedorModel = Fornecedor::findOne($despesaModel->id_fornecedor);
            $beneficiarioModel = Beneficiario::findOne($despesaModel->id_beneficiario);
            $itemModel = Item::findOne($despesaModel->id_item);
            $despesapassagemModel = DespesaPassagem::findOne($despesaModel->id);
            $despesadiariaModel = DespesaDiaria::findOne($despesaModel->id);
        }

        return $this->render('view', [
            'despesaModel' => $despesaModel,
            'fornecedorModel' => $fornecedorModel,
            'beneficiarioModel' => $beneficiarioModel,
            'itemModel' => $itemModel,
            'despesapassagemModel' => $despesapassagemModel,
            'despesadiariaModel'=> $despesadiariaModel
        ]);
    }

    /**
     * Creates a new Despesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $despesaModel = new Despesa();
        $fornecedorModel = new Fornecedor();
        $beneficiarioModel = new Beneficiario();
        $itemModel = new Item();
        $despesapassagemModel = new DespesaPassagem();
        $despesadiariaModel = new DespesaDiaria();

        $fornecedores = $fornecedorModel->find()->orderBy('nome ASC')->all();

        $listaFornecedores = [];
        foreach($fornecedores as $f){
            $listaFornecedores[] = $f->nome;
        }
        if ($despesaModel->load(Yii::$app->request->post())) {


            $beneficiarioModel->load(Yii::$app->request->post());
            $fornecedorModel->load(Yii::$app->request->post());
            $itemModel->load(Yii::$app->request->post());
            $despesapassagemModel->load(Yii::$app->request->post());
            $despesadiariaModel->load(Yii::$app->request->post());

            $data_pgto = \DateTime::createFromFormat('d/m/Y', $despesaModel->data_pgto);
            $data_emissao_NF = \DateTime::createFromFormat('d/m/Y', $despesaModel->data_emissao_NF);
            $despesaModel->data_pgto = isset($data_pgto) && !empty($data_pgto) ? $data_pgto->format('Y-m-d') : null;
            $despesaModel->data_emissao_NF = isset($data_emissao_NF) && !empty($data_emissao_NF) ? $data_emissao_NF->format('Y-m-d') : null;

            // recuperação valores para despesa passagem
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_volta);
            $despesapassagemModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesapassagemModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;

            // recuperação valores para despesa diaria
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_volta);
            $despesadiariaModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesadiariaModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;


            if(!empty($beneficiarioModel->nome) || !empty($beneficiarioModel->rg)){
                $beneficiarioModel->save();
                $despesaModel->id_beneficiario = $beneficiarioModel->id;
            }


            if(!empty($fornecedorModel->nome) || !empty($fornecedorModel->cpf_cnpj)){
                $fornecedor = Fornecedor::find()->where(['cpf_cnpj' => $fornecedorModel->cpf_cnpj])->one();
                if(!isset($fornecedor)){
                    $fornecedorModel->save();
                    $despesaModel->id_fornecedor = $fornecedorModel->id;    
                }else{
                    $despesaModel->id_fornecedor = $fornecedor->id;
                }
            }

           /* se o tipo de despesa não for do tipo 0 usado para mostrar a messagem para seleção de tipo despesa
             depois de salvar na tabela despesa deve recuperar o id da despesa e salvar nas tabelas despesa e diaria*/

           if($despesaModel->tipo_desp !=0){
                if ($despesaModel->save() ){

                    if(!empty($despesapassagemModel->data_hora_ida) || !empty($despesapassagemModel->data_hora_volta) || !empty($despesapassagemModel->destino) || !empty($despesapassagemModel->localizador)){

                        $despesapassagemModel->id_despesa = $despesaModel->id;

                        $despesapassagemModel->save();


                    }

                    if(!empty($despesadiariaModel->data_hora_ida) || !empty($despesadiariaModel->data_hora_volta) || !empty($despesadiariaModel->destino) || !empty($despesadiariaModel->localizador)){

                        $despesadiariaModel->save();
                        $despesadiariaModel->id_despesa = $despesaModel->id ;


                    }
                }
            }

           // $despesaModel->save();
            return $this->redirect(['view', 'id' => $despesaModel->id]);
        }
                     
        return $this->render('create', [
            'despesaModel' => $despesaModel,
            'fornecedorModel' => $fornecedorModel,
            'beneficiarioModel' => $beneficiarioModel,
            'itemModel' => $itemModel,
            'fornecedores' => $listaFornecedores,
            'despesapassagemModel' => $despesapassagemModel,
            'despesadiariaModel' => $despesadiariaModel
        ]);
    }

    public function actionGetfornecedorinfo($nome){
        $fornecedor = Fornecedor::find()->where(['nome' => $nome])->one();

        return $this->asJson(isset($fornecedor) ? $fornecedor : ["id" => null]);
    }

    public function actionGetiteminfo($numero, $projeto = 1, $tipo){
        $item = Item::find()->where([
            'numero_item' => $numero,
            'id_projeto' => $projeto,
            'tipo_item' => $tipo
            ])->one();

        return $this->asJson(isset($item) ? $item : ["id" => null]);
    }

    /**
     * Updates an existing Despesa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $despesaModel = $this->findModel($id);
        if(isset($despesaModel)){
            $fornecedorModel = Fornecedor::findOne($despesaModel->id_fornecedor);
            $beneficiarioModel = Beneficiario::findOne($despesaModel->id_beneficiario);
            $itemModel = Item::findOne($despesaModel->id_item);
            $despesapassagemModel = DespesaPassagem::findOne($despesaModel->id);
            $despesadiariaModel = DespesaDiaria::findOne($despesaModel->id);
    
            $fornecedorModel = isset($fornecedorModel) ? $fornecedorModel : new Fornecedor();
            $beneficiarioModel = isset($beneficiarioModel) ? $beneficiarioModel : new Beneficiario();
            $itemModel = isset($itemModel) ? $itemModel : new Item();
            $despesapassagemModel = isset($despesapassagemModel) ? $despesapassagemModel : new DespesaPassagem();
            $despesadiariaModel = isset($despesadiariaModel) ? $despesadiariaModel : new DespesaDiaria();


            $despesaModel->data_pgto = date('d/m/Y', strtotime($despesaModel->data_pgto));
            $despesaModel->data_emissao_NF = date('d/m/Y', strtotime($despesaModel->data_emissao_NF));


            // recuperação valores para despesa passagem
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_volta);
            $despesapassagemModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesapassagemModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;

            // recuperação valores para despesa diaria
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_volta);
            $despesadiariaModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesadiariaModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;


            $fornecedores = $fornecedorModel->find()->orderBy('nome ASC')->all();
            $listaFornecedores = [];
            foreach($fornecedores as $f){
                $listaFornecedores[] = $f->nome;
            }
    
        }
        
        if ($despesaModel->load(Yii::$app->request->post())) {

            $beneficiarioModel->load(Yii::$app->request->post());
            $fornecedorModel->load(Yii::$app->request->post());
            $itemModel->load(Yii::$app->request->post());

            $data_pgto = \DateTime::createFromFormat('d/m/Y', $despesaModel->data_pgto);
            $data_emissao_NF = \DateTime::createFromFormat('d/m/Y', $despesaModel->data_emissao_NF);
            $despesaModel->data_pgto = $data_pgto->format('Y-m-d');
            $despesaModel->data_emissao_NF = $data_emissao_NF->format('Y-m-d');

            // recuperação valores para despesa passagem
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesapassagemModel->data_hora_volta);
            $despesapassagemModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesapassagemModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;

            // recuperação valores para despesa diaria
            $data_hora_ida = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_ida);
            $data_hora_volta = \DateTime::createFromFormat('d/m/Y h:m', $despesadiariaModel->data_hora_volta);
            $despesadiariaModel->data_hora_ida = isset($data_hora_ida) && !empty($data_hora_ida) ? $data_hora_ida->format('Y-m-d h:m') : null;
            $despesadiariaModel->data_hora_volta = isset($data_hora_volta) && !empty($data_hora_volta) ? $data_hora_volta->format('Y-m-d h:m') : null;


            if(!empty($beneficiarioModel->nome) || !empty($beneficiarioModel->rg)){
                $beneficiario = Beneficiario::find()->where(['rg' => $beneficiarioModel->rg])->one();
                if(!isset($beneficiario)){
                    $beneficiarioModel->save();
                    $despesaModel->id_beneficiario = $beneficiarioModel->id;
                }else{
                    if($beneficiarioModel->nome != "") $beneficiario->nome = $beneficiarioModel->nome;
                    if($beneficiarioModel->orgao_emissor != "") $beneficiario->orgao_emissor = $beneficiarioModel->orgao_emissor;
                    if($beneficiarioModel->nivel_academico != "") $beneficiario->nivel_academico = $beneficiarioModel->nivel_academico;
                    $beneficiario->save();
                    $despesaModel->id_beneficiario = $beneficiario->id;
                }
            }else{
                $despesaModel->id_beneficiario = null;
            }

            if(!empty($fornecedorModel->nome) || !empty($fornecedorModel->cpf_cnpj)){
                $fornecedor = Fornecedor::find()->where(['cpf_cnpj' => $fornecedorModel->cpf_cnpj])->one();
                if(!isset($fornecedor)){
                    $fornecedorModel->save();
                    $despesaModel->id_fornecedor = $fornecedorModel->id;    
                }else{
                    if($fornecedor->nome != $fornecedorModel->nome){
                        $fornecedorModel->save();
                    }
                    $despesaModel->id_fornecedor = $fornecedor->id;
                }
            }

            if(!empty($fornecedorModel->nome) || !empty($fornecedorModel->cpf_cnpj)){
                $fornecedor = Fornecedor::find()->where(['cpf_cnpj' => $fornecedorModel->cpf_cnpj])->one();
                if(!isset($fornecedor)){
                    $fornecedorModel->save();
                    $despesaModel->id_fornecedor = $fornecedorModel->id;
                }else{
                    $despesaModel->id_fornecedor = $fornecedor->id;
                }
            }

            if($despesaModel->tipo_desp !=0){
                if ($despesaModel->save() ){

                    if(!empty($despesapassagemModel->data_hora_ida) || !empty($despesapassagemModel->data_hora_volta) || !empty($despesapassagemModel->destino) || !empty($despesapassagemModel->localizador)){

                        $despesapassagemModel->id_despesa = $despesaModel->id;

                        $despesapassagemModel->save();


                    }

                    if(!empty($despesadiariaModel->data_hora_ida) || !empty($despesadiariaModel->data_hora_volta) || !empty($despesadiariaModel->destino) || !empty($despesadiariaModel->localizador)){

                        $despesadiariaModel->save();
                        $despesadiariaModel->id_despesa = $despesaModel->id ;


                    }
                }
            }

            // $despesaModel->save();
            return $this->redirect(['view', 'id' => $despesaModel->id]);
        }

           // $despesaModel->save();
            //return $this->redirect(['index']);
            
       // }

        return $this->render('update', [
            'despesaModel' => $despesaModel,
            'fornecedorModel' => $fornecedorModel,
            'beneficiarioModel' => $beneficiarioModel,
            'itemModel' => $itemModel,
            'fornecedores' => $listaFornecedores,
            'despesapassagemModel' => $despesapassagemModel,
            'despesadiariaModel'  => $despesadiariaModel,

        ]);
    }

    /**
     * Deletes an existing Despesa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Despesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Despesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Despesa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
