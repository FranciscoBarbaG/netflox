<?php

namespace app\controllers;

use Yii;
use app\models\Participantes;
use app\models\ParticipantesSearch;
use yii\filters\AccessControl;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParticipantesController implements the CRUD actions for Participantes model.
 */
class ParticipantesController extends Controller
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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->rol == 'admin';
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Participantes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParticipantesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Participantes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Participantes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Participantes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Participantes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Participantes model.
     * @return string
     * @throws \Exception
     */
    public function actionAjaxCreate()
    {
        $model = new Participantes();
        $model->show_id = Yii::$app->request->post('show_id');
        $model->persona_id = Yii::$app->request->post('persona_id');
        $model->rol_id = Yii::$app->request->post('rol_id');

        if ($model->save()) {
            $participantesProvider = (new ParticipantesSearch())->search(Yii::$app->request->queryParams, $model->show_id);

            return json_encode(GridView::widget([
                'summary' => '',
                'dataProvider' => $participantesProvider,
                'columns' => [
                    'persona.nombre',
                    'rol.rol',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model) {
                                return '<span id="' . $model->id . '" class="glyphicon glyphicon-trash delete"></span>';
                            }
                        ],
                    ],
                ],
            ]));
        }

        return json_encode('');
    }

    /**
     * Creates a new Participantes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Participantes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Participantes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Participantes model.
     * @return false|string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));

        $participantesProvider = (new ParticipantesSearch())->search(Yii::$app->request->queryParams, $model->show_id);

        $model->delete();

        return json_encode(GridView::widget([
            'summary' => '',
            'dataProvider' => $participantesProvider,
            'columns' => [
                'persona.nombre',
                'rol.rol',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return '<span id="' . $model->id . '" class="glyphicon glyphicon-trash delete"></span>';
                        }
                    ],
                ],
            ],
        ]));
    }
}
