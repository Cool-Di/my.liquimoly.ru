<?php

namespace app\models;
use yii\data\ActiveDataProvider;


class NewsSearch extends News
    {

    public function rules()
        {
        return [
            [['news_id'], 'integer'],
            [['news_time', 'news_name', 'news_short_desc', 'news_desc', 'news_img', 'news_showme'], 'safe'],
            ];
        }
    public function search( $params )
        {
        $query = News::find()->orderBy(['news_time'=>SORT_DESC]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => false,
                'pagination' =>
                [ 'pageSize' => 10 ]
            ]);

        $this->load( $params );

        if ( !$this->validate() )
            {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
            }

        // grid filtering conditions
        $query->andFilterWhere( [ 'news_id' => $this->news_id, ] );
        $query->andFilterWhere( [ 'like', 'news_time', $this->news_time ] )
            ->andFilterWhere( [ 'like', 'news_name', $this->news_name ] )
            ->andFilterWhere( [ 'like', 'news_short_desc', $this->news_short_desc ] )
            ->andFilterWhere( [ 'like', 'news_desc', $this->news_desc ] )
            ->andFilterWhere( [ 'like', 'news_img', $this->news_img ] )
            ->andFilterWhere( [ 'like', 'news_showme', $this->news_showme ] );

        return $dataProvider;
        }
    }