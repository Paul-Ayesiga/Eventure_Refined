<?php

namespace App\Agents;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Mistral;
use NeuronAI\Chat\History\InMemoryChatHistory;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use App\Tools\EventSearchTool;
use App\Tools\BookingInfoTool;
use App\Tools\EventDetailsTool;
use App\Tools\DatabaseSchemaTool;
use App\Tools\DatabaseQueryTool;
use App\Tools\ModelInfoTool;
use App\Tools\DynamicQueryTool;

class EventureAssistant extends Agent
{
    /**
     * Define the AI provider to use (Mistral in this case)
     */
    protected function provider(): AIProviderInterface
    {
        return new Mistral(
            key: env('MISTRAL_API_KEY', ''),
            model: env('MISTRAL_MODEL', 'mistral-small-latest')
        );
    }

    /**
     * Define the system instructions for the agent
     */
    public function instructions(): string
    {
        return new SystemPrompt(
            background: [
                "You are an AI assistant for Eventure, an event management and ticketing platform. Your name is 'Eventure Assistant'.",
                "You have access to tools that allow you to search for events, check booking information, get detailed event information, and query the database directly.",
                "You can use the database tools to get information about the database schema, execute SQL queries, and get information about Laravel models.",
                "You also have access to a dynamic query tool that can generate and execute SQL queries based on natural language questions.",
                "IMPORTANT: You must ONLY return information that is actually in the database. Do NOT make up or hallucinate events that don't exist in the database. If no events match a query, simply state that no matching events were found."
            ],
            steps: [
                "Listen carefully to the user's request or question",
                "For questions about listing, finding, or showing events, ALWAYS use the dynamic_query tool with the user's question as the 'question' parameter",
                "IMPORTANT: Only return information about events that actually exist in the database. If the dynamic_query tool returns 'I couldn't find any events matching your criteria', do NOT make up events - simply tell the user that no matching events were found",
                "If the user is asking about a specific event by name (e.g., 'Tell me about [event name]'), use the get_event_details tool with the event name",
                "If the user is asking about their bookings, use the get_booking_info tool",
                "If the user is asking about general platform features, explain how Eventure works",
                "For complex database queries that the dynamic_query tool might not handle well:",
                "  1. First use get_database_schema or get_model_info to understand the relevant tables/models",
                "  2. Then use execute_query to run a custom SQL query to get the specific information",
                "Always use the most appropriate tool when responding to specific queries",
                "Always be helpful, concise, and friendly in your responses"
            ],
            output: [
                "Provide clear, concise answers to user questions",
                "When showing information from tools, format it nicely for readability",
                "When showing database query results, explain what the data means in plain language",
                "NEVER make up or hallucinate events that don't exist in the database",
                "If no events match a query, clearly state that no matching events were found and suggest alternatives",
                "Use a friendly, helpful tone",
                "If you don't know something, admit it and offer to help with something else"
            ]
        );
    }

    /**
     * Define the chat history implementation
     *
     * @return \NeuronAI\Chat\History\AbstractChatHistory
     */
    protected function chatHistory(): \NeuronAI\Chat\History\AbstractChatHistory
    {
        return new InMemoryChatHistory(
            contextWindow: 16000 // Adjust based on the model's context window
        );
    }

    /**
     * Define the tools available to the agent
     *
     * @return array
     */
    protected function tools(): array
    {
        return [
            // Tool for searching events
            Tool::make(
                'search_events',
                'Search for events based on keywords, category, or date.',
            )->addProperty(
                new ToolProperty(
                    name: 'query',
                    type: 'string',
                    description: 'The search query (e.g., "music", "conference", etc.)',
                    required: true
                )
            )->addProperty(
                new ToolProperty(
                    name: 'category',
                    type: 'string',
                    description: 'The event category (e.g., "music", "business", "sports")',
                    required: false
                )
            )->addProperty(
                new ToolProperty(
                    name: 'date',
                    type: 'string',
                    description: 'The date to search from in YYYY-MM-DD format',
                    required: false
                )
            )->setCallable(new EventSearchTool()),

            // Tool for getting booking information
            Tool::make(
                'get_booking_info',
                'Get information about bookings for the current user or by reference number.',
            )->addProperty(
                new ToolProperty(
                    name: 'reference',
                    type: 'string',
                    description: 'The booking reference number (optional)',
                    required: false
                )
            )->setCallable(new BookingInfoTool()),

            // Tool for getting detailed event information
            Tool::make(
                'get_event_details',
                'Get detailed information about a specific event.',
            )->addProperty(
                new ToolProperty(
                    name: 'event_id',
                    type: 'string',
                    description: 'The ID or name of the event',
                    required: true
                )
            )->setCallable(new EventDetailsTool()),

            // Tool for getting database schema information
            Tool::make(
                'get_database_schema',
                'Get information about the database schema for specified tables.',
            )->addProperty(
                new ToolProperty(
                    name: 'tables',
                    type: 'string',
                    description: 'Comma-separated list of table names (optional, if not provided all tables will be returned)',
                    required: false
                )
            )->setCallable(new DatabaseSchemaTool()),

            // Tool for executing database queries
            Tool::make(
                'execute_query',
                'Execute a SQL query on the database (SELECT queries only).',
            )->addProperty(
                new ToolProperty(
                    name: 'query',
                    type: 'string',
                    description: 'The SQL query to execute (must be a SELECT query)',
                    required: true
                )
            )->addProperty(
                new ToolProperty(
                    name: 'params',
                    type: 'array',
                    description: 'Parameters for the query (optional)',
                    required: false
                )
            )->addProperty(
                new ToolProperty(
                    name: 'limit',
                    type: 'integer',
                    description: 'Maximum number of rows to return (default: 10)',
                    required: false
                )
            )->setCallable(new DatabaseQueryTool()),

            // Tool for getting model information
            Tool::make(
                'get_model_info',
                'Get information about Laravel models.',
            )->addProperty(
                new ToolProperty(
                    name: 'models',
                    type: 'string',
                    description: 'Comma-separated list of model names (optional, if not provided all models will be returned)',
                    required: false
                )
            )->setCallable(new ModelInfoTool()),

            // Tool for dynamic query generation and execution
            Tool::make(
                'dynamic_query',
                'IMPORTANT: Use this tool for all event listing and searching. It generates and executes a SQL query based on a natural language question.',
            )->addProperty(
                new ToolProperty(
                    name: 'question',
                    type: 'string',
                    description: 'The natural language question to answer (e.g., "list all available events", "find music events", "show upcoming events")',
                    required: true
                )
            )->setCallable(new DynamicQueryTool())
        ];
    }
}
