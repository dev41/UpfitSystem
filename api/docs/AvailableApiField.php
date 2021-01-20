<?php
namespace api\models;

class AvailableApiField
{

    /**
     * @model_name ApiClub
     * @model_map_fields
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
     */
    const MODEL_API_CLUB = 1;

    /**
     * @model_name ApiActivity
     * @model_map_fields
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
     */
    const MODEL_API_ACTIVITY = 2;

    /**
     * @model_name ApiCoaching
     * @model_map_fields
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
     */
    const MODEL_API_COACHING = 3;

    /**
     * @model_name ApiEvent
     * @model_map_fields
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
     */
    const MODEL_API_EVENT = 4;

    /**
     * @model_name ApiNews
     * @model_map_fields
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
     */
    const MODEL_API_NEWS = 5;

    /**
     * @model_name ApiSale
     * @model_map_fields
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
     */
    const MODEL_API_SALE = 6;



    /**
     * @model_name ApiSubplace
     * @model_map_fields
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
     */
    const MODEL_API_SUBPLACE = 7;

}