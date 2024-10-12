<?php

/**
 * Created by PhpStorm.
 * User: hassan alaa
 * Date: 5/30/20
 * Time: 6:14 AM
 */

namespace App\Http\Responses;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class ApiResponder implements ResponsesInterface
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code according to a passed int
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode): ApiResponder
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Respond with a validation error.
     *
     * @param $errors
     *
     * @return mixed
     */
    public function respondWithValidationError($errors)
    {
        return $this->setStatusCode(422)->respondWithError($errors);
    }

    /**
     * Respond with a not found error.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondNotFound(string $message = 'Not Found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * Respond with an internal error.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondInternalError(string $message = 'Internal Server Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * Respond with an authorization error.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondAuthorizationError(string $message = 'You don\'t have the rights to access this resource.')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }

    /**
     * Respond with an authentication error.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondAuthenticationError(string $message = 'Forbidden!')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * Respond that resource created.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function respondCreated(string $message = 'Resource created successfully!', $data = [])
    {
        return $this->setStatusCode(201)->respondWithError($message,$data);
    }

    /**
     * Respond with generic error.
     *
     * @param $message
     *
     * @return mixed
     */
    public function respondWithError($message, $data = [])
    {

        if (!empty($data)) {
            return $this->respond(['message' => $message, 'data' => $data]);
        }
        return $this->respond(['message' => $message]);
    }

    /**
     * Respond with a message showing that the desired resource has been deleted successfully.
     *
     * @param string $resourceName
     *
     * @return mixed
     */
    public function respondWithResourceDeletedSuccessfully(string $resourceName)
    {
        return $this->respond(['message' => "{$resourceName} has been deleted successfully"]);
    }

    /**
     * Respond with data.
     *
     * @param $data
     *
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        $data['status_code'] = $this->getStatusCode();
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Respond with paginated data.
     *
     * @param LengthAwarePaginator $items
     * @param $data
     *
     * @return mixed
     */
    public function respondWithPagination(LengthAwarePaginator $items, $data)
    {
        $data = array_merge($data, [
            'paginator' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'prev_page' => $items->previousPageUrl() != null ?
                    (int)explode('=', $items->previousPageUrl())[1] : null,
                'prev_page_url' => $items->previousPageUrl(),
                'next_page' => $items->nextPageUrl() != null ?
                    (int)explode('=', $items->nextPageUrl())[1] : null,
                'next_page_url' => $items->nextPageUrl(),
                'pages' => $items->lastPage(),
                'total' => $items->total()
            ]
        ]);
        return $this->respond($data);
    }

    /**
     * @param LengthAwarePaginator $items
     * @param $data
     *
     * @return mixed
     */
    public function respondWithSelectTwoPagination(LengthAwarePaginator $items, $data): mixed
    {
        return $this->respond([
            'results' => $data,
            'pagination' => [
                "more" => request()->page != $items->lastPage() && request()->page < $items->lastPage()
            ]
        ]);
    }
}
