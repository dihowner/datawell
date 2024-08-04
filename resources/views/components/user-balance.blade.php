<div class="d-block fs-20 mb-5 mt-4 balanceholder">
    <strong class="text-danger">Current Balance: </strong>{{ $UtilityService::CURRENCY.number_format($userDetail['wallet_balance'], 2) }}

    @if (Session::get('rate_us'))
        <div class="col-md-12 mt-2">
            Dear Client, Kindly rate our service by clicking the link below 
                <a href="https://g.page/r/Cd1tg4RolrB7EA0/review" class="btn btn-primary"><i class="fa fa-star"></i> Rate Us</a>
        </div>        
    @endif

</div>