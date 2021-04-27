<?php

namespace App\Exceptions;

use Exception;
use Psr\Log\LoggerInterface;
use Throwable;
use Illuminate\Database\QueryException;

class UpdateSummaryException extends QueryException
{
    public function __construct(QueryException $e)
    {
        parent::__construct($e->getSql(), $e->getBindings(), $e);
    }

    public function report()
    {
        try {
            $logger = app(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $ex;
        }

        $logger->error(
            $this->getMessage() . ' caught by UpdateSummaryException',
        );
    }
}
