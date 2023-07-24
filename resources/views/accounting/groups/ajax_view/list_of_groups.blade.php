<div id="jstree_demo_div">
    @php
        function printChildGroup($items) {
            // $mt = 'data-jstree=\'{"icon":"fa-light fa-file-audio"}\'';
            foreach($items as $item) {
                echo '<ul class="ps-3">';
                echo '<li class="fw-bold jstree-open">';

                // if (auth()->user()->can('account_groups_edit')) {

                //     echo '<span href="'. route('accounting.groups.edit', $item->id) .'" data-class_name="' . $item->id.'group' . '" class="' . $item->id.'group' . '" id="editAccountGroupBtn">'. $item->name .'</span> ';
                // }else {

                //     echo '<span class="' . $item->id.'group' . '">'. $item->name .'</span> ';
                // }

                echo '<span href="'. route('account.groups.edit', $item->id) .'" data-class_name="' . $item->id.'group' . '" class="' . $item->id.'group' . '" id="editAccountGroupBtn">'. $item->name .'</span> ';

                // if (auth()->user()->can('account_groups_add')) {

                //     echo '<span href="'. route('account.groups.create') .'" data-group_id="'. $item->id .'" class="fa-sharp fa-solid fa-plus ms-1 text-success fw-icon add_btn_frm_group" id="addAccountGroupBtn"></span>';
                // }

                echo '<span href="'. route('account.groups.create') .'" data-group_id="'. $item->id .'" class="fas fa-plus ms-1 add_btn_frm_group" id="addAccountGroupBtn"></span>';

                // if (auth()->user()->can('account_groups_delete')) {

                //     echo '<span href="'. route('accounting.groups.delete', $item->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_group_btn" id="delete"></span>';
                // }

                echo '<span href="'. route('account.groups.delete', $item->id) .'" class="far fa-trash-alt text-primary ms-1 fw-icon delete_group_btn" id="delete"></span>';

                if(count($item->subgroups) > 0) {

                    printChildGroup($item->subgroups);
                }
                echo '</li>';
                echo '</ul>';
            }
        }
    @endphp

    @foreach ($groups as $group)
        <ul>
            <li class="fw-bold parent jstree-open">
                <span  data-class_name="{{ $group->id }}" class="{{ $group->id }}" id="parentText">
                    {{ $group->name }}
                </span>
                {{-- <span href="#" data-group_id="{{ $group->id }}" class="fa-sharp fa-solid fa-plus ms-1 text-success fw-icon add_btn_frm_group" id="add_btn"></span>
                <span href="{{ route('accounting.groups.delete', $group->id) }}" class="far fa-trash-alt text-primary ms-1 fw-icon delete_group_btn" id="delete"></span> --}}
                @php
                    if (count($group->subgroups) > 0) {

                        printChildGroup($group->subgroups);
                    }
                @endphp
            </li>
        </ul>
    @endforeach
</div>

<script>
    $('#jstree_demo_div').jstree(
        {
            "core" : {
                "multiple" : true,
                "animation" : 0
            }
        }
    );
</script>
