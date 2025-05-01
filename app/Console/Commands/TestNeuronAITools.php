<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Agents\EventureAssistant;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use Illuminate\Support\Facades\Log;

class TestNeuronAITools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:neuron-tools {tool?} {--param1=} {--param2=} {--param3=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Neuron AI tools directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Neuron AI Tools...');
        
        // Create the agent to get access to its tools
        $agent = new EventureAssistant();
        $tools = $this->getToolsFromAgent($agent);
        
        if (empty($tools)) {
            $this->error('No tools found in the agent.');
            return 1;
        }
        
        // List available tools if no tool is specified
        $toolName = $this->argument('tool');
        if (!$toolName) {
            $this->info('Available tools:');
            foreach ($tools as $name => $tool) {
                $this->line("- {$name}: {$tool['description']}");
                if (!empty($tool['properties'])) {
                    $this->line('  Parameters:');
                    foreach ($tool['properties'] as $prop) {
                        $required = $prop['required'] ? '(required)' : '(optional)';
                        $this->line("  - {$prop['name']} {$required}: {$prop['description']}");
                    }
                }
                $this->newLine();
            }
            return 0;
        }
        
        // Check if the specified tool exists
        if (!isset($tools[$toolName])) {
            $this->error("Tool '{$toolName}' not found.");
            return 1;
        }
        
        $tool = $tools[$toolName];
        $this->info("Testing tool: {$toolName}");
        $this->line("Description: {$tool['description']}");
        
        // Collect parameters
        $params = [];
        foreach ($tool['properties'] as $prop) {
            $paramValue = $this->option('param' . $prop['index']);
            
            // If required parameter is missing, prompt for it
            if ($prop['required'] && $paramValue === null) {
                $paramValue = $this->ask("Enter value for {$prop['name']} ({$prop['description']})");
            }
            
            if ($paramValue !== null) {
                $params[$prop['name']] = $paramValue;
                $this->info("Parameter {$prop['name']}: {$paramValue}");
            }
        }
        
        try {
            // Call the tool
            $this->info('Calling tool...');
            $callable = $tool['callable'];
            $result = $callable(...array_values($params));
            
            // Output the result
            $this->newLine();
            $this->info('Tool Result:');
            $this->line($result);
            
            // Log the result for debugging
            Log::info("Neuron AI Tool '{$toolName}' Test Result", [
                'tool' => $toolName,
                'params' => $params,
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            Log::error("Neuron AI Tool '{$toolName}' Test Error", [
                'tool' => $toolName,
                'params' => $params,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Extract tools information from the agent
     *
     * @param EventureAssistant $agent
     * @return array
     */
    private function getToolsFromAgent(EventureAssistant $agent)
    {
        // Use reflection to access the protected tools method
        $reflection = new \ReflectionClass($agent);
        $method = $reflection->getMethod('tools');
        $method->setAccessible(true);
        
        $tools = $method->invoke($agent);
        $toolsInfo = [];
        
        foreach ($tools as $tool) {
            $reflection = new \ReflectionClass($tool);
            
            // Get tool name
            $nameProperty = $reflection->getProperty('name');
            $nameProperty->setAccessible(true);
            $name = $nameProperty->getValue($tool);
            
            // Get tool description
            $descProperty = $reflection->getProperty('description');
            $descProperty->setAccessible(true);
            $description = $descProperty->getValue($tool);
            
            // Get tool properties
            $propsProperty = $reflection->getProperty('properties');
            $propsProperty->setAccessible(true);
            $properties = $propsProperty->getValue($tool);
            
            // Get callable
            $callableProperty = $reflection->getProperty('callable');
            $callableProperty->setAccessible(true);
            $callable = $callableProperty->getValue($tool);
            
            // Format properties
            $formattedProps = [];
            $index = 1;
            foreach ($properties as $prop) {
                $propReflection = new \ReflectionClass($prop);
                
                $propNameProperty = $propReflection->getProperty('name');
                $propNameProperty->setAccessible(true);
                $propName = $propNameProperty->getValue($prop);
                
                $propDescProperty = $propReflection->getProperty('description');
                $propDescProperty->setAccessible(true);
                $propDesc = $propDescProperty->getValue($prop);
                
                $propRequiredProperty = $propReflection->getProperty('required');
                $propRequiredProperty->setAccessible(true);
                $propRequired = $propRequiredProperty->getValue($prop);
                
                $formattedProps[] = [
                    'name' => $propName,
                    'description' => $propDesc,
                    'required' => $propRequired,
                    'index' => $index++
                ];
            }
            
            $toolsInfo[$name] = [
                'description' => $description,
                'properties' => $formattedProps,
                'callable' => $callable
            ];
        }
        
        return $toolsInfo;
    }
}
