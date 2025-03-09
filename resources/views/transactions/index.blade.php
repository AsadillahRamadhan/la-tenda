<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>La Tenda | Cashier</title>
</head>
<style>
    body {
        font-family: 'Poppins';
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .element::-webkit-scrollbar {
        display: none;
    }
</style>

<body>
    <div class="grid grid-cols-12">
        <div class="col-span-8 py-8 px-4 h-screen">
            <div class="text-xl text-center">La Tenda Cashier</div>
            <div class="mt-12 flex flex-col gap-y-4 h-[90%] overflow-y-scroll">
                @foreach ($products as $category => $product)
                    <div>
                        <div class="w-full h-12 rounded-2xl bg-slate-400 flex justify-center items-center cursor-pointer mb-4 select-none text-white"
                            onclick="opens(`{{ $category }}`)">
                            {{ $category }}</div>

                        <div class="flex-col gap-y-2 hidden" id="{{ $category }}">
                            @foreach ($product as $p)
                                <div class=" bg-slate-200 rounded-xl p-4 flex justify-between items-center">
                                    <span>{{ $p->name }}</span>
                                    <button class="bg-lime-600 w-8 aspect-square rounded-lg cursor-pointer"><i
                                            class="fa-solid fa-cart-shopping text-white"
                                            onclick="addMenu(`{{ $p->id }}`, `{{ $p->name }}`, {{ $p->price }})"></i></button>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        <form method="POST" action="{{ route('transaction.store') }}" onsubmit="submitForm(this, 'order')"
            class="col-span-4 flex flex-col justify-between h-screen w-full px-4 py-8 bg-slate-200">
            @csrf
            <div class="text-xl text-center">Summary</div>
            <div class=" h-[80%]  py-4">
                <div class="grid grid-cols-12 text-slate-700 font-semibold mb-6">
                    <div class="col-span-8">
                        Menu
                    </div>
                    <div class="col-span-4 text-center">
                        Qty
                    </div>
                </div>
                <div class="flex flex-col gap-y-6 h-[90%]  overflow-y-scroll" id="summary">

                </div>



            </div>
            <div class="grid grid-cols-12 text-slate-700 font-semibold mb-6">
                <div class="col-span-8">
                    Total Price
                </div>
                <div class="col-span-4">
                    <span id="total_price">Rp 0,00</span>
                </div>
            </div>
            <div class="grid grid-cols-12 text-slate-700 font-semibold mb-6">
                <div class="col-span-8">
                    Payment
                </div>
                <div class="col-span-4">
                    <select name="payment_method" id="payment_method" class="form-control">
                        @foreach ($payment_methods as $payment_method)
                            <option value="{{ $payment_method }}">{{ $payment_method }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <button type="submit"
                class="w-full px-1 py-2 bg-orange-500 rounded-full text-white cursor-pointer">Order</button>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const opens = (category) => {
            document.querySelector(`#${category}`).classList.toggle('flex');
            document.querySelector(`#${category}`).classList.toggle('hidden');
        }

        const currencyFormat = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }

        let addedMenuId = [];
        let totalPrice = 0;
        const addMenu = (id, name, price) => {
            const summary = document.querySelector('#summary');
            if (addedMenuId.includes(id)) {
                const qty = document.querySelector(`#qty_${id}`);
                qty.value = parseInt(qty.value) + 1;
            } else {
                summary.insertAdjacentHTML('beforeend', getEl(id, name, price));
                addedMenuId.push(id);
            }
            if (`{{ $with_tax }}` != 'yes') {
                totalPrice += price;
            } else {
                totalPrice += price + (price * 11 / 100)
            }



            document.querySelector('#total_price').innerHTML = currencyFormat(totalPrice);

        }

        const increase = (id, price) => {
            event.preventDefault()
            const el = document.querySelector(`#qty_${id}`);
            el.value = parseInt(el.value) + 1;
            if (`{{ $with_tax }}` != 'yes') {
                totalPrice += price;
            } else {
                totalPrice += price + (price * 11 / 100)
            }
            document.querySelector('#total_price').innerHTML = currencyFormat(totalPrice);
        }

        const decrease = (id, price) => {
            event.preventDefault()
            const el = document.querySelector(`#qty_${id}`);
            if (parseInt(el.value) - 1 == 0) {
                el.parentNode.parentNode.remove();
                addedMenuId = addedMenuId.reduce((acc, item) => {
                    if (parseInt(item) !== id) acc.push(item);
                    return acc;
                }, []);
            } else {
                el.value = parseInt(el.value) - 1;
            }
            if (`{{ $with_tax }}` != 'yes') {
                totalPrice -= price;
            } else {
                totalPrice -= price + (price * 11 / 100)
            }
            document.querySelector('#total_price').innerHTML = currencyFormat(totalPrice);
        }

        const getEl = (id, name, price) => {
            return `<div class="grid grid-cols-12" id="item_${id}">
                        <div class="col-span-8 flex items-center">${name}</div>
                        <div class="col-span-4 flex items-center justify-center h-8 gap-x-2">
                            <button type="button" onclick="decrease(${id}, ${price})"
                                class="bg-gray-300 w-8 aspect-square rounded-full cursor-pointer">-</button>
                                <input type="hidden" name="id[]" value="${id}">
                            <input type="number" name="qty[]" id="qty_${id}" value="1"
                                class="w-12 border h-8 border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="increase(${id}, ${price})"
                                class="bg-gray-300 w-8 aspect-square rounded-full cursor-pointer">+</button>
                        </div>
                    </div>`;
        }

        const submitForm = (form, text) => {
            event.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: `Yes, ${text} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @if (session('message'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    position: "center",
                    icon: "{{ session('status') }}",
                    title: "{{ session('message') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            })
        </script>
    @endif
</body>

</html>
