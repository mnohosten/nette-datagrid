<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class Column
{

    private string $name;
    private string $label;
    /** @var callable|null */
    protected $render = null;

    private static PropertyAccessorInterface $propertyAccessor;

    public function __construct(
        string $name,
        string $label
    )
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function setRender(callable $render)
    {
        $this->render = $render;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function render($item)
    {
        if(isset($this->render)) {
            return call_user_func_array($this->render, [$item, $this->name]);
        }
        return $this->getValue($item);
    }

    protected function getAccessor(): PropertyAccessorInterface
    {
        if(!isset(self::$propertyAccessor)) {
            self::$propertyAccessor = new PropertyAccessor();
        }
        return self::$propertyAccessor;
    }

    protected function getValue($item)
    {
        return $this->getAccessor()->getValue($item, $this->name);
    }
}
