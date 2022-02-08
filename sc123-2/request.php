<?php
namespace Admins;

use \Core\Http\Response;
use \Core\Utils\Validate;

class Request extends \Http\AuthenticatedRequest {
    // \Admins\Request::handleGet :: void -> \Core\Http\Response
    public static function handleGet() {
        $acts = self::pathQueryParts();
        // GET /admins
        if (empty($acts)) {
            return self::getList();
        }
        // GET /admins/new
        if ((count($acts) == 1) &&
            (strtolower($acts[0]) == 'new')) {
            return self::getCreateForm();
        }
        // The admin's ID must be numeric.
        if (!is_numeric($acts[0])) {
            return self::invalidPathResponse();
        }
        $model = Model::fromId($acts[0]);
        if (is_null($model)) {
            return self::invalidIdResponse($acts[0]);
        }
        // GET /admins/{id}
        if (count($acts) == 1) {
            return self::getEntry($model);
        }
        // GET /admins/{id}/edit
        if ((count($acts) == 2) && 
            (strtolower($acts[1]) == 'edit')) {
            return self::getEditForm($model);
        }
        return self::invalidPathResponse();
    }

    // \Admins\Request::getList :: void -> \Core\Http\Response
    public static function getList() {
        $models = Model::fromFilters(
            Model::userDefaultListFilters(\Login\Session::user())
        );
        if (self::returnType() == Response::TYPE_JSON) {
            return Response::asJson(
                array_map(
                    function ($model) {
                        return $model->publicProperties();
                    },
                    $models
                )
            );
        }
        return Response::asHtml(View::list($models));
    }

    // \Admins\Request::getEntry :: Model -> \Core\Http\Response
    public static function getEntry($model) {
        if (self::returnType() == Response::TYPE_JSON) {
            return Response::asJson(
                $model->publicProperties()
            );
        }
        return Response::asHtml(View::entry($model));
    }

    // \Admins\Request::getCreateForm :: void -> \Core\Http\Response
    public static function getCreateForm() {
        if (self::returnType() == Response::TYPE_JSON) {
            return Response::asJson(
                "Get the New Admin form as HTML, not JSON.",
                Response::CODE_NOT_ALLOWED
            );
        }
        return Response::asHtml(
            View::newEntryForm(View::defaultFormConfig())
        );
    }

    // \Admins\Request::getEditForm :: (Model, [string => a]?) -> \Core\Http\Response
    public static function getEditForm($model, $values = [ ]) {
        if (self::returnType() == Response::TYPE_JSON) {
            return Response::asJson(
                "Get the Admin Edit form as HTML, not JSON.",
                Response::CODE_NOT_ALLOWED
            );
        }
        return Response::asHtml(
            View::editEntryForm($model, $values)
        );
    }

    // \Admins\Request::handlePost :: void -> \Core\Http\Response
    // The only valid request is:
    //   POST /admins/new
    public static function handlePost() {
        $acts = self::pathQueryParts();
        if ((is_null($acts)) ||
            (!((count($acts) == 1) &&
               (strtolower($acts[0]) == 'new')))) {
            return self::invalidPathResponse();
        }

        if (!Model::canbeAddedBy(\Login\Session::user())) {
            if (self::returnType() == Response::TYPE_JSON) {
                return Response::asJson(
                    "You don't have permission to create admins.",
                    Response::CODE_UNPROCESSABLE
                );
            }
            return Response::asHtml(
                View::newEntryForm(
                    ['errors' => ['permission' => "You don't have permission to create admins."]],
                    self::queryValues()
                ),
                Response::CODE_UNPROCESSABLE
            );
        }

        $check = Validate::userInput(
            self::queryValues(),
            Model::userInputPostTests()
        );

        if (!$check['passed']) {
            if (self::returnType() == Response::TYPE_JSON) {
                return Response::asJson(
                    ['input' => $check['values'], 'errors' => $check['errors']],
                    Response::CODE_UNPROCESSABLE
                );
            }
            $response = Response::asHtml(
                View::newEntryForm(
                    View::formConfig(['errors' => $check['errors']]),
                    $check['values']
                ),
                Response::CODE_UNPROCESSABLE
            );
            return $response;
        }

        try {
            $model = Model::createFromInput(
                Validate::transform($check['values'], Model::userInputTransforms())
            );
        } catch (\Exception $e) {
            $error = "Unable to create new admin: ".$e->getMessage();
            if (self::returnType() == Response::TYPE_JSON) {
                return Response::asJson(
                    ['errors' => $error],
                    Response::CODE_INTERNAL_ERROR
                );
            }
            return Response::asHtml(
                View::newEntryForm(
                    ['errors' => ['header' => $error]],
                    $check['values']
                ),
                Response::CODE_INTERNAL_ERROR
            );
        }

        return self::entryConfirmation($model, 'Admin created successfully!');
    }

    // \Admins\Request::handlePatch :: void -> \Core\Http\Response
    // The only valid request is:
    //   PATCH /admins/{id}/edit
    public static function handlePatch() {
        $acts = self::pathQueryParts();
        if ((empty($acts)) ||
            (!is_numeric($acts[0])) ||
            (count($acts) != 2) ||
            ($acts[1] != 'edit')) {
            return self::invalidPathResponse();
        }

        $model = Model::fromId($acts[0]);
        if (is_null($model)) {
            return self::invalidIdResponse($acts[0]);
        }
        if (!Model::canUserEditEntry(\Login\Session::user(), $model)) {
            if (self::returnType() == Response::TYPE_JSON) {
                return Response::asJson(
                    "You don't have permission to edit the specified user.",
                    Response::CODE_UNPROCESSABLE
                );
            }
            return Response::asHtml(
                View::editEntryForm(
                    $model,
                    self::queryValues(),
                    ['errors' => ['permission' => "You don't have permission to edit the specified user."]]
                ),
                Response::CODE_UNPROCESSABLE
            );
        }

        $check = Validate::userInput(
            self::queryValues(),
            Model::userInputPatchTests()
        );

        if (!$check['passed']) {
            if (self::returnType() == Response::TYPE_JSON) {
                return Response::asJson(
                    ['input' => $check['values'], 'errors' => $check['errors']],
                    Response::CODE_UNPROCESSABLE
                );
            }
            return Response::asHtml(
                View::editEntryForm($model, $check['values']),
                Response::CODE_UNPROCESSABLE
            );
        }

        $model->patch(
            Validate::transform($check['values'], Model::userInputTransforms())
        );
        return self::entryConfirmation($model, 'Admin edited successfully!');
    }

    // \Admins\Request::handleDelete :: void -> \Core\Http\Response
    // The only valid request is:
    //   DELETE /admins/{id}
    // TODO

    // \Admins\Request::entryConfirmation :: (Model, string?) -> \Core\Http\Response
    public static function entryConfirmation($model, $message = null) {
        if (self::returnType() == Response::TYPE_JSON) {
            return Response::asJson(['admin' => $model->publicProperties()]);
        }
        if ($message) {
            \Messages\Alerts::add($message);
        }
        $path = (self::queryValue('redirectTo') ?? '/admins/'.$model->adminId);
        $response = new Response();
        $response->addHeaders(['Location' => $path]);
        return $response;
    }
}
