<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class SubplaceController extends BaseApiController
{
    /**
     * Returns the info about places clubs
     *
     * @param array $conditions
     * Condition params:
     *
     * <b>conditions[0]</b> = [operator]
     *
     * <b>conditions[1]</b> = [field] (with first symbol #)
     *
     * <b>conditions[2]</b> = [value]
     *
     * <small><i>available operators: '>' '>=' '<' '<=' '=' '<>' 'like' 'in' 'not in'</i></small>
     *
     * @available_fields_model Api Subplace :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#place_id <span> &lt; int &gt; </span> </li>
     * <li>#place_parent_id <span> &lt; int &gt; </span> </li>
     * <li>#place_name <span> &lt; string &gt; </span> </li>
     * <li>#place_description <span> &lt; string &gt; </span> </li>
     * <li>#place_address <span> &lt; string &gt; </span> </li>
     * <li>#place_active <span> &lt; int &gt; </span> </li>
     * <li>#place_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#place_created_by <span> &lt; int &gt; </span> </li>
     * <li>#place_updated_at <span> &lt; datetime &gt; </span> </li>
     * <li>#place_updated_by <span> &lt; int &gt; </span> </li>
     * </ul></small>
     * </br>
     *
     *
     * @param array $sort <b>sort[#field]</b> = [type]
     *
     * <small><i>available types: 'ASC' 'DESC'</i></small>
     *
     * @param array $pagination <b>pagination[type]</b> = [value]
     *
     * @param string $language
     * Params:
     * <b>language</b> = 'ru' or 'en' default uk
     *
     * @return array
     *
     * {
     *   'places': [
     *     {
     *      'id': 7,
     *      'parent_id': 2,
     *      'type': 2,
     *      'name': 'Зал Йоги',
     *      'description': 'Имеются коврики, пледы и удобные подушечки для медитации. Также в вашем распоряжении позитивная атмосфера кулба FIT HAUS.',
     *      'active': 1,
     *      'city': 'Москва',
     *      'address': 'г. Киев, ул. Дмитриевская,39',
     *      'lat': '55.758',
     *      'lng': '37.6081',
     *      'images': [
     *      'https://dev.upfit.com.ua/storage/images/subplace/4/1533538676_pool.jpg'
     *      ]
     *     },
     *     {
     *      'id': 6,
     *      'parent_id': 3,
     *      'type': 2,
     *      'name': 'Кардио Зал',
     *      'description': 'Отдельный зал, где расположены более 30 кардиотренажеров: это беговые дорожки Star Track, степперы, лыжи и веломашины Stair Master...',
     *      'active': 1,
     *      'city': 'Москва',
     *      'address': 'г. Киев, ул. Дмитриевская,39',
     *      'lat': '55.758',
     *      'lng': '37.6081',
     *      'images': [
     *      'https://dev.upfit.com.ua/storage/images/subplace/4/1533538676_pool.jpg'
     *      ]
     *     }
     *   ],
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/subplace/index base url
     * @see https://app.upfit.com.ua/api/v1/subplace/index?language=ru&conditions[0]=>&conditions[1]=#place_id&conditions[2]=4&sort[#place_id]=ASC&pagination[pageSize]=2 example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }
}