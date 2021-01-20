<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class ClubController extends BaseApiController
{
    /**
     * Returns clubs list
     *
     * @param array $conditions
     * Condition params:
     *
     * <b>conditions[0]</b> = [operator]
     *
     * <b>conditions[1]</b> = [#field]
     *
     * <b>conditions[2]</b> = [value]
     *
     * <small><i>available operators: '>' '>=' '<' '<=' '=' '<>' 'like' 'in' 'not in'</i></small>
     *
     * @available_fields_model Api Club :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#club_id <span> &lt; int &gt; </span> </li>
     * <li>#club_name <span> &lt; string &gt; </span> </li>
     * <li>#club_description <span> &lt; string &gt; </span> </li>
     * <li>#club_address <span> &lt; string &gt; </span> </li>
     * <li>#club_country <span> &lt; string &gt; </span> </li>
     * <li>#club_city <span> &lt; string &gt; </span> </li>
     * <li>#club_lat <span> &lt; float &gt; </span> </li>
     * <li>#club_lng <span> &lt; float &gt; </span> </li>
     * <li>#club_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#club_created_by <span> &lt; int &gt; </span> </li>
     * <li>#club_updated_at <span> &lt; datetime &gt; </span> </li>
     * <li>#club_updated_by <span> &lt; int &gt; </span> </li>
     * </ul></small>
     * </br>
     *
     * @available_fields_other_model Api Coaching
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
     *   'clubs': [
     *     {
     *      'id': '1',
     *      'name': 'Super Сlub',
     *      'city': 'Запоріжжя',
     *      'address': 'проспект Соборний 158',
     *      'lat': '47.8374',
     *      'lng': '35.143',
     *      'logo': 'https://dev.upfit.com.ua/storage/images/club/1/logo/1541577537logo_5760.jpg'
     *     },
     *     {
     *      'id': '2',
     *      'name': 'FIT HAUS',
     *      'city': 'Полтава',
     *      'address': 'вулиця Гоголя 21',
     *      'lat': '49.5855',
     *      'lng': '34.5568',
     *      'logo': 'https://dev.upfit.com.ua/storage/images/club/2/logo/1541577537logo_5760.jpg'
     *     },
     *     {
     *      'id': '3',
     *      'name': 'Kiev Sport',
     *      'city': 'Киев',
     *      'address': 'Бульвар Дружбы Народов 5,',
     *      'lat': '49.5855',
     *      'lng': '34.5568',
     *      'logo': 'https://dev.upfit.com.ua/storage/images/club/3/logo/1541577537logo_5760.jpg'
     *     }
     *   ],
     *   'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/club/index base url
     * @see https://app.upfit.com.ua/api/v1/club/index?conditions[0]=<&conditions[1]=#club_id&conditions[2]=4&sort[#club_id]=DESC&pagination[pageSize]=3&language=ru example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }

    /**
     * Returns the info about club
     *
     * @param int $club_id
     *
     * @param string $language
     * Params:
     * <b>language</b> = 'ru' or 'en'
     *
     * @return array
     *
     * {
     *   'club': [
     *     {
     *      'id': '1',
     *      'name': 'Super Сlub',
     *      'type': '1',
     *      'city': 'Запоріжжя',
     *      'address': 'проспект Соборний 158',
     *      'images': [
     *       'https://dev.upfit.com.ua/storage/images/club/1/1541581743_100x500.png',
     *       'https://dev.upfit.com.ua/storage/images/club/1/1541581743_kievclublogo.png'
     *      ],
     *      'logo': 'https://dev.upfit.com.ua/storage/images/club/1/logo/1541577537logo_5760.jpg',
     *      'lat': '47.8374',
     *      'lng': '35.143',
     *      'description': 'Этот клуб видит только суперадмин',
     *      'created_at': '31-08-2018'
     *      }
     *   ],
     *   'staff': [
     *     {
     *      'club_name': 'FIT HAUS',
     *      'username': 'andrey',
     *      'positions': 'trainer',
     *      'club_id': '2',
     *      'user_id': '2'
     *      'first_name': 'Andrey',
     *      'last_name': 'Saimon',
     *      'description': ',
     *      'images': [
     *       'https://dev.upfit.com.ua/storage/images/staff/1/1541581743_100x500.png',
     *       'https://dev.upfit.com.ua/storage/images/staff/1/1541581743_kievclublogo.png'
     *      ],
     *       'avatar': 'https://dev.upfit.com.ua/storage/images/staff/1/logo/1541581743logo_push_icon.jpg'
     *      },
     *     {
     *      'club_name': 'FIT HAUS',
     *      'username': 'vasiliy',
     *      'positions': 'admin',
     *      'club_id': '2',
     *      'user_id': '3'
     *      'first_name': 'Vasiliy',
     *      'last_name': 'Saimon',
     *      'description': ',
     *      'images': [
     *       'https://dev.upfit.com.ua/storage/images/staff/1/1541581743_100x500.png',
     *       'https://dev.upfit.com.ua/storage/images/staff/1/1541581743_kievclublogo.png'
     *      ],
     *      'avatar': 'https://dev.upfit.com.ua/storage/images/staff/1/logo/1541581743logo_push_icon.jpg'
     *      }
     *   ],
     *   'places': [
     *     {
     *      'id': '4',
     *      'club_id': '1',
     *      'name': 'Бассейн',
     *      'type': '3',
     *      'city': null,
     *      'address': 'г. Киев, Бульвар Дружбы Народов 5,',
     *      'images': [
     *       'https://dev.upfit.com.ua/storage/images/subplace/1/1541581743_100x500.png',
     *       'https://dev.upfit.com.ua/storage/images/subplace/1/1541581743_kievclublogo.png'
     *      ],
     *      'description': 'Да, он небольшой. А разве может райский уголок быть большим? Конечно, киевское солнце не сравнить с калифорнийским, но зеленая трава, синяя вода, белый шезлонг и легкая музыка заставляют забыть об этом. А еще мы здесь круглый год тренируемся.',
     *      'created_at': '31-08-2018'
     *      },
     *     {
     *      'id': '8',
     *      'club_id': '1',
     *      'name': 'Бассейн',
     *      'type': '3',
     *      'city': null,
     *      'address': 'г. Киев, Бульвар Дружбы Народов 5,',
     *      'images': [
     *       'https://dev.upfit.com.ua/storage/images/subplace/1/1541581743_100x500.png',
     *       'https://dev.upfit.com.ua/storage/images/subplace/1/1541581743_kievclublogo.png'
     *      ],
     *      'description': 'Да, он небольшой. А разве может райский уголок быть большим? Конечно, киевское солнце не сравнить с калифорнийским, но зеленая трава, синяя вода, белый шезлонг и легкая музыка заставляют забыть об этом. А еще мы здесь круглый год тренируемся.',
     *      'created_at': '03-09-2018'
     *      }
     *   ],
     *   'coaching': [
     *     {
     *      'capacity': 30,
     *      'coaching_level_id': 1,
     *      'id': 1,
     *      'parent_id': null,
     *      'name': 'Йога',
     *      'description': 'Занятия ведет преподаватель хатха йоги, медицинский психолог Евгения Танковская.',
     *      'price': 120,
     *      'color': null,
     *      'created_at': '2018-08-31 07:35:22',
     *      'created_by': 1,
     *      'updated_at': null,
     *      'updated_by': null
     *      },
     *     {
     *      'capacity': 15,
     *      'coaching_level_id': 3,
     *      'id': 2,
     *      'parent_id': null,
     *      'name': 'Boxaerobics',
     *      'description': 'Отрабатывая на мешках технику удара, нужно двигаться не только правильно, но и красиво! Это тяжело, но очень круто – выходишь как победитель после настоящего боя',
     *      'price': 130,
     *      'color': null,
     *      'created_at': '2018-08-31 07:35:22',
     *      'created_by': 1,
     *      'updated_at': null,
     *      'updated_by': null
     *      }
     *   ],
     *   'events': [
     *     {
     *      'event_id': '1',
     *      'parent_id': '2',
     *      'trainers': [
     *      {
     *       'id': 2,
     *       'username': 'andrey',
     *       'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-F',
     *       'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *       'password_reset_token': null,
     *       'email': 'vangov@mail.com',
     *       'api_auth_key': null,
     *       'fb_user_id': '181724282660547',
     *       'fb_token': null,
     *       'push_token': null,
     *       'device_id': null,
     *       'role_id': 4,
     *       'first_name': 'Andrey',
     *       'last_name': 'Vangov',
     *       'address': 'Украина, 01133, г. Киев, ул. Кутузова, 18/7',
     *       'description': null,
     *       'phone': '+370674132612',
     *       'birthday': '2018-11-06',
     *       'avatar': null,
     *       'status': 1,
     *       'created_at': '2018-11-06 11:12:13',
     *       'created_by': 1,
     *       'updated_at': null,
     *       'updated_by': null
     *      },
     *      {
     *       'id': 4,
     *       'username': 'ivan',
     *       'auth_key': 'xYLvDk-HkrBFXeudsUa6ZFPJK5J1Pzuk',
     *       'password_hash': '$2y$13$V1GtkXd0xHXlxdB9eac/4.VMfoAi77er72oy.J5PAFKvOqJbguSSC',
     *       'password_reset_token': null,
     *       'email': 'ivan.p@mail.com',
     *       'api_auth_key': null,
     *       'fb_user_id': '291724282660547',
     *       'fb_token': null,
     *       'push_token': null,
     *       'device_id': null,
     *       'role_id': 5,
     *       'first_name': 'Ivan',
     *       'last_name': 'Petrov',
     *       'address': 'Украина, 02133, г. Харьков, ул. Победы, 28/7',
     *       'description': null,
     *       'phone': '+370514132612',
     *       'birthday': '2018-11-06',
     *       'avatar': null,
     *       'status': 1,
     *       'created_at': '2018-11-06 11:12:13',
     *       'created_by': 1,
     *       'updated_at': null,
     *       'updated_by': null
     *      }
     *      ],
     *      'start': '2018-11-14 13:40:00',
     *      'end': '2018-11-07 12:30:00',
     *      'title': 'Йога',
     *      'description': 'Занятия ведет преподаватель хатха йоги, медицинский психолог Евгения Танковская.',
     *      'level': 'easy',
     *      'price': '120',
     *      'color': ',
     *      'capacity': '30',
     *      'customersId': [
     *       '1',
     *       '2',
     *       '3',
     *       '4',
     *       '6',
     *       '7'
     *      ]
     *   ],
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/club/details base url
     * @see https://app.upfit.com.ua/api/v1/club/details?club_id=1&language=ru example request
     *
     * @throws 1102 Required params not found
     */
    public function actionDetails(int $club_id, $language = null): array
    {
    }
}