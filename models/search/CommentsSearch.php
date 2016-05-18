<?php

namespace rangeweb\comments\models\search;

use rangeweb\comments\models\Comments;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `rangeweb\comments\models\Comments`.
 */
class CommentsSearch extends Comments
{
    public $orderBy = 'date_create DESC';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'user_id', 'object_id', 'parent_id'], 'integer'],
            [['text', 'note', 'object', 'date_create', 'date_update'], 'safe'],
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
    public function search($params, $addParams =[])
    {
        $pagination = ['pageSize' => 10];
        if ($addParams['limit']) {
            $pagination = false;
        }
        
        $query = Comments::find();

        $query->orderBy($this->orderBy);

        if (isset($addParams['limit'])) {
            $query->limit($addParams['limit']);
        }
        
        if (isset($addParams['pagination'])) {
            $pagination = $addParams['pagination'];
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'object' => $this->object,
            'object_id' => $this->object_id,
            'parent_id' => $this->parent_id,
            'date_create' => $this->date_create,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }

}
