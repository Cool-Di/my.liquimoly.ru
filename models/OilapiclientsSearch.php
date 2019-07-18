<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Oilapiclients;

/**
 * OilapiclientsSearch represents the model behind the search form about `app\models\Oilapiclients`.
 */
class OilapiclientsSearch extends Oilapiclients
{
	public $userpkk;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'limit'], 'integer'],
            [['token', 'name', 'host', 'userpkk', 'ip', 'begin_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Oilapiclients::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->setSort([
        'attributes' => [
	            'id',
	            'userpkk' => [
	                'asc' => ['user.username' => SORT_ASC],
	                'desc' => ['user.username' => SORT_DESC],
            	    'default' => SORT_ASC
        	    ],
    	        'name' => [
	                'asc' => ['name' => SORT_ASC],
	                'desc' => ['name' => SORT_DESC],
            	],
            	'host' => [
	                'asc' => ['host' => SORT_ASC],
	                'desc' => ['host' => SORT_DESC],
            	],
            	'limit' => [
	                'asc' => ['limit' => SORT_ASC],
	                'desc' => ['limit' => SORT_DESC],
            	],
        	]
		]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do  not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

    	$query->joinWith(['userlist' => function ($q) {
        	$q->where('user.username LIKE "%' . $this->userpkk . '%"');
	    }]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'limit' => $this->limit,
            'begin_time' => $this->begin_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
