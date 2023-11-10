# SPTemplateEngine

SPTemplateEngine is a lightweight PHP templating engine designed to simplify the separation of logic and presentation in web development projects. It allows you to assign variables, render templates, and even call functions within your templates.

## Features

- **Variable Assignment:** Easily assign variables to be used in your templates.
- **Template Rendering:** Replace placeholders in templates with assigned values.
- **Function Support:** Call functions within templates for dynamic content.
- **Namespace Assumption:** Functions are assumed to be in the same namespace as the engine class.
- **Automatic Function Parsing:** Functions are parsed and replaced with their results during rendering.

## Installation

You can install SPTemplateEngine via composer:

```bash
composer require sp-templating-engine/sp-templating-engine
```

## Usage
```php

<?php

// Include Composer autoloader
require 'vendor/autoload.php';

use SelfPhp\SelfPhPTemplatingEngine\SPTemplateEngine;

// Create an instance with a template
$template = new SPTemplateEngine('<p>Hello, {{ $name }}!</p>');

// Assign variables
$template->assign('name', 'John');
$template->assignArray(['age' => 25, 'city' => 'Example City']);

// Render the template
$renderedOutput = $template->render();

echo $renderedOutput;

?>

```

## Function Calls in Templates
You can call functions within your templates using the following syntax:

```php 

<?php 

{{ functionName(arg1, arg2) }}

?>

```

Make sure functions are in the same namespace as the SPTemplateEngine class. If the function exists, it will be called and its result will replace the function call in the template.

## Documentation
For more detailed information, feel free to contact me by sending an email. Click
[here](mailto:gicehajunior76@gmail.com) to send an email a real quick, or raise an issue on GitHub for discussion.

## Version Information
SPTemplateEngine v1.0.0-beta is the first beta version release of the library. Please be aware that there might be additional changes and improvements in future releases. Feel free to use this version for production applications as it is recommended for use in production environments.

## Author
[Giceha Junior](https://github.com/Gicehajunior/) - Original Author, Maintainer, Contributor, and Owner of SPTemplateEngine. To contact me, send an email to 
[Email Me](mailto:gicehajunior76@gmail.com) or raise an issue on GitHub for discussion.

## License
This library is released under the [MIT License](https://github.com/Gicehajunior/SPTemplateEngine/blob/main/LICENSE). See the LICENSE file for details. 

