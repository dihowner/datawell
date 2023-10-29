<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <title>Sign Up | The Data Well Nigeria</title>
  </head>
  <body>
    @include('sweetalert::alert')
    
    <section class="flex h-screen lg:center">
      <a href="https://datawellng.com"><img
          alt="The Data Well Logo"
          loading="lazy"
          width="1288"
          height="1288"
          decoding="async"
          data-nimg="1"
          class="absolute z-50 w-14 lg:w-20 xl:w-24 left-[6%] top-[15px] lg:top-[30px] shadow-md lg:shadow-2xl"
          src="{{ asset('frontend/images/The Data Well logo oppo.png') }}"
      /></a>
      <div
        class="hidden w-5/12 h-full text-white bg-[#D10A22] lg:flex lg:flex-col lg:justify-center lg:items-center"
      >
        <h4 class="text-4xl font-bold">Welcome Back!</h4>
        <p class="w-8/12 mt-4 text-lg text-center text-gray-100">
          Ready to get connected? Login now and stay connected to your world!
        </p>
        <a href="login"
          ><button
            class="px-20 py-4 mt-8 text-base font-bold border-white rounded-full hover:bg-white border hover:text-[#D10A22]"
            tabindex="0"
            type="button"
          >
            Sign in
          </button></a>
      </div>
      <div
        class="flex flex-col justify-center items-center w-full h-full lg:w-7/12"
      >
        <h4
          class="text-xl font-bold sm:text-2xl md:text-3xl lg:text-4xl text-[#D10A22]"
        >
          Create an Account
        </h4>
        <form method="post" action="{{ route('joinus') }}"
          class="w-full flex flex-col justify-center items-center max-w-sm mt-5 space-y-5 sm:space-y-2 md:space-y-5 lg:space-y-5 md:mt-5 lg:mt-5"
        >  @csrf
            @if ($errors->any() || session()->has('error'))
                <div class="alert alert-danger" style="font-size: 18px">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        @if (session()->has('error'))
                            <li>{{ session()->get('error') }}</li>
                        @endif
                    </ul>
                </div>
            @endif
            
          <div class="">
            <label class="text-gray-500" data-shrink="false" for=""
              >Username</label
            >
            <div class="">
              <input
                aria-invalid="false"
                id=":rp:"
                type="text" placeholder="Your username" name="username"
                class="bg-gray-100 sm:w-80 sm:px-4 sm:py-1.5 border-b border-black focus:outline-none focus:border-blue-500 focus:border-b" />
            </div>
          </div>
          <div>
            <label
              class="text-gray-500"
              data-shrink="false"
              for=":rq:"
              id=":rq:-label"
              >Fullname</label
            >
            <div>
              <input
                aria-invalid="false"
                id=":rq:" placeholder="Your full name" name="fullname"
                type="text"
                class="bg-gray-100 sm:w-80 sm:px-4 sm:py-1.5 border-b border-black focus:outline-none focus:border-blue-500 focus:border-b" />
            </div>
          </div>
          <div>
            <label
              class="text-gray-500"
              data-shrink="false"
              for=":rr:"
              id=":rr:-label"
              >Email Address</label
            >
            <div>
              <input
                aria-invalid="false"
                id=":rr:"
                type="email" placeholder="Enter your valid Email Address" name="emailaddress"
                class="bg-gray-100 sm:w-80 sm:px-4 sm:py-1.5 border-b border-black focus:outline-none focus:border-blue-500 focus:border-b" />
            </div>
          </div>
          <div>
            <label class="text-gray-500" data-shrink="false" for="" id=""
              >Phone Number</label
            >
            <div>
              <input
                aria-invalid="false"
                id=":rs:" placeholder="Enter your valid mobile number" name="phone_number" maxlength="11" minlength="11"
                class="bg-gray-100 sm:w-80 sm:px-4 sm:py-1.5 border-b border-black focus:outline-none focus:border-blue-500 focus:border-b" />
            </div>
          </div>
          <div>
            <label class="text-gray-500" data-shrink="false" for="" id=""
              >Password</label
            >
            <div>
              <input
                aria-invalid="false"
                id="" type="password" placeholder="Enter your secure Password" name="password"
                class="bg-gray-100 sm:w-80 sm:px-4 sm:py-1.5 border-b border-black focus:outline-none focus:border-blue-500 focus:border-b" />
            </div>
          </div>
          <button
            class="px-8 py-2 mt-8 text-sm font-bold text-white rounded-full lg:mt-12 lg:py-4 lg:text-base lg:px-20 bg-[#D01A22] hover:bg-white hover:text-[#D01A22] border"
            tabindex="0"
            type="submit"
          >
            Sign up
          </button>
        </form>
        <p class="mt-4 text-xs text-gray-700 lg:hidden">
          Already have an account?<a href="login">
              <button
              class="text-xs normal-case text-[#D01A22] css-1ujsas3"
              tabindex="0"
              type="button">
              Sign in<span class=""></span></button></a>
        </p>
      </div>
    </section>
  </body>
</html>
