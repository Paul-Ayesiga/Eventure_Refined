<?php

namespace App\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DatabaseQueryTool
{
    /**
     * Execute a database query
     *
     * @param string $query The SQL query to execute
     * @param array $params Parameters for the query (optional)
     * @param int $limit Maximum number of rows to return (default: 10)
     * @return string
     */
    public function __invoke(string $query, array $params = [], int $limit = 10)
    {
        try {
            // Security checks
            $this->validateQuery($query);
            
            // Add LIMIT clause if not present
            if (!preg_match('/\bLIMIT\s+\d+\b/i', $query)) {
                $query = rtrim($query, '; ') . " LIMIT {$limit};";
            }
            
            Log::info("Executing query: {$query}", ['params' => $params]);
            
            // Execute the query
            $results = DB::select($query, $params);
            
            // Format the results
            if (empty($results)) {
                return "No results found for the query.";
            }
            
            $count = count($results);
            $limitReached = $count >= $limit;
            
            $output = "Query returned {$count} " . ($count == 1 ? "row" : "rows") . 
                      ($limitReached ? " (limit reached)" : "") . ":\n\n";
            
            // Convert to JSON for easy reading
            $output .= "```json\n" . json_encode($results, JSON_PRETTY_PRINT) . "\n```";
            
            return $output;
        } catch (\Exception $e) {
            Log::error("Error in DatabaseQueryTool: " . $e->getMessage());
            return "Error executing query: " . $e->getMessage();
        }
    }
    
    /**
     * Validate the query for security
     *
     * @param string $query The SQL query to validate
     * @throws \Exception If the query is not allowed
     */
    private function validateQuery(string $query)
    {
        // Only allow SELECT queries
        if (!preg_match('/^\s*SELECT\b/i', $query)) {
            throw new \Exception("Only SELECT queries are allowed for security reasons.");
        }
        
        // Disallow queries that might be dangerous
        $disallowedPatterns = [
            '/\bINSERT\b/i',
            '/\bUPDATE\b/i',
            '/\bDELETE\b/i',
            '/\bDROP\b/i',
            '/\bALTER\b/i',
            '/\bCREATE\b/i',
            '/\bTRUNCATE\b/i',
            '/\bREPLACE\b/i',
            '/\bGRANT\b/i',
            '/\bREVOKE\b/i',
            '/\bUNION\b/i',
            '/\bINTO\s+OUTFILE\b/i',
            '/\bINTO\s+DUMPFILE\b/i',
            '/\bLOAD_FILE\b/i',
            '/\bSYSTEM_USER\b/i',
            '/\bSCHEMA\b/i',
            '/\bINFORMATION_SCHEMA\b/i',
            '/\bSYS\b/i',
            '/\bPERFORMANCE_SCHEMA\b/i',
        ];
        
        foreach ($disallowedPatterns as $pattern) {
            if (preg_match($pattern, $query)) {
                throw new \Exception("The query contains disallowed SQL (matched pattern: " . $pattern . ")");
            }
        }
        
        // Check for sensitive tables that should be protected
        $sensitiveTables = [
            'users',
            'password_reset_tokens',
            'personal_access_tokens',
            'migrations',
            'failed_jobs',
        ];
        
        foreach ($sensitiveTables as $table) {
            if (preg_match('/\bFROM\s+' . $table . '\b/i', $query) || 
                preg_match('/\bJOIN\s+' . $table . '\b/i', $query)) {
                
                // Allow access to the users table only for the current user
                if ($table === 'users' && Auth::check()) {
                    $userId = Auth::id();
                    if (!preg_match('/\bWHERE\s+.*\bid\s*=\s*' . $userId . '\b/i', $query)) {
                        throw new \Exception("Access to the users table is restricted to your own user record.");
                    }
                } else {
                    throw new \Exception("Access to the {$table} table is restricted for security reasons.");
                }
            }
        }
    }
}
