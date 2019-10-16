@extends('masters.clientarea')

@section('content')

<div class="ui segment container text">

    {{-- Begin add a new provider form --}}
    <form class="ui form" id="form-add-client" method="POST" action="{{ route('do.addProvider') }}">
        <h4 class="ui dividing header">{{ __('Add new provider') }}</h4>
        <div class="inline fields">

            <div class="eight wide field">
                <label>Provider&nbsp;Name</label>
                <div class="ui left icon icon input">
                    <input type="text" id="client_name" name="client_name" placeholder="Provider Name">
                    <i class="user icon"></i>
                </div>
            </div>

            <div class="three wide field proper-size">
                <button type="submit" class="ui button green labled icon">
                    <i class="icon add"></i> {{ __('Add Provider') }}
                </button>
            </div>

        </div>
        @csrf
    </form>
    {{-- End add a new provider form --}}


    {{-- Begin provider list --}}
    <div class="ui middle aligned divided list" id="client-list">

        @foreach ($clients as $client)
            <div class="item" data-client-id="{{ $client['uuid'] }}" data-client-name="{{ $client['title'] }}">
                <div class="right floated content">
                    <div class="ui tiny icon button teal show-edit-client-modal">
                        <i class="edit icon"></i>
                    </div>
                    <div class="ui tiny icon button red show-delete-client-modal">
                        <i class="trash alternate outline icon"></i>
                    </div>
                </div>
                <div class="content middle aligned">{{ $client['title'] }}</div>
            </div>
        @endforeach

    </div>
    <div class="pagination" id="pagination"></div>
    {{-- End provider list --}}


    {{-- Begin confirm remove provider modal --}}
    <div class="ui tiny modal" id="delete-client-modal" action="{{ route('do.removeProvider') }}">
        <div class="header">
            <span>Remove Client</span>
        </div>
        <div class="content">
            <p>All things belonging to the provider will be deleted.</p>
            <p>Are you sure you want to delete the provider?</p>
            <div class="ui hidden message">
                <div class="header"></div>
                <div class="content"></div>
            </div>
        </div>
        <div class="actions">
            <div class="ui black deny button">
                <span>No</span>
            </div>
            <div class="ui positive left labeled icon button">
                <span>Yes, Delete</span>
                <i class="trash alternate outline icon"></i>
            </div>
        </div>
    </div>
    {{-- End confirm remove provider modal --}}


    {{-- Begin edit provider modal --}}
    <div class="ui tiny modal" id="edit-client-modal" method="POST" action="{{ route('do.updateProvider') }}">
        <div class="header">
            <span>Provider Client</span>
        </div>
        <div class="content">

            <form class="ui form" id="form-add-client" >

                <div class="fields">
                    <div class="field">
                        <label>Provider&nbsp;Name</label>
                        <div class="ui left icon icon input">
                            <input type="text" id="edit_client_name" name="edit_client_name" placeholder="Provider Name">
                            <i class="user icon"></i>
                        </div>
                    </div>
                </div>

                @csrf
            </form>

            <div class="ui hidden message">
                <div class="header"></div>
                <div class="content"></div>
            </div>

        </div>
        <div class="actions">
            <div class="ui black deny button">
                <span>No</span>
            </div>
            <div class="ui positive left labeled icon button">
                <span>Update</span>
                <i class="trash alternate outline icon"></i>
            </div>
        </div>
    </div>
    {{-- End edit provider modal --}}


    {{-- Begin message modal --}}
    <div class="ui tiny modal" id="message-modal">
        <div class="header">
            <span>Error</span>
        </div>
        <div class="content">
        </div>
        <div class="actions">
            <div class="ui black deny button">
                <span>Ok</span>
            </div>
        </div>
    </div>
    {{-- End message modal --}}






</div>


@endsection

@section('ExtraJavascript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.simplePagination.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/clients.js') }}"></script>
@endsection
