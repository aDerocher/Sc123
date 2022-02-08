<?php
namespace Admins;

use \Core\Utils\Query;
use \Core\Utils\Validate;

class Model {
    use \Core\Traits\Properties, \Traits\Permissions;

    // \Admins\Model::TYPE_AUTHENTICATED :: string
    const TYPE_AUTHENTICATED = 'authenticated';

    // \Admins\Model::TYPE_ACCOUNTING :: string
    const TYPE_ACCOUNTING = 'accounting';

    // \Admins\Model::TYPE_ADMIN :: string
    const TYPE_ADMIN = 'admin';

    // \Admins\Model::TYPE_SALES :: string
    const TYPE_SALES = 'sales';

    // \Admins\Model::TYPE_SUPPORT :: string
    const TYPE_SUPPORT = 'support';

    // \Admins\Model::createFromInput :: input -> Model? !
    // input = [
    //   'userType' => (string) one of the values from the admins.userType column
    //   'email' => string
    //   'password' => (string) the hashed password
    //   'firstName' => string
    //   'lastName' => string
    //   'live' => bool (optional, database defaults to true)
    // ]
    // `createFromInput` calls Query::insert, which might throw an exception.
    public static function createFromInput($input) {
        $id = Query::insert('admins', $input);
        if ($id === 0) {
            return new Model($input);
        }
        return Model::fromId($id);
    }

    // \Admins\Model::userInputPostTests :: void -> tests
    // tests = see `\Core\Utils\Validate::userInput`
    public static function userInputPostTests() {
        return [
            'userType' => [
                'test' => function ($value, $values) {
                    if (!in_array($value, Model::userTypes())) {
                        return "\"{$value}\" is not a valid user type.";
                    }
                },
                'none' => "Please specify a user type.",
            ],

            'email' => [
                'test' => function ($value, $values) {
                    if (!Validate::emailAddress($value)) {
                        return "\"{$value}\" is not a valid email address.";
                    }
                    if (!empty(self::fromFilters([['email', $value]]))) {
                        return "This admin email already exists in the system.";
                    }
                },
                'none' => "Please specify an email address.",
            ],

            'password' => [
                'test' => '\Admins\Model::testPassword',
                'none' => "Please provide a password. It must be at least 5 characters long.",
            ],

            'firstName' => [
                'test' => function ($value, $values) {
                    if (empty($value)) {
                        return "Please provide a first name.";
                    }
                },
                'none' => "Please provide a first name.",
            ],

            'lastName' => [
                'test' => function ($value, $values) {
                    if (empty($value)) {
                        return "Please provide a last name.";
                    }
                },
                'none' => "Please provide a last name.",
            ],

            'live' => [
                'test' => function ($value, $values) {
                    if (!Validate::boolString($value)) {
                        return "Please specify a valid `live` value ('1' or '0').";
                    }
                },
            ],
        ];
    }

    // \Admins\Model::testPassword :: (string, [string => a]?) -> string?
    public static function testPassword($value, $values = null) {
        if (strlen($value) < 5) {
            return "The password must be at least 5 characters.";
        }
    }

    // \Admins\Model::userInputPatchTests :: void -> tests
    // tests = see `\Core\Utils\Validate::userInput`
    public static function userInputPatchTests() {
        return array_merge(
            array_map(function ($item) {
                unset($item['none']);
                return $item;
            }, self::userInputPostTests()),
            [
                'email' => [
                    'test' => function ($value, $values) {
                        if (!Validate::emailAddress($value)) {
                            return "\"{$value}\" is not a valid email address.";
                        }
                    },
                ],

                // An empty password in a patch request is interpreted
                // as a request to leave the password the same.
                'password' => [
                    'test' => function ($value, $values) {
                        if ((!empty($value)) &&
                            (!empty($error = self::testPassword($value, $values)))) {
                            return $error;
                        }
                    },
                ],
            ]
        );
    }

    // \Admins\Model::userInputTransforms :: void -> transforms
    // transforms = [string => (input -> a)]
    // input = see `\Admins\Model::createFromInput`
    public static function userInputTransforms() {
        return [
            'password' => function ($input) {
                if ((array_key_exists('email', $input)) &&
                    (array_key_exists('password', $input)) &&
                    (!empty($input['password']))) {
                    return self::hashLoginCredentials(
                        $input['email'],
                        $input['password']
                    );
                }
                return null;
            },

            'live' => function ($input) {
                if (array_key_exists('live', $input)) {
                    return (bool) $input['live'];
                }
                return true;
            }
        ];
    }

    // \Admins\Model::hashLoginCredentials :: (string, string) -> string
    public static function hashLoginCredentials($email, $password) {
        return md5($email.$password);
    }

    // \Admins\Model::fromId :: (numeric, string?) -> Model?
    public static function fromId($id, $key = \Login\Session::SESSION_USER_ID_KEY) {
        return (self::fromFilters([[$key, $id]])[0] ?? null);
    }

    // \Admins\Model::fromFilters :: filters -> [Model]
    // filters = see `\Core\Utils\Query::filterClauses`
    public static function fromFilters($filters) {
        $rows = Query::select('admins', $filters);
        if (empty($rows)) {
            return null;
        }
        $types = Query::getColumnTypes('admins');
        $admins = [ ];
        foreach ($rows as $row) {
            $admins[] = new Model($row, $types);
        }
        return $admins;
    }

    // \Admins\Model::userTypes :: void -> [string]?
    public static function userTypes() {
        return Query::enumFieldValues('admins', 'userType');
    }

    // \Admins\Model::userDefaultListFilters :: \Admins\Model -> filters
    // filters = see `\Core\Utils\Query::filterClauses`
    public static function userDefaultListFilters($user) {
        if ($user->userType == self::TYPE_ADMIN) {
            return [ ];
        }
        return [
            'live' => true,
        ];
    }

    // \Admins\Model::canUserEditEntry :: (\Admins\Model, \Admins\Model) -> bool
    public static function canUserEditEntry($user, $model) {
        return (
            ($user->adminId == $model->adminId) ||
            (in_array($user->userType, [
                self::TYPE_ADMIN,
            ]))
        );
    }

    // \Admins\Model::canBeAddedBy :: (\Admins\Model) -> bool
    public static function canBeAddedBy($user) {
        return in_array($user->userType, [
            self::TYPE_ADMIN,
        ]);
    }

    // new \Admins\Model :: ([string => a]?, [string => string]?) -> Model
    public function __construct($properties = [ ], $types = [ ]) {
        $this->properties = $this->castProperties(
            $properties,
            (empty($types) ? Query::getColumnTypes('admins') : $types)
        );
    }

    // patch :: [string => a] -> bool
    // patch receives an associative array representing column/value
    // pairs and sends an update query to the database.
    public function patch($values) {
        return (Query::patch('admins', [['adminId', $this->adminId]], $values) > 0);
    }

    // delete :: void -> bool
    public function delete() {
        return (Query::delete('admins', [['adminId', $this->adminId]]) > 0);
    }
}
