<?php

if (!function_exists('get_children_data_from_array')) {
    function get_children_data_from_array($parentEloquent, $parentData, $childrenKey, $parentRelationName)
    {
        if (array_key_exists($childrenKey, $parentData)) {
            return array_map(function ($child) use ($parentEloquent, $parentRelationName) {
                return [...$child, $parentRelationName => $parentEloquent->id];
            }, $parentData[$childrenKey]);
        }
        return [];
    }
}
