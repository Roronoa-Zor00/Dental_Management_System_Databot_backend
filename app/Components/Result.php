<?php

namespace App\Components;


use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Result
{
    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var int
     */
    protected $total_count;

    /**
     * @var string
     */
    protected $message = 'Process done.';

    /**
     * @var null|mixed
     */
    protected $data;

    const MESSAGE_FORBIDDEN = "Forbidden Action.";
    const MESSAGE_INCOMPLETE = "Invalid Data.";
    const MESSAGE_NOT_FOUND = "Resource not found.";
    const MESSAGE_UNAUTHORIZED = "Not enough permission.";
    const MESSAGE_ERROR_500 = "An error has occurred on the server.";
    const MESSAGE_FAILED_CREATE = "Failed to create resource.";
    const MESSAGE_FAILED_UPDATE = "Failed to update resource.";
    const MESSAGE_FAILED_DELETE = "Failed to delete resource.";
    const MESSAGE_SUCCESS = "Resource success.";
    const MESSAGE_SUCCESS_LIST = "Successfully listed resource.";
    const MESSAGE_SUCCESS_CREATE = "Successfully created resource.";
    const MESSAGE_SUCCESS_UPDATE = "Successfully updated resource.";
    const MESSAGE_SUCCESS_DELETE = "Successfully deleted resource.";

    /**
     * Command result object constructor
     *
     * @param bool $success
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @param int $total_count
     */
    public function __construct($success = false, $message = '', $data = null, $statusCode = 200, $total_count = 0)
    {
        $this->success = $success;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->total_count = $total_count;
        $this->data = $data;
    }

    /**
     * @param bool $success
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @param int $total_count
     * @return $this
     */
    public function set($success = false, $message = '', $data = null, $statusCode = 200, $total_count = 0)
    {
        $this->success = $success;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->total_count = $total_count;

        return $this;
    }

    /**
     * determine if command transaction was successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->success;
    }

    /**
     * the status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * the total count
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * the command result message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * the command result returned data
     *
     * @return null
     */
    public function getData()
    {
        if( is_null($this->data) || (is_array($this->data) && sizeof($this->data)==0) ) return new \stdClass();

        if( is_array($this->data) ) return new Collection($this->data);
       
        return $this->data;
    }

    public function dynamicPagination($objectName,$currentPage,$perPage,$dataName){
        $data = $objectName->paginate($perPage, ['*'], $dataName, $currentPage);

        $array['total'] = $data->total();
        $array['per_page'] = $data->perPage();
        $array['current_page'] = $data->CurrentPage();
        $array['last_page'] = $data->LastPage();
        $array[$dataName] = $data->items();
        return $array;
    }

}