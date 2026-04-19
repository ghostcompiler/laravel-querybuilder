<?php

namespace GhostCompiler\LaravelQueryBuilder\Exceptions;

use InvalidArgumentException;

class InvalidQueryBuilderQuery extends InvalidArgumentException
{
    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    public function __construct(
        protected array $errors,
        string $message = 'The query builder request contains invalid parameters.',
    ) {
        parent::__construct($message);
    }

    /**
     * @return list<array{parameter: string, reason: string}>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
