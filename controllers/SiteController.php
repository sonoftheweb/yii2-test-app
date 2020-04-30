<?php

namespace app\controllers;

use app\helpers\ApplicationHelpers;
use app\models\Countries;
use app\models\Customer;
use app\models\Order;
use app\models\Status;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
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
        list($customer, $order, $session) = [new Customer, new Order, Yii::$app->session];

        // if this is a post request and models are validated insert into db
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $customer->load($data);
            $order->load($data);

            if ($customer->validate() && $order->validate()) {
                // create or update customer
                $customer = Customer::newOrUpdate($customer->attributes);

                // create new order
                $order->link('customer', $customer);
                $session->setFlash('orders', 'Order added successfully.');
            } else {
                $session->setFlash('orders', 'Unable to add order.');
            }
            // slap in a redirect here so refresh will not resend the form data again
        }

        // instantiate new orders
        list($order, $customer, $orders, $countries, $statuses) = [
            new Order,
            new Customer,
            Order::find()->all(),
            Countries::listModel('id', ['country_name']),
            Status::listModel('id', ['name'])
        ];

        /*print('<pre>');
        print_r($orders);
        print('</pre>');*/

        // normally this would come from a db table, but since I am using
        $orderType = ApplicationHelpers::orderTypes();

        return $this->render('index', [
            'customer' => $customer,
            'order' => $order,
            'order_type' => $orderType,
            'countries' => $countries,
            'statuses' => $statuses
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
