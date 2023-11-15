<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="grid grid-cols-2 md:grid-cols-3 gap-10">
                        @foreach($products as $product)
                            <div>
                                <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow">
                                    <img class="p-10 rounded-t-lg" src="{{ asset('storage/' . $product->image) }}">

                                    <div class="px-5 pb-5">

                                        <h5 class="text-xl font-semibold tracking-tight text-gray-900">{{ $product->title }}</h5>
                                        <p>{{ $product->description }}</p>
                                        <form
                                            action="{{ route('cart.add', ['productId' => $product->id]) }}"
                                            method="post" id="add-to-cart" class="add-to-cart">
                                            @csrf
                                            <div class="grid grid-cols-2 gap-4 mt-2.5 mb-5">

                                                <div class="text-red-700 mt-2">
                                                    @if ($product->quantity <= 0)
                                                        out of stock
                                                    @else
                                                    {{ $product->quantity }} in stock
                                                    @endif
                                                </div>

                                                <div>
                                                    @if ($product->quantity <= 0)
                                                        <div
                                                            class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"
                                                            role="alert">
                                                            <svg class="flex-shrink-0 inline w-3 h-3 mr-3"
                                                                 aria-hidden="true"
                                                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                                 viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                                            </svg>
                                                            <span class="sr-only">Info</span>
                                                            <div>
                                                                <span class="font-medium text-xs whitespace-nowrap">Currently Unavailable</span>
                                                            </div>
                                                        </div>
                                                    @else


                                                        <div class="inline-flex rounded-md shadow-sm cart" role="group">
                                                            <p class="hidden"
                                                               id="available-quantity">{{ $product->quantity }}</p>
                                                            <button type="button" id="decrement"
                                                                    onclick="decrementToCart($(this))"
                                                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                                                -
                                                            </button>
                                                            @forelse ($product->cart as $item)
                                                                <input readonly type="text" name="count"
                                                                       id="cart-quantity"
                                                                       class="cart-quantity text-center w-12 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
                                                                       value="{{ $item->count }}">
                                                            @empty
                                                                <input readonly type="text" name="count"
                                                                       id="cart-quantity"
                                                                       class="cart-quantity text-center w-12 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
                                                                       value="1">
                                                            @endforelse
                                                            <button type="button" id="increment"
                                                                    onclick="incrementToCart($(this))"
                                                                    class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                                                +
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div>
                                                    <span
                                                        class="text-3xl font-bold text-gray-900">${{ $product->price }}</span>
                                                </div>

                                                <div>
                                                    <div x-data="{ modelOpen: false }">
                                                        <button @click="modelOpen =!modelOpen"
                                                                class="flex items-center justify-center px-3 py-2 space-x-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                                            <svg class="w-3.5 h-3.5 mr-2"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 fill="currentColor"
                                                                 viewBox="0 0 18 21">
                                                                <path
                                                                    d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                                                            </svg>
                                                            Add to cart
                                                        </button>

                                                        <div x-show="modelOpen"
                                                             class="fixed inset-0 z-50 overflow-y-auto"
                                                             aria-labelledby="modal-title" role="dialog"
                                                             aria-modal="true">
                                                            <div
                                                                class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                                                <div x-cloak @click="modelOpen = false"
                                                                     x-show="modelOpen"
                                                                     x-transition:enter="transition ease-out duration-300 transform"
                                                                     x-transition:enter-start="opacity-0"
                                                                     x-transition:enter-end="opacity-100"
                                                                     x-transition:leave="transition ease-in duration-200 transform"
                                                                     x-transition:leave-start="opacity-100"
                                                                     x-transition:leave-end="opacity-0"
                                                                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40"
                                                                     aria-hidden="true"></div>

                                                                <div x-cloak x-show="modelOpen"
                                                                     x-transition:enter="transition ease-out duration-300 transform"
                                                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                     x-transition:leave="transition ease-in duration-200 transform"
                                                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                     class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
                                                                    <div
                                                                        class="flex items-center justify-between space-x-4">
                                                                        <h1 class="text-xl font-medium text-gray-800 ">
                                                                            Add to cart</h1>

                                                                        <button @click="modelOpen = false"
                                                                                class="text-gray-600 focus:outline-none hover:text-gray-700">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 class="w-6 h-6" fill="none"
                                                                                 viewBox="0 0 24 24"
                                                                                 stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                      stroke-linejoin="round"
                                                                                      stroke-width="2"
                                                                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                            </svg>
                                                                        </button>
                                                                    </div>

                                                                    <p class="mt-2 text-sm text-gray-500 ">
                                                                        Product added to cart. <a
                                                                            href="{{ route('cart') }}">GO TO
                                                                            CART</a>
                                                                    </p>


                                                                    <div class="flex justify-end mt-6">
                                                                        <button @click="modelOpen = false"
                                                                                type="button"
                                                                                class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                                                            Browse more products
                                                                        </button>
                                                                        <a href="{{ route('cart') }}"
                                                                           class="ml-2 px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
                                                                            Go to Cart
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/custom-user.js') }}"></script>

    <script>
        function incrementToCart(this_) {
            var count = this_.closest('div').find('.cart-quantity').val();
            const avail_quantity = this_.closest('div').find('#available-quantity').text();
            const decrement = this_.closest('div').find('#decrement')

            count++;

            if (count > avail_quantity) {
                this_.prop('disabled', true)
            } else {
                this_.closest('div').find('.cart-quantity').val(count)
            }

            if (decrement.is(':disabled')) {
                decrement.removeAttr('disabled')
            }
        }

        function decrementToCart(this_) {
            var count = this_.closest('div').find('.cart-quantity').val()
            const increment = this_.closest('div').find('#increment');

            count--;
            if (count < 1) {
                this_.prop('disabled', true);
            } else {
                this_.closest('div').find('.cart-quantity').val(count);
            }

            if (increment.is(':disabled')) {
                increment.removeAttr('disabled');
            }
        }
    </script>

</x-app-layout>
