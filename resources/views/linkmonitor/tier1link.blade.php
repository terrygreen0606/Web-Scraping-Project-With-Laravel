@extends('masters.clientarea')

@section('ExtraStylesheets')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endsection

@section('content')


 {{-- Begin Tier1 list --}}
<div class="bs-example">
    <nav class="navbar navbar-light bg-light">
        <div class="form-inline">
            <input class="form-control mr-sm-3" id="search" name="search" type="search" placeholder="Search" aria-label="Search">
        </div>
    </nav>
    <button id="addTier1Link" type="submit" class="btn btn-primary but-pos" data-toggle="modal" data-target="#tier1Modal" >Add Tier 1 Link</button>
    <table class="table table-striped" id="tier1">
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Provider</th>
                <th>Tier 1 Link</th>
                <th>301 Url</th>
                <th>Anchor Text</th>
                <th>Target Url</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tier1 as $key=>$tiers1)
            <tr class="tier1Item" data-tier1-id="{{ $tiers1['id'] }}">
                <td class="counterCell"></td>
                <td>{{ $tiers1['client_id']}}</td>
                <td>{{ $tiers1['provider_id']}}</td>
                <td>{{ $tiers1['tier1_link']}}</td>
                <td>{{ $tiers1['emUrl']}}</td>
                <td>{{ $tiers1['anchor_text']}}</td>
                <td>{{ $tiers1['target_url']}}</td>
                <td>
                    <div class="right floated content">
                        <div class="ui tiny icon button teal show-edit-tier1-modal"  data-toggle="modal" data-target="#editTier1">
                            <i class="edit icon"></i>
                        </div>
                        <div class="ui tiny icon button red show-delete-tier1-modal" data-toggle="modal" data-target="#delete-tier1-modal">
                            <i class="trash alternate outline icon"></i>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        {{-- <tbody id="updatePage"></tbody> --}}
    </table>
    {{-- End Tier1 list --}}


    {{-- Begin addTier1 modal --}}
    <div class="modal fade" id="tier1Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-weight" id="exampleModalLabel">Add Tier 1 Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-signin" id="form-add-Tier1" method="POST" action="{{ route('do.addTier1') }}">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="title-weight">Select Clients</label>
                            <select class="form-control" id="clientId" name="clientId" selected="disabled" required>

                                @foreach ( $client as $clients)
                                    <option name="clientId">{{ $clients['title']}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="title-weight">Select Providers</label>
                            <select class="form-control" id="providerId" name="providerId" required>

                                @foreach ( $provider as $providers)
                                    <option name="providerId">{{ $providers['title']}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Tier 1 Link</label>
                            <input type="text" class="form-control" id="tier1Link" name="tier1Link" placeholder="http://example_Tier1_Link.com" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">301 Url</label>
                            <input type="radio" id="radio-enable" name="checkUrl" value="enable" checked/> Yes
                            <input type="radio" id="radio-disable" name="checkUrl" value="disable"  /> No
                            <input type="text" class="form-control" id="emUrl" name="emUrl" placeholder="Leave this place empty if unnecessary">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Anchor Text</label>
                            <input type="text" class="form-control" id="anchorText" name="anchorText" placeholder="Put Anchor Text here" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Target Url</label>
                            <input type="text" class="form-control" id="targetUrl" name="targetUrl" placeholder="http://example_Target.com">
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary but-width">Save</button>
                            <hr>
                            <button type="button" class="btn btn-secondary but-width" data-dismiss="modal">Cancel</button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End addTier1 modal --}}


    {{-- Begin removeTier1 modal --}}
    <div class="modal fade" id="delete-tier1-modal" tabindex="0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-weight" id="exampleModalLabel">Remove Tier1</h5>
                </div>
                <div class="modal-body">
                    <form id="form-remove-Tier1" method="POST" action="{{ route('do.removeTier1') }}">

                        <div class="form-group">
                            <p>All things belong the Tier1 will be deleted.</p>
                            <p>Are you sure you want to delete the Tier1?</p>
                            <div class="ui hidden message">
                                <div class="header"></div>
                                <div class="content"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary but-width">Yes, Delete</button>
                            <hr>
                            <button type="button" class="btn btn-secondary but-width" data-dismiss="modal">No</button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End removeTier1 modal --}}


    {{-- Begin editTier1 modal --}}
    <div class="modal fade" id="editTier1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-weight" id="exampleModalLabel">Add Tier 1 Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-signin" id="formEditTier1" method="POST" action="{{ route('do.updateTier1') }}">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="title-weight">Select Clients</label>
                            <select class="form-control" id="clientIdEdit" name="clientIdEdit" selected="disabled" required>

                                @foreach ( $client as $clients)
                                    <option name="clientIdEdit">{{ $clients['title']}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="title-weight">Select Providers</label>
                            <select class="form-control" id="providerIdEdit" name="providerIdEdit" required>

                                @foreach ( $provider as $providers)
                                    <option name="providerIdEdit">{{ $providers['title']}}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Tier 1 Link</label>
                            <input type="text" class="form-control" id="tier1LinkEdit" name="tier1LinkEdit" placeholder="http://example_Tier1_Link.com" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">301 Url</label>
                            <input type="radio" id="radio-enableEdit" name="checkUrlEdit" value="enable" checked/> Yes
                            <input type="radio" id="radio-disableEdit" name="checkUrlEdit" value="disable"  /> No
                            <input type="text" class="form-control" id="emUrlEdit" name="emUrlEdit" placeholder="Leave this place empty if unnecessary">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Anchor Text</label>
                            <input type="text" class="form-control" id="anchorTextEdit" name="anchorTextEdit" placeholder="Put Anchor Text here" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="title-weight">Target Url</label>
                            <input type="text" class="form-control" id="targetUrlEdit" name="targetUrlEdit" placeholder="http://example_Target.com">
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary but-width">Save</button>
                            <hr>
                            <button type="button" class="btn btn-secondary but-width" data-dismiss="modal">Cancel</button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End editTier1 modal --}}

</div>

@endsection

@section('ExtraJavascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    {{-- <script type="text/javascript" src="{{ asset('assets/js/jquery.simplePagination.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('assets/js/tier1.js') }}"></script>
@endsection
