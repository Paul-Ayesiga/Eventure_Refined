<?php

namespace App\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Support\Carbon;

class DynamicQueryTool
{
    /**
     * Execute a dynamic query based on natural language input
     *
     * @param string $question The natural language question to answer
     * @return string
     */
    public function __invoke(string $question)
    {
        try {
            Log::info("DynamicQueryTool called with question: {$question}");

            // Get results directly from the Event model
            $rawResult = $this->generateQueryDirect($question);
            Log::info("DynamicQueryTool - Query executed", ['resultLength' => strlen($rawResult)]);

            // Format the results in a more readable way
            $formattedResult = $this->formatResults($rawResult, $question);
            Log::info("DynamicQueryTool - Results formatted", ['formattedResultLength' => strlen($formattedResult)]);

            return $formattedResult;
        } catch (\Exception $e) {
            Log::error("Error in DynamicQueryTool: " . $e->getMessage());
            return "Error executing dynamic query: " . $e->getMessage();
        }
    }

    /**
     * Get database schema information
     *
     * @return array
     */
    private function getDatabaseSchema()
    {
        $schema = [];

        try {
            // Get all tables from the database
            $tables = [];

            // Different approach based on database driver
            $connection = DB::connection();
            $driver = $connection->getDriverName();

            if ($driver === 'mysql') {
                $tables = $connection->select('SHOW TABLES');
                $tables = array_map(function($table) {
                    return reset($table);
                }, $tables);
            } elseif ($driver === 'pgsql') {
                $tables = $connection->select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
                $tables = array_map(function($table) {
                    return $table->table_name;
                }, $tables);
            } elseif ($driver === 'sqlite') {
                $tables = $connection->select("SELECT name FROM sqlite_master WHERE type='table'");
                $tables = array_map(function($table) {
                    return $table->name;
                }, $tables);
            } else {
                // Fallback to Laravel's Schema
                $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
            }

            // Exclude sensitive tables
            $sensitiveTables = ['users', 'password_reset_tokens', 'personal_access_tokens', 'migrations', 'failed_jobs'];
            $tables = array_diff($tables, $sensitiveTables);

            // Get column information for each table
            foreach ($tables as $table) {
                $columns = Schema::getColumnListing($table);
                $columnTypes = [];

                foreach ($columns as $column) {
                    try {
                        $columnTypes[$column] = DB::getSchemaBuilder()->getColumnType($table, $column);
                    } catch (\Exception $e) {
                        // If we can't get the column type, just mark it as 'unknown'
                        $columnTypes[$column] = 'unknown';
                    }
                }

                $schema[$table] = $columnTypes;
            }

            Log::info("DynamicQueryTool - Schema retrieved successfully", ['tableCount' => count($schema)]);

        } catch (\Exception $e) {
            Log::error("Error getting database schema: " . $e->getMessage());
            // Return a minimal schema with just the events table
            $schema['events'] = [
                'id' => 'integer',
                'name' => 'string',
                'event_type' => 'string',
                'venue' => 'string',
                'start_date' => 'datetime',
                'end_date' => 'datetime',
                'start_datetime' => 'datetime',
                'end_datetime' => 'datetime',
                'category' => 'string',
                'description' => 'text',
                'status' => 'string',
                'is_archived' => 'boolean'
            ];
        }

        return $schema;
    }

    /**
     * Generate a SQL query directly based on the question without schema
     *
     * @param string $question The natural language question
     * @return string
     */
    private function generateQueryDirect(string $question)
    {
        try {
            // Convert question to lowercase for easier matching
            $lowerQuestion = strtolower($question);

            // Determine the type of query and extract search terms
            $queryType = 'list'; // Default
            $searchTerm = '';
            $category = '';

            // Check for category-specific queries
            if (preg_match('/\b(music|concert|festival|sports|business|conference|workshop|seminar|art|exhibition|charity|fundraising|networking|social|community|educational|training|virtual|online)\b/i', $question, $matches)) {
                $queryType = 'category';
                $category = $matches[1];
                Log::info("DynamicQueryTool - Category detected", ['category' => $category]);
            }
            // Check for search queries
            elseif (Str::contains($lowerQuestion, ['find', 'search', 'any', 'are there', 'show me', 'looking for', 'about', 'related to'])) {
                $queryType = 'search';
                $searchTerm = $this->extractSearchTerm($question);
                Log::info("DynamicQueryTool - Search term detected", ['searchTerm' => $searchTerm]);
            }
            // Check for upcoming events
            elseif (Str::contains($lowerQuestion, ['upcoming', 'future', 'soon', 'coming', 'next'])) {
                $queryType = 'upcoming';
            }
            // Check for event details
            elseif (Str::contains($lowerQuestion, ['details', 'about event', 'tell me about', 'more info', 'information on'])) {
                $queryType = 'details';
                $eventName = $this->extractEventName($question);
            }

            // Get events directly based on query type
            $events = [];

            switch ($queryType) {
                case 'search':
                    if (!empty($searchTerm)) {
                        // Log the search term for debugging
                        Log::info("DynamicQueryTool - Searching with term", ['searchTerm' => $searchTerm]);

                        // Get the total count of events in the database
                        $totalEvents = Event::count();
                        Log::info("DynamicQueryTool - Total events in database", ['count' => $totalEvents]);

                        $events = Event::where(function($q) use ($searchTerm) {
                                $q->where('name', 'like', "%{$searchTerm}%")
                                  ->orWhere('description', 'like', "%{$searchTerm}%")
                                  ->orWhere('category', 'like', "%{$searchTerm}%");
                            })
                            ->where('status', 'Published')
                            ->where(function($q) {
                                $q->where('is_archived', false)
                                  ->orWhereNull('is_archived');
                            })
                            ->orderBy('start_date', 'asc')
                            ->limit(10)
                            ->get();

                        // Log the search results
                        Log::info("DynamicQueryTool - Search results", ['count' => $events->count()]);

                        // If no events found, return a clear message
                        if ($events->count() === 0) {
                            return "I couldn't find any events matching your criteria. Please try a different search term or category.";
                        }

                        $events = $events->toArray();
                    } else {
                        // If no search term, fall back to listing all events
                        $events = Event::where('status', 'Published')
                            ->where(function($q) {
                                $q->where('is_archived', false)
                                  ->orWhereNull('is_archived');
                            })
                            ->orderBy('start_date', 'asc')
                            ->limit(10)
                            ->get()
                            ->toArray();
                    }
                    break;

                case 'upcoming':
                    $today = Carbon::now()->format('Y-m-d');
                    $events = Event::where('start_date', '>=', $today)
                        ->where('status', 'Published')
                        ->where(function($q) {
                            $q->where('is_archived', false)
                              ->orWhereNull('is_archived');
                        })
                        ->orderBy('start_date', 'asc')
                        ->limit(10)
                        ->get();

                    // Log the upcoming events results
                    Log::info("DynamicQueryTool - Upcoming events results", ['count' => $events->count()]);

                    // If no events found, return a clear message
                    if ($events->count() === 0) {
                        return "I couldn't find any upcoming events. Please check back later as our event calendar is updated regularly.";
                    }

                    $events = $events->toArray();
                    break;

                case 'details':
                    if (!empty($eventName)) {
                        $events = Event::where('name', 'like', "%{$eventName}%")
                            ->where('status', 'Published')
                            ->where(function($q) {
                                $q->where('is_archived', false)
                                  ->orWhereNull('is_archived');
                            })
                            ->limit(1)
                            ->get();

                        // Log the event details results
                        Log::info("DynamicQueryTool - Event details results", ['count' => $events->count(), 'eventName' => $eventName]);

                        // If no events found, return a clear message
                        if ($events->count() === 0) {
                            return "I couldn't find any event matching '{$eventName}'. Please check the event name and try again.";
                        }

                        $events = $events->toArray();
                    } else {
                        // If no event name, fall back to listing all events
                        $events = Event::where('status', 'Published')
                            ->where(function($q) {
                                $q->where('is_archived', false)
                                  ->orWhereNull('is_archived');
                            })
                            ->orderBy('start_date', 'asc')
                            ->limit(10)
                            ->get();

                        // Log the fallback results
                        Log::info("DynamicQueryTool - Fallback results (no event name)", ['count' => $events->count()]);

                        // If no events found, return a clear message
                        if ($events->count() === 0) {
                            return "I couldn't find any events in the database. Please check back later as our event calendar is updated regularly.";
                        }

                        $events = $events->toArray();
                    }
                    break;

                case 'list':
                default:
                    $events = Event::where('status', 'Published')
                        ->where(function($q) {
                            $q->where('is_archived', false)
                              ->orWhereNull('is_archived');
                        })
                        ->orderBy('start_date', 'asc')
                        ->limit(10)
                        ->get();

                    // Log the list results
                    Log::info("DynamicQueryTool - List results", ['count' => $events->count()]);

                    // If no events found, return a clear message
                    if ($events->count() === 0) {
                        return "I couldn't find any events in the database. Please check back later as our event calendar is updated regularly.";
                    }

                    $events = $events->toArray();
                    break;
            }

            // Remove duplicate events by name
            $uniqueEvents = [];
            $eventNames = [];

            foreach ($events as $event) {
                if (!in_array($event['name'], $eventNames)) {
                    $eventNames[] = $event['name'];
                    $uniqueEvents[] = $event;
                }
            }

            // Format as JSON for the DatabaseQueryTool
            $jsonResult = json_encode($uniqueEvents, JSON_PRETTY_PRINT);

            // Create a result string that mimics what DatabaseQueryTool would return
            $count = count($uniqueEvents);
            $result = "Query returned {$count} " . ($count == 1 ? "row" : "rows") . ":\n\n";
            $result .= "```json\n" . $jsonResult . "\n```";

            return $result;

        } catch (\Exception $e) {
            Log::error("Error in generateQueryDirect: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a SQL query based on the question and schema
     *
     * @param string $question The natural language question
     * @param array $schema The database schema
     * @return string
     */
    private function generateQuery(string $question, array $schema)
    {
        // Log the schema for debugging
        Log::info("Schema for query generation:", ['schema' => $schema]);

        // Determine which tables are relevant to the question
        $relevantTables = $this->findRelevantTables($question, $schema);

        // Generate different queries based on the question type
        $lowerQuestion = strtolower($question);

        // List events query
        if (Str::contains($lowerQuestion, ['list events', 'show events', 'available events', 'all events'])) {
            return $this->generateListEventsQuery($relevantTables);
        }

        // Search events query
        if (Str::contains($lowerQuestion, ['find events', 'search events', 'events about', 'events related to'])) {
            $searchTerm = $this->extractSearchTerm($question);
            return $this->generateSearchEventsQuery($relevantTables, $searchTerm);
        }

        // Upcoming events query
        if (Str::contains($lowerQuestion, ['upcoming events', 'future events', 'events soon'])) {
            return $this->generateUpcomingEventsQuery($relevantTables);
        }

        // Event details query
        if (Str::contains($lowerQuestion, ['event details', 'about event', 'tell me about event'])) {
            $eventName = $this->extractEventName($question);
            return $this->generateEventDetailsQuery($relevantTables, $eventName);
        }

        // Default to a basic events query if we can't determine the intent
        return $this->generateListEventsQuery($relevantTables);
    }

    /**
     * Find tables relevant to the question
     *
     * @param string $question The natural language question
     * @param array $schema The database schema
     * @return array
     */
    private function findRelevantTables(string $question, array $schema)
    {
        $lowerQuestion = strtolower($question);
        $relevantTables = [];

        // If the question is about events, include the events table
        if (Str::contains($lowerQuestion, ['event', 'events'])) {
            if (isset($schema['events'])) {
                $relevantTables['events'] = $schema['events'];
            }
        }

        // If no relevant tables found, include all tables
        if (empty($relevantTables)) {
            $relevantTables = $schema;
        }

        return $relevantTables;
    }

    /**
     * Generate a query to list events
     *
     * @param array $relevantTables The relevant tables
     * @return string
     */
    private function generateListEventsQuery(array $relevantTables)
    {
        // Check if events table exists
        if (!isset($relevantTables['events'])) {
            throw new \Exception("Cannot find events table in the database schema.");
        }

        // Build the query
        $query = "SELECT id, name, event_type, start_date, venue, category FROM events";

        // Add conditions for published events that aren't archived
        if (isset($relevantTables['events']['status']) && isset($relevantTables['events']['is_archived'])) {
            $query .= " WHERE status = 'Published' AND (is_archived = 0 OR is_archived IS NULL)";
        }

        // Add order by start_date
        if (isset($relevantTables['events']['start_date'])) {
            $query .= " ORDER BY start_date ASC";
        }

        // Add limit
        $query .= " LIMIT 10";

        return $query;
    }

    /**
     * Generate a query to search events
     *
     * @param array $relevantTables The relevant tables
     * @param string $searchTerm The search term
     * @return string
     */
    private function generateSearchEventsQuery(array $relevantTables, string $searchTerm)
    {
        // Check if events table exists
        if (!isset($relevantTables['events'])) {
            throw new \Exception("Cannot find events table in the database schema.");
        }

        // Build the query
        $query = "SELECT id, name, event_type, start_date, venue, category FROM events";

        // Add search conditions
        $query .= " WHERE (name LIKE '%{$searchTerm}%' OR description LIKE '%{$searchTerm}%'";

        // Add category search if available
        if (isset($relevantTables['events']['category'])) {
            $query .= " OR category LIKE '%{$searchTerm}%'";
        }

        // Add tags search if available
        if (isset($relevantTables['events']['tags'])) {
            $query .= " OR tags LIKE '%{$searchTerm}%'";
        }

        $query .= ")";

        // Add conditions for published events that aren't archived
        if (isset($relevantTables['events']['status']) && isset($relevantTables['events']['is_archived'])) {
            $query .= " AND status = 'Published' AND (is_archived = 0 OR is_archived IS NULL)";
        }

        // Add order by start_date
        if (isset($relevantTables['events']['start_date'])) {
            $query .= " ORDER BY start_date ASC";
        }

        // Add limit
        $query .= " LIMIT 10";

        return $query;
    }

    /**
     * Generate a query to get upcoming events
     *
     * @param array $relevantTables The relevant tables
     * @return string
     */
    private function generateUpcomingEventsQuery(array $relevantTables)
    {
        // Check if events table exists
        if (!isset($relevantTables['events'])) {
            throw new \Exception("Cannot find events table in the database schema.");
        }

        // Build the query
        $query = "SELECT id, name, event_type, start_date, venue, category FROM events";

        // Add conditions for upcoming events
        $today = date('Y-m-d');
        $query .= " WHERE start_date >= '{$today}'";

        // Add conditions for published events that aren't archived
        if (isset($relevantTables['events']['status']) && isset($relevantTables['events']['is_archived'])) {
            $query .= " AND status = 'Published' AND (is_archived = 0 OR is_archived IS NULL)";
        }

        // Add order by start_date
        if (isset($relevantTables['events']['start_date'])) {
            $query .= " ORDER BY start_date ASC";
        }

        // Add limit
        $query .= " LIMIT 10";

        return $query;
    }

    /**
     * Generate a query to get event details
     *
     * @param array $relevantTables The relevant tables
     * @param string $eventName The event name
     * @return string
     */
    private function generateEventDetailsQuery(array $relevantTables, string $eventName)
    {
        // Check if events table exists
        if (!isset($relevantTables['events'])) {
            throw new \Exception("Cannot find events table in the database schema.");
        }

        // Build the query
        $query = "SELECT * FROM events WHERE name LIKE '%{$eventName}%'";

        // Add conditions for published events that aren't archived
        if (isset($relevantTables['events']['status']) && isset($relevantTables['events']['is_archived'])) {
            $query .= " AND status = 'Published' AND (is_archived = 0 OR is_archived IS NULL)";
        }

        // Add limit
        $query .= " LIMIT 1";

        return $query;
    }

    /**
     * Extract search term from the question
     *
     * @param string $question The natural language question
     * @return string
     */
    private function extractSearchTerm(string $question)
    {
        $lowerQuestion = strtolower($question);

        // Check for specific categories in the question
        $categories = ['music', 'concert', 'festival', 'sports', 'business', 'conference', 'workshop', 'seminar', 'art', 'exhibition', 'charity', 'fundraising', 'networking', 'social', 'community', 'educational', 'training', 'virtual', 'online'];

        foreach ($categories as $category) {
            if (strpos($lowerQuestion, $category) !== false) {
                return $category;
            }
        }

        // Try to extract search term after "about", "related to", etc.
        $patterns = [
            '/find events about (.+)/i',
            '/search events about (.+)/i',
            '/events related to (.+)/i',
            '/find (.+) events/i',
            '/search for (.+) events/i',
            '/any (.+) events/i',
            '/show me (.+) events/i',
            '/are there (.+) events/i',
            '/looking for (.+) events/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $question, $matches)) {
                return trim($matches[1]);
            }
        }

        // Default to a generic term if we can't extract one
        return '';
    }

    /**
     * Extract event name from the question
     *
     * @param string $question The natural language question
     * @return string
     */
    private function extractEventName(string $question)
    {
        $lowerQuestion = strtolower($question);

        // Try to extract event name after "about", "details of", etc.
        $patterns = [
            '/event details for (.+)/i',
            '/about event (.+)/i',
            '/tell me about event (.+)/i',
            '/details of (.+) event/i',
            '/information about (.+) event/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $question, $matches)) {
                return trim($matches[1]);
            }
        }

        // Default to a generic term if we can't extract one
        return '';
    }

    /**
     * Format the results in a more readable way
     *
     * @param string $rawResult The raw result from the database query
     * @param string $question The original question
     * @return string
     */
    private function formatResults(string $rawResult, string $question)
    {
        // Log the raw result for debugging
        Log::info("DynamicQueryTool formatResults - Raw Result:", ['rawResult' => $rawResult]);

        // Check if there are no results
        if (strpos($rawResult, "No results found") !== false) {
            Log::info("DynamicQueryTool formatResults - No results found");
            return "I couldn't find any events matching your criteria.";
        }

        // Extract the JSON data from the raw result
        if (preg_match('/```json\n(.*)\n```/s', $rawResult, $matches)) {
            $jsonData = $matches[1];
            Log::info("DynamicQueryTool formatResults - Extracted JSON:", ['jsonData' => $jsonData]);

            $data = json_decode($jsonData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("DynamicQueryTool formatResults - JSON decode error:", ['error' => json_last_error_msg()]);
                return $rawResult; // Return the original if we can't parse the JSON
            }

            if (empty($data)) {
                Log::info("DynamicQueryTool formatResults - Empty data after JSON decode");
                return "I couldn't find any events matching your criteria. Please try a different search term or category.";
            }

            // Determine the type of question to format the results appropriately
            $lowerQuestion = strtolower($question);

            // Format for event listing
            if (strpos($lowerQuestion, 'list') !== false ||
                strpos($lowerQuestion, 'show') !== false ||
                strpos($lowerQuestion, 'available') !== false) {
                return $this->formatEventListing($data);
            }

            // Format for event search
            if (strpos($lowerQuestion, 'find') !== false ||
                strpos($lowerQuestion, 'search') !== false) {
                return $this->formatEventListing($data);
            }

            // Format for upcoming events
            if (strpos($lowerQuestion, 'upcoming') !== false ||
                strpos($lowerQuestion, 'future') !== false) {
                return $this->formatEventListing($data);
            }

            // Format for event details
            if (strpos($lowerQuestion, 'details') !== false ||
                strpos($lowerQuestion, 'about') !== false ||
                strpos($lowerQuestion, 'tell me') !== false) {
                if (count($data) === 1) {
                    return $this->formatEventDetails($data[0]);
                }
            }

            // Default formatting for other types of queries
            return $this->formatGenericResults($data);
        }

        // If we couldn't extract JSON, return the original result
        return $rawResult;
    }

    /**
     * Format a list of events
     *
     * @param array $events The events data
     * @return string
     */
    private function formatEventListing(array $events)
    {
        if (empty($events)) {
            return "I couldn't find any events matching your criteria. Please try a different search term or category.";
        }

        $result = "Here are the events I found:\n\n";

        foreach ($events as $event) {
            $result .= "**" . $event['name'] . "**\n";

            // Format date
            if (isset($event['start_date'])) {
                $date = date('F j, Y', strtotime($event['start_date']));
                $result .= "📅 " . $date . "\n";
            }

            // Format venue/location
            if (!empty($event['venue'])) {
                $result .= "📍 " . $event['venue'] . "\n";
            }

            // Format category
            if (!empty($event['category'])) {
                $result .= "🏷️ " . $event['category'] . "\n";
            }

            // Add description if available (shortened)
            if (!empty($event['description'])) {
                $description = strip_tags($event['description']);
                if (strlen($description) > 100) {
                    $description = substr($description, 0, 97) . '...';
                }
                $result .= "📝 " . $description . "\n";
            }

            // Add a separator between events
            $result .= "\n";
        }

        return $result;
    }

    /**
     * Format detailed information about a single event
     *
     * @param array $event The event data
     * @return string
     */
    private function formatEventDetails(array $event)
    {
        $result = "**" . $event['name'] . "**\n\n";

        // Essential information
        if (isset($event['start_date'])) {
            $date = date('F j, Y', strtotime($event['start_date']));
            $result .= "📅 **Date**: " . $date . "\n";
        }

        if (isset($event['start_datetime']) && isset($event['end_datetime'])) {
            $startTime = date('g:i A', strtotime($event['start_datetime']));
            $endTime = date('g:i A', strtotime($event['end_datetime']));
            $result .= "⏰ **Time**: " . $startTime . " - " . $endTime;

            if (isset($event['timezone'])) {
                $result .= " (" . $event['timezone'] . ")";
            }

            $result .= "\n";
        }

        if (!empty($event['venue'])) {
            $result .= "📍 **Venue**: " . $event['venue'] . "\n";
        }

        if (!empty($event['category'])) {
            $result .= "🏷️ **Category**: " . $event['category'] . "\n";
        }

        if (!empty($event['event_type'])) {
            $result .= "🎪 **Type**: " . ucfirst($event['event_type']) . "\n";
        }

        if (!empty($event['currency'])) {
            $result .= "💰 **Currency**: " . $event['currency'] . "\n";
        }

        // Description (if available)
        if (!empty($event['description'])) {
            $description = strip_tags($event['description']);
            if (strlen($description) > 200) {
                $description = substr($description, 0, 197) . '...';
            }
            $result .= "\n📝 **Description**: " . $description . "\n";
        }

        // Add information about repeat
        if (!empty($event['event_repeat']) && $event['event_repeat'] != 'Does not repeat') {
            $result .= "\n🔄 **Repeats**: " . $event['event_repeat'];

            if (!empty($event['repeat_days'])) {
                $result .= " (for " . $event['repeat_days'] . " days)";
            }

            if (!empty($event['end_date'])) {
                $endDate = date('F j, Y', strtotime($event['end_date']));
                $result .= " until " . $endDate;
            }

            $result .= "\n";
        }

        return $result;
    }

    /**
     * Format generic query results
     *
     * @param array $data The query results
     * @return string
     */
    private function formatGenericResults(array $data)
    {
        $result = "Here are the results:\n\n";

        foreach ($data as $index => $item) {
            $result .= "**Item " . ($index + 1) . "**\n";

            foreach ($item as $key => $value) {
                // Skip internal fields and null values
                if (in_array($key, ['id', 'created_at', 'updated_at']) || is_null($value)) {
                    continue;
                }

                // Format the value based on its type
                if (is_array($value)) {
                    $value = json_encode($value);
                } elseif (is_bool($value)) {
                    $value = $value ? 'Yes' : 'No';
                }

                $result .= "- **" . ucwords(str_replace('_', ' ', $key)) . "**: " . $value . "\n";
            }

            $result .= "\n";
        }

        return $result;
    }
}
