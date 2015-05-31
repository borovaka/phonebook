<?php
namespace frontend\modules\phonebook\models;

use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;
use yii\widgets\ActiveForm;

class PhoneForm extends Model
{

    public $id;
    public $first_name;
    public $last_name;
    public $contact;
    public $phone_order;
    public $created_at;
    public $updated_at;
    public $phones = [];
    public $phonetype;


    public function __construct()
    {
        parent::__construct();

        $this->contact = new Contact();
        $this->phones = [new Phone];
        $this->phonetype = new PhoneType();
    }

    public function rules()
    {

        return $this->contact->rules();
        /*return [
            [['first_name'], 'required'],
            [['phone'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'min' => 3,'max' => 255],
            [['id_type', 'phone'], 'integer'],
            [['id_type'], 'required'],
        ];*/
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'Име',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
            'id_type' => 'Тип',
        ];
    }


    public function createPhonebook($post)
    {


        $this->contact->load($post, 'PhoneForm');
        $this->phones = DynamicFormModel::createMultiple(Phone::classname());
        DynamicFormModel::loadMultiple($this->phones, $post, 'Phone');

        // ajax validation
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ArrayHelper::merge(
                ActiveForm::validateMultiple($this->phones),
                ActiveForm::validate($this->contact)
            );
        }

        // validate all models
        $valid = $this->contact->validate();
        $valid = DynamicFormModel::validateMultiple($this->phones) && $valid;

        if ($valid) {

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $this->contact->save(false)) {
                    foreach ($this->phones as $key => $modelPhones) {
                        $modelPhones->id_contact = $this->contact->id;
                        if (!($flag = $modelPhones->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return true;

                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }

    }

    public function updatePhonebook($post)
    {

        $this->phone_order = explode(',', $post['phone-order']);
        $this->contact->load($post, 'PhoneForm');
        $oldIDs = ArrayHelper::map($this->phones, 'id', 'id');
        $modelsPhones = DynamicFormModel::createMultiple(Phone::classname(), $this->phones);
        DynamicFormModel::loadMultiple($modelsPhones, $post, 'Phone');
        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPhones, 'id', 'id')));

        // ajax validation
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ArrayHelper::merge(
                ActiveForm::validateMultiple($modelsPhones),
                ActiveForm::validate($this->contact)
            );
        }

        // validate all models
        $valid = $this->contact->validate();
        $valid = DynamicFormModel::validateMultiple($modelsPhones) && $valid;

        if ($valid) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $this->contact->save(false)) {
                    if (!empty($deletedIDs)) {
                        Phone::deleteAll(['id' => $deletedIDs]);
                    }
                    foreach ($modelsPhones as $key => $modelPhones) {
                        $modelPhones->id_contact = $this->contact->id;
                        if (!($flag = $modelPhones->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return true;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }

    }


    public function loadData($id)
    {
        $this->contact = Contact::findOne($id);
        foreach ($this->contact as $key => $val) {
            $this->$key = $val;
        }

        $this->phones = $this->contact->phones;
    }

    public function getPhoneElements($form, $model)
    {

        $items = [];
        foreach ($model->phones as $i => $modelPhones) {

            $wgBody = <<< WG_BODY
                     <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">

                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i
                                        class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i
                                        class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
WG_BODY;
            // necessary for update action.
            if (!$modelPhones->isNewRecord) {
                $wgBody .= Html::activeHiddenInput($modelPhones, "[{$i}]id");
            }
            $wgBody .= <<< WG_BODY
                            <div class="row">
                                <div class="col-sm-5">
                                     {$form->field($modelPhones, "[{$i}]phone")->textInput(['maxlength' => true])}
                                </div>
                                <div class="col-sm-2">
                                    {$form->field($modelPhones, "[{$i}]id_type")->dropDownList(ArrayHelper::map($model->phoneTypes, 'id', 'name'))}
                                </div>

                            </div>

                        </div>
                    </div>
WG_BODY;
            $item['content'] = $wgBody;
            $item['options'] = ['class' =>'sortable-item'];
            $items[] = $item;
        }
        return $items;
    }


    public function getModelsPhones()
    {
        return $this->phones = (empty($this->phones)) ? [new Phone] : $this->phones;
    }

    public function getPhoneTypes()
    {
        return PhoneType::find()->all();
    }


}