<?php

$result = buildTree($cats, $parent_id);
echo $result;

function buildTree($cats, $parent_id, $only_parent = false) {
    if (is_array($cats) and isset($cats[$parent_id])) {
        $tree = '<ul>';
        if ($only_parent == false) {
            foreach ($cats[$parent_id] as $cat) {
                $tree .= '<li>' . $cat->name;
                $tree .= buildTree($cats, $cat->id);
                $tree .= '</li>';
            }
        } elseif (is_numeric($only_parent)) {
            $cat = $cats[$parent_id][$only_parent];
            $tree .= '<li>' . $cat->name;
            $tree .= buildTree($cats, $cat->id, $only_parent);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
    } else {
        return null;
    }

    return $tree;
}
