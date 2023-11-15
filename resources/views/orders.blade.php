<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h3 class="capitalize font-medium text-lg text-center m-4">Your Orders</h3>
                        @if(count($orders) == 0)
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

                            @if (count($orders) != 0)
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Order Number
                                        </th>
                                        <th scope="col" class="px-6 text-center py-3">
                                            Order Details
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($orders as $orderNumber => $orderDetails)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                {{ $orderNumber }}
                                            </th>
                                            <td class="px-6 py-4">
                                                <div class="order-tab grid grid-cols-2 gap-5 px-60">
                                                    @foreach($orderDetails as $order)
                                                        <div class="text-md font-medium uppercase">
                                                            {{ $order->product }}
                                                        </div>
                                                        <div class="text-right">
                                                            VAT ({{ $order->tax_percentage }}%)
                                                        </div>
                                                        <div class="text-md font-medium uppercase">
                                                            Quantity: {{ $order->quantity }}
                                                        </div>
                                                        <div class="text-right">
                                                            Subtotal: ${{ $order->total_amount }}
                                                        </div>
                                                    @endforeach

                                                    @foreach($totalAmounts as $orderNo => $totalAmount)
                                                        @if($orderNo == $orderNumber)
                                                            <div class="font-medium text-lg">
                                                                Total Amount
                                                            </div>
                                                            <div class="text-right font-bold text-lg">
                                                                ${{ $totalAmount }}
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        amount = $('.total-amount').text();

        console.log(amount);
    </script>
</x-app-layout>
