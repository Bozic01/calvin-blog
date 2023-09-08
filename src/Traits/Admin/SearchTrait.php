<?php

namespace App\Traits\Admin;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

trait SearchTrait
{
    /**
     * @param Request $request
     * @param QueryBuilder $baseQuery
     * @param array $searchFields
     * @return QueryBuilder
     */
    protected function extendQueryWithSearch(Request $request, QueryBuilder $baseQuery, array $searchFields): QueryBuilder
    {
        if ($request->query->get('query')) {

            $searchQueryString = '';
            $index = 0;
            foreach ($searchFields as $field) {
                if($index != 0) {
                    $searchQueryString .= ' OR ';
                }
                $searchQueryString .= $field . ' LIKE :search';
                $index++;
            }

            return $baseQuery->andWhere($searchQueryString)
                ->setParameter('search', '%' . $request->query->get('query') . '%');
        }
        return $baseQuery;
    }
}
