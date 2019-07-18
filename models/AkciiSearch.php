<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Akcii;

/**
 * AkciiSearch represents the model behind the search form about `app\models\Akcii`.
 */
class AkciiSearch extends Akcii
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['akcii_id', 'akcii_type', 'akcii_showme'], 'integer'],
            [['akcii_time', 'akcii_name', 'akcii_deistvie', 'akcii_short_desc', 'akcii_desc', 'akcii_img'], 'safe'],
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
        $query = Akcii::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'akcii_time' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'akcii_id' => $this->akcii_id,
        ]);

        $query->andFilterWhere(['like', 'akcii_time', $this->akcii_time])
            ->andFilterWhere(['like', 'akcii_name', $this->akcii_name])
            ->andFilterWhere(['like', 'akcii_deistvie', $this->akcii_deistvie])
            ->andFilterWhere(['like', 'akcii_short_desc', $this->akcii_short_desc])
            ->andFilterWhere(['like', 'akcii_desc', $this->akcii_desc])
            ->andFilterWhere(['like', 'akcii_img', $this->akcii_img])
            ->andFilterWhere(['=', 'akcii_type', $this->akcii_type])
            ->andFilterWhere(['=', 'akcii_showme', $this->akcii_showme]);

        return $dataProvider;
    }
}
