<?php
namespace App\Services;

/**
 * Class BaseService
 *
 * @package App\Services
 */
class BaseService
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    // Call function on repository but not write same function in service
    // This method help Controller not need inject many dependency
    public function __call($method, $arguments)
    {
        return $this->repository->$method(...$arguments);
    }
}
