<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/styles.css" />
  <link <link href="https://cdn.rawgit.com/michalsnik/aos/2.3.4/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="node_modules/a11y-slider/dist/a11y-slider.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="stylesheet" href="//unpkg.com/a11y-slider@latest/dist/a11y-slider.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="images/dwell-icon.png">
  <script src="node_modules/a11y-slider/dist/a11y-slider.js"></script>
  <title>Home | The Data Well Nigeria</title>
</head>

<body>
  <?php include_once "navbar.php"; ?>

  <main id="main" class="hide-on-mobile-nav">
    <section class="flex justify-center px-8 md:mx-auto md:px-40 my-16" id="hero">
      <div class="w-full flex relative">
        <div class="flex paginator">
          <div class="" id="firstDiv">
            
            <div class="py-10 md:flex md:items-center md:space-x-4 lg:space-x-8 md:between md:py-0">
              <div data-aos="fade-right" data-aos-duration="1000"
                class="col-center md:col-start h-full md:w-1/2 mt-8 md:mt-0">
                <h1
                  class="text-2xl md:text-3xl lg:text-4xl 2xl:text-5xl text-center md:text-left font-bold text-gray-800">
                  Earn money through online reselling
                </h1>

                <p class="md:w-8/12 text-base lg:text-lg text-center md:text-left text-gray-500 mt-2 md:mt-4">
                  Offer internet data, VTU services, and utility bill payments to your friends and family and generate a substantial income.
                </p>

                <a href="https://datawellng.com/create-account" class="flex justify-center py-4 md:justify-start">
                  <button
                    class="px-4 py-2 mt-4 text-sm rounded sm:px-6 sm:py-3 sm:mt-6 md:text-base md:rounded-md lg:px-10 lg:py-4 lg:mt-8 lg:text-lg font-bold text-white transition-colors hover:bg-[#A0081A] bg-[#D10A22] lg:rounded-lg normal-case">
                    Get Started
                  </button>
                </a>
              </div>

              <div data-aos="fade-left" data-aos-duration="1000" class="w-full md:w-1/2 h-60 md:h-full bg-zinc-200 order-first md:order-last">
                <img src="images/datawell.png" height="600px"/>
              </div>
            </div>
          </div>
          

          <div class="second hidden" id="secondDiv">
            <div class="py-10 md:flex md:items-center md:space-x-4 lg:space-x-8 md:between md:py-0">
              <div data-aos="fade-right" data-aos-duration="1000"
                class="col-center md:col-start h-full md:w-1/2 mt-8 md:mt-0">
                <h1
                  class="text-2xl md:text-3xl lg:text-4xl 2xl:text-5xl text-center md:text-left font-bold text-gray-800">
                  Stay connected with ease!
                </h1>

                <p class="md:w-8/12 text-base lg:text-lg text-center md:text-left text-gray-500 mt-2 md:mt-4">
                  Benefit from significantly reduced prices on internet data
                  plans, airtime top-ups via VTU, and utility bill payments.
                  Plus, you can even convert your airtime to cash. quos.
                </p>

                <a href="https://datawellng.com/create-account" class="flex justify-center py-4 md:justify-start">
                  <button
                    class="px-4 py-2 mt-4 text-sm rounded sm:px-6 sm:py-3 sm:mt-6 md:text-base md:rounded-md lg:px-10 lg:py-4 lg:mt-8 lg:text-lg font-bold text-white transition-colors hover:bg-[#A0081A] bg-[#D10A22] lg:rounded-lg normal-case">
                    Get Started
                  </button>
                </a>
              </div>

              <div data-aos="fade-left" data-aos-duration="1000" class="w-full md:w-1/2 h-60 md:h-full bg-zinc-200 order-first md:order-last">
                <img src="images/dw_signup.jpg"/>
              </div>
            </div>
          </div>
        </div>

        <div class="md:h-11 flex md:w-20 md:mr-12 absolute bottom-0 right-4">
          <span id="firstButton"></span>
          <span id="secondButton"></span>
        </div>
      </div>
    </section>

    <section class="mx-auto px-8 md:px-40 md:pt-16" id="services">
      <div class="text-center" style="margin-top: -20px">
        <h6 data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
          class="font-bold text-gray-800 text-2xl text-center lg:text-4xl">
          Our Services
        </h6>
        <p data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
          class="text-sm sm:text-base md:text-lg w-full text-center text-gray-500 mt-2 md:mt-4">
          Here are some of the services we offer
        </p>
      </div>

      <div class="grid sm:grid-cols-2 gap-10 sm:gap-14 md:gap-16 lg:gap-20 mt-10 sm:mt-14 md:mt-16 lg:mt-20">
        <div class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8" data-aos="fade-up"
          data-aos-once="true" data-aos-duration="1000">
          <div class="rounded-full bg-[#D10A22] p-8 relative w-20 h-20">
            <img alt="Data Purchase" loading="lazy" decoding="async" data-nimg="fill" class="p-2"
              src="svg/Data Bundle Icon.svg" style="
                  position: absolute;
                  height: 100%;
                  width: 100%;
                  inset: 0px;
                  color: transparent;
                " />
          </div>

          <div class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0">
            <h6 class="font-bold tracking-wide text-gray-800 text-base lg:text-lg">
              Data Purchase
            </h6>

            <p class="text-gray-500 text-base md:text-lg mt-1 md:mt-2">
              We offer affordable data plans for all major networks in
              Nigeria. Stay connected to the internet with our fast and
              reliable data plans, starting from as low as ₦50.
            </p>
          </div>
        </div>

        <div class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8" data-aos="fade-up"
          data-aos-once="true" data-aos-duration="1000">
          <div class="rounded-full bg-[#D10A22] p-8 relative w-20 h-20">
            <img alt="Airtime Purchase" loading="lazy" decoding="async" data-nimg="fill" class="p-2"
              src="svg/Airtime Purchase.svg" style="
                  position: absolute;
                  height: 100%;
                  width: 100%;
                  inset: 0px;
                  color: transparent;
                " />
          </div>

          <div class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0">
            <h6 class="font-bold tracking-wide text-gray-800 text-base lg:text-lg">
              Airtime Purchase
            </h6>

            <p class="text-gray-500 text-base md:text-lg mt-1 md:mt-2">
              Top up your mobile phone easily and conveniently on our
              platform. We offer instant airtime recharge for all networks in
              Nigeria at a discounted rate.
            </p>
          </div>
        </div>

        <div class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8" data-aos="fade-up"
          data-aos-once="true" data-aos-duration="1000">
          <div class="rounded-full bg-[#D10A22] p-8 relative w-20 h-20">
            <img alt="Bills Payment" loading="lazy" decoding="async" data-nimg="fill" class="p-2"
              src="svg/bills payment.svg" style="
                  position: absolute;
                  height: 100%;
                  width: 100%;
                  inset: 0px;
                  color: transparent;
                " />
          </div>

          <div class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0">
            <h6 class="font-bold tracking-wide text-gray-800 text-base lg:text-lg">
              Bills Payment
            </h6>

            <p class="text-gray-500 text-base md:text-lg mt-1 md:mt-2">
              Say goodbye to long queues and delays when it comes to bills
              payment. You can now pay your electricity bills, cable TV bills,
              and other utility bills on our platform, with just a few clicks.
            </p>
          </div>
        </div>

        <div class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8" data-aos="fade-up"
          data-aos-once="true" data-aos-duration="1000">
          <div class="rounded-full bg-[#D10A22] p-8 relative w-20 h-20">
            <img alt="Airtime to Cash Conversion" loading="lazy" decoding="async" data-nimg="fill" class="p-2"
              src="svg/Airtime to Cash icon.svg" style="
                  position: absolute;
                  height: 100%;
                  width: 100%;
                  inset: 0px;
                  color: transparent;
                " />
          </div>

          <div class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0">
            <h6 class="font-bold tracking-wide text-gray-800 text-base lg:text-lg">
              Airtime to Cash Conversion
            </h6>

            <p class="text-gray-500 text-base md:text-lg mt-1 md:mt-2">
              Do you have excess airtime that you want to convert to cash?
              Look no further, as our platform offers a seamless and
              affordable way to convert airtime to cash. Get instant cash in
              your bank account in no time.
            </p>
          </div>
        </div>
      </div>

      <div class="text-center my-14 md:my-20 2xl:my-32 aos-init" data-aos="fade-up" data-aos-once="true"
        data-aos-duration="1000">
        <a href="about#services" class="flex items-center justify-center">
          <button
            class="flex items-center px-4 text-sm normal-case md:px-6 lg:px-8 md:text-base text-[#D10A22] border-[#D10A22] border-2 rounded-md hover:border-transparent hover:bg-[#D10A22] hover:text-white space-y-8"
            tabindex="0" type="button">
            View All Services
            <span class="ml-2">
              <svg width="20px" height="20px" class="fill-current hover:fill-[white]" focusable="false"
                aria-hidden="true" viewBox="0 0 24 24" data-testid="EastIcon">
                <path d="m15 5-1.41 1.41L18.17 11H2v2h16.17l-4.59 4.59L15 19l7-7-7-7z"></path>
              </svg>
            </span>
            <span class="MuiTouchRipple-root css-w0pj6f"></span>
          </button>
        </a>
      </div>
    </section>

    <section id="articles" class="bg-gray-100 py-14 md:py-20 2xl:py-32">
      <div class="col-center mx-auto p-8 md:px-40">
        <div class="space-y-4 text-center w-fit col-center">
          <h2 class="font-sans text-2xl font-bold text-gray-800 text-center lg:text-4xl" data-aos="fade-up"
            data-aos-once="true" data-aos-duration="1000">
            Top Articles
          </h2>
        </div>

        <div
          class="grid mt-6 sm:grid-cols-2 lg:grid-cols-3 sm:mt-10 md:mt-14 lg:mt-20 sm:gap-x-10 md:gap-x-14 lg:gap-x-14 xl:gap-x-16 2xl:gap-x-20 gap-y-6 sm:gap-y-10 md:gap-y-14 lg:gap-y-28 auto-rows-auto">
          
         <?php 
         $blogData = fetchBlog("https://blog.datawellng.com/api/index.php");
          if ($blogData != 'error') {
              $blogData = json_decode((string) $blogData, true);
              foreach ($blogData as $blogIndex => $blogInfo) {
                  ?>
                        <a href="<?php echo $blogInfo['url'] ;?>" target="_blank">
                          <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
                            <div class="md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                              <img alt="<?php echo $blogInfo['title'] ;?>" loading="lazy" width="150"
                                height="150" decoding="async" data-nimg="1"
                                class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                                src="<?php echo $blogInfo['attachment'] ;?>" />

                              <div
                                class="w-full h-[calc(100%-160px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                                <h4 class="text-base font-bold text-gray-800 md:text-lg">
                                  <?php echo $blogInfo['title'] ;?>
                                </h4>

                                <div class="space-x-4 start flex">
                                  <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                                    <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500"
                                      focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                                      <path
                                        d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                                      </path>
                                      <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                    </svg>
                                    <?php echo timeElapsedString($blogInfo['post_date']) ;?>
                                  </h4>
                                  
                                </div>
                              </div>
                            </div>
                          </div>
                        </a>
                  <?php
              }
          }
         
         ?>
  <!--
          <a href="blog/Cheaper_Data_Bundle_now_available_for_University_Students">
            <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
              <div class="md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                <img alt="Cheaper Data Bundle now available for University Students" loading="lazy" width="150"
                  height="150" decoding="async" data-nimg="1"
                  class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                  src="images/Cheaper_Data_Bundle_now_available_for_University_Students.webp" />

                <div
                  class="w-full h-[calc(100%-160px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                  <h4 class="text-base font-bold text-gray-800 md:text-lg">
                    Cheaper Data Bundle now available for University Students
                  </h4>

                  <div class="space-x-4 start flex">
                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                        </path>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                      </svg>
                      3 months ago
                    </h4>

                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CommentOutlinedIcon">
                        <path
                          d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM20 4v13.17L18.83 16H4V4h16zM6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z">
                        </path>
                      </svg>
                      0
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a href="blog/Stay_Connected_During_the_Elections_with_Cheaper_MTN_Data_Bundles">
            <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
              <div
                class="between-start md:flex-col md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                <img alt="Stay Connected During the Elections with Cheaper MTN Data Bundles" loading="lazy" width="150"
                  height="150" decoding="async" data-nimg="1"
                  class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                  src="images/Stay_Connected_During_the_Elections_with_Cheaper_MTN_Data_Bundles.webp"
                  style="color: transparent" />

                <div
                  class="w-2/3 h-[calc(100%-112px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                  <h4 class="text-base font-bold text-gray-800 md:text-lg">
                    Stay Connected During the Elections with Cheaper MTN Data
                    Bundles
                  </h4>

                  <div class="space-x-4 start flex">
                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                        </path>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                      </svg>
                      3 months ago
                    </h4>

                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CommentOutlinedIcon">
                        <path
                          d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM20 4v13.17L18.83 16H4V4h16zM6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z">
                        </path>
                      </svg>
                      0
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a href="blog/Stay_Connected_During_the_Elections_with_Cheaper_MTN_Data_Bundles_2">
            <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
              <div
                class="between-start md:flex-col md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                <img alt="Stay Connected During the Elections with Cheaper MTN Data Bundles" loading="lazy" width="150"
                  height="150" decoding="async" data-nimg="1"
                  class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                  src="images/Stay_Connected_During_the_Elections_with_Cheaper_MTN_Data_Bundles_2.webp" />

                <div
                  class="w-2/3 h-[calc(100%-112px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                  <h4 class="text-base font-bold text-gray-800 md:text-lg">
                    Stay Connected During the Elections with Cheaper MTN Data
                    Bundles
                  </h4>

                  <div class="space-x-4 start flex">
                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                        </path>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                      </svg>
                      3 months ago
                    </h4>

                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CommentOutlinedIcon">
                        <path
                          d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM20 4v13.17L18.83 16H4V4h16zM6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z">
                        </path>
                      </svg>
                      0
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a href="blog/Stay_Connected_with_Her_Family_and_Friends_At_Cheaper_Costs">
            <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
              <div class="md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                <img alt="Stay Connected with Her Family and Friends At Cheaper Costs" loading="lazy" width="150"
                  height="150" decoding="async" data-nimg="1"
                  class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                  src="images//Stay_Connected_with_Her_Family_and_Friends_At_Cheaper_Costs.webp"
                  style="color: transparent" />

                <div
                  class="w-full h-[calc(100%-160px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                  <h4 class="text-base font-bold text-gray-800 md:text-lg">
                    Stay Connected with Her Family and Friends At Cheaper
                    Costs
                  </h4>

                  <div class="space-x-4 start flex">
                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                        </path>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                      </svg>
                      3 months ago
                    </h4>

                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CommentOutlinedIcon">
                        <path
                          d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM20 4v13.17L18.83 16H4V4h16zM6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z">
                        </path>
                      </svg>
                      0
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </a>

          <a href="blog/Where_do_business_owners_get_cheaper_data_bundle">
            <div data-aos="fade-up" data-aos-once="true" data-aos-duration="1000" class="h-full">
              <div
                class="between-start md:flex-col md:flex-none h-full overflow-hidden transition-shadow bg-gray-200 rounded-md hover:shadow-md">
                <img alt="Where do business owners get cheaper data bundle?" loading="lazy" width="150" height="150"
                  decoding="async" data-nimg="1" class="w-full h-40 object-cover object-center md:w-full sm:h-48"
                  src="images/Where_do_business_owners_get_cheaper_data_bundle.webp" style="color: transparent" />

                <div
                  class="w-2/3 h-[calc(100%-112px)] md:w-full px-4 py-6 xl:py-8 space-y-1.5 md:space-y-3 col-between sm:h-[calc(100%-192px)] order-first md:order-last">
                  <h4 class="text-base font-bold text-gray-800 md:text-lg">
                    Where do business owners get cheaper data bundle?
                  </h4>

                  <div class="space-x-4 start flex">
                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="AccessTimeIcon">
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z">
                        </path>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                      </svg>
                      3 months ago
                    </h4>

                    <h4 class="text-xs font-medium flex text-gray-600 md:text-sm">
                      <svg width="18px" height="18px" class="text-base mr-2 fill-current text-gray-500 css-vubbuv"
                        focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="CommentOutlinedIcon">
                        <path
                          d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM20 4v13.17L18.83 16H4V4h16zM6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z">
                        </path>
                      </svg>
                      0
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </a>
        -->
        </div>

        <div class="mt-20 text-center" data-aos="fade-up" data-aos-once="true" data-aos-duration="1000">
          <a href="http://blog.datawellng.com/" target="_blank" class="flex items-center justify-center">
            <button
              class="flex items-center px-4 text-sm normal-case md:px-6 lg:px-8 md:text-base text-[#D10A22] border-[#D10A22] border-2 rounded-md hover:border-transparent hover:bg-[#D10A22] hover:text-white space-x-2 space-y-8"
              tabindex="0" type="button">
              More
              <span class="ml-2">
                <svg class="h-4 w-4 fill-current hover:fill-[white]"
                  class="MuiSvgIcon-root MuiSvgIcon-fontSizeMedium css-vubbuv" focusable="false" aria-hidden="true"
                  viewBox="0 0 24 24" data-testid="EastIcon">
                  <path d="m15 5-1.41 1.41L18.17 11H2v2h16.17l-4.59 4.59L15 19l7-7-7-7z"></path>
                </svg>
              </span>
              <span class="MuiTouchRipple-root css-w0pj6f"></span>
            </button>
          </a>
        </div>
      </div>
    </section>

    <section data-aos="fade-up" data-aos-once="true" data-aos-duration="1000"
      class="my-10 overflow-hidden sm:my-12 md:my-14 lg:my-16 xl:my-20 mx-auto px-8 md:px-40">
      <div class="flex flex-row justify-center">
        <div class="slider flex flex-row justify-between">
          <!-- Add your slider items here -->
          <div class="">
            <img alt="GOTV" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/gotv.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="DSTV" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/mtn.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="Smile" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/smile.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="MTN" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/mtn.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="Glo" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/globacom.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="9Mobile" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/9mobile.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="Startimes" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/startiems.png"
              style="color: transparent" />
          </div>
          <div class="">
            <img alt="Spectranet" loading="lazy" width="160" height="160" decoding="async"
              class="object-contain w-28 md:w-[160px] h-28 md:h-[160px]" src="images/spectranet.png"
              style="color: transparent" />
          </div>
          <!-- Add more slider items as needed -->
        </div>
      </div>
    </section>
  </main>

  <footer class="hide-on-mobile-nav py-10 font-medium tracking-wide text-zinc-300 bg-zinc-700 md:py-14 2xl:py-20">
    <div class="mx-auto px-8 md:px-40 flex flex-col lg:flex-row lg:flex lg:justify-between">
      <div class="flex flex-col lg:flex-row mt-8 lg:space-y-0 lg:mt-0">
        <p class="text-xs mb-4 lg:mb-0 text-center sm:text-sm md:text-base xl:text-xl lg:text-left">
          © 2023 DataWell. All Rights Reserved.
        </p>
        <div class="lg:ml-8 text-xs text-center sm:text-sm md:text-base xl:text-xl">
          <a class="hover:underline" href="about">About Us</a> &nbsp;
          <a class="hover:underline" href="about#services">Our Services</a>
        </div>
      </div>
      <div class="flex justify-center order-first space-x-8 text-zinc-600 lg:justify-between lg:order-last">
        <a class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
          tabindex="0" href="https://www.facebook.com/datawellng" target="_blank">
          <svg class="w-6 h-6 text-2xl rounded-full fill-zinc-700 text-zinc-700 css-vubbuv" focusable="false"
            aria-hidden="true" viewBox="0 0 24 24" data-testid="FacebookRoundedIcon">
            <path
              d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z">
            </path>
          </svg>
        </a>
        <a class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
          tabindex="0" href="https://www.twitter.com/datawellng" target="_blank">
          <svg class="w-6 h-6 text-2xl rounded-full fill-zinc-700 text-zinc-700 css-vubbuv" focusable="false"
            aria-hidden="true" viewBox="0 0 24 24" data-testid="TwitterIcon">
            <path
              d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z">
            </path>
          </svg>
        </a>
        <a class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
          tabindex="0" href="https://www.instagram.com/datawellng" target="_blank">
          <svg class="w-6 h-6 text-2xl fill-zinc-700 css-vubbuv" focusable="false" aria-hidden="true"
            viewBox="0 0 24 24" data-testid="InstagramIcon">
            <path
              d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z">
            </path>
          </svg>
        </a>
        <a class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
          tabindex="0" href="https://www.linkedin.com/company/the-data-well" target="_blank">
          <svg class="w-6 h-6 text-2xl fill-zinc-700 css-vubbuv" focusable="false" aria-hidden="true"
            viewBox="0 0 24 24" data-testid="LinkedInIcon">
            <path
              d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z">
            </path>
          </svg>
          <span class="MuiTouchRipple-root css-w0pj6f"></span>
        </a>
      </div>
    </div>
  </footer>

  <?php
  function fetchBlog($blogUrl) {
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $blogUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the response as a string

        // Execute cURL session and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Display the response
        return $response;

    }
    
    function timeElapsedString($datetime, $full = false) {
        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diffString = [
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];
    
        foreach ($diffString as $key => &$value) {
            if ($diff->$key) {
                $value = $diff->$key . ' ' . ($diff->$key > 1 ? $value . 's' : $value);
            } else {
                unset($diffString[$key]);
            }
        }
    
        if (!$full) {
            $diffString = array_slice($diffString, 0, 1);
        }
    
        return $diffString ? implode(', ', $diffString) . ' ago' : 'just now';
    }
    ?>

  <script src="home.js"></script>
  <script src="//unpkg.com/a11y-slider@latest/dist/a11y-slider.js"></script>
  <script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>

</html>