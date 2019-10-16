@extends('masters.clientarea')

@section('content')

    <div class="ui segment" id="image-list-segment" data-page="0" data-last="false" data-count="10" data-url="{{ route('do.imageList') }}" data-user-id="{{ $user->uuid }}">

        <div class="ui three column doubling grid centered">
            <div class="row">
                <div class="column">
                    <div class="ui icon message yellow hidden" id="image-not-found-message">
                        <i class="inbox icon"></i>
                        <div class="content">
                            <div class="header">
                                There is no image to show !
                            </div>
                            <p>You can upload new images from <a class="ui button mini red" href="{{ route('form.uploadImage') }}">here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ui container text">
            
        </div>

        <div class="ui five column doubling grid">
            <div class="row" id="image-list"></div>
        </div>

        <div>
            <div class="pagination" id="images-pagination"></div>
        </div>

        <div class="ui active inverted dimmer loading">
            <div class="ui loader"></div>
        </div>
        
    </div>


    {{-- Begin remove image modal --}}
    <div class="ui tiny modal" id="remove-image-modal" action="{{ route('do.removeImage') }}">
        <i class="close icon"></i>
        <div class="header">
            <span>Remvoe image</span>
        </div>
        <div class="content">
            <div>
                <img class="ui middle aligned small image remove-image-preview" src="">
                <span>Are you sure you want to remove the image?</span>
            </div>
            <div class="ui hidden message">
                <i class="close icon"></i>
                <div class="header"></div>
                <div class="content"></div>
            </div>
            <input type="hidden" name="image_id">
            <input type="hidden" name="user_id" value="{{ $user->uuid }}">
        </div>
        <div class="actions">
            <div class="ui black deny button">
                <span>No</span>
            </div>
            <div class="ui positive left labeled icon button">
                <span>Yes, remvoe</span>
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
    {{-- End remove image modal --}}



    {{-- Begin edit image modal --}}
    <div class="ui small modal" id="edit-image-modal">
        <i class="close icon"></i>
        <div class="header">
            <span>Edit image</span>
        </div>
        <div class="content">
            <div>
                <img class="ui centered medium image image-preview" src="">
            </div>

            {{-- Begin form --}}
            <form class="ui form" id="form-edit-image" method="POST" action="{{ route('do.updateImage') }}">
            
                <div class="fields two">
                    <div class="field">
                        <label>Title</label>
                        <div class="ui input">
                            <input type="text" name="title" placeholder="Title">
                        </div>
                    </div>
                </div>

                <div class="fields two">
                    <div class="field">
                        <label>Category</label>
                        <select class="ui search dropdown" name="category">
                            <option value="">Choose category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->uuid }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            
                <div class="field">
                    <label>Description</label>
                    <div class="ui input">
                        <input type="text" name="description" placeholder="Description">
                    </div>
                </div>

                <div class="ui hidden message">
                    <i class="close icon"></i>
                    <div class="header"></div>
                    <ul class="list"></ul>
                </div>
            
                @csrf
            
                <input type="hidden" name="image_id">
                <input type="hidden" name="user_id" value="{{ $user->uuid }}">
            </form>
            {{-- End form --}}

        </div>
        <div class="actions">
            <div class="ui black deny button">
                <span>No</span>
            </div>
            <div class="ui positive left labeled icon button">
                <span>Yes, update</span>
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>
    {{-- End edit image modal --}}


@endsection

@section('ExtraJavascript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.simplePagination.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/image-manager.js') }}"></script>
@endsection