<?php
namespace app\controllers;

use app\src\entities\attribute\Attribute;
use app\src\entities\attribute\AttributeClubSearch;
use app\src\library\BaseController;

class AttributeClubController extends BaseController
{
    public function actionGetCreateForm()
    {
        $attributeId = (int) $this->getParam('attributeId');
        $clubId = (int) $this->getParam('clubId');

        if (!$attributeId) {
            $attribute = new Attribute();
            $attribute->parent_id = $clubId;
        } else {
            $attribute = Attribute::findOne(['id' => $attributeId]);
        }

        $form = $this->renderAjax('/club/partial/attribute_form', [
            'attribute' => $attribute,
            'names' => Attribute::getAvailableAttributesNames(),
            'types' => Attribute::getTypeLabels(),
            'renderAjax' => true,
        ]);

        return $this->responseJson([
            'html' => $form,
        ]);
    }

    public function actionAddAttribute()
    {
        $attributes = $this->getParams();
        $clubId = (int) $this->getParam('clubId');

            $attribute = new Attribute();
            $attribute->load($attributes);
            Attribute::deleteAll([
                'id' => $attribute->id,
                'parent_id' => $clubId
            ]);
            $attribute->parent_id = $clubId;
            $attribute->save();

        $attributeSearch = new AttributeClubSearch($clubId);

        $attributesList = $this->renderAjax('/club/partial/attributes-list', [
            'attributesDataProvider' => $attributeSearch->getSearchDataProvider(),
        ]);

        return $this->responseJson([
            'html' => $attributesList,
        ]);
    }

    public function actionDelete()
    {
        $clubId = (int) $this->getParam('clubId');
        $attributeId = (int) $this->getParam('attributeId');

        Attribute::deleteAll([
            'id' => $attributeId,
            'parent_id' => $clubId
        ]);

        $attributesSearch = new AttributeClubSearch($clubId);

        $attributesList = $this->renderAjax('/club/partial/attributes-list', [
            'attributesDataProvider' => $attributesSearch->getSearchDataProvider(),
        ]);

        return $this->responseJson([
            'html' => $attributesList,
        ]);
    }
}