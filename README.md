# Mabrouk/ProjectSetting

mabrouk/project-setting is a Laravel api package for dealing with project settings.

## Table of Content
[Usage sequence](#usage-sequence)

[Installation](#Installation)

[Configurations according to project needs](#Configurations-according-to-project-needs)

[Out of the box models](#Out-of-the-box-models)

[Out of the box routes](#Out-of-the-box-routes)

[What else?](#What-else?)

<!-- [Models Api Resources to expect in requests response](#Models-Api-Resources-to-expect-in-requests-response) -->

[Any thing else?](#Any-thing-else?)

[License](#License)

## Usage sequence

> After installation and modifing configuration:

* run command ```php artisan setting:install```.
* include predefined routes which control project setting types, groups, sections and settings display names in your api documentation to make it available for implementation from frontend developer side. or guide frontend developer to this documentation [Models Api Resources to expect in requests response](#Models-Api-Resources-to-expect-in-requests-response) section

## Installation

You can install the package using composer.

```bash
composer require mabrouk/project-setting
```

* Now you need to run the following ```command``` in order to migrate package tables and publish ```project_settings.php``` config file to config directory

```bash
php artisan setting:install
```

## Configurations according to project needs

Config file have several configuration options and already have enough comments to describe every key meaning and how to use.

You may access it under ```config/project_settings.php```

> After modifying ```project_settings.php``` config file don't forget to run below command:

```bash
php artisan config:cache
```

## Out of the box models

We have 4 basic models to deal with:

- ```ProjectSettingType```
- ```ProjectSettingGroup```
- ```ProjectSettingSection```
- ```ProjectSetting```

## Out of the box routes

Let's run the ```route:list``` command and discover our package predefined routes

```bash
php artisan route:list
```

## What else?

// to be continue

<!-- ## Models Api Resources to expect in requests response -->

<!-- - ProjectSettingGroupResource returned in all permission-groups requests except index
```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => ProjectSettingResource::collection($this->permissions),
        ];
    }
}
```

- ProjectSettingGroupSimpleResource returned in permission-groups index request

```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingGroupSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
```

- ProjectSettingResource

```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'selected' => $this->isSelected,
            'group' => new ProjectSettingGroupSimpleResource($this->group),
            'sub_permissions' => SubProjectSettingResource::collection($this->subProjectSettings),
        ];
    }
}
```

- SubProjectSettingResource

```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubProjectSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'selected' => $this->isSelected,
        ];
    }
}
```

- RoleResource ==> Used in role crud except index

```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Mabrouk\ProjectSetting\Models\ProjectSettingGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permission_groups' => ProjectSettingGroupResource::collection(ProjectSettingGroup::all()),
        ];
    }
}
```

- RoleSimpleResource ==> used in roles index only

```php
<?php

namespace Mabrouk\ProjectSetting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
``` -->

## Any thing else?
Actually one more thing to know is that this package depend on [mabrouk/translatable](https://github.com/ah-mabrouk/Translatable) package in order to handle translation dynamically for any chosen language.

> You will need to pass additional input "locale" in update requests of mentioned models and need to create groups and sections with your application default language.

To get response with desired language you need to pass additional header to your requests "X-locale" with one of the available locales in your application

> Both "locale" and "X-locale" accept values like ['en', 'ar', 'fr', ...] etc depending on languages you support in your project.

## License

mabrouk/project-setting package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
