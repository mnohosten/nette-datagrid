<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid;

class Action
{
    private string $name;
    private string $label;
    private string $href;
    /** @var callable|null */
    protected $render = null;

    public function __construct(
        string $name,
        string $label,
        string $href
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->href = $href;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setRender(callable $render)
    {
        $this->render = $render;
    }

    public function hasCustomRender(): bool
    {
        return !is_null($this->render);
    }

    public function render(Action $action, $item)
    {
        if(isset($this->render)) {
            return call_user_func_array($this->render, [$action, $item]);
        }
    }

}
