  <section class="static">
    <nav class="container md:mb-10" id="nav">
      <!-- Mobile -->

      <div class="lg:hidden flex items-center container mx-auto py-4 justify-between" id="menuItems">
        <div class="space-x-4">
          <!-- Hamburger Icon Button -->
          <button id="hamburgerBtn" class="bg-transparent border rounded-full ml-4 p-2">
            <svg class="w-4 h-4 fill-gray-400" focusable="false" aria-hidden="true" viewBox="0 0 24 24"
              data-testid="MenuIcon">
              <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
          </button>


          <!-- Mobile Menu -->
          <div id="mobileMenuContainer" class="lg:hidden">
            <ul id="mobileMenu"
              class="hidden flex flex-col mx-auto items-center absolute z-10 bg-white h-screen mt-24 w-screen animate__animated animate__fadeInLeft">
              <li
                class="before:w-full text-neutral-700 hover:text-black before:bg-neutral-700 py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
                <a href="home">Home</a>
              </li>
              <li
                class="false text-neutral-700 hover:text-black before:bg-neutral-700 py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
                <a href="about">About</a>
              </li>
              <li
                class="false text-neutral-700 hover:text-black before:bg-neutral-700 py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
                <a href="pricing">Pricing</a>
              </li>
              <li
                class="false text-neutral-700 hover:text-black before:bg-neutral-700 py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
                <a href="contact">Contact</a>
              </li>
              <li
                class="false text-neutral-700 hover:text-black before:bg-neutral-700 py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
                <a href="blog/blog">Blog</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Login Button -->
        <a href="login"
          class="bg-[#D10A22] z-10 hover:bg-red-800 text-white rounded-3xl flex px-4 py-2 sm:px-6 sm:py-3 text-xs sm:text-sm font-bold">
          <svg class="w-4 h-4 mr-2 fill-white text-base" focusable="false" aria-hidden="true" viewBox="0 0 24 24"
            data-testid="HttpsOutlinedIcon">
            <path
              d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z">
            </path>
          </svg>
          Login
        </a>

        <!-- DataWell Logo on small screens -->
        <a href="home">
          <img alt="The Data Well Logo" loading="lazy" width="1288" height="1288" decoding="async" data-nimg="1"
            class="w-16 lg:hidden mr-4" src="images/The Data Well logo oppo.png" />
        </a>
      </div>

      <hr class="border-red-600" />

      <div class="relative text-white lg:w-full hidden lg:block z-[999999999999999]">
        <div class="absolute bg-[#D10A22] top-0 left-0 -z-[1] lg:block h-[72px] lg:w-[80%] xl:w-[60%] 2xl:w-[50%]">
        </div>
        <ul class="space-x-8 flex mx-auto px-20 text-white">
          <li
            class="before:w-full ml-20 text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="home">Home</a>
          </li>
          <li
            class="false text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="about">About</a>
          </li>
          <li
            class="false text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="about#services">Services</a>
          </li>
          <li
            class="false text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="pricing">Pricing</a>
          </li>
          <li
            class="false text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="contact">Contact</a>
          </li>
          <li
            class="false text-neutral-50 hover:text-white before:bg-white py-6 font-bold tracking-wide before:transition-all hover:transition-all transition-all before:absolute relative before:bottom-4 before:rounded-full before:h-1 before:hover:w-1/2">
            <a href="blog/blog">Blog</a>
          </li>

          <li class="relative px-16 py-6 space-x-2 font-bold tracking-wide cursor-pointer loginLi center">
            <a href="login" class="flex flex-row items-center">
              <div
                class="absolute h-full w-full top-0 left-0 -z-[1] transform -skew-x-[27deg] bg-[#A0081A] logindiv transition-colors duration-500">
              </div>
              <div class="flex flex-col"></div>
              <svg class="w-4 h-4 mr-2 fill-white text-base" focusable="false" aria-hidden="true" viewBox="0 0 24 24"
                data-testid="HttpsOutlinedIcon">
                <path
                  d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z">
                </path>
              </svg>
              <span>Login</span>
              <svg class="w-6 h-6 mr-2 fill-white" focusable="false" aria-hidden="true" viewBox="0 0 24 24"
                data-testid="NavigateNextRoundedIcon">
                <path
                  d="M9.31 6.71c-.39.39-.39 1.02 0 1.41L13.19 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.72 6.7c-.38-.38-1.02-.38-1.41.01z">
                </path>
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <a href="home" class="absolute z-50 w-16 lg:w-24 xl:w-32 right-10 top-2 lg:top-8">
      <img src="images/The Data Well logo oppo.png" alt="The Data Well Logo" loading="lazy" decoding="async"
        data-nimg="1" class="w-full h-auto lg:block hidden animate__animated animate__fadeInDown"
        style="color: transparent" />
    </a>
  </section>