<?php

namespace Permittedleader\Tables\View\Components\Actions;

use Closure;

class Action
{
    public string $component = 'actions.default';

    public Closure|string|bool $route = false;

    public Closure|bool $authGate = true;

    public $action;

    public $icon = 'fa-solid fa-eye';

    public bool $showLabel = false;

    public const ACTION_LINK = 'link';
    public const ACTION_LIVEWIRE = 'livewire';
    public const ACTION_CLICK = 'alpine';

    public string|bool $color = false;

    /**
     * Action component
     *
     * @param  Closure|string  $routeName Pass either a closure providing the route or the route name
     * @param  string  $title Title to be displayed
     */
    public function __construct($routeName, public $title = '', public $method = self::ACTION_LINK)
    {
        if($method == self::ACTION_LINK){
            if ($routeName instanceof Closure) {
                $this->route = $routeName;
            } else {
                $this->route = function ($data) use ($routeName) {
                    return route($routeName, $data);
                };
            }
        } elseif ($method == self::ACTION_LIVEWIRE||$method == self::ACTION_CLICK){
            $this->route = false;
            $this->action($routeName);
        }
    }

    /**
     * Make an Action component
     *
     * @param  Closure|string  $routeName Pass either a closure providing the route or the route name
     * @param  string  $title Title to be displayed
     */
    public static function make($routeName, $title)
    {
        return self::makeLink($routeName, $title);
    }

    /**
     * Make an Action component
     *
     * @param  Closure|string  $routeName Pass either a closure providing the route or the route name
     * @param  string  $title Title to be displayed
     */
    public static function makeLink($routeName, $title)
    {
        return new static($routeName,$title, self::ACTION_LINK);
    }

    /**
     * makeAction
     *
     * @param Closure|string $actionName Function to load action name to be called
     * @param string $title
     * @return self
     */
    public static function makeAction($actionName, $title)
    {
        return new static($actionName,$title, self::ACTION_LIVEWIRE);
    }

    public static function makeClick($action, $title)
    {
        return new static($action,$title, self::ACTION_CLICK);
    }

    /**
     * Define the component used for this Action
     *
     * @param  string  $component
     * @return self
     */
    public function component($component)
    {
        $this->component = 'actions.'.$component;

        return $this;
    }

    /**
     * Define the color to render the button
     *
     * @param  string  $component
     * @return self
     */
    public function color($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Define if the user should have this action used for this Action
     *
     * @param  bool  $gate
     * @return self
     */
    public function gate(bool|string|Closure $gate)
    {
        if ($gate instanceof Closure) {
            $this->authGate = $gate;
        } elseif(is_bool($gate)) {
            $this->authGate = function ($data) use ($gate) {
                return $gate;
            };
        } else {
            $this->authGate = function ($data) use ($gate) {
                return auth()->user()->can($gate,$data);
            };
        }

        return $this;
    }

    /**
     * Get the gate to use 
     *
     * @param  object|array  $data
     * @return string
     */
    public function getGate($data)
    {
        if ($this->authGate instanceof Closure) {
            return ($this->authGate)($data);
        } else {
            return $this->authGate;
        }
    }

    /**
     * Define the icon used for this Action
     *
     * @param  string  $iconClasses
     * @return self
     */
    public function icon($iconClasses)
    {
        $this->icon = $iconClasses;

        return $this;
    }

    /**
     * Set the route used for this Action
     *
     * @return self
     */
    public function setRoute(Closure $route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get the Route for the component to render
     *
     * @param  object|array  $data
     * @return string
     */
    public function getRoute($data)
    {
        if ($this->route instanceof Closure) {
            return ($this->route)($data);
        } else {
            return route($this->route);
        }
    }

    /**
     * Get the action for the component to render
     *
     * @param  object|array  $data
     * @return string
     */
    public function getLivewireAction($data)
    {
        if ($this->action instanceof Closure) {
            return ($this->action)($data);
        } else {
            return $this->action;
        }
    }

    /**
     * Define an action (e.g. wire:click) for this component
     *
     * @param  string  $action
     * @return self
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get the action for the component to render
     *
     * @param  object|array  $data
     * @return string
     */
    public function getAction($data)
    {
        if($this->method == self::ACTION_LINK){
            $action =  ['href'=> $this->getRoute($data)];
        } elseif ($this->method == self::ACTION_LIVEWIRE) {
            $action = ['wire:click'=> $this->getLivewireAction($data)];
        } elseif ($this->method == self::ACTION_CLICK){
            $action = ['@click'=> $this->getLivewireAction($data)];
        }

        $action['title'] = $this->title;

        return array_merge($this->getColor(),$action);
    }

    /**
     * Get color for the button
     *
     * @return array
     */
    public function getColor():array
    {
        if ($this->color){
            return ['color'=>$this->color];
        } else {
            return [];
        }
    }

    /**
     * Determine if the label should be shown
     *
     * @param boolean $showLabel
     * @return self
     */
    public function showLabel($showLabel = true)
    {
        $this->showLabel = $showLabel;
        return $this;
    }

    /**
     * Render this Action component for the table
     *
     * @return View
     */
    public function render()
    {
        
        return $this->getGate([]) ? view('tables::components.'.$this->component, ['actionComponent' => $this, 'data' => []]) : '';
    }

    /**
     * Render this Action component for a Table Row
     *
     * @param  object|array  $data
     * @return View
     */
    public function renderForRow($data)
    {
        return $this->getGate($data) ? view('tables::components.'.$this->component, ['actionComponent' => $this, 'data' => $data]) : '';
    }

    // Convenience functions
    
    /**
     * Create an create component
     *
     * @param  Closure|string  $routeName
     * @return self
     */
    public static function create($routeName)
    {
        $action = new static($routeName,__('tables::tables.actions.create'));

        return $action->icon('fa-solid fa-plus')->color('bg-success-light')->gate(function ($data) {
            return auth()->user()->can('create', $data);
        });
    }

    /**
     * Create an edit component
     *
     * @param  Closure|string  $routeName
     * @return self
     */
    public static function edit($routeName)
    {
        $action = new static($routeName,__('tables::tables.actions.update'));

        return $action->component('default')->icon('fa-solid fa-pen-to-square')->gate(function ($data) {
            return auth()->user()->can('update', $data);
        });
    }

    /**
     * Create a show component
     *
     * @param  Closure|string  $routeName
     * @return self
     */
    public static function show($routeName)
    {
        $action = new static($routeName,__('tables::tables.actions.retrieve'));

        return $action->gate(function ($data) {
            return auth()->user()->can('view', $data);
        });
    }

    /**
     * Create a delete component
     *
     * @param  Closure|string  $routeName
     * @return self
     */
    public static function delete($routeName)
    {
        $action = new static($routeName,__('tables::tables.actions.destroy'));

        return $action->component('default')->icon('fa-solid fa-trash')->color('bg-danger-light')->gate(function ($data) {
            if (method_exists($data, 'bootSoftDeletes')) {
                return auth()->user()->can('delete', $data) && ! $data->trashed();
            } else {
                return auth()->user()->can('delete', $data);
            }

        });
    }
}
