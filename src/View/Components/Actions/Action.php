<?php

namespace Permittedleader\TablesForLaravel\View\Components\Actions;

use Closure;

class Action
{
    public string $component = 'actions.default';

    public $route;

    public $gate = false;

    public $title = '';

    public $action;

    public $icon = 'fa-solid fa-eye';

    /**
     * Action component
     *
     * @param  Closure|string  $routeName Pass either a closure providing the route or the route name
     * @param  string  $title Title to be displayed
     */
    public function __construct($routeName, $title)
    {
        if ($routeName instanceof Closure) {
            $this->route = $routeName;
        } else {
            $this->route = function ($data) use ($routeName) {
                return route($routeName, $data);
            };
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
        return new static($routeName,$title);
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
     * Define the gate used for this Action
     *
     * @param  string  $gate
     * @return self
     */
    public function gate($gate)
    {
        $this->gate = $gate;

        return $this;
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
     * Render this Action component for the table
     *
     * @return View
     */
    public function render()
    {
        return view('tables::components.'.$this->component, ['actionComponent' => $this, 'data' => []]);
    }

    /**
     * Render this Action component for a Table Row
     *
     * @param  object|array  $data
     * @return View
     */
    public function renderForRow($data)
    {
        return view('tables::components.'.$this->component, ['actionComponent' => $this, 'data' => $data]);
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

        return $action->component('edit')->gate('update');
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

        return $action->gate('view');
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

        return $action->component('delete')->gate('delete');
    }
}
