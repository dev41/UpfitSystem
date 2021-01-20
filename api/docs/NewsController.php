<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class NewsController extends BaseApiController
{
    /**
     * Returns news list
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
     * @available_fields_model Api News :
     * @available_fields
     *
     * <small class="field-list"><ul>
     * <li>#news_id <span> &lt; int &gt; </span> </li>
     * <li>#news_name <span> &lt; string &gt; </span> </li>
     * <li>#news_description <span> &lt; string &gt; </span> </li>
     * <li>#news_active <span> &lt; int &gt; </span> </li>
     * <li>#news_created_at <span> &lt; datetime &gt; </span> </li>
     * <li>#news_created_by <span> &lt; int &gt; </span> </li>
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
     *  'news': [
     *  {
     *   'id': '1',
     *   'club_id': '2',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'created_at': '24-09-2018'
     *   'image': 'https://dev.upfit.com.ua/storage/images/news/1/1545047781_5760.jpg'
     *  },
     *  {
     *   'id': '2',
     *   'club_id': '3',
     *   'name': 'some name',
     *   'description': 'some description' (format html),
     *   'created_at': '24-09-2018'
     *   'image': 'https://dev.upfit.com.ua/storage/images/news/2/1545047781_5760.jpg'
     *  }
     *  ],
     *  'success': true
     * }
     * @see https://app.upfit.com.ua/api/v1/news/index base url
     * @see https://app.upfit.com.ua/api/v1/news/index?language=ru&conditions[0]=<&conditions[1]=#news_id&conditions[2]=4&sort[#news_id]=ASC&pagination[pageSize]=2 example request
     */
    public function actionIndex(array $conditions = [], array $sort = [], array $pagination = [], $language = null): array
    {
    }
}