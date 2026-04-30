<?php

namespace GhostCompiler\LaravelQueryBuilder\Exceptions;

class InvalidQueryBuilderQuery extends QueryBuilderException
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
