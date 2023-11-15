<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>

                    
                        <h3 class="capitalize font-medium text-lg text-center m-4">Your shopping cart
                            ({{ count($cartItems) }})
                        </h3>
                        @if(count($cartItems) == 0)
                            <p class="text-center m-4">
                                is empty, browse
                                <a href="{{ route('product.list') }}"
                                   class="inline-flex items-center font-normal text-blue-600 dark:text-blue-500 hover:font-medium">
                                    PRODUCTS
                                </a>
                            </p>
                        @endif
                    </div>

                    <div>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                            <table class="w-full text-sm text-left text-gray-500">
                                <tbody>
                                @foreach ($cartItems as $cartItem)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <img class="h-auto max-w-xs"
                                                 src="{{ asset('storage/' . $cartItem->product->image) }}"
                                                 alt="{{ $cartItem->product->title }}">
                                        </td>
                                        <th scope="row" class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-medium text-gray-900 uppercase">{{ $cartItem->product->title }}</p>
                                            <p>${{ $cartItem->subtotal }}</p>
                                            <p>Selected Quantity: {{ $cartItem->count }}</p>
                                            <form action="{{ route('cart.remove', ['id' => $cartItem->id]) }}"
                                                  method="post" id="remove-from-cart" class="remove-from-cart">
                                                @method('DELETE')
                                                @csrf
                                                <div>
                                                    <button type="submit"
                                                            class="remove-btn mb-5 mt-4 flex items-center justify-center px-3 py-2 space-x-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:bg-blue-700 hover:bg-blue-600 focus:outline-none focus:bg-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                                        Remove from cart
                                                    </button>
                                                </div>
                                            </form>
                                        </th>
                                        <td class="px-6 py-4">
                                            <form
                                                action="{{ route('cart.add', ['productId' => $cartItem->product->id]) }}"
                                                method="post" id="update-cart-form" class="update-cart-form">
                                                @csrf

                                                <div class="inline-flex rounded-md shadow-sm cart" role="group">
                                                    <p class="hidden"
                                                       id="available-quantity">{{ $cartItem->product->quantity }}</p>
                                                    <button type="button" id="decrement"
                                                            onclick="decrementToCart($(this))"
                                                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                                        -
                                                    </button>
                                                    <input readonly type="text" name="count" id="cart-quantity"
                                                           class="cart-quantity text-center w-12 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700"
                                                           value="{{ $cartItem->count }}">
                                                    <button type="button" id="increment"
                                                            onclick="incrementToCart($(this))"
                                                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                                        +
                                                    </button>
                                                </div>

                                                <button type="submit"
                                                        class="mb-5 mt-9 flex items-center justify-center px-3 py-2 space-x-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:bg-blue-700 hover:bg-blue-600 focus:outline-none focus:bg-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                                    <svg class="w-3.5 h-3.5 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                         fill="currentColor" viewBox="0 0 18 21">
                                                        <path
                                                            d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                                                    </svg>
                                                    Update cart
                                                </button>

                                            </form>


                                        </td>
                                        <td class="px-6 py-4">

                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    Subtotal
                                                </div>
                                                <div class="text-right">
                                                    ${{ $cartItem->subtotal }}
                                                </div>
                                                <div>
                                                    Estimated Tax
                                                </div>
                                                <div class="text-right">
                                                    ${{ $cartItem->tax_amount }}
                                                </div>
                                            </div>

                                            <hr class="h-px my-8 bg-gray-200 border-0">

                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="font-medium">
                                                    Total Amount
                                                </div>
                                                <div class="text-right">
                                                    ${{ $cartItem->total_amount }}
                                                </div>
                                            </div>


                                            @if($cartItem->product->quantity == 0)
                                                <div
                                                    class="mt-5 flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"
                                                    role="alert">
                                                    <svg class="flex-shrink-0 inline w-3 h-3 mr-3" aria-hidden="true"
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
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if (count($cartItems) > 0)
                            <div class="px-60">
                                <div class="px-6 py-10">

                                    <div class="grid grid-cols-2 gap-5">
                                        @foreach($taxDetails as $taxPercentage => $taxAmount)
                                            <div>
                                                (VAT)
                                                <span class="font-semibold">{{ $taxPercentage }}%</span>

                                            </div>
                                            <div class="text-right font-medium">
                                                ${{ $taxAmount }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr class="h-px my-8 bg-gray-200 border-0">

                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="font-medium text-lg">
                                            Total Payable Amount
                                        </div>
                                        <div class="text-right font-bold text-lg">
                                            ${{ $payableAmount }}
                                        </div>
                                    </div>


                                    <div x-data="{ modelOpen: false }">
                                        <button @click="modelOpen =!modelOpen"
                                                class="w-full mt-5 text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2">
                                            Check Out
                                        </button>

                                        <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto"
                                             aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div
                                                class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                                <div x-cloak @click="modelOpen = false" x-show="modelOpen"
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
                                                    <div class="flex items-center justify-between space-x-4">
                                                        <h1 class="text-xl font-medium text-gray-800 ">Check Out</h1>

                                                        <button @click="modelOpen = false"
                                                                class="text-gray-600 focus:outline-none hover:text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <p class="mt-2 text-sm text-gray-500 ">
                                                        Check Out or <a href="{{ route('product.list') }}">BROWSE MORE
                                                            PRODUCTS</a>
                                                    </p>


                                                    <div class="flex justify-end mt-6">
                                                        <a href="{{ route('product.list') }}" type="button"
                                                           class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                                            Browse more products
                                                        </a>
                                                        <form action="{{ route('check.out') }}" method="post">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="ml-2 px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-blue-500 rounded-md dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:bg-blue-700 hover:bg-blue-600 focus:outline-none focus:bg-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                                                Check Out
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/custom-cart.js') }}"></script>

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
