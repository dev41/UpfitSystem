<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class CoachingController extends BaseApiController
{
    /**
     * Returns the info about coaching
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
     * @available_fields_model Api Coaching :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#coaching_id <span> &lt; int &gt; </span> </li>
     * <li>#coaching_parent_id <span> &lt; int &gt; </span> </li>
     * <li>#coaching_name <span> &lt; string &gt; </span> </li>
     * <li>#coaching_description <span> &lt; string &gt; </span> </li>
     * <li>#coaching_level <span> &lt; string &gt; </span> </li>
     * <li>#coaching_price <span> &lt; float &gt; </span> </li>
     * <li>#coaching_capacity <span> &lt; int &gt; </span> </li>
     * <li>#coaching_color <span> &lt; string &gt; </span> </li>
     * <li>#coaching_created_by <span> &lt; int &gt; </span> </li>
     * <li>#coaching_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#coaching_updated_at <span> &lt; datetime &gt; </span> </li>
     * <li>#coaching_updated_by <span> &lt; int &gt; </span> </li>
     * </ul></small>
     * </br>
     *
     * @available_fields_other_model Api Club, Api Subplace
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
     *   'coaching': [
     *      {
     *       'coaching_id': '1',
     *       'club_id': '2',
     *       'place_id': '7',
     *       'trainers': [],
     *       'name': 'Йога',
     *       'description': 'Занятия ведет преподаватель хатха йоги, медицинский психолог Евгения Танковская.',
     *       'level': 'easy',
     *       'price': '120',
     *       'image': 'https://dev.upfit.com.ua/storage/images/coaching/1/1545047359_5760.jpg',
     *       'color': ',
     *       'capacity': '30'
     *      },
     *      {
     *       'coaching_id': '2',
     *       'club_id': '3',
     *       'place_id': '5',
     *       'trainers': [],
     *       'name': 'Boxaerobics',
     *       'description': 'Отрабатывая на мешках технику удара, нужно двигаться не только правильно, но и красиво! Это тяжело, но очень круто – выходишь как победитель после настоящего боя',
     *       'level': 'hard',
     *       'price': '130',
     *       'image': https://dev.upfit.com.ua/storage/images/coaching/2/1545047359_5760.jpg,
     *       'color': null,
     *       'capacity': '15'
     *      }
     *   ],
     *   'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/coaching/index base url
     * @see https://app.upfit.com.ua/api/v1/coaching/index?language=ru&conditions[0]=<&conditions[1]=#coaching_id&conditions[2]=4&sort[#coaching_id]=ASC&pagination[pageSize]=2 example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }
}