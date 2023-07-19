<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\File;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $model = new File();

        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post('File');

            unset($post['file']);
            $model->description = $post['description'];
                $model->time = time();

            if (isset($post['id']) && $post['id']) {

                $model = File::find()->where(['id' => $post['id']])->one();
                
                $model->save(false);
                return $this->redirect(['index']);
            } else {
            
                $model->save(false);
                return $this->redirect(['index']);

            }

        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    public function actionUpload()
    {

        $model = new File(); // Replace 'YourModel' with the appropriate model class

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');

            $fileName = $file->baseName . '.' . $file->extension;
            $fileHash = md5($file->baseName);

            $checkFile = File::find()->where(['hash' => $fileHash])->one();

            if ($checkFile) {
                return [
                    'success' => false,
                    'message' => 'File has been exists',
                    'data' => [
                        'id' => $checkFile->id,
                        'name' => $checkFile->name,
                        'size' => (filesize(Yii::getAlias('@webroot') . '/uploads/'.$checkFile->name) / 1024) . ' kb',
                        'description' => $checkFile->description
                    ]
                ];
            } else {

                $model->hash = $fileHash;
                $model->name = $fileName;
           

                $model->save(false);

                $file->saveAs(Yii::getAlias('@webroot') . '/uploads/' . $fileName);

                return ['success' => true, 'message' => 'File uploaded successfully', 'data' => $model];
            }


        }

        // In case of a non-AJAX request or if the file upload failed, render the view normally.
        return ['success' => false, 'message' => 'File upload failed'];

    }
    
    
}