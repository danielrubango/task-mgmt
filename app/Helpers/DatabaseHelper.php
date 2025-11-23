<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    /**
     * Bulk update multiple rows with different values in one query.
     *
     * @param string $table       The table name
     * @param string $indexColumn The column used for matching (usually 'id')
     * @param array  $data        Format: [id => ['column1' => value1, 'column2' => value2]]
     * @return int                Number of affected rows
     */
    
    public static function updateBatch(string $table, string $indexColumn, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        // Extract index column values into $ids and remove from data
        $ids = array_column($data, $indexColumn);

        // Remove the index column from each row
        foreach ($data as &$row) {
            unset($row[$indexColumn]);
        }
        
        unset($row);

        $columns = array_keys(reset($data));

        $query = "UPDATE {$table} SET ";

        foreach ($columns as $column) {
            $query .= "{$column} = CASE {$indexColumn} ";
            foreach ($data as $index => $values) {
                $id = $ids[$index];
                $value = $values[$column];

                if (is_null($value)) {
                    $query .= "WHEN {$id} THEN NULL ";
                } elseif (is_numeric($value)) {
                    $query .= "WHEN {$id} THEN {$value} ";
                } else {
                    $query .= "WHEN {$id} THEN '" . addslashes($value) . "' ";
                }
            }
            $query .= "END, ";
        }

        $query = rtrim($query, ', ');
        $query .= " WHERE {$indexColumn} IN (" . implode(',', $ids) . ")";

        return DB::statement($query);
    }
}
