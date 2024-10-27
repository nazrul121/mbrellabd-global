<?php
    if(Request::segment(4)=='brand'){
        $url = route('common.brand-products',Request::segment(5));
    }
    elseif(Request::segment(4)=='category'){
        $url = route('common.category-products',Request::segment(5));
    }
    elseif(Request::segment(4)=='sub-category'){
        $url = route('common.sub-category-products',Request::segment(5));
    }
    elseif(Request::segment(4)=='child-category'){
        $url = route('common.child-category-products',Request::segment(5));
    }
    elseif(Request::segment(4)=='highlight'){
        $url = route('common.highlight-products',Request::segment(5));
    }
    elseif(Request::segment(4)=='variant-products'){
        $url = '/common/catalog/product/variant-products/'.Request::segment(5).'/'.Request::segment(6);
    }


    elseif( request()->get('design_code') ){
        $url = route('common.design-code-products',request()->get('design_code') );
    }
    elseif( request()->get('child_category_id') ){
        $url = route('common.child-category-products',request()->get('child_category_id'));
    }
    elseif( request()->get('sub_category_id') ){
        $url = route('common.sub-category-products',request()->get('sub_category_id'));
    }
    elseif( request()->get('category_id') ){
        $url = route('common.category-products',request()->get('category_id'));
    }

    else $url = route('common.product');
?>
