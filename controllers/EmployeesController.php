<?php

namespace app\controllers;

use Yii;
use app\models\Employees;
use app\models\Supermarkets;
use app\models\EmployeesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\models\Standard;

use yii\web\UploadedFile;

/**
 * EmployeesController implements the CRUD actions for Employees model.
 */
class EmployeesController extends Controller
{   public $avatar;
    /**
     * @inheritdoc
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
     * Lists all Employees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }
    public function actionIndexx()
    {   
        $superlist = ArrayHelper::map(Supermarkets::find()->all(), 'id', 'name');     
        $super = Supermarkets::findOne(1);
        $employees = $super->employees;
        return $this->render('indexx', [
                'employees' => $employees,
                'superlist' => $superlist
            ]);
    }

    /**
     * Displays a single Employees model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        
        $model = new Employees();
        $superlist = ArrayHelper::map(Supermarkets::find()->all(), 'id', 'name');
        if ($model->load(Yii::$app->request->post())) 
        {

            $avatar = UploadedFile::getInstance($model, 'avatar');
            $model->avatar = $avatar;
            //var_dump($model);die();
            if ($model->avatar == "") {
               $model->save(); 
            }else
            {   
                $model->save();
                $avatar->saveAs('uploads/' . 'avatars/' . $avatar->baseName . '.' . $avatar->extension);
            }

            Yii::$app->session->setFlash('success', "Guardado correctamente!");
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'superlist' => $superlist
            ]);
        }
    }

    /**
     * Updates an existing Employees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {   
        $superlist = ArrayHelper::map(Supermarkets::find()->all(), 'id', 'name');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $avatar = UploadedFile::getInstance($model, 'avatar');
            $model->avatar = $avatar;
            //var_dump($model);die();
            if ($model->avatar == "") {
               $model->save(); 
            }else
            {   
                $model->save();
                $avatar->saveAs('uploads/' . 'avatars/' . $avatar->baseName . '.' . $avatar->extension);
            }


            Yii::$app->session->setFlash('success', "Modificado correctamente!");
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'superlist' => $superlist
            ]);
        }
    }

    /**
     * Deletes an existing Employees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', "Eliminado correctamente!");
        return $this->redirect(['index']);
    }


    /**
     * Finds the Employees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employees::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
