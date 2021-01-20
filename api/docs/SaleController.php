<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class SaleController extends BaseApiController
{
    /**
     * Returns sale list
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
     * @available_fields_model Api Sale :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#sale_id <span> &lt; int &gt; </span> </li>
     * <li>#sale_name <span> &lt; string &gt; </span> </li>
     * <li>#sale_description <span> &lt; string &gt; </span> </li>
     * <li>#sale_start <span> &lt; datetime &gt; </span> </li>
     * <li>#sale_end <span> &lt; datetime &gt; </span> </li>
     * <li>#sale_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#sale_created_by <span> &lt; int &gt; </span> </li>
     * </ul></small>
     * </br>
     *
     * @available_fields_other_model Api Club
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
     *  'sale': [
     *  {
     *   'id': '9',
     *   'club_id': '3',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'start': '07-09-2018',
     *   'end': '24-09-2018',
     *   'created_at': '24-09-2018'
     *   'image': 'https://dev.upfit.com.ua/storage/images/sale/9/1545041001_5760.jpg'
     *  },
     *  {
     *   'id': '8',
     *   'club_id': '3',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'start': '05-09-2018',
     *   'end': '24-09-2018',
     *   'created_at': '24-09-2018'
     *   'image': 'https://dev.upfit.com.ua/storage/images/sale/8/1545041001_5760.jpg'
     *  }
     *  ],
     *  'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/sale/index base url
     * @see https://app.upfit.com.ua/api/v1/sale/index?language=ruconditions[0]=>&conditions[1]=#sale_id&conditions[2]=4&sort[#sale_id]=ASC&pagination[pageSize]=2 example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }
}