<x-form-section submit="Orders">
    <x-slot name="title">
        {{ __('Orders') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can check your orders here.') }}
    </x-slot>

    <x-slot name="form">
            <table>
                <thead>
                    <tr>
                        <th>
                            <x-label value="{{ __('Order ID') }}" />
                        </th>
                        <th>
                            <x-label value="{{ __('Date') }}" />
                        </th>
                        <th>
                            <x-label value="{{ __('Total Price') }}" />
                        </th>
                        <th>
                            <x-label value="{{ __('Status') }}" />
                        </th>
                        <th>
                            <x-label value="{{ __('Invoice') }}" />
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->ref_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->created_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->total_price }}  Ft
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $order->status }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('order.invoice', $order->id) }}">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </x-slot>
</x-form-section>