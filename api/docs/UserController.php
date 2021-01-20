<?php
namespace api\controllers;

use app\src\library\BaseApiController;

class UserController extends BaseApiController
{
    /**
     * Login user by identity
     *
     * @param string $identity `username` or `email`(max of 50 characters for username and 100 for email).
     * @param  string $password Minimum of 6 characters required.
     *
     * @return array
     * {
     *   'user': {
     *       'id': 1,
     *       'username': 'admin',
     *       'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
     *       'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *       'password_reset_token': null,
     *       'email': 'admin@mail.com',
     *       'api_auth_key': 'OsqPsFY6R6EKErb6fsw2PCAuni6KhkoI',
     *       'fb_user_id': '426581574530111',
     *       'fb_token': null,
     *       'push_token': null,
     *       'device_id': null,
     *       'role_id': 1,
     *       'first_name': 'Henry',
     *       'last_name': 'Saimon',
     *       'address': 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
     *       'description': null,
     *       'phone': '+370682132612',
     *       'birthday': '2018-08-20',
     *       'avatar': null,
     *       'status': 1,
     *       'created_at': '2018-08-20 08:43:49',
     *       'created_by': 1,
     *       'updated_at': null,
     *       'updated_by': null
     *   },
     *   'info': {
     *       'clubs': [],
     *   },
     *   'success': true,
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/login base url
     * @see https://app.upfit.com.ua/api/v1/user/login?username=admin&password=123456 example request
     *
     * @throws 1103 Required params not found
     * @throws 1203 Identity or password incorrect
     */
    public function actionLogin(string $identity, string $password): array
    {
    }

    /**
     * Logout from system
     *
     * @return array
     * {
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/logout base url
     */
    public function actionLogout(): array
    {
    }

    /**
     * Register user by identity
     *
     * @param string $identity `username` or `email`(max of 50 characters for username and 100 for email).
     * @param  string $password Minimum of 6 characters required.
     *
     * @param  string $first_name Max of 50 characters.
     * @param  string $last_name Max of 50 characters.
     * @param  string $address Max of 255 characters.
     * @param  string $phone Max of 20 characters.
     * @param  string $birthday Format (Y-m-d).
     * @param  string $description Description of user.
     *
     * @return array
     * {
     *  'user': {
     *      'username': 'name',
     *      'first_name': 'firstname',
     *      'last_name': 'lastname',
     *      'address': 'citu',
     *      'phone': '123123',
     *      'birthday': '2018-08-20',
     *      'description': 'i',
     *      'email': null,
     *      'password_hash': '$2y$13$Ca.y4icFxPeCy0jLHcIpMOWAuMfzqs9GVsS0gHrjDwhRiOUtZDWl6',
     *      'auth_key': '4BP44Pgmenh0qpE3UHZcT6qcLm99soNp',
     *      'api_auth_key': 'aPuEAY6Ppseq_4AcYhDp3_Z3n2FR2-ag',
     *      'created_at': '2018-08-27 11:33:51',
     *      'created_by': 1,
     *      'status': 1,
     *      'id': 42
     *  }
     *  'success': true,
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/register base url
     * @see https://app.upfit.com.ua/api/v1/user/register?username=admin&password=123456 example request
     *
     * @throws 1102 Required params not found
     * @throws 1103 Params must be unique
     *
     */
    public function actionRegister(
        string $identity,
        string $password,
        string $first_name = '',
        string $last_name = '',
        string $address = '',
        string $phone = '',
        string $birthday = '',
        string $description = ''
    ): array
    {
    }

    /**
     * Login by Facebook
     *
     * @param int $id Users Facebook ID.
     * @param string $token Facebook token.
     *
     * @return array
     * {
     *  'user': {
     *      'id': 1,
     *      'username': 'admin',
     *      'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
     *      'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *      'password_reset_token': null,
     *      'email': 'admin@mail.com',
     *      'api_auth_key': '5xFmIAddVwP9HdSDDRJz5pTrVG3pPGdV',
     *      'fb_user_id': '426581574530112',
     *      'fb_token': 'EAAD8HxQv1dEBAKYeHjZASEHiZBZAyAJzmPltcYoQjWhpl4ClLXcXgVOjJQOUp7btPZAvKfnjx4N785Ld19R8J10ObDUbBOwQ324xyHEeGxZAz9etdAuFI5ZBgjSvKV0qvu3ANpIgRZCDYZBDQkdIGCOTu4zloBFW41FCZAnhL3rhVlqLmYWAECDSjkztERRg1ivQycfZBLjThEZAgZDZD',
     *      'push_token': null,
     *      'device_id': null,
     *      'role_id': 1,
     *      'first_name': 'Henry',
     *      'last_name': 'Saimon',
     *      'address': 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
     *      'description': null,
     *      'phone': '+370682132612',
     *      'birthday': '2018-08-20',
     *      'avatar': null,
     *      'status': 1,
     *      'created_at': '2018-08-20 08:43:49',
     *      'created_by': 1,
     *      'updated_at': null,
     *      'updated_by': null
     *  },
     *  'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/login-fb base url
     * @see https://app.upfit.com.ua/api/v1/user/login-fb?id=426581574530112&token=EAAD8H... example request
     *
     * @throws 1101 Model not found
     * @throws 1102 Required params not found
     * @throws 1204 Id or token incorrect
     * @throws 1205 Fb api bad response
     */
    public function actionLoginFb(int $id, string $token): array
    {
    }

    /**
     * Register user by Facebook
     *
     * @param int $id Users Facebook ID.
     * @param string $token Facebook token.
     *
     * @param array $data Available fields:
     *
     *  <b>string</b> `username`
     *
     *  <b>string</b> `password`
     *
     *  <b>string</b> `email`
     *
     *  <b>string</b> `fb_user_id`
     *
     *  <b>string</b> `push_token`
     *
     *  <b>string</b> `device_id`
     *
     *  <b>int</b> `role_id`
     *
     *  <b>string</b> `first_name`
     *
     *  <b>string</b> `last_name`
     *
     *  <b>string</b> `address`
     *
     *  <b>string</b> `phone`
     *
     *  <b>string</b> `birthday` format: `(Y-m-d)`
     *
     *  <b>string</b> `description`.
     *
     * @return array
     * {
     *   'user': {
     *       'fb_user_id': '426581574530112',
     *       'fb_token': 'EAAD8HxQv1dEBAPuIKeHtQsKkpJPAFZBGSiZCwycgvsNeIhInW06yXDYMZAZBFOBMxrSy3l4IZATpxYLOuuhyosHVyng3PXKt2UaA0aHScHgzM0wWraPwFCxg13F5dEuyWSx1R84kTA7nuVvaWhposjZBwDlqaDpgEUbh3YwQnRIZCMj5UVZBuThJVs5BH42wCfOZAWSpu5Sgw7gZDZD',
     *       'email': null,
     *       'auth_key': 'SFVG252_ZdYsuP-Vl8y8Wnl-cATUu1eL',
     *       'api_auth_key': 'eMsYoNSG_36VhHjJd2ZG9ygwGPZrFsbS',
     *       'created_at': '2018-08-27 12:12:41',
     *       'created_by': 1,
     *       'status': 1,
     *       'id': 54
     *   },
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/register-fb base url
     * @see https://app.upfit.com.ua/api/v1/user/register-fb?id=426581574530112&token=EAAD8H... example request
     *
     * @throws 1102 Required params not found
     * @throws 1205 Fb api bad response
     */
    public function actionRegisterFb(int $id, string $token, array $data = []): array
    {
    }

    /**
     * Update user by id
     *
     * @param int $id The users ID.
     *
     * @param array $data Available fields:
     *
     * <b>string</b> `username`,
     *
     * <b>string</b> `password`,
     *
     * <b>string</b> `email`,
     *
     * <b>string</b> `fb_user_id`,
     *
     * <b>string</b> `push_token`,
     *
     * <b>string</b> `device_id`,
     *
     * <b>int</b> `role_id`,
     *
     * <b>string</b> `first_name`,
     *
     * <b>string</b> `last_name`,
     *
     * <b>string</b> `address`,
     *
     * <b>string</b> `phone`,
     *
     * <b>string</b> `birthday` (Y-m-d),
     *
     * <b>string</b> `description`.
     *
     * @return array
     * {
     *   'user': {
     *       'id': 4,
     *       'username': 'newName',
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
     *       'birthday': '2018-08-20',
     *       'avatar': null,
     *       'status': 1,
     *       'created_at': '2018-08-20 08:43:49',
     *       'created_by': 1,
     *       'updated_at': '2018-08-27 12:31:13',
     *       'updated_by': 1
     *   },
     *   'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/update base url
     * @see https://app.upfit.com.ua/api/v1/user/update?id=4&data[username]=newName example request
     *
     * @throws 1101 Model not found
     * @throws 1102 Required params not found
     */
    public function actionUpdate(int $id, array $data = []): array
    {
    }

    /**
     * Request customer to club
     *
     * @param int $club_id The clubs ID.
     * @param string $card_number The card number.
     *
     * @return array
     * {
     *  'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/request-customer-to-club base url
     * @see https://app.upfit.com.ua/api/v1/user/request-customer-to-club?club_id=1&card_number=1233212 example request
     *
     * @throws 1102 Required params not found
     * @throws 1104 Relation already exist
     */
    public function actionRequestCustomerToClub(int $club_id, string $card_number = null): array
    {
    }

    /**
     * Customer leave the club
     *
     * @param int $club_id The clubs ID.
     * @param boolean $leave_events Leave the coaching.
     *
     * @return array
     * {
     *  'success': true
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/leave-club base url
     * @see https://app.upfit.com.ua/api/v1/user/leave-club?club_id=1&leave_event=true example request
     *
     * @throws 1102 Required params not found
     * @throws 1206 Customer are not a club member
     */
    public function actionLeaveClub(int $club_id, bool $leave_events = false): array
    {
    }

    /**
     * Get user info by id
     *
     * @param  int $user_id The user ID.
     *
     * @return array
     * {
     *   'user': {
     *       'id': 1,
     *       'username': 'admin',
     *       'auth_key': 'vCzeiVsVEjgSi2WOcTIY-p9kL-tqSV-R',
     *       'password_hash': '$2y$13$bRJPMcerRXnF0C3hoihxx.bqW6LDW3byUcFPJKf0WSLEb4koAlJ5a',
     *       'password_reset_token': null,
     *       'email': 'admin@mail.com',
     *       'api_auth_key': 'OsqPsFY6R6EKErb6fsw2PCAuni6KhkoI',
     *       'fb_user_id': '426581574530111',
     *       'fb_token': null,
     *       'push_token': null,
     *       'device_id': null,
     *       'role_id': 1,
     *       'first_name': 'Henry',
     *       'last_name': 'Saimon',
     *       'address': 'Saxon Euphory Club, улица Михайла Максимовича, Киев',
     *       'description': null,
     *       'phone': '+370682132612',
     *       'birthday': '2018-08-20',
     *       'avatar': null,
     *       'status': 1,
     *       'created_at': '2018-08-20 08:43:49',
     *       'created_by': 1,
     *       'updated_at': null,
     *       'updated_by': null
     *   },
     *   'info': {
     *       'clubs': [],
     *   },
     *   'success': true,
     * }
     *
     * @see https://app.upfit.com.ua/api/v1/user/get-user-info base url
     * @see https://app.upfit.com.ua/api/v1/user/get-user-info?user_id=1 example request
     *
     * @throws 1102 Required params not found
     * @throws 1101 Model not found
     */
    public function actionGetUserInfo(int $user_id): array
    {
    }
}