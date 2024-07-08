<?php

function add_action($hook, $callback, $priority = 10)
{
    HookHelper::addAction($hook, $callback, $priority);
}

function add_filter($hook, $callback, $priority = 10)
{
    HookHelper::addFilter($hook, $callback, $priority);
}

function apply_filters($hook, $value, ...$args)
{
    return HookHelper::applyFilters($hook, $value, $args);
}
