<table class="display data_tbl data__table">
    <thead>
        <tr>
            <th class="text-start">Serial</th>
            <th class="text-start">Name</th>
            <th class="text-start">Code</th>
            <th class="text-start">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr data-info="{{ $category }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $category->name }}</td> 
                <td>{{ $category->code}}</td> 
            
                <td> 
                    <div class="dropdown table-dropdown">
                        <a href="javascript:;" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span></a>
                        <a href="{{ route('expanses.categories.delete', $category->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span></a>
                    </div>
                </td> 
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $('.data_tbl').DataTable({"lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]]});
</script>