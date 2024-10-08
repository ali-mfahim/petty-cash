<div class="btn-group dropstart">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item edit-category-btn " href="javascript:;" data-category-id="{{$category->id}}"> Edit</a>
        <!-- <a class="dropdown-item disabled" href="#">Option 2</a> -->
        <a class="dropdown-item delete-category-btn" href="javascript:;" data-category-id="{{$category->id}}" data-route="{{route('categories.destroy' , $category->id)}}"> Delete</a>
    </div>
</div>