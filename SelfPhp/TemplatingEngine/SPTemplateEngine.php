<?php

namespace SelfPhp\TemplatingEngine;

use SelfPhp\SP;

/**
 * *******************************************************
 * 
 *           SELF-PHP TEMPLATING ENGINE CLASS
 * 
 * The SPTemplateEngine class provides a lightweight templating
 * solution for PHP projects, allowing easy separation of logic
 * and presentation.
 * 
 * [ Features ]
 * - Assign variables using the assign() and assignArray() methods.
 * - Render templates by replacing placeholders with assigned values.
 * - Support for calling functions within templates (e.g., {{ env('VAR_NAME') }}).
 * - Functions are assumed to be in the same namespace as the engine class.
 * - Automatic parsing of functions and replacing them with their results.
 * 
 * [ Example Usage ]
 * $template = new SPTemplateEngine('<p>Hello, {{ $name }}!</p>');
 * $template->assign('name', 'John');
 * $template->assignArray(['age' => 25, 'city' => 'Example City']);
 * $renderedOutput = $template->render();
 * 
 * [ Note ]
 * - Functions in templates should follow the syntax {{ functionName(arg1, arg2) }}.
 * - Ensure that assigned variables match the placeholders in the template.
 * 
 * [ Version Information ]
 * SPTemplateEngine v1.0.0
 * 
 * [ Author ]
 * Giceha Junior - https://github.com/Gicehajunior
 * 
 * [ License ]
 * This class is released under the MIT License. https://github.com/Gicehajunior/SPTemplateEngine/blob/main/LICENSE
 * 
 * *******************************************************
 */
class SPTemplateEngine extends SP{
    /**
     * @var string $template The template string to be rendered.
     */
    private $template;

    /**
     * @var array $variables An associative array to store assigned variables.
     */
    private $variables;

    /**
     * @var string $selfphp The selfphp class instance. 
     */
    private $selfphp;

    /**
     * Constructor method for initializing the SPTemplateEngine instance.
     *
     * @param string $template The template string to be rendered.
     */
    public function __construct($template) {
        $this->template = $template;
        $this->variables = [];

        $this->selfphp = new SP();
    } 

    /**
     * Loads the content of a file and includes it in the template.
     * 
     * @param string $file The name of the file to extend.
     * @param mixed $data The data to be passed to the extended file.
     * @return mixed The content of the extended file.
     */
    function page_extends($file, $data=null) {   
        $filecontent = $this->selfphp->resource($file, $data);

        return $filecontent;
    }

    /**
     * Handles file inclusions in the template.
     *
     * @param string $template The template string to be processed.
     * @return string The processed template string with file inclusions.
     */
    private function handleFileInclusions($template) {
        $output = $template;

        // Handle file inclusions like @extends(__app__)
        $output = preg_replace_callback('/{{\s*@extends\(\s*"__([^"]+)__"\s*\)\s*}}/', function ($matches) {
            $filename = $matches[1]; 
            $filecontent = $this->page_extends($filename); // Adjust the path as needed
            
            // check if the filecontent is not empty and return the filecontent
            if (!empty($filecontent)) {
                return $filecontent;
            }
            
            return $matches[0]; // Return the original expression if file not found
        }, $output);  

        return $output;
    }

    /**
     * Assigns a variable with a specified key and value.
     *
     * @param string $key   The key for the assigned variable.
     * @param mixed  $value The value to be assigned to the variable.
     */
    public function assign($key, $value) {
        $this->variables[$key] = $value;
    }

    /**
     * Assigns variables from an associative array.
     *
     * @param array $array An associative array of variables to be assigned.
     */
    public function assignArray($array = []) {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $this->variables[$key] = $value;
            }
        }
    }

    /**
     * Parses functions within the template and replaces them with their results.
     *
     * @param string $template The template string to be parsed.
     * @return string The template string with functions replaced by their results.
     */
    public function parseFunctions($template) {
        $output = $template;
        
        // Handle function calls like {{ env('VAR_NAME') }}
        $output = preg_replace_callback('/{{\s*([\w]+)\s*\((.*?)\)\s*}}/', function ($matches) {
            $functionName = $matches[1];
            $arguments = $matches[2];
            
            // Split the arguments and remove any quotes around them
            // if an argument is null, do not map it to trim function
            $arguments = array_map(function ($arg) {
                if ($arg === 'null') {
                    return null;
                }
                return trim($arg, '"\'');
            }, explode(',', $arguments));

            // Assuming functions are in the same namespace
            $fullyQualifiedFunction = $functionName;

            if (function_exists($fullyQualifiedFunction)) {
                return call_user_func_array($fullyQualifiedFunction, $arguments);
            }
            return $matches[0]; // Return the original expression if function not found
        }, $output);

        return $output;
    }

    /**
     * Renders the template by replacing placeholders with assigned values.
     *
     * @return string The rendered template string.
     */
    public function render() {
        $output = $this->template; 

        foreach ($this->variables as $key => $value) { 
            $placeholder = preg_quote(trim('$' . $key . ''), '/');
            $output = preg_replace_callback('/{{\s*' . $placeholder . '\s*}}/i', function ($matches) use ($value) {
                return $value;
            }, $output);
        }

        $output = $this->parseFunctions($output);
        $output = $this->handleFileInclusions($output); 
        
        return $output;
    }
}