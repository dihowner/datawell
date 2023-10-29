<!-- Jquery js-->
<script src="{{ asset('assets/js/vendors/jquery-3.5.1.min.js') }}"></script>
<!-- Bootstrap4 js-->
<script src="{{ asset('assets/plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/rating/jquery.rating-stars.js') }}"></script>

<!--Sidemenu js-->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
<!-- Clipboard js -->
<script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/plugins/clipboard/clipboard.js') }}"></script>

<!-- P-scroll js-->
<script src="{{ asset('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>
<!--Moment js-->
{{-- <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script> --}}
<!-- Daterangepicker js-->
{{-- <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/js/daterange.js') }}"></script> --}}

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).ready(function() {
        startDate = moment().subtract(1, 'month');
        endDate = moment().subtract(1, 'month').add(5, 'day');

        $('#range').daterangepicker({
            drops : "auto",
            maxDate: moment(),
            locale: {
                format: 'MM/DD/YY'
            }
        });
    });
</script>
