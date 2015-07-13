<?php


namespace Demo\Application;


/**
 * Class description
 *
 * @package Demo\Application
 * @author  Nigel Greenway <nigel.greenway@prestoclassical.co.uk>
 */
interface QueryHandlerInterface
{
    /**
     * Handle the query
     *
     * @param QueryInterface $query
     *
     * @return mixed
     */
    public function handle(QueryInterface $query);
}