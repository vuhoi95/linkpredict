<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SearchForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use backend\models\Paper;
use backend\models\Author;
use backend\models\Papertemp;
use backend\models\Authortemp;
use yii\data\ActiveDataProvider;
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $model = new SearchForm();

        $search = null;
        $search_year = [];
        $list_author_link = [];

        $DOTstring = 'dinetwork {';

        $listDOTstring = [];

        $author_will_link = [];

        $author_will_link_view = [];
        
        if ($model->load(Yii::$app->request->post())) {

            $id_author_data = [140, 141, 460, 1217, 1226, 1566, 1567, 2450, 2613, 2849];

            $search_all = Author::find()->where(['=','author',$model->author])->asArray()->all();
            $search = null;
            foreach ($search_all as $key => $value) {
                if(in_array($value['id_author'], $id_author_data)){
                    $search = $value;
                    break; 
                }
            }

            $id_search = $search['id_author'];//id search

            if(in_array($search['id_author'], $id_author_data)){
                $sql = "SELECT * FROM paper WHERE (AUTHORS ";

                $sql = $sql. 'LIKE '.'"%['.$search['id_author'].',%"';
                $sql = $sql. 'or AUTHORS LIKE '.'"%,'.$search['id_author'].',%"';
                $sql = $sql. 'or AUTHORS LIKE '.'"%,'.$search['id_author'].']%")';
                $sql = $sql. 'and year < 2002';

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand($sql);

                $search = $command->queryAll();

                foreach ($search as $key => $value) {
                    $authors = json_decode($value['authors']);

                    $authors_change = [];
                    foreach ($authors as $k => $v) {
                        if(in_array($v, $id_author_data)){
                            $authors_change[] = $v;
                        }
                    }
                   
                    $search[$key]['authors'] = json_encode($authors_change);

                }

                for($i=1992;$i<2002;$i++){
                    $listDOTstring[$i][0] = 'dinetwork {';
                    $listDOTstring[$i][1] = [];
                }

                foreach ($search as $key => $value) {
                    $authors = json_decode($value['authors']);

                    $string = '';
                    for($j=0;$j < count($authors);$j++){
                       
                        $author = Author::find()->where(['=','id_author',$authors[$j]])->asArray()->one();

                        if($j+1<count($authors)){
                            $string .= $author['author'] .' and ';
                        }
                        else{
                            $string .= $author['author'];
                        }

                        if(!in_array($author['author'], $listDOTstring[$value['year']][1]) && $model->author != $author['author']){
                            $listDOTstring[$value['year']][0] .= '|'.$model->author.'| '.'-> |'.$author['author'].'|;';
                            $listDOTstring[$value['year']][1][] = $author['author'];
                        }
                        
                       
                        if($model->author!=$author['author'] && !in_array($author['author'], $list_author_link)){
                            $list_author_link[] = $author['author'];
                        }


                    }

                    $search[$key]['authors_view'] = $string;

                    $search_year[$value['year']][] = $search[$key];

                    $string = '';

                }

                ksort($search_year);

                for($i=1992;$i<2002;$i++){
                    $listDOTstring[$i][0] .= '}';
                }

                foreach ($list_author_link as $key) {
                    $DOTstring = $DOTstring.'|'.$model->author.'| '.'-> |'.$key.'|;';
                }

                $DOTstring = $DOTstring.'}';

                $file = fopen("../../myfile.txt",'r');
                $forecast  = [];
                $i=1;
                while(!feof($file))
                {
                    $forecast[$i] = str_split(fgets($file),16);
                    $i++;
                }
                fclose($file);

                $link_year = [] ;

                for($i=0;$i<4;$i++){
                    $count = 0;
                    for($j=0;$j<count($id_author_data);$j++){
                        for($h=0;$h<count($id_author_data);$h++){
                            if($j == $h){
                                $link_year[$i+2002][$id_author_data[$j]][$id_author_data[$h]] = 0;
                            }
                            else if($j<$h){
                                $count++;
                                $link_year[$i+2002][$id_author_data[$j]][$id_author_data[$h]] 
                                    = $forecast[$count][$i];
                                $link_year[$i+2002][$id_author_data[$h]][$id_author_data[$j]] 
                                = $forecast[$count][$i];
                            }
                            else{
                                
                            }
                        }
                    }
                }

                for($i=0;$i<count($id_author_data);$i++){
                    for($j=0;$j<4;$j++){
                        for($h=0;$h<count($id_author_data);$h++){
                            if($h!=$i){
                                $author_will_link[$id_author_data[$i]][$j+2002] = [];
                            }
                        }
                    }
                }

                for($i=0;$i<count($id_author_data);$i++){
                    for($j=0;$j<4;$j++){
                        for($h=0;$h<count($id_author_data);$h++){
                            if($h!=$i){
                                if($link_year[$j+2002][$id_author_data[$i]][$id_author_data[$h]] > 0){
                                    
                                    $author_will_link[$id_author_data[$i]][$j+2002][] = $id_author_data[$h];
                                }
                            }
                        }
                    }
                }

                $author_will_link_view = [];

                foreach ($author_will_link[$id_search] as $k => $v) {
                    $author_will_link_view[$k] = [];
                    foreach ($v as $k1 => $v1) {
                        $author = Author::find()->where(['=','id_author',$v1])->asArray()->one();
                        if(!in_array($author['author'], $author_will_link_view[$k])&& $model->author != $author['author']){
                            $author_will_link_view[$k][] = $author['author'];
                        }
                    }
                }

            }
            else{
                \Yii::$app->getSession()->setFlash('error', 'Tác giả không tồn tại dữ liệu dự báo!');
            }
        }

        return $this->render('index',[
            'model' => $model,
            'search' => $search_year,
            'list_author_link' => $list_author_link,
            'DOTstring' => $DOTstring,
            'listDOTstring' => $listDOTstring,
            'author_will_link_view' => $author_will_link_view,

        ]);
        
    }

    public function actionDemo(){
        return $this->render('demo');
    }

   
    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionNeighboor(){
        $paper_list = Papertemp::find()->where(['>','id_paper',0])->asArray()->all();

        $author_list = Authortemp::find()->where(['>','id_author',0])->asArray()->all();

        $neighboors = [];

        $neighboors_temp = [];

        foreach ($author_list as $key => $value) {
            $neighboor[0] = $value['id_author'];
            $neighboor[1] = [];
            $neighboors[] = $neighboor;
            $neighboors_temp[$value['id_author']] = []; 
        }

        foreach ($paper_list as $key => $value) {
            $id_authors = json_decode($value['authors']);
            
            foreach ($neighboors as $k => $v) {
                if(in_array($v[0], $id_authors)){
                    foreach ($id_authors as $k2 => $v2) {
                        if (!in_array($v2, $neighboors_temp[$v[0]])) {
                            $neighboors_temp[$v[0]][] = $v2; 
                        }

                        if (!in_array($v2, $v[1])&&$v[0]!=$v2) {
                            $v[1][]= $v2;
                        }

                    }

                    $neighboors[$k] = $v;
                }
            }
        }

        $file = fopen("../../text.txt",'w');
        for($i = 0;$i< count($neighboors)-1; $i++) {
            for($j = $i+1;$j< count($neighboors);$j++) {
                $link = 0;
   
                if(in_array($neighboors[$i][0], $neighboors_temp[$neighboors[$j][0]])){
                    $link = 1;
                }

                fprintf($file,"%-5d%-5d%-5d%-10.5f%-10.5f%-5d%-5d\n",$neighboors[$i][0],$neighboors[$j][0],
                    $simCN[$neighboors[$i][0]][$neighboors[$j][0]]['sum_cn'],$simAA[$neighboors[$i][0]][$neighboors[$j][0]],
                    $simJA[$neighboors[$i][0]][$neighboors[$j][0]],$simPA[$neighboors[$i][0]][$neighboors[$j][0]],
                    $link);
            }
        }
    }

    public function actionPercent(){

        $paper_save = []; 

        $author = Authortemp::find()->where(['>','id_author',0])->asArray()->all();

        $paper= Papertemp::find()->where(['>','id_paper',0])->all();

        // $array = [1,2];    

        $paper_year = [];

        foreach ($paper as $key => $value) {
            $paper_year[$value['year']][] = $value;
        }

        $id = [];

        foreach ($author as $k => $v) {
           $id[] = $v['id_author'];
        }

        foreach ($paper_year as $key => $value) {
                foreach ($value as $k => $v) {
                    $id_authors = json_decode($v['authors']);

                    if(count($id_authors) < 2){
                       
                        for($i = 0; $i < $array[0];  $i++){
                            $id_authors[] = (int)$author[$i]['id_author'];
                        }
                        
                        $value[$k]['authors'] = json_encode($id_authors);
                    }

                    $paper_save[] = $v;

                $neighboors = [];
                
                for($i = 0; $i < count($id) -1; $i++) {
                    for($j = $i+1; $j < count($id); $j++) {
                        $neighboors[$id[$j]][$id[$i]] = 0;
                    }
                }

                foreach ($value as $k => $v) {
                    $id_authors = json_decode($v['authors']);

                    foreach ($author as $k1 => $v1) {
                        if(in_array($v1['id_author'], $id_authors)){
                            foreach ($id_authors as $k2 => $v2) {
                                if($v2 < $v1['id_author']){
                                    $neighboors[$v1['id_author']][$v2] = 1;
                                }
                                else if($v2 > $v1['id_author']){
                                    $neighboors[$v2][$v1['id_author']] = 1;
                                }
                            }
                        } 
                    }
                }

                $count = 0;


                foreach ($neighboors as $k => $v) {
                    foreach ($v as $k1 => $v1) {
                        $count+=$v1;
                    }
                }


            } 
        }

        foreach ($paper_save as $key => $value) {
            $value->save();
        }

        echo $link;     
    }

    public function actionWriteLink(){

        $author = Authortemp::find()->where(['>','id_author',0])->asArray()->all();

        $paper= Papertemp::find()->where(['>','id_paper',0])->all();

        $neighboors = [];

        foreach ($paper as $key => $value) {
            $neighboors[$value['year']] = [];
        }

        ksort($neighboors);

        $id = [];

        foreach ($author as $k => $v) {
           $id[] = $v['id_author'];
        }

        foreach ($neighboors as $key => $value) {
            for($i = 0; $i < count($id) -1; $i++) {
                for($j = $i+1; $j < count($id); $j++) {
                    $neighboors[$key][$id[$j]][$id[$i]] = 0;
                }
            }
        }

        foreach ($paper as $key => $value) {
            $id_authors = json_decode($value['authors']);

            foreach ($author as $k => $v) {
                if(in_array($v['id_author'], $id_authors)){
                    foreach ($id_authors as $k1 => $v1) {
                        if($v1 < $v['id_author']){
                            $neighboors[$value['year']][$v['id_author']][$v1] = 1;
                        }
                        else if($v1 > $v['id_author']){
                            $neighboors[$value['year']][$v1][$v['id_author']] = 1;
                        }
                       
                    }
                }

                
            }
        }

        foreach ($neighboors as $k => $v) {
            $file = fopen("../../".$k.".txt",'w');
            foreach ($v as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    fprintf($file,"%-5d%-5d%-5d\n",$k1,$k2,$v2);
                }
                
            }
            fclose($file);
        }

    }

    public function actionWriteFile(){

        $paper= Paper::find()->where(['>','id_paper',0])->asArray()->all();

        $paper_year = [];

        foreach ($paper as $key => $value) {
            $id_authors = json_decode($value['authors']);

            if(count($id_authors)>1){
                $paper_year[$value['year']][] = $value;
            }            
        }

        ksort($paper_year);
        $file = fopen("../../info.txt",'w');
        foreach ($paper_year as $key => $value) {
           foreach ($value as $k => $v) {
                fprintf($file,"%-5d%-5s\n",$key,$v['authors']);
           }
        }
        fclose($file);

    }

}
