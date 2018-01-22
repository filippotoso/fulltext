<?php

namespace FilippoToso\FullText\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

trait FullText
{

    /**
     * Quote an identifier
     * @method quoteIdentifier
     * @param  string     $identifier The name of the identifier
     * @return string     Quoted identifier
     */
    protected static function quoteIdentifier($identifier) {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    /**
     * Custom scope to execute a FULLTEXT search
     * @method scopeWhereMatch
     * @param  QueryBuilder  $query   [description]
     * @param  string|array  $columns Columns to be included in the search
     * @param  string        $text    Text to execute the search against
     * @return QueryBuilder
     */
    public function scopeWhereMatch($query, $columns, $text) {

        $columns = is_array($columns) ? $columns : [$columns];

        $columns = array_map(function($column){
            return static::quoteIdentifier($column);
        }, $columns);

        $sql = sprintf('MATCH (%s) AGAINST (? IN NATURAL LANGUAGE MODE)', implode(',', $columns));

        return $query->whereRaw($sql, [$text]);

    }

    /**
     * Custom scope to add a FULLTEXT score field to the query results
     * @method scopeSelectMatchScore
     * @param  QueryBuilder  $query   [description]
     * @param  string|array  $columns Columns to be included in the search
     * @param  string        $text    Text to execute the search against
     * @param  string|null   $name    Name of the field (optional, default: score)
     * @return QueryBuilder
     */
    public function scopeSelectMatchScore($query, $columns, $text, $name = 'score') {

        $columns = is_array($columns) ? $columns : [$columns];

        $columns = array_map(function($column){
            return static::quoteIdentifier($column);
        }, $columns);

        $name = static::quoteIdentifier($name);

        $text = DB::connection()->getPdo()->quote($text);

        $sql = sprintf('MATCH (%s) AGAINST (%s IN NATURAL LANGUAGE MODE) AS %s', implode(',', $columns), $text, $name);

        return $query->addSelect(DB::raw($sql));

    }

}
