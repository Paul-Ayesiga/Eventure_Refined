<?php

namespace App\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseSchemaTool
{
    /**
     * Get database schema information for specified tables
     *
     * @param string|array $tables Table names to get schema for (comma-separated string or array)
     * @return string
     */
    public function __invoke($tables = null)
    {
        try {
            // If tables is a string, convert to array
            if (is_string($tables)) {
                $tables = array_map('trim', explode(',', $tables));
            }
            
            // If no tables specified, get all tables
            if (empty($tables)) {
                $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
            }
            
            $result = "# Database Schema Information\n\n";
            
            foreach ($tables as $table) {
                $result .= "## Table: {$table}\n\n";
                
                // Get columns
                $columns = Schema::getColumnListing($table);
                
                if (!empty($columns)) {
                    $result .= "### Columns:\n\n";
                    
                    foreach ($columns as $column) {
                        $type = DB::getSchemaBuilder()->getColumnType($table, $column);
                        $result .= "- `{$column}` ({$type})\n";
                    }
                    
                    $result .= "\n";
                }
                
                // Get relationships (based on foreign keys)
                try {
                    $foreignKeys = DB::select(
                        "SELECT 
                            tc.constraint_name, 
                            tc.table_name, 
                            kcu.column_name, 
                            ccu.table_name AS foreign_table_name,
                            ccu.column_name AS foreign_column_name 
                        FROM 
                            information_schema.table_constraints AS tc 
                            JOIN information_schema.key_column_usage AS kcu
                              ON tc.constraint_name = kcu.constraint_name
                              AND tc.table_schema = kcu.table_schema
                            JOIN information_schema.constraint_column_usage AS ccu
                              ON ccu.constraint_name = tc.constraint_name
                              AND ccu.table_schema = tc.table_schema
                        WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name=?",
                        [$table]
                    );
                    
                    if (!empty($foreignKeys)) {
                        $result .= "### Relationships:\n\n";
                        
                        foreach ($foreignKeys as $fk) {
                            $result .= "- `{$fk->column_name}` references `{$fk->foreign_table_name}({$fk->foreign_column_name})`\n";
                        }
                        
                        $result .= "\n";
                    }
                } catch (\Exception $e) {
                    // Some databases might not support this query
                    Log::warning("Could not get foreign keys for table {$table}: " . $e->getMessage());
                }
                
                // Get indexes
                try {
                    $indexes = DB::select("SHOW INDEX FROM {$table}");
                    
                    if (!empty($indexes)) {
                        $result .= "### Indexes:\n\n";
                        $indexGroups = [];
                        
                        foreach ($indexes as $index) {
                            $indexName = $index->Key_name;
                            if (!isset($indexGroups[$indexName])) {
                                $indexGroups[$indexName] = [
                                    'name' => $indexName,
                                    'columns' => [],
                                    'unique' => $index->Non_unique == 0
                                ];
                            }
                            
                            $indexGroups[$indexName]['columns'][] = $index->Column_name;
                        }
                        
                        foreach ($indexGroups as $index) {
                            $type = $index['unique'] ? 'UNIQUE' : 'INDEX';
                            $columns = implode(', ', $index['columns']);
                            $result .= "- {$type} `{$index['name']}` on (`{$columns}`)\n";
                        }
                        
                        $result .= "\n";
                    }
                } catch (\Exception $e) {
                    // Some databases might not support this query
                    Log::warning("Could not get indexes for table {$table}: " . $e->getMessage());
                }
                
                // Add a sample row if available
                try {
                    $sampleRow = DB::table($table)->first();
                    
                    if ($sampleRow) {
                        $result .= "### Sample Data:\n\n";
                        $result .= "```json\n" . json_encode($sampleRow, JSON_PRETTY_PRINT) . "\n```\n\n";
                    }
                } catch (\Exception $e) {
                    // Table might be empty or have permissions issues
                    Log::warning("Could not get sample data for table {$table}: " . $e->getMessage());
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error in DatabaseSchemaTool: " . $e->getMessage());
            return "Error retrieving database schema: " . $e->getMessage();
        }
    }
}
