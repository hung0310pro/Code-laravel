<?php

namespace Barryvdh\Debugbar\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

/**
 * Collector for Models.
 */
class ModelsCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    public $models = [];

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $events->listen('eloquent.*', function ($event, $models) {
            if (Str::contains($event, 'eloquent.retrieved')) {
                foreach (array_filter($models) as $model) {
                    $class = get_class($model);
                    $this->models[$class] = ($this->models[$class] ?? 0) + 1;
                }
            }
        });
    }

    public function collect()
    {
        ksort($this->models, SORT_NUMERIC);

        return array_reverse($this->models);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'models';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "models" => [
                "icon" => "cubes",
                "widget" => "PhpDebugBar.Widgets.HtmlVariableListWidget",
                "map" => "models",
                "default" => "{}"
            ]
        ];
    }
}
