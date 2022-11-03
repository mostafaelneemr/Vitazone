@php
    $brands = array();
@endphp
<div class="sub-cat-main row no-gutters">
    <div class="col-12">
        <div class="sub-cat-content">
            <div class="sub-cat-list">
                <div class="card-columns">
                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                        <div class="card">
                            <ul class="sub-cat-items">
                                <li class="sub-cat-name"><a href="{{ route('products.category', \App\Category::find($first_level_id)->{'slug_' .locale()}) }}">{{ \App\Category::find($first_level_id)->{'name_'.locale()} }}</a></li>
                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
                                    <li><a href="{{ route('products.category', \App\Category::find($second_level_id)->{'slug_' .locale()}) }}">{{ \App\Category::find($second_level_id)->{'name_'.locale()} }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
