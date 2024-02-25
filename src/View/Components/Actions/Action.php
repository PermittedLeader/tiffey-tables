<?php

namespace Permittedleader\TablesForLaravel\View\Components\Actions;

use Closure;

class Action
{
    public string $component = 'actions.default';

    public Closure|string|bool $route = false;

    public Closure|bool $authGate = true;

    public $title = '';

    public $action;

    public $icon = 'fa-solid fa-eye';

    public bool $showLabel = false;

    public const ACTION_LINK = 'link';
    public const ACTION_LIVEWIRE = 'livewire';

    /**
     * Action component
     *
     * @param  Closure|string  $routeName Pass either a closure providing the route or the route name
     * @param  string  $title Title to be displayed
     */
    public function __construct($routeName, $title, $method = self::ACTION_LINK)
    {
        if($method == self::ACTION_LINK){
            if ($routeName instanceof Closure) {
                $this->route = $routeName;
            } else {
                $this->route = function ($data) use ($routeName) {
                    return route($routeName, $data);
                };
            }
        } elseif ($method == self::ACTION_LIVEWIRE){
            $this->route = false;
            $this->action($routeName);
        }
        

        $this->title = $title;
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

    public static function makeAction($actionName, $title)
    {
        return new static($actionName,$title, self::ACTION_LIVEWIRE);
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
     * Define if the user should have this action used for this Action
     *
     * @param  bool  $gate
     * @return self
     */
    public function gate(bool|Closure $gate)
    {
        if ($gate instanceof Closure) {
            $this->authGate = $gate;
        } else {
            $this->authGate = function ($data) use ($gate) {
                return $gate;
            };
        }

        return $this;
    }

    /**
     * Get the Route for the component to render
     *
     * @param  object|array  $data
     * @return string
     */
    public function getGate($data)
    {
        if ($this->authGate instanceof Closure) {
            return ($this->authGate)($data);
        } else {
            return route($this->authGate);
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
     * Create an edit component
     *
     * @param  Closure|string  $routeName
     * @return self
     */
    public static function edit($routeName)
    {
        $action = new static($routeName,'Edit');

        return $action->component('edit')->gate(function($data){
            return auth()->user()->can('update',$data);
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
        $action = new static($routeName,'View');

        return $action->gate(function($data){
            return auth()->user()->can('view',$data);
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
        $action = new static($routeName,'Delete');

        return $action->component('delete')->gate(function($data){
            return auth()->user()->can('delete',$data);
        });
    }
}
