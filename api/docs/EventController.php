<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class EventController extends BaseApiController
{
    /**
     * Returns the info about coaching
     *
     * @param int $club_id The clubs ID.
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
     * @available_fields_model Api Event :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#event_id <span> &lt; int &gt; </span> </li>
     * <li>#event_name <span> &lt; string &gt; </span> </li>
     * <li>#event_description <span> &lt; string &gt; </span> </li>
     * <li>#event_level <span> &lt; string &gt; </span> </li>
     * <li>#event_price <span> &lt; float &gt; </span> </li>
     * <li>#event_capacity <span> &lt; int &gt; </span> </li>
     * <li>#event_start <span> &lt; datetime &gt; </span> </li>
     * <li>#event_end <span> &lt; datetime &gt; </span> </li>
     * <li>#event_created_by <span> &lt; int &gt; </span> </li>
     * <li>#event_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#event_updated_at <span> &lt; datetime &gt; </span> </li>
     * <li>#event_updated_by <span> &lt; int &gt; </span> </li>
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
     * @return array
     *
     * {
     *   'event': [
     *      {
     *       'event_id': '1',
     *       'club_id': '2',
     *       'place_id': '7',
     *       'trainers': [
     *       {
     *        'id': 2,
     *        'username': 'andrey',
     *        'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-F',
     *        'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *        'password_reset_token': null,
     *        'email': 'vangov@mail.com',
     *        'api_auth_key': null,
     *        'fb_user_id': '181724282660547',
     *        'fb_token': null,
     *        'push_token': null,
     *        'device_id': null,
     *        'role_id': 4,
     *        'first_name': 'Andrey',
     *        'last_name': 'Vangov',
     *        'address': 'Украина, 01133, г. Киев, ул. Кутузова, 18/7',
     *        'description': null,
     *        'phone': '+370674132612',
     *        'birthday': '2018-11-06',
     *        'avatar': null,
     *        'status': 1,
     *        'created_at': '2018-11-06 11:12:13',
     *        'created_by': 1,
     *        'updated_at': null,
     *        'updated_by': null
     *        'images': [
     *         'https://dev.upfit.com.ua/storage/images/staff/2/1542634553_Green.png',
     *         'https://dev.upfit.com.ua/storage/images/staff/2/1542634554_1472109377.jpg',
     *         'https://dev.upfit.com.ua/storage/images/staff/2/1542634554_1481880075.png',
     *        ],
     *        'avatar': 'https://dev.upfit.com.ua/storage/images/staff/2/logo/1542623981logo_1481880266.png'
     *       },
     *       {
     *        'id': 4,
     *        'username': 'ivan',
     *        'auth_key': 'xYLvDk-HkrBFXeudsUa6ZFPJK5J1Pzuk',
     *        'password_hash': '$2y$13$V1GtkXd0xHXlxdB9eac/4.VMfoAi77er72oy.J5PAFKvOqJbguSSC',
     *        'password_reset_token': null,
     *        'email': 'ivan.p@mail.com',
     *        'api_auth_key': null,
     *        'fb_user_id': '291724282660547',
     *        'fb_token': null,
     *        'push_token': null,
     *        'device_id': null,
     *        'role_id': 5,
     *        'first_name': 'Ivan',
     *        'last_name': 'Petrov',
     *        'address': 'Украина, 02133, г. Харьков, ул. Победы, 28/7',
     *        'description': null,
     *        'phone': '+370514132612',
     *        'birthday': '2018-11-06',
     *        'avatar': null,
     *        'status': 1,
     *        'created_at': '2018-11-06 11:12:13',
     *        'created_by': 1,
     *        'updated_at': null,
     *        'updated_by': null
     *        'images': [
     *         'https://dev.upfit.com.ua/storage/images/staff/4/1542634553_Green.png',
     *         'https://dev.upfit.com.ua/storage/images/staff/4/1542634554_1472109377.jpg',
     *         'https://dev.upfit.com.ua/storage/images/staff/4/1542634554_1481880266.png'
     *        ],
     *        'avatar': 'https://dev.upfit.com.ua/storage/images/staff/4/logo/1542623981logo_1481880266.png'
     *       }
     *       ],
     *       'start': '2018-11-07 10:30:00',
     *       'end': '2018-11-07 12:30:00',
     *       'title': 'Йога',
     *       'description': 'Занятия ведет преподаватель хатха йоги, медицинский психолог Евгения Танковская.',
     *       'level': 'easy',
     *       'price': '120',
     *       'image': 'https://dev.upfit.com.ua/storage/images/coaching/10/1545047359_5760.jpg',
     *       'color': ',
     *       'capacity': '30',
     *       'customersId': [
     *        '1',
     *        '2',
     *        '3',
     *        '4',
     *        '6',
     *        '7'
     *       ]
     *      }
     *   ],
     *   'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/event/index base url
     * @see https://app.upfit.com.ua/api/v1/event/index?conditions[0]=<&conditions[1]=#event_id&conditions[2]=4&sort[#event_id]=ASC&pagination[pageSize]=2 example request
     *
     * @throws 1102 Required params not found
     */
    public function actionIndex(int $club_id, array $conditions = [], array $sort = [], array $pagination = []): array
    {
    }

    /**
     * Add user to event
     *
     * @param int $event_id The events-ID.
     * @param int $user_id The users-ID.
     *
     * @return array
     * {
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/event/add-customer base url
     * @see https://app.upfit.com.ua/api/v1/event/add-customer?event_id=1&user_id=4 example request
     *
     * @throws 1101 Model not found
     * @throws 1102 Required params not found
     * @throws 1201 Event capacity limit
     * @throws 1202 User event subscribe exist
     * @throws 1206 Customer not in club
     */
    public function actionAddCustomer(int $event_id, int $user_id): array
    {
    }

    /**
     * Add user to event
     *
     * @param int $event_id The events-ID.
     *
     * @return array
     * {
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/event/leave-event base url
     * @see https://app.upfit.com.ua/api/v1/event/leave-event?event_id=1 example request
     *
     * @throws 1101 Model not found
     * @throws 1102 Required params not found
     */
    public function actionLeaveEvent(int $event_id): array
    {
    }
}