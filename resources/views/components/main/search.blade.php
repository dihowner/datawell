<div class="ml-7">
    <form method="GET" class="">
        <div class="row form-group p-3">
            <div class="col-sm-5 mb-2">
                <input type="text" name="query" class="form-control form-control-lg mb-2"
                    value="{{ request()->get('query') }}">
            </div>

            <div class="col-sm-2 mb-2">
                <button class="btn btn-danger btn-lg btn-block" type="submit"><i class="fa fa-search"></i>
                    Search</button>
            </div>

            <div class="col-sm-2 mb-2">
                <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-dark btn-lg btn-block"><i
                        class="fa fa-refresh"></i> Reset
                </a>
            </div>
        </div>
    </form>

</div>
