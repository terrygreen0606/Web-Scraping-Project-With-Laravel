@extends('masters.clientarea')

@section('content')
<div class="ui middle aligned tow column centered grid">

    <div class="row">
        <div class="column">
            <div class="ui text container segment">


                {{-- Begin message --}}
                <div class="ui message yellow">
                    <h4 class="header">Upload image</h4>
                    <div class="content">
                        Please choose images to upload by clicking on the "Add new image" button.
                    </div>
                </div>
                {{-- End message --}}


                {{-- Begin image selector --}}
                <button class="ui button icon labeled green" id="choose-upload-image">
                    <i class="plus icon"></i>
                    Add new image
                </button>
                <input type="file" id="upload_image_input" name="upload_image_input[]" multiple="multiple" accept=".jpg,.jpeg,.png"
                            data-url="{{ route('do.uploadImage') }}" data-user-id="{{ $user->uuid }}"
                            >
                <div class="ui divider"></div>
                {{-- End image selector --}}


                {{-- Begin image list --}}
                <div id="uploaded-images">


                </div>
                {{-- End image list --}}


            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJavascript')
    <script type="text/javascript" src="{{ asset('assets/js/image-manager.js') }}"></script>
@endsection