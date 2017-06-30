<?php

namespace UniversityOfAdelaide\OpenShift\Model;

class Model
{
    const DEFAULT = [];

    public static function create($data = [])
    {
        return array_merge_recursive(self::DEFAULT, $data);
    }
}
