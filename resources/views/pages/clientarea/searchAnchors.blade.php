@extends('masters.clientarea')

@section('content')

<div class="ui segment">
    <form class="ui form" id="form-search-anchors" method="POST" action="{{ route('do.searchAnchors') }}">
        <h4 class="ui dividing header">{{ __('Search Anchors') }}</h4>

        <div class="field">
            <label>Anchor URL</label>
            <div class="ui left icon icon input">
                <input type="text" id="anchor_url" name="anchor_url" placeholder="Anchor URL">
                <i class="linkify icon"></i>
            </div>
        </div>

        <div class="field">
            <div class="ui middle aligned divided list" id="field-search-anchors-source"></div>
        </div>

        @csrf

        <div class="ui buttons">

            <div class="ui teal icon top left pointing dropdown button" id="add-source-dropdown">
                <i class="plus icon"></i>
                {{ __('Add Source') }}

                <div class="menu">
                    <div class="item" id="show-upload-source-file-modal">Upload File</div>
                    <div class="item" id="show-add-new-source-modal">Paste Plain Text</div>
                </div>

            </div>

        </div>

        <button class="ui button labled icon disabled" id="remove-all-source">
            <i class="trash icon"></i> {{ __('Clear All') }}
        </button>

        <button class="ui button labled icon disabled" id="do-save-csv" data-action="{{ route('do.searchAnchorsStoreCSVFile') }}">
            <i class="save outline icon"></i> {{ __('Store CSV') }}
        </button>

        <button class="ui button labled icon disabled" id="delete-selected" data-action="{{ route('do.searchAnchorsStoreCSVFile') }}">
            <i class="fa fa-trash-alt outline icon"></i> {{ __('Delete') }}
        </button>

        {{-- <button type="submit" class="ui button labled icon disabled" id="do-search-sources">
            <i class="search icon"></i> {{ __('Search') }}
        </button> --}}

        {{-- <button class="ui button labled icon disabled" id="restart-search-sources">
            <i class="sync icon"></i> {{ __('Search again') }}
        </button> --}}

    </form>


    {{-- Begin result table --}}
    <table class="ui celled structured table table-striped" id="anchor-search-result-table">
        <thead>
            <tr>
                <th class="center aligned collapsing">Select</th>
                <th class="center aligned collapsing">#</th>
                <th>URL</th>
                <th class="center aligned collapsing">Status</th>
                <th class="center aligned collapsing">Found</th>
                <th class="center aligned collapsing">Anchor Text</th>
                <th class="center aligned collapsing">Anchor URL</th>
                <th class="center aligned collapsing">File Size</th>
                <th class="center aligned collapsing">Date Checked</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    {{-- End result table --}}

</div>


{{-- Begin add new source modal --}}
<div class="ui small modal" id="add-new-source-modal">
    <i class="close icon"></i>
    <div class="header">
        Add Source
    </div>

    <div class="content">

        <form class="ui form" id="form-send-plain-text-source" action="{{ route('do.searchAnchorsPlainText') }}">
            <div class="field">
                <label>Sources</label>
                <div class="ui input">
                    <textarea id="modal-source-input" name="modal_source_input"></textarea>
                </div>
            </div>
        </form>

    </div>

    <div class="actions">
        <div class="ui button black deny">Cancel</div>
        <div class="ui button teal approve">Analyze</div>
    </div>

</div>
{{-- End add new sources modal --}}


{{-- Begin clear all sources confirmation modal --}}
<div class="ui tiny modal" id="clear-all-sources-modal">
    <i class="close icon"></i>
    <div class="header">
        Clear All Sources
    </div>

    <div class="content">
        <p>Are you sure you want to clear all links?
    </div>

    <div class="actions">
        <div class="ui button black deny">No</div>
        <div class="ui button teal approve">Yes</div>
    </div>

</div>
{{-- End clear all sources confirmation modal --}}


{{-- Begin show page preview iframe modal --}}
<div class="ui large modal" id="page-preview-modal">
    <i class="close icon"></i>
    <div class="header">
        Page Preview
    </div>

    <div class="content">
        <iframe style="width: 100%;" id="page-preview-iframe"></iframe>
    </div>

    <div class="actions">
        <div class="ui button black deny">Close</div>
    </div>

</div>
{{-- End show page preview iframe modal --}}


{{-- Begin upload .rtf and txt files modal --}}
<div class="ui small modal" id="upload-source-file-modal">
    <i class="close icon"></i>
    <div class="header">
        Upload Source File
    </div>

    <div class="content">
        <form class="ui form" id="form-upload-source-file" action="{{ route('do.searchAnchorsUploadFile') }}">

            <div class="field">
                <label>Select your file to upload</label>
                <div class="ui input">
                    <input type="file" id="source_file" name="source_file">
                </div>
            </div>

            @csrf

        </form>
    </div>

    <div class="actions">
        <div class="ui button black deny">Close</div>
        <div class="ui button teal approve">
            <i class="upload icon"></i>
            Upload
        </div>
    </div>

</div>
{{-- End upload .rtf and txt files modal --}}


{{-- Begin notify user to enter target anchor into its input modal --}}
<div class="ui tiny modal" id="enter-target-anchor-notification-modal">
    <i class="close icon"></i>
    <div class="header">
        Enter the Anchor URL
    </div>

    <div class="content">
        <p class="red">Please enter the anchor URL you want to search it.</p>
    </div>

    <div class="actions">
        <div class="ui button teal ok">Ok</div>
    </div>

</div>
{{-- End notify user to enter target anchor into its input modal --}}


{{-- Begin store csv result modal --}}
<div class="ui tiny modal" id="store-csv-modal">
    <i class="close icon"></i>
    <div class="header">
        Store CSV file
    </div>

    <div class="content">
        <p class="failed hidden">There is a problem with storing the CSV file</p>
        <p class="success hidden">Your CSV file has been stored successfully</p>
    </div>

    <div class="actions">
        <div class="ui button teal ok">Ok</div>
    </div>

</div>
{{-- End store csv result modal --}}


@endsection

@section('ExtraJavascript')
<script type="text/javascript" src="{{ asset('assets/js/search-anchors.js') }}"></script>
@endsection
