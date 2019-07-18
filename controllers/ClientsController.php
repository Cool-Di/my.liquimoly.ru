<?php

namespace app\controllers;

use Yii;
use app\models\Contractor;
use yii\data\ArrayDataProvider;

class ClientsController extends \yii\web\Controller
{
    public function my_filter($item){
		$holidngfilter = Yii::$app->request->getQueryParam('filterholidng', '');
		$codefilter = Yii::$app->request->getQueryParam('filtercode', '');
		$namefilter = Yii::$app->request->getQueryParam('filternamel', '');
		$emailfilter = Yii::$app->request->getQueryParam('filteremail', '');

    	if (mb_strlen($holidngfilter) > 0) {
        	if (mb_strpos($item['Holding'], $holidngfilter) !== false) {
            	return true;
	        } else {
    	        return false;
    	    }
    	} else if (mb_strlen($codefilter) > 0) {
        	if (mb_strpos($item['Code'], $codefilter) !== false) {
            	return true;
	        } else {
    	        return false;
    	    }
    	} else if (mb_strlen($namefilter) > 0) {
        	if (mb_strpos($item['name'], $namefilter) !== false) {
            	return true;
	        } else {
    	        return false;
        	}
    	} else if (mb_strlen($emailfilter) > 0) {
        	if (mb_strpos($item['email'], $emailfilter) !== false) {
            	return true;
	        } else {
    	        return false;
        	}
	    } else {
        	return true;
        }
    }

	public function actionChangepasswd($user_id)
    {
	    $user = Yii::$app->user->identity;

		$n_passwd = Yii::$app->security->generateRandomString(8);
		$password = Yii::$app->security->generatePasswordHash($n_passwd, 4);

		$check = Contractor::check_manager_access($user_id, $user->email);
        if ($check > 0){
			$command = Yii::$app->db->createCommand()->update('user', ['password' => $password], '	id = :code');
			$command->bindParam(':code', $user_id);
			$command->execute();
	    	return $this->render('changepasswd', ['new_passwd' => $n_passwd]);
		} else return $this->redirect(['clients']);
	}

    public function actionIndex()
    {
	    $role = key( Yii::$app->authManager->getRolesByUser( Yii::$app->user->id ) );
        $user = Yii::$app->user->identity;
	    if ($role == 'money_user' || $role == 'root'){
	    	$clients = Contractor::getClientsbymanager($user->email);
	  	} elseif ($role == 'holding_user'){
			$clients = Contractor::getClientsbyholding($user->username);
	  	}

    	$filteredresultData = array_filter($clients, [$this, 'my_filter']);

		$codefilter = Yii::$app->request->getQueryParam('filtercode', '');
		$namefilter = Yii::$app->request->getQueryParam('filternamel', '');
		$emailfilter = Yii::$app->request->getQueryParam('filteremail', '');

        $searchModel = ['Code' => $codefilter, 'name' => $namefilter, 'email' => $emailfilter];

		$dataProvider = new ArrayDataProvider([
		    'allModels' => $filteredresultData,
		    'pagination' => [
		    	'pageSize' => 20
		    ],
		    'sort' => [
        		'attributes' => ['Code', 'Status', 'name'],
		    ],
		    'key' => 'id',
		]);

    	return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

}
