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

        $id_author_data=[];
        
        if ($model->load(Yii::$app->request->post())) {
               
            $file1 = fopen("../../authors.txt",'r');

            while(!feof($file1))
            {
                $id_author_data = explode(", ",fgets($file1));
            }

            fclose($file1);

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

                $search = $command->queryAll();//search những bài báo có id của search

                foreach ($search as $key => $value) {
                    $authors = json_decode($value['authors']);//danh sách tác giả trong 1 bài báo

                    $authors_change = [];//lưu lại tác giả chỉ có trong 10 tác giả trên
                    foreach ($authors as $k => $v) {
                        if(in_array($v, $id_author_data)){
                            $authors_change[] = $v;
                        }
                    }
                    //reset lại authors của bài báo về mảng chỉ có id của tác giả trong 10 tác giả trên
                    $search[$key]['authors'] = json_encode($authors_change);

                }

                for($i=1992;$i<2002;$i++){
                    $listDOTstring[$i][0] = 'dinetwork {';
                    $listDOTstring[$i][1] = [];
                }

                foreach ($search as $key => $value) {
                    $authors = json_decode($value['authors']);

                    $string = '';
                    for($j=0;$j < count($authors);$j++){//duyệt từng tác giả
                        //tìm tác giả trong bảng author
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
                        
                        //ktra tác giả khác với tác giả nhập vào=>là tác giả lk với tác giả nhập vào
                        if($model->author!=$author['author'] && !in_array($author['author'], $list_author_link)){
                            $list_author_link[] = $author['author'];//mảng lưu các tác giả liên kết với tác giả nhập vào
                        }


                    }

                    $search[$key]['authors_view'] = $string;

                    $search_year[$value['year']][] = $search[$key];

                    $string = '';

                }

                ksort($search_year);//sắp xếp năm theo thứ tự tăng dần

                for($i=1992;$i<2002;$i++){
                    $listDOTstring[$i][0] .= '}';
                }

                foreach ($list_author_link as $key) {
                    $DOTstring = $DOTstring.'|'.$model->author.'| '.'-> |'.$key.'|;';
                }

                $DOTstring = $DOTstring.'}';

                $file = fopen("../../data/forecast.txt",'r');
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

    public function actionWriteFile(){
        //$author = Author::find()->where(['>','id_author',0])->asArray()->all();

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
