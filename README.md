# Getting Started With MateLemonBundle

This Bundle generates CRUD for your entities into your Symfony application 
with the ability to customize your Controllers/Forms/Views files standards.

1. [Installation](#installation)
2. [Run Lemon Generator](#run-lemon-generator)

## Installation

### Prerequisites

This bundle requires the following additional package:

* Symfony 2.8.x or 3.2.x

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest version of this bundle:

``` bash
$ composer require mate/lemon-bundle dev-master
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

``` php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Mate\LemonBundle\MateLemonBundle(),
        );
    }
}
```

### Step 3: Add the overriding parameter

Add the overriding parameter to the `app/config/parameters.yml` file:

```yaml
# app/config/parameters.yml
mate.lemon.template.override: false
```
NOTE: If you want to override the default Lemon generator templates, you have to turn this parameter to true, and then create your own template system by adding new folder to your bundle called `LemonTemplate/`.

## Run Lemon Generator

After creating and generating your own entities with Doctrine, you should pass the task for the Lemon Generator command.

``` bash
$ php bin/console mate:lemon:generate:full
```

The `mate:lemon:generate:full` command generates CRUD for a given **bundle** and **entity**.
After running this command, you have to just follow the instructions.


**What you'll get:**

| Element                    | Path                                                                                                     |
|----------------------------|---------------------------------------------------------------------------------------|
| Controller                 | !YourBundle!/Controller/!YourEntity!/!EntityName!Controller.php                       |
| Form                       | !YourBundle!/Form/!EntityName!Type.php                                                |
| Form (View)                | !ViewsFolder!/LemonGenerator/!EntityName!/form.html.twig                              |
| Index (View)               | !ViewsFolder!/LemonGenerator/!EntityName!/index.html.twig                             |
| Show (View)                | !ViewsFolder!/LemonGenerator/!EntityName!/show.html.twig                              |
| Menu (View)                | !ViewsFolder!/LemonGenerator/Include/menu.html.twig                                   |
| Header (View)              | !ViewsFolder!/LemonGenerator/Include/header.html.twig                                 |
| Layout (View)              | !ViewsFolder!/LemonGenerator/layout.html.twig                                         |

