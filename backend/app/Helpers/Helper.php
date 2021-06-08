<?php

if (!function_exists('get_children_data_from_array')) {
    function get_children_data_from_array($parentEloquent, $parentData, $childrenKey, $parentRelationName)
    {
        if (isset($parentData[$childrenKey])) {
            return array_map(function ($child) use ($parentEloquent, $parentRelationName) {
                $child[$parentRelationName] = $parentEloquent->id;
                return $child;
            }, $parentData[$childrenKey]);
        }
        return [];
    }
}
if (!function_exists('shallow_copy_array')) {
    function shallow_copy_array($source)
    {
        $dist = [];
        foreach ($source as $key => $value) {
            if (!is_array($value)) {
                $dist[$key] = $value;
            }
        }
        return $dist;
    }
}
if (!function_exists('shallow_copy_array_of_array')) {
    function shallow_copy_array_of_array($source)
    {
        $dist = [];
        foreach ($source as $element) {
            $dist[] = shallow_copy_array($element);
        }
        return $dist;
    }
}
