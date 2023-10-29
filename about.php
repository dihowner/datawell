<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css" />
    <link
      href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="node_modules/a11y-slider/dist/a11y-slider.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <script src="node_modules/a11y-slider/dist/a11y-slider.js"></script>

    <title>About us | The Data Well Niegeria</title>
  </head>
  <body>
  <?php include_once "navbar.php"; ?>

    <main id="main" class="hide-on-mobile-nav">
      <section
        class="flex container mx-auto px-4 md:px-0 justify-center bg-gray-100 h-60 lg:h-96"
      >
        <div class="h-full flex flex-col justify-center items-center">
          <div class="space-y-2 text-center lg:space-y-4 w-fit col-center">
            <h2
              data-aos="animate-fadeInDown"
              class="font-sans text-3xl font-bold text-gray-800 lg:text-5xl md:text-4xl"
            >
              About Us
            </h2>
            <p class="text-sm text-gray-500 md:text-base lg:text-lg center">
              Trustworthy experience, dependable service.
            </p>
          </div>
          <div class="mt-4 space-x-2 lg:mt-8 center">
            <a href="https://intelregion.peaktopup.com/create-account">
            <button
              class="px-4 py-2 lg:px-10 lg:py-4 text-xs lg:text-sm font-bold text-white bg-[#D10A22] rounded lg:rounded-lg normal-case hover:bg-[#A0081A] css-1ujsas3"
              tabindex="0"
              type="button"
            >
              Get Started<span class="MuiTouchRipple-root css-w0pj6f"></span>
            </button>
          </a>
          <a href="https://intelregion.peaktopup.com/login">
            <button
              class="px-4 py-2 lg:px-10 lg:py-4 text-xs lg:text-sm font-bold text-white bg-[#D10A22] rounded lg:rounded-lg normal-case hover:bg-[#A0081A] css-1ujsas3"
              tabindex="0"
              type="button"
            >
              Login
            </button>
          </a>
          </div>
        </div>
      </section>

      <section
        class="flex container mx-auto px-8 md:px-0 justify-center py-10 sm:py-14 md:py-16 lg:py-20 container_fluid center"
      >
        <div class="text-center md:w-10/12 xl:w-8/12">
          <h6
            class="text-lg font-bold text-gray-800 sm:text-xl md:text-2xl"
            data-aos="fade-up"
            data-aos-once="true"
            data-aos-duration="1000"
          >
            About our Company
          </h6>
          <p
            class="mt-2 text-sm text-gray-500 sm:text-base md:text-lg lg:mt-4"
            data-aos="fade-up"
            data-aos-once="true"
            data-aos-duration="1000"
          >
            Welcome to Datawellng, your one-stop shop for all your retailing
            data, airtime, bills payment and conversion of airtime to cash
            needs. Our platform offers you a hassle-free and affordable way to
            stay connected and handle your bills payments online.
          </p>

          <section id="services" class="pt-14 md:pt-20 2xl:pt-32 undefined">
            <div class="text-center col-center">
              <h6
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
                class="font-bold text-gray-800 text-lg sm:text-xl md:text-2xl"
              >
                Our Services
              </h6>
              <p
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
                class="text-sm sm:text-base md:text-lg text-gray-500 undefined"
              >
                Here are some of the services we offer
              </p>
            </div>
            <div
              class="grid sm:grid-cols-2 gap-10 sm:gap-14 md:gap-16 lg:gap-20 mt-10 sm:mt-14 md:mt-16 lg:mt-20"
            >
              <div
                class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8"
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
              >
                <div class="rounded-full bg-[#D10A22] p-8 relative w-16 h-16">
                  <img
                    alt="Data Purchase"
                    loading="lazy"
                    decoding="async"
                    data-nimg="fill"
                    class="p-2"
                    src="svg/Data Bundle Icon.svg"
                    style="
                      position: absolute;
                      height: 100%;
                      width: 100%;
                      inset: 0px;
                      color: transparent;
                    "
                  />
                </div>

                <div
                  class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0"
                >
                  <h6
                    class="font-bold tracking-wide text-gray-800 text-base lg:text-sm"
                  >
                    Data Purchase
                  </h6>

                  <p class="text-gray-500 text-base md:text-sm mt-1 md:mt-2">
                    We offer affordable data plans for all major networks in
                    Nigeria. Stay connected to the internet with our fast and
                    reliable data plans, starting from as low as ₦50.
                  </p>
                </div>
              </div>

              <div
                class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8"
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
              >
                <div class="rounded-full bg-[#D10A22] p-8 relative w-16 h-16">
                  <img
                    alt="Airtime Purchase"
                    loading="lazy"
                    decoding="async"
                    data-nimg="fill"
                    class="p-2"
                    src="svg/Airtime Purchase.svg"
                    style="
                      position: absolute;
                      height: 100%;
                      width: 100%;
                      inset: 0px;
                      color: transparent;
                    "
                  />
                </div>

                <div
                  class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0"
                >
                  <h6
                    class="font-bold tracking-wide text-gray-800 text-base lg:text-sm"
                  >
                    Airtime Purchase
                  </h6>

                  <p class="text-gray-500 text-base md:text-sm mt-1 md:mt-2">
                    Top up your mobile phone easily and conveniently on our
                    platform. We offer instant airtime recharge for all networks
                    in Nigeria at a discounted rate.
                  </p>
                </div>
              </div>

              <div
                class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8"
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
              >
                <div class="rounded-full bg-[#D10A22] p-8 relative w-16 h-16">
                  <img
                    alt="Bills Payment"
                    loading="lazy"
                    decoding="async"
                    data-nimg="fill"
                    class="p-2"
                    src="svg/bills payment.svg"
                    style="
                      position: absolute;
                      height: 100%;
                      width: 100%;
                      inset: 0px;
                      color: transparent;
                    "
                  />
                </div>

                <div
                  class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0"
                >
                  <h6
                    class="font-bold tracking-wide text-gray-800 text-base lg:text-sm"
                  >
                    Bills Payment
                  </h6>

                  <p class="text-gray-500 text-base md:text-sm mt-1 md:mt-2">
                    Say goodbye to long queues and delays when it comes to bills
                    payment. You can now pay your electricity bills, cable TV
                    bills, and other utility bills on our platform, with just a
                    few clicks.
                  </p>
                </div>
              </div>

              <div
                class="flex flex-col items-center md:flex md:flex-row md:w-full md:space-x-8"
                data-aos="fade-up"
                data-aos-once="true"
                data-aos-duration="1000"
              >
                <div class="rounded-full bg-[#D10A22] p-8 relative w-16 h-16">
                  <img
                    alt="Airtime to Cash Conversion"
                    loading="lazy"
                    decoding="async"
                    data-nimg="fill"
                    class="p-2"
                    src="svg/Airtime to Cash icon.svg"
                    style="
                      position: absolute;
                      height: 100%;
                      width: 100%;
                      inset: 0px;
                      color: transparent;
                    "
                  />
                </div>

                <div
                  class="mt-4 text-center lg:text-left w-fit lg:mt-4 xl:mt-0"
                >
                  <h6
                    class="font-bold tracking-wide text-gray-800 text-base lg:text-sm"
                  >
                    Airtime to Cash Conversion
                  </h6>

                  <p class="text-gray-500 text-base md:text-sm mt-1 md:mt-2">
                    Do you have excess airtime that you want to convert to cash?
                    Look no further, as our platform offers a seamless and
                    affordable way to convert airtime to cash. Get instant cash
                    in your bank account in no time.
                  </p>
                </div>
              </div>
            </div>
          </section>
          <p
            class="mt-10 text-sm text-gray-500 sm:mt-14 md:mt-16 lg:mt-20 sm:text-base md:text-lg"
            data-aos="fade-up"
            data-aos-once="true"
            data-aos-duration="1000"
          >
            At Datawellng, we prioritize the satisfaction of our customers. We
            are trustworthy and transparent, ensuring that all transactions are
            carried out safely and securely. Our platform is user-friendly and
            easy to navigate, making it easy for you to complete your
            transactions with ease.Stay connected, pay bills, and convert
            airtime to cash effortlessly with Datawellng.
          </p>
        </div>
      </section>
    </main>

    <footer
      class="hide-on-mobile-nav py-10 font-medium tracking-wide text-zinc-300 bg-zinc-700 md:py-14 2xl:py-20"
    >
      <div
        class="mx-auto px-8 md:px-40 flex flex-col lg:flex-row lg:flex lg:justify-between"
      >
        <div class="flex flex-col lg:flex-row mt-8 lg:space-y-0 lg:mt-0">
          <p
            class="text-xs mb-4 lg:mb-0 text-center sm:text-sm md:text-base xl:text-xl lg:text-left"
          >
            © 2023 DataWell. All Rights Reserved.
          </p>
          <div
            class="lg:ml-8 text-xs text-center sm:text-sm md:text-base xl:text-xl"
          >
            <a class="hover:underline" href="/">Terms &amp; Conditions</a>
            <a class="hover:underline" href="/">Privacy Policy</a>
          </div>
        </div>
        <div
          class="flex justify-center order-first space-x-8 text-zinc-600 lg:justify-between lg:order-last"
        >
          <a
            class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
            tabindex="0"
            href="https://www.facebook.com/datawellng"
            target="_blank"
          >
            <svg
              class="w-6 h-6 text-2xl rounded-full fill-zinc-700 text-zinc-700 css-vubbuv"
              focusable="false"
              aria-hidden="true"
              viewBox="0 0 24 24"
              data-testid="FacebookRoundedIcon"
            >
              <path
                d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"
              ></path>
            </svg>
          </a>
          <a
            class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
            tabindex="0"
            href="https://www.twitter.com/datawellng"
            target="_blank"
          >
            <svg
              class="w-6 h-6 text-2xl rounded-full fill-zinc-700 text-zinc-700 css-vubbuv"
              focusable="false"
              aria-hidden="true"
              viewBox="0 0 24 24"
              data-testid="TwitterIcon"
            >
              <path
                d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"
              ></path>
            </svg>
          </a>
          <a
            class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
            tabindex="0"
            href="https://www.instagram.com/datawellng"
            target="_blank"
          >
            <svg
              class="w-6 h-6 text-2xl fill-zinc-700 css-vubbuv"
              focusable="false"
              aria-hidden="true"
              viewBox="0 0 24 24"
              data-testid="InstagramIcon"
            >
              <path
                d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"
              ></path>
            </svg>
          </a>
          <a
            class="rounded-full border border-zinc-700 p-1 transition-colors bg-zinc-400 hover:bg-zinc-300 css-1yxmbwk"
            tabindex="0"
            href="https://www.linkedin.com/company/the-data-well"
            target="_blank"
          >
            <svg
              class="w-6 h-6 text-2xl fill-zinc-700 css-vubbuv"
              focusable="false"
              aria-hidden="true"
              viewBox="0 0 24 24"
              data-testid="LinkedInIcon"
            >
              <path
                d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"
              ></path>
            </svg>
            <span class="MuiTouchRipple-root css-w0pj6f"></span>
          </a>
        </div>
      </div>
    </footer>

    <script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
    <script src="app.js"></script>
  </body>
</html>
