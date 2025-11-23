<?php

namespace App\Traits;

use App\Helpers\DatabaseHelper;

trait BulkUpdatable
{
    /**
     * Batch update rows for the model using DatabaseHelper.
     *
     * @param array  $data        Format: [id => ['column1' => value1, 'column2' => value2]]
     * @param string $indexColumn Column used for matching (default: 'id')
     * @return bool
     */
    public static function updateBatch(array $data, string $indexColumn = 'id'): bool
    {
        if (empty($data)) {
            return false;
        }

        $table = (new static)->getTable();

        return DatabaseHelper::updateBatch($table, $indexColumn, $data);
    }
}
