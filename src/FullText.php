<?php

namespace FilippoToso\FullText;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class FullText
{

    /**
     * Add a FULLTEXT index to the specified table
     * @method fullTextIndex
     * @param  string        $table   Name of the database table
     * @param  string|array  $columns Columns to be included in the index
     * @param  string|null   $name    Name of the index (optional)
     * @param  string|null   $connection    Name of the connection to be used (optional)
     * @return void
     */
    public static function fullTextIndex($table, $columns, $name = null, $connection = null)
    {

        $columns = is_array($columns) ? $columns : [$columns];

        $name = is_null($name) ? 'ftidx_' . implode('_', $columns) : $name;

        if (!Schema::connection($connection)->hasTable($table)) {
            throw new InvalidArgumentException('The specified table can\'t be found: ' . $table);
        }

        if (!Schema::connection($connection)->hasColumns($table, $columns)) {
            throw new InvalidArgumentException('One or more specified columns can\t be found in the table: ' . $table);
        }

        $sql = sprintf('CREATE FULLTEXT INDEX `%s` ON `%s` (`%s`)', $name, $table, implode('`, `', $columns));
        DB::connection($connection)->statement($sql);
    }
}
