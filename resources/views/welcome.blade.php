@extends( 'layout.base' )

@section( 'layout.base.body' )
    <div id="page-container" class="h-full w-full flex" style="background-color: #fff;">
        <div class="container flex-auto flex-col items-center justify-center flex m-4 sm:mx-auto">
            <div class="flex justify-center items-center py-6">
                <img style="height: 224px;transform: scale(3);padding-bottom: -45px;"  src="{{ asset( 'svg/touchpay.svg' ) }}" alt="TouchPay">
            </div>
            <div class="ns-box rounded shadow w-full md:w-1/2 lg:w-1/3 overflow-hidden" style="z-index: 4;">
                <div id="section-header" class="ns-box-header p-4" style="background-color: #fff">
                    <p class="text-center b-8 text-sm" style="color: #c61654;">{{ __( "If you see this page, this means TouchPay is correctly installed on your system. As this page is meant to be the frontend, TouchPay doesn't have a frontend for the meantime. This page shows useful links that will take you to the important resources." ) }}</p>
                </div>
                <div class="flex" style="background-color: #c61654;">
                    <div class="flex w-1/3" style="border-right:25px solid #fff"><a class="link text-sm w-full py-2 text-center" href="{{ ns()->route( 'ns.dashboard.home' ) }}">{{ __( 'Dashboard' ) }}</a></div>
                    <div class="flex w-1/3" style="border-right:25px solid #fff"><a class="link text-sm w-full py-2 text-center" href="{{ ns()->route( 'ns.login' ) }}">{{ __( 'Sign In' ) }}</a></div>
                    <div class="flex w-1/3" style="border-right:25px solid #fff"><a class="link text-sm w-full py-2 text-center" href="{{ ns()->route( 'ns.register' ) }}">{{ __( 'Sign Up' ) }}</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection