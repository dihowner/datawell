@include('frontpage-navbar')

<main>

    <section id="price-intro" class="flex mx-auto px-4 md:px-0 justify-center bg-gray-100 h-60 lg:h-96">
        <div class="h-full flex flex-col justify-center items-center">
            <div class="space-y-2 text-center lg:space-y-4 w-fit col-center">
                <h2 data-aos="animate-fadeInDown" data-aos-duration="1000" data-aos-once="true"
                    class="font-sans text-3xl font-bold text-gray-800 lg:text-5xl md:text-4xl">
                    Pricing
                </h2>
                <p class="text-sm text-gray-500 md:text-base lg:text-lg center">
                    Trustworthy experience, dependable service.
                </p>
            </div>
            <div class="mt-4 space-x-2 lg:mt-8 center">
                <a href="{{ route('get.register') }}">
                    <button
                        class="px-4 py-2 lg:px-10 lg:py-4 text-xs lg:text-sm font-bold text-white bg-[#D10A22] rounded lg:rounded-lg normal-case hover:bg-[#A0081A] css-1ujsas3"
                        tabindex="0" type="button">
                        Get Started<span class="MuiTouchRipple-root css-w0pj6f"></span>
                    </button>
                </a>
                <a href="{{ route('get.login') }}">
                    <button
                        class="px-4 py-2 lg:px-10 lg:py-4 text-xs lg:text-sm font-bold text-white bg-[#D10A22] rounded lg:rounded-lg normal-case hover:bg-[#A0081A] css-1ujsas3"
                        tabindex="0" type="button">
                        Login
                    </button>
                </a>
            </div>
        </div>
    </section>

    <section id="price-details" class="container mx-auto px-8 md:px-24">
        <div class="space-y-20 py-14 md:py-20 2xl:py-32 md:space-y-32 lg:space-y-40 xl:space-y-60 container_fluid">
            <div class="max-w-xs mx-auto md:max-w-none">
                <h1 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                    class="text-xl font-bold text-center text-gray-800 sm:text-2xl xl:text-4xl md:text-3xl lg:text-left">
                    Data Pricing
                </h1>
                <div class="md:flex mt-5 space-y-10 md:mt-8 lg:mt-10 md:space-y-0 lg:space-x-4 md:between-start">

                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                        class="p-8 lg:p-12 bg-white h-fit rounded-lg shadow-md lg:shadow-xl lg:min-w-[310px] xl:min-w-[370px] col-center">
                        <img alt="MTN" data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            loading="lazy" width="128" height="128" decoding="async" data-nimg="1"
                            class="object-contain mx-auto w-20 h-20 rounded-full md:h-24 md:w-24 lg:w-32 lg:h-32 bg-gray-50"
                            src="/images/mtn.png" />
                        <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="mt-1 text-sm text-center font-bold md:text-base lg:mt-2">
                            MTN
                        </p>
                        <div class="mt-8 space-y-4 lg:space-y-8 md:mt-12 lg:mt-16">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">1GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦300</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">2GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦600</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">5GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1500</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">10GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦3000</span>
                            </p>
                        </div>
                    </div>

                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                        class="p-8 lg:p-12 bg-white h-fit rounded-lg shadow-md lg:shadow-xl lg:min-w-[310px] xl:min-w-[370px] col-center">
                        <img alt="Airtel" data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            loading="lazy" width="128" height="128" decoding="async" data-nimg="1"
                            class="object-contain mx-auto w-20 h-20 rounded-full md:h-24 md:w-24 lg:w-32 lg:h-32 bg-gray-50"
                            src="/images/airtel.jpg" />
                        <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="mt-1 text-sm text-center font-bold md:text-base lg:mt-2">
                            Airtel
                        </p>
                        <div class="mt-8 space-y-4 lg:space-y-8 md:mt-12 lg:mt-16">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">1GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦250</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">2GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦500</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">5GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1250</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">10GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦2500</span>
                            </p>
                        </div>
                    </div>

                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                        class="p-8 lg:p-12 bg-white h-fit rounded-lg shadow-md lg:shadow-xl lg:min-w-[310px] xl:min-w-[370px] col-center">
                        <img alt="Glo" data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            loading="lazy" width="128" height="128" decoding="async" data-nimg="1"
                            class="object-contain mx-auto w-20 h-20 rounded-full md:h-24 md:w-24 lg:w-32 lg:h-32 bg-gray-50"
                            src="images/globacom.png" />
                        <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="mt-1 text-sm text-center font-bold md:text-base lg:mt-2">
                            Glo
                        </p>
                        <div class="mt-8 space-y-4 lg:space-y-8 md:mt-12 lg:mt-16">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-center lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">1GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦275</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-center lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">2GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦550</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-center lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">5GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1375</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-center lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">10GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦2750</span>
                            </p>
                        </div>
                    </div>

                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                        class="p-8 lg:p-12 bg-white h-fit rounded-lg shadow-md lg:shadow-xl lg:min-w-[310px] xl:min-w-[370px] col-center">
                        <img alt="9Mobile" data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            loading="lazy" width="128" height="128" decoding="async" data-nimg="1"
                            class="object-contain mx-auto w-20 h-20 rounded-full md:h-24 md:w-24 lg:w-32 lg:h-32 bg-gray-50"
                            src="/images/9mobile.png" />
                        <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="mt-1 text-center text-sm font-bold md:text-base lg:mt-2">
                            9Mobile
                        </p>
                        <div class="mt-8 space-y-4 lg:space-y-8 md:mt-12 lg:mt-16">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">500MB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦530</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">1.5GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1000</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">2GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1300</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">3.072GB</span><span
                                    class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦1550</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">4.5GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦2050</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">11GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦4000</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">15GB</span><span class="text-gray-500">=&gt;</span><span
                                    class="font-bold text-gray-800">₦4950</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h1 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                    class="text-xl font-bold text-center text-gray-800 sm:text-2xl xl:text-4xl md:text-3xl lg:text-left">
                    Other Pricing
                </h1>
                <div class="mt-5 md:flex space-y-10 lg:mt-10 lg:space-x-10 lg:between-start lg:space-y-0">
                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="w-full">
                        <h6 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="w-full px-4 py-4 flex justify-between text-sm font-bold text-gray-500 md:text-base bg-gray-50 inline-between">
                            <span>Bill Payments</span><span>Discount</span>
                        </h6>
                        <div class="px-4 mt-4 space-y-4 lg:mt-8 lg:space-y-8">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">DSTV</span><span class="font-bold text-gray-800">1%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">GOTV</span><span class="font-bold text-gray-800">1%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">STARTIMES</span><span
                                    class="font-bold text-gray-800">1.5%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">SMILE RECHARGE</span><span
                                    class="font-bold text-gray-800">2%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">SPECTRANET</span><span
                                    class="font-bold text-gray-800">2.5%</span>
                            </p>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="w-full">
                        <h6 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="w-full px-4 py-4 text-sm font-bold text-gray-500 md:text-base bg-gray-50 inline-between">
                            <span>Airtime Discounts</span><span>Discount</span>
                        </h6>
                        <div class="px-4 mt-4 space-y-4 lg:mt-8 lg:space-y-8">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">MTN</span><span class="font-bold text-gray-800">2%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">MTN - PREMIUM</span><span
                                    class="font-bold text-gray-800">3%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">AIRTEL</span><span
                                    class="font-bold text-gray-800">3%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">9MOBILE</span><span
                                    class="font-bold text-gray-800">4%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">9MOBILE - PREMIUM</span><span
                                    class="font-bold text-gray-800">5%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">GLO</span><span class="font-bold text-gray-800">5%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">GLO - PREMIUM</span><span
                                    class="font-bold text-gray-800">7%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">VISAPHONE</span><span
                                    class="font-bold text-gray-800">5%</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="w-full">
                        <h6 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                            class="w-full px-4 py-4 text-sm flex justify-between font-bold text-gray-500 md:text-base bg-gray-50 inline-between">
                            <span>Airtime to Cash</span><span>Rate</span>
                        </h6>
                        <div class="px-4 mt-4 space-y-4 lg:mt-8 lg:space-y-8">
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">MTN</span><span class="font-bold text-gray-800">85%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">GLO</span><span class="font-bold text-gray-800">75%</span>
                            </p>
                            <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
                                class="space-x-4 text-sm flex justify-between lg:space-x-8 between md:text-base">
                                <span class="text-gray-500">9MOBILE</span><span
                                    class="font-bold text-gray-800">80%</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('footer')
