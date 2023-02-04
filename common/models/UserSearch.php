<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserId', 'ParentId', 'resetPassword', 'IsActive'], 'integer'],
            [['DowUserId', 'Username', 'Fullname', 'AuthKey', 'PasswordHash', 'PasswordResetToken', 'Email', 'CreatedAt', 'UpdatedAt'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'UserId' => $this->UserId,
            'ParentId' => $this->ParentId,
            'CreatedAt' => $this->CreatedAt,
            'UpdatedAt' => $this->UpdatedAt,
            'resetPassword' => $this->resetPassword,
            'IsActive' => $this->IsActive,
        ]);

        $query->andFilterWhere(['like', 'DowUserId', $this->DowUserId])
            ->andFilterWhere(['like', 'Username', $this->Username])
            ->andFilterWhere(['like', 'Fullname', $this->Fullname])
            ->andFilterWhere(['like', 'AuthKey', $this->AuthKey])
            ->andFilterWhere(['like', 'PasswordHash', $this->PasswordHash])
            ->andFilterWhere(['like', 'PasswordResetToken', $this->PasswordResetToken])
            ->andFilterWhere(['like', 'Email', $this->Email]);

        return $dataProvider;
    }
}
