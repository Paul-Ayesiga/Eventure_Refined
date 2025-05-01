<?php

namespace App\Tools;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class ModelInfoTool
{
    /**
     * Get information about Laravel models
     *
     * @param string|array $models Model names to get info for (comma-separated string or array)
     * @return string
     */
    public function __invoke($models = null)
    {
        try {
            // If models is a string, convert to array
            if (is_string($models)) {
                $models = array_map('trim', explode(',', $models));
            }
            
            // If no models specified, get all models
            if (empty($models)) {
                $models = $this->getAllModels();
            } else {
                // Ensure model names are properly formatted
                $models = array_map(function($model) {
                    if (!Str::startsWith($model, 'App\\Models\\')) {
                        return 'App\\Models\\' . $model;
                    }
                    return $model;
                }, $models);
            }
            
            $result = "# Laravel Model Information\n\n";
            
            foreach ($models as $modelClass) {
                // Skip if the model doesn't exist
                if (!class_exists($modelClass)) {
                    $result .= "Model `{$modelClass}` not found.\n\n";
                    continue;
                }
                
                $shortName = (new ReflectionClass($modelClass))->getShortName();
                $result .= "## Model: {$shortName}\n\n";
                
                // Get table name
                $model = new $modelClass();
                $table = $model->getTable();
                $result .= "- **Table**: `{$table}`\n";
                
                // Get primary key
                $primaryKey = $model->getKeyName();
                $result .= "- **Primary Key**: `{$primaryKey}`\n";
                
                // Get fillable attributes
                $fillable = $model->getFillable();
                if (!empty($fillable)) {
                    $result .= "- **Fillable**: `" . implode('`, `', $fillable) . "`\n";
                }
                
                // Get guarded attributes
                $guarded = $model->getGuarded();
                if (!empty($guarded) && $guarded !== ['*']) {
                    $result .= "- **Guarded**: `" . implode('`, `', $guarded) . "`\n";
                }
                
                // Get timestamps info
                $timestamps = $model->usesTimestamps() ? 'Yes' : 'No';
                $result .= "- **Uses Timestamps**: {$timestamps}\n";
                
                // Get soft deletes info
                $softDeletes = in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive($modelClass)) ? 'Yes' : 'No';
                $result .= "- **Uses Soft Deletes**: {$softDeletes}\n\n";
                
                // Get relationships
                $result .= "### Relationships:\n\n";
                $relationships = $this->getModelRelationships($modelClass);
                
                if (empty($relationships)) {
                    $result .= "No relationships found.\n\n";
                } else {
                    foreach ($relationships as $name => $type) {
                        $result .= "- **{$name}**: {$type}\n";
                    }
                    $result .= "\n";
                }
                
                // Get scopes
                $scopes = $this->getModelScopes($modelClass);
                if (!empty($scopes)) {
                    $result .= "### Scopes:\n\n";
                    foreach ($scopes as $scope) {
                        $result .= "- **{$scope}**\n";
                    }
                    $result .= "\n";
                }
                
                // Get accessors and mutators
                $accessorsMutators = $this->getAccessorsMutators($modelClass);
                if (!empty($accessorsMutators)) {
                    $result .= "### Accessors & Mutators:\n\n";
                    foreach ($accessorsMutators as $method => $type) {
                        $result .= "- **{$method}**: {$type}\n";
                    }
                    $result .= "\n";
                }
                
                // Add a sample instance if available
                try {
                    $sample = $modelClass::first();
                    if ($sample) {
                        $result .= "### Sample Instance:\n\n";
                        $result .= "```json\n" . json_encode($sample->toArray(), JSON_PRETTY_PRINT) . "\n```\n\n";
                    }
                } catch (\Exception $e) {
                    // Model might not have any instances
                    Log::warning("Could not get sample instance for model {$modelClass}: " . $e->getMessage());
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error in ModelInfoTool: " . $e->getMessage());
            return "Error retrieving model information: " . $e->getMessage();
        }
    }
    
    /**
     * Get all model classes in the application
     *
     * @return array
     */
    private function getAllModels()
    {
        $models = [];
        $modelsPath = app_path('Models');
        
        if (is_dir($modelsPath)) {
            $files = scandir($modelsPath);
            
            foreach ($files as $file) {
                if (Str::endsWith($file, '.php')) {
                    $modelName = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);
                    if (class_exists($modelName)) {
                        $models[] = $modelName;
                    }
                }
            }
        }
        
        return $models;
    }
    
    /**
     * Get relationships for a model
     *
     * @param string $modelClass
     * @return array
     */
    private function getModelRelationships($modelClass)
    {
        $relationships = [];
        $reflection = new ReflectionClass($modelClass);
        
        $relationshipTypes = [
            'hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 
            'hasManyThrough', 'hasOneThrough', 'morphTo', 
            'morphOne', 'morphMany', 'morphToMany', 'morphedByMany'
        ];
        
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // Skip methods that are inherited from the parent class
            if ($method->class !== $modelClass) {
                continue;
            }
            
            // Get method body
            $fileName = $method->getFileName();
            $startLine = $method->getStartLine();
            $endLine = $method->getEndLine();
            
            if ($fileName && $startLine && $endLine) {
                $lines = file($fileName);
                $methodBody = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
                
                // Check if method contains a relationship
                foreach ($relationshipTypes as $relationType) {
                    if (preg_match('/\$this->' . $relationType . '\s*\(/i', $methodBody)) {
                        $relationships[$method->getName()] = $relationType;
                        break;
                    }
                }
            }
        }
        
        return $relationships;
    }
    
    /**
     * Get scopes for a model
     *
     * @param string $modelClass
     * @return array
     */
    private function getModelScopes($modelClass)
    {
        $scopes = [];
        $reflection = new ReflectionClass($modelClass);
        
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            if (Str::startsWith($methodName, 'scope')) {
                $scopeName = Str::camel(substr($methodName, 5));
                $scopes[] = $scopeName;
            }
        }
        
        return $scopes;
    }
    
    /**
     * Get accessors and mutators for a model
     *
     * @param string $modelClass
     * @return array
     */
    private function getAccessorsMutators($modelClass)
    {
        $accessorsMutators = [];
        $reflection = new ReflectionClass($modelClass);
        
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();
            
            // Check for accessors (get{Attribute}Attribute)
            if (preg_match('/^get(.+)Attribute$/', $methodName, $matches)) {
                $attributeName = Str::snake($matches[1]);
                $accessorsMutators[$attributeName] = 'accessor';
            }
            
            // Check for mutators (set{Attribute}Attribute)
            if (preg_match('/^set(.+)Attribute$/', $methodName, $matches)) {
                $attributeName = Str::snake($matches[1]);
                $accessorsMutators[$attributeName] = 'mutator';
            }
        }
        
        return $accessorsMutators;
    }
}
