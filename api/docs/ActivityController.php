<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class ActivityController extends BaseApiController
{
    /**
     * Returns activity list
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
     * @available_fields_model Api Activity :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#activity_id <span> &lt; int &gt; </span> </li>
     * <li>#activity_name <span> &lt; string &gt; </span> </li>
     * <li>#activity_description <span> &lt; string &gt; </span> </li>
     * <li>#activity_price <span> &lt; float &gt; </span> </li>
     * <li>#activity_capacity <span> &lt; int &gt; </span> </li>
     * <li>#activity_start <span> &lt; datetime &gt; </span> </li>
     * <li>#activity_end <span> &lt; datetime &gt; </span> </li>
     * <li>#activity_created_by <span> &lt; datetime &gt; </span> </li>
     * <li>#activity_created_at <span> &lt; int &gt; </span> </li>
     * </ul></small>
     * </br>
     *
     * @available_fields_other_model Api Club
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
     *  'activity': [
     *  {
     *   'id': '6',
     *   'club_id': '2',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'price': '120',
     *   'capacity': '30',
     *   'start': '07-09-2018',
     *   'end': '24-09-2018',
     *   'created_at': '24-09-2018'
     *   'image': 'https://dev.upfit.com.ua/storage/images/activity/6/1545041615_5760.jpg',
     *   'organizers': [
     *   {
     *    'id': 1,
     *    'username': 'admin',
     *    'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
     *    'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *    'password_reset_token': null,
     *    'email': 'admin@mail.com',
     *    'api_auth_key': 'smaT-Y5U1DuHL56wRRvz_xMlzBMIJodv',
     *    'fb_user_id': '426581574530111',
     *    'fb_token': null,
     *    'push_token': null,
     *    'device_id': null,
     *    'role_id': 1,
     *    'first_name': 'Henry',
     *    'last_name': 'Saimon',
     *    'address': 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
     *    'description': ',
     *    'phone': '+370682132612',
     *    'birthday': '2018-11-06',
     *    'avatar': null,
     *    'status': 1,
     *    'created_at': '2018-11-06 11:12:13',
     *    'created_by': 1,
     *    'updated_at': '2018-11-08 08:41:27',
     *    'updated_by': 1
     *   },
     *  },
     *  {
     *   'id': '8',
     *   'club_id': '3',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'price': '300',
     *   'capacity': '25',
     *   'start': '05-09-2018',
     *   'end': '24-09-2018',
     *   'created_at': '24-09-2018'
     *   'image': '"https://dev.upfit.com.ua/storage/images/activity/8/1545041615_5760.jpg"',
     *   'organizers': [
     *   {
     *    'id': 1,
     *    'username': 'admin',
     *    'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
     *    'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *    'password_reset_token': null,
     *    'email': 'admin@mail.com',
     *    'api_auth_key': 'smaT-Y5U1DuHL56wRRvz_xMlzBMIJodv',
     *    'fb_user_id': '426581574530111',
     *    'fb_token': null,
     *    'push_token': null,
     *    'device_id': null,
     *    'role_id': 1,
     *    'first_name': 'Henry',
     *    'last_name': 'Saimon',
     *    'address': 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
     *    'description': ',
     *    'phone': '+370682132612',
     *    'birthday': '2018-11-06',
     *    'avatar': null,
     *    'status': 1,
     *    'created_at': '2018-11-06 11:12:13',
     *    'created_by': 1,
     *    'updated_at': '2018-11-08 08:41:27',
     *    'updated_by': 1
     *   },
     *  }
     *  ],
     *  'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/activity/index base url
     * @see https://app.upfit.com.ua/api/v1/activity/index?language=ru&conditions[0]=>&conditions[1]=#activity_id&conditions[2]=4&sort[#activity_id]=ASC&pagination[pageSize]=2 example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }
}