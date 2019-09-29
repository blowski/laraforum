<?php

function create($class, $attributes = [], int $count = 1)
{
    if($count < 1) {
        throw new \Exception("Have to create at least 1, $count passed");
    }

    if($count > 1) {
        return factory($class, $count)->create($attributes);
    }

    return factory($class)->create($attributes);
}

function make($class, $attributes = [], int $count = 1)
{
    if($count < 1) {
        throw new \Exception("Have to make at least 1, $count passed");
    }

    if($count > 1) {
        return factory($class, $count)->make($attributes);
    }

    return factory($class)->make($attributes);
}
