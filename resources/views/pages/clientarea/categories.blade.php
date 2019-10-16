@extends('masters.clientarea')

@section('content')
<div class="ui middle aligned tow column centered grid">

    <div class="row">
        <div class="column">
            <div class="ui text container segment">
                <form class="ui form" id="form-add-catetory" method="POST" action="{{ route('addCategory') }}">
                    <h4 class="ui dividing header">{{ __('Manage Categories') }}</h4>

                    <div class="inline fields">
                        <div class="eight wide field">
                            <label>Title</label>
                            <div class="ui input">
                                <input type="text" id="category_title" name="category_title"  placeholder="Category title">
                            </div>
                        </div>
                        <div class="eight wide field">
                            <button type="submit" class="ui button teal labled icon submit-category">
                                <i class="icon save"></i> {{ __('Add category') }}
                            </button>
                        </div>
                    </div>

                    <div class="ui hidden message">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <ul class="list"></ul>
                    </div>

                    @csrf

                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->uuid }}">
                </form>
                
                {{-- Begin category list --}}
                <table class="ui celled black striped table" data-user-id="{{ $user->uuid }}">
                    <tbody class="category-list">
                        @foreach ($categories as $category)
                            <tr data-id="{{ $category->uuid }}">
                                <td>
                                    <span class="category-title">{{ $category->title }}</span>
                                </td>
                                <td width="160px">
                                    <div class="ui buttons category-buttons">
                                        <button class="ui teal button show-edit-category-form">Edit</button>
                                        <div class="or"></div>
                                        <button class="ui red button show-delete-category-modal">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- End category list --}}

            </div>
        </div>
    </div>
</div>


{{-- Begin delete category modal --}}
<div class="ui tiny modal" id="delete-category-modal" action="{{ route('deleteCategory') }}">
    <i class="close icon"></i>
    <div class="header">
        <span>Delete category</span>
    </div>
    <div class="content">
        <p>Are you sure you want to delete the category?</p>
        <div class="ui hidden message">
            <i class="close icon"></i>
            <div class="header"></div>
            <div class="content"></div>
        </div>
    </div>
    <div class="actions">
        <div class="ui black deny button">
            <span>No</span>
        </div>
        <div class="ui positive left labeled icon button">
            <span>Yes, delete</span>
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>
{{-- End delete category modal --}}


{{-- Begin edit category form --}}
<div class="transition hidden" id="container-form-update-catetory">

    <form class="ui form" id="form-update-catetory" method="POST" action="{{ route('updateCategory') }}">

        <div class="field">
            <div class="ui input">
                <input type="text" id="category_title" name="category_title" placeholder="Category title">
            </div>
        </div>

        @csrf
        <input type="hidden" id="category_id" name="category_id">
        <input type="hidden" id="user_id" name="user_id" value="{{ $user->uuid }}">
    </form>

    <div class="ui buttons" id="form-update-catetory-buttons">
        <button class="ui teal button category-do-edit">Save</button>
        <div class="or"></div>
        <button class="ui red button category-cancel-edit">Cancel</button>
    </div>

</div>
{{-- End edit category form --}}


@endsection

@section('ExtraJavascript')
    <script type="text/javascript" src="{{ asset('assets/js/manage-category.js') }}"></script>
@endsection