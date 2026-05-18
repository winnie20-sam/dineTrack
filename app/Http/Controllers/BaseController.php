<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Shared\CustomResponseController;
use App\Models\Shared\CustomConstants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class BaseController extends Controller
{
    public $resp, $const;



    /**
     * Base controller constructor
     */
    public function __construct()
    {
        $this->resp  = new CustomResponseController();
        $this->const = new CustomConstants();
    }



    // =========================================================================
    // JSON Response Helpers
    // =========================================================================

    /**
     * Return a success JSON response
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function jsonSuccess(string $message, mixed $data = []): JsonResponse
    {
        return $this->resp->response(CustomConstants::RESPONSE_STATUS_SUCCESS, $message, $data);
    }

    /**
     * Return a failed JSON response
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function jsonFailed(string $message, mixed $data = []): JsonResponse
    {
        return $this->resp->response(CustomConstants::RESPONSE_STATUS_FAILED, $message, $data);
    }

    /**
     * Return a record not found JSON response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function jsonNotFound(string $message = CustomConstants::NOT_FOUND): JsonResponse
    {
        return $this->resp->response(CustomConstants::RESPONSE_STATUS_RECORD_NOT_FOUND, $message, []);
    }

    /**
     * Return a record already exists JSON response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function jsonRecordExists(string $message = CustomConstants::RECORD_ALREADY_EXISTS): JsonResponse
    {
        return $this->resp->response(CustomConstants::RESPONSE_STATUS_RECORD_EXISTS, $message, []);
    }

    /**
     * Return a validation failed JSON response
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function jsonValidationFailed(string $message, mixed $data = []): JsonResponse
    {
        return $this->resp->response(CustomConstants::RESPONSE_STATUS_PAYLOAD_VALIDATION_FAIL, $message, $data);
    }



    // =========================================================================
    // Redirect Response Helpers
    // =========================================================================

    /**
     * Redirect to a route with a success flash message
     *
     * @param string $route
     * @param string $message
     * @param array $params
     * @return RedirectResponse
     */
    protected function success(string $route, string $message, array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    /**
     * Redirect to a route with an error flash message
     *
     * @param string $route
     * @param string $message
     * @param array $params
     * @return RedirectResponse
     */
    protected function error(string $route, string $message, array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with('error', $message);
    }

    /**
     * Redirect to a route with a warning flash message
     *
     * @param string $route
     * @param string $message
     * @param array $params
     * @return RedirectResponse
     */
    protected function warning(string $route, string $message, array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with('warning', $message);
    }

    /**
     * Redirect back with a success flash message
     *
     * @param string $message
     * @return RedirectResponse
     */
    protected function backWithSuccess(string $message): RedirectResponse
    {
        return redirect()->back()->with('success', $message);
    }

    /**
     * Redirect back with an error flash message and old input preserved
     *
     * @param string $message
     * @return RedirectResponse
     */
    protected function backWithError(string $message): RedirectResponse
    {
        return redirect()->back()->withInput()->with('error', $message);
    }
}
