<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\ExcelUploadForm;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'rules' => [
            //         [
            //             'actions' => ['login', 'error'],
            //             'allow' => true,
            //         ],
            //         [
            //             'actions' => ['logout', 'index'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function readAuthorYear(){
        $file = fopen("../../data/info.txt",'r');
        $w  = [];

        $unzip_authors = [];

        for($i=1;$i<15;$i++) {
           $w[1991+$i]  = [];   
        }

        while(!feof($file))
        {

            $link = $this->stringToListNumber(fgets($file));

            $year = $link[0];

            unset($link[0]);

            foreach ($link as $key => $value) {
                if(!(int)$value){
                    unset($link[$key]);
                }
            }
            
            usort($link, function ($a,$b) { 
                return ($a > $b); 
            });

            for($i=0;$i<count($link)-1;$i++) {
                for($j=$i+1;$j<count($link);$j++) {
                    $w[$year][] = $this->zip($link[$i],$link[$j]);
                    $unzip_authors[$this->zip($link[$i],$link[$j])][0] = $this->zip($link[$i],$link[$j]);
                    $unzip_authors[$this->zip($link[$i],$link[$j])][1] = [$link[$i],$link[$j]];
                }
            }

        }

        $count_w = [];

        foreach ($w as $key => $value) {
            foreach ($value as $k => $v) {
                $count_w[$v][] = $key;
            }
        }

        foreach ($count_w as $key => $value) {
            $unzip_authors[$key][2] = count(array_unique($value));
        }

        // asort($count_w);

        usort($unzip_authors, function ($a,$b) { 
                return ($a[2] < $b[2]); 
            });

        echo '<pre>';
        print_r($unzip_authors);

        die;

        fclose($file);
    }

    public function actionUpload(){
        $model = new ExcelUploadForm();
        $chunkSize = 2000;
        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            if ($model->upload()) {
                ini_set('memory_limit', '-1');
                set_time_limit(1200);
                $filePath = './' . $model->path;
                if (!file_exists($filePath)) {
                    throw new BadRequestHttpException('File doesn\'t exists.');
                }

                $file = fopen($filePath,'r');

                $authors_id = [];

                while(!feof($file))
                {
                    $authors_id = $this->stringToListNumber(fgets($file));
                }

                $link_for_year  = [];

                foreach ($authors_id as $key => $value)
                {
                    $sql = "SELECT * FROM paper WHERE (AUTHORS ";

                    $sql = $sql. 'LIKE '.'"%['.$value.',%"';
                    $sql = $sql. 'or AUTHORS LIKE '.'"%,'.$value.',%"';
                    $sql = $sql. 'or AUTHORS LIKE '.'"%,'.$value.']%")';

                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand($sql);

                    $search = $command->queryAll();

                    foreach ($search as $key => $value) {
                        $link_for_year[$value['year']][] = $this->stringToListNumber($value['authors']);
                        
                    }
                }

                $matrix = [];

                for($i=0;$i<14;$i++){
                    foreach ($authors_id as $key => $value){
                        foreach ($authors_id as $k => $v){
                            $matrix[$i+1992][$value][$v] = 0;
                        }
                    }
                }

                foreach ($link_for_year as $key => $value) {
                    foreach ($value as $k => $v) {
                        for($i=0;$i<count($v)-1;$i++) {
                           for($j=$i+1;$j<count($v);$j++) {
                                if(in_array($v[$i], $authors_id)&&in_array($v[$j], $authors_id)){
                                    $matrix[$key][$v[$i]][$v[$j]] = 1;
                                    $matrix[$key][$v[$j]][$v[$i]] = 1;
                                }
                                
                            }
                        }
                    }
                }

                $conver_id = [];

                foreach ($authors_id as $key => $value) {
                    $conver_id[$value] = $key/2;
                }

                foreach ($matrix as $key => $value) {

                    $w = [];
                    for($i=0;$i<10;$i++){
                        for($j=0;$j<10;$j++){
                            $w[$i][$j] =0;
                        }   
                    }

                    foreach ($value as $k => $v) {
                        foreach ($v as $k1 => $v1) {
                            $w[$conver_id[$k]][$conver_id[$k1]] = $v1;
                        }
                    }

                    $w = $this->cal_Weight($w,10);

                    $file = fopen("../../".$key.".txt",'w');
                    foreach ($w as $k1 => $v1) {
                        foreach ($v1 as $k2 => $v2) {
                            fprintf($file,"%-10.7f",$v2);
                        }
                        fprintf($file,"\n",'');
                    }

                    fclose($file);
                    
                }

            }
        }
    }

    public function bFS($node,$arr,$n)
    {
            
        $minpath = [];
        $queue = [];
        $check = [];
        $index = 0;
        $length = 1;

        $queue[0] = $node;

        for ($i = 0; $i <= $n; $i++)
        {
            $check[$i] = true;
            $minpath[$i] = 0;
        }

        $check[$node] = false;
         while ($index < $length)
        {
            $u = $queue[$index];
            for($i=0;$i<$n;$i++)
                if ($check[$i] && $arr[$u][$i] == 1)
                {
                    $minpath[$i] = $minpath[$u] + 1;
                    $queue[$length] = $i;
                    $check[$i] = false;
                    $length += 1;
                }
            $index += 1;
        }
        return $minpath;
    }

    public function cal_Weight($arr,$n)
    {
        $weight = [];
        for ($i = 0; $i < $n; $i++)
        {
            $minpath = [];
            $minpath = $this->bFS($i, $arr, $n);
            for ($j = 0; $j < $n; $j++)
            {
                if ($minpath[$j] == 0)
                    $weight[$i][$j] = 0;
                else
                    $weight[$i][$j] = 1/$minpath[$j];
            }
        }

        return $weight;
    }

    public function stringToListNumber($str){
        $str = str_replace("[","",$str);
        $str = str_replace("]"," ",$str);
        $str = str_replace(","," ",$str);

        $list = explode(" ", $str);

        foreach ($list as $key => $value) {
            if($value == ""){
                unset($list[$key]);
            }
        }
        
        return $list;
    }

    public function zip($x,$y)
    {
        return $x * 30000 + $y;
    }

    public function unzip($v)
    {
        $y = $v % 30000;
        $x = ($v-$y) / 30000;
        $list = [];

        $list[] = $x;
        $list[] = $y;

        return $list;
    }
}
