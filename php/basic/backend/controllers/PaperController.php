<?php

namespace backend\controllers;

use Yii;
use backend\models\Paper;
use backend\models\Author;
use backend\models\PaperSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use fproject\components\DbHelper;

/**
 * PaperController implements the CRUD actions for Paper model.
 */
class PaperController extends Controller
{
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
     * Lists all Paper models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaperSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paper model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function readFolder($dir){
        $list = array();

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if($file!='.'&&$file!='..'){
                        $list[] = $file;
                    }
                    
                }
                closedir($dh);
            }
        }

        return $list;
    }

    /**
     * Creates a new Paper model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Paper();

        $year = [
            '1992' => '92',
            '1993' => '93',
            '1994' => '94',
            '1995' => '95',
            '1996' => '96',
            '1997' => '97',
            '1998' => '98',
            '1999' => '99',
            '2000' => '00',
            '2001' => '01',
            '2002' => '02',
            '2003' => '03',
            '2004' => '04',
            '2005' => '05',
            
        ];

        $list_author_new = [];
        $list_author_old = [];

        $list_author_db = Author::find()->where(['>','id_author','0'])->asArray()->all();

        
        for($i = 0 ; $i < count($list_author_db) ; $i++){
            $list_author_old[$list_author_db[$i]['author']] = $i+1;
        }

        $cout_author = count($list_author_db)+1;
        $list_paper  = [];

        if ($model->load(Yii::$app->request->post())) {

            $dir = "../../uploads/".$year[$model->yearr];
            
            $month = $this->readFolder($dir);

            for($i=0;$i<count($month);$i++){
                $file = $this->readFolder($dir.'/'.$month[$i]);

                for($j=0;$j<count($file);$j++){
                    $path = $dir.'/'.$month[$i].'/'.$file[$j];

                    $paper = $this->readAbs($path);

                    $paper_save = new Paper();
                    $authors = [];

                    //them moi mot tac gia chua ton tai
                    for($k = 0; $k < count($paper['authors']) ; $k++){
                        if (!array_key_exists($paper['authors'][$k], $list_author_new)&&!array_key_exists($paper['authors'][$k], $list_author_old)) {
                            $list_author_new[$paper['authors'][$k]] = $cout_author;

                            $author_save = new Author();
                            $author_save->author = $paper['authors'][$k];
                            $author_save->id_author = $cout_author;
                            $author_save->save();

                            $cout_author++;
                            
                        }

                        if(array_key_exists($paper['authors'][$k], $list_author_new)){
                            $authors[] = $list_author_new[$paper['authors'][$k]];
                        }
                        else{
                            $authors[] = $list_author_old[$paper['authors'][$k]];
                        }

                    }

                    $paper_save->year = $model->year;
                    $paper_save->abstracts = $paper['abstracts'];
                    $paper_save->title = $paper['title'];
                    $paper_save->paper = $paper['paper'];
                    $authors = json_encode($authors);

                    $paper_save->authors = $authors;
                          
                    $paper_save->save();
         
                }
            }
            

            return $this->redirect(['index']);
        } 
        else {
            return $this->render('create', [
                'model' => $model,
                'year' => $year,
            ]);
        }
    }


    protected function readAbs($path){

        $fp = @fopen($path, "r");
        $paper = array();
        // Kiểm tra file mở thành công không
        if (!$fp) {
            echo 'Mở file không thành công';
        }
        else
        {
            // Lặp qua từng dòng để đọc
            $array_all  = array();
            $i = 0;
            while(!feof($fp))
            {
                $array_all[$i] = fgets($fp);
                $i++; 
            }
            
            $paper['paper'] = substr($array_all[0],7); 

            $paper['abstracts'] = substr($array_all[3],10);
            $paper['title'] = substr($array_all[1],7);
            
            $name_temp = substr($array_all[2],9);
           
            $paper['authors'] = split(' and ',$name_temp);
            
        }

        return $paper;
    }

    
    /**
     * Updates an existing Paper model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_paper]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Paper model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paper model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paper the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paper::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
