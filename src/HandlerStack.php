<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */
declare(strict_types=1);

namespace SimplePie;

class HandlerStack
{
    /** @var callable */
    protected $handler;

    /** @var array */
    protected $stack = [];

    /** @var callable|null */
    protected $cached;

    /**
     * Creates a default handler stack that can be used by clients.
     * @param  callable|null $handler [description]
     * @return [type]                 [description]
     */
    public static function create(callable $handler = null)
    {
    }

    /**
     * [__construct description]
     * @param callable|null $handler [description]
     */
    public function __construct(callable $handler = null)
    {
        $this->handler = $handler;
    }

    /**
     * Invokes the handler stack as a composed handler
     */
    public function __invoke(RequestInterface $request, array $options)
    {
    }

    public function __toString()
    {
        $depth = 0;
        $stack = [];
        if ($this->handler) {
            $stack[] = "0) Handler: " . $this->debugCallable($this->handler);
        }

        $result = '';
        foreach (array_reverse($this->stack) as $tuple) {
            $depth++;
            $str = "{$depth}) Name: '{$tuple[1]}', ";
            $str .= "Function: " . $this->debugCallable($tuple[0]);
            $result = "> {$str}\n{$result}";
            $stack[] = $str;
        }

        foreach (array_keys($stack) as $k) {
            $result .= "< {$stack[$k]}\n";
        }

        return $result;
    }

    public function setHandler(callable $handler)
    {
        $this->handler = $handler;
        $this->cached = null;
    }

    public function hasHandler()
    {
        return (bool) $this->handler;
    }

    public function unshift(callable $middleware, $name = null)
    {
        array_unshift($this->stack, [$middleware, $name]);
        $this->cached = null;
    }

    public function push(callable $middleware, $name = '')
    {
        $this->stack[] = [$middleware, $name];
        $this->cached = null;
    }

    public function before($findName, callable $middleware, $withName = '')
    {
        $this->splice($findName, $withName, $middleware, true);
    }

    public function after($findName, callable $middleware, $withName = '')
    {
        $this->splice($findName, $withName, $middleware, false);
    }

    public function remove($remove)
    {
        $this->cached = null;
        $idx = is_callable($remove) ? 0 : 1;

        $this->stack = array_values(array_filter(
            $this->stack,
            function ($tuple) use ($idx, $remove) {
                return $tuple[$idx] !== $remove;
            }
        ));
    }

    public function resolve()
    {
        if (!$this->cached) {
            if (!($prev = $this->handler)) {
                throw new \LogicException('No handler has been specified');
            }

            foreach (array_reverse($this->stack) as $fn) {
                $prev = $fn[0]($prev);
            }

            $this->cached = $prev;
        }

        return $this->cached;
    }

    protected function findByName($name)
    {
        foreach ($this->stack as $k => $v) {
            if ($v[1] === $name) {
                return $k;
            }
        }

        throw new \InvalidArgumentException("Middleware not found: $name");
    }

    /**
     * Splices a function into the middleware list at a specific position.
     */
    protected function splice($findName, $withName, callable $middleware, $before)
    {
        $this->cached = null;
        $idx = $this->findByName($findName);
        $tuple = [$middleware, $withName];

        if ($before) {
            if ($idx === 0) {
                array_unshift($this->stack, $tuple);
            } else {
                $replacement = [$tuple, $this->stack[$idx]];
                array_splice($this->stack, $idx, 1, $replacement);
            }
        } elseif ($idx === count($this->stack) - 1) {
            $this->stack[] = $tuple;
        } else {
            $replacement = [$this->stack[$idx], $tuple];
            array_splice($this->stack, $idx, 1, $replacement);
        }
    }

    /**
     * Provides a debug string for a given callable.
     */
    private function debugCallable($fn)
    {
        if (is_string($fn)) {
            return "callable({$fn})";
        }

        if (is_array($fn)) {
            return is_string($fn[0])
                ? "callable({$fn[0]}::{$fn[1]})"
                : "callable(['" . get_class($fn[0]) . "', '{$fn[1]}'])";
        }

        return 'callable(' . spl_object_hash($fn) . ')';
    }
}
