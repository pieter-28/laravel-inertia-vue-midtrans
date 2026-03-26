<script setup lang="ts">
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Product, type User } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Product', href: '/product' },
    { title: 'Checkout', href: '#' },
];

const page = usePage() as {
    props: {
        auth: {
            user: User;
        };
    };
};

const user = page.props.auth.user as User;

const props = defineProps<{
    product: Product;
}>();

const quantity = ref(1);

const totalPrice = computed(() => {
    return props.product.price * quantity.value;
});

const isInvalidQty = computed(() => {
    return quantity.value < 1 || quantity.value > props.product.stock;
});

declare global {
    interface Window {
        snap: any;
    }
}

const loading = ref(false);

const openSnap = (snapToken: string) => {
    if (!window.snap) {
        toast.error('Payment tidak tersedia');
        return;
    }

    window.snap.pay(snapToken, {
        onSuccess: () => {
            setTimeout(() => {
                toast.success('Pembayaran berhasil');
                window.location.href = '/transaction';
            }, 1000);
        },
        onPending: () => {
            setTimeout(() => {
                toast('Menunggu pembayaran');
                window.location.href = '/transaction';
            }, 1000);
        },
        onError: () => {
            setTimeout(() => {
                toast.error('Pembayaran gagal');
                window.location.href = '/transaction';
            }, 1000);
        },
        onClose: () => {
            setTimeout(() => {
                toast('Popup ditutup');
                window.location.href = '/transaction';
            }, 1000);
        },
    });
};

const handlePayment = async () => {
    if (isInvalidQty.value) return;

    loading.value = true;

    try {
        const res = await axios.post('/payment/', {
            product_id: props.product.id,
            customer_id: user.id,
            quantity: quantity.value,
            customer_name: user.name,
            customer_email: user.email,
            customer_phone: '09877274255',
        });

        const snapToken = res.data?.snap_token;

        if (!snapToken) {
            toast.error('Token payment tidak ditemukan');
            return;
        }

        openSnap(snapToken);
    } catch (error) {
        console.log(error);
        toast.error('Terjadi kesalahan saat checkout');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Checkout" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="h-full w-full p-4 sm:p-6 lg:p-8">
            <div class="mx-auto grid items-stretch gap-6 lg:grid-cols-3">
                <!-- LEFT -->
                <div class="flex lg:col-span-2">
                    <div class="flex h-full w-full flex-col rounded-lg border p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-xl font-semibold">Checkout</h1>

                            <Link
                                href="/product"
                                class="rounded-md border bg-white px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700"
                            >
                                ← Kembali
                            </Link>
                        </div>

                        <!-- Product Info -->
                        <div class="space-y-1">
                            <h2 class="text-base font-medium">
                                {{ product.name }}
                            </h2>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ product.description || 'No description available' }}
                            </p>
                        </div>

                        <!-- Spacer -->
                        <div class="flex-1"></div>

                        <!-- Price -->
                        <div class="mt-4 text-lg font-semibold text-indigo-600">Rp {{ product.price.toLocaleString('id-ID') }}</div>

                        <!-- Quantity -->
                        <div class="mt-6 space-y-2">
                            <label class="text-sm">Quantity</label>

                            <div class="flex items-center gap-3">
                                <Input type="number" v-model="quantity" min="1" :max="product.stock" class="w-24" />

                                <span class="text-xs text-gray-500"> Stock: {{ product.stock }} </span>
                            </div>

                            <p v-if="isInvalidQty" class="text-xs text-red-500">Quantity tidak valid</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="flex">
                    <div class="flex h-full w-full flex-col rounded-lg border p-6">
                        <h2 class="mb-4 text-base font-medium">Summary</h2>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Price</span>
                                <span>Rp {{ product.price.toLocaleString('id-ID') }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Quantity</span>
                                <span>{{ quantity }}</span>
                            </div>
                        </div>

                        <!-- Spacer -->
                        <div class="flex-1"></div>

                        <div class="mt-4 border-t pt-4">
                            <div class="flex justify-between font-semibold">
                                <span>Total</span>
                                <span> Rp {{ totalPrice.toLocaleString('id-ID') }} </span>
                            </div>
                        </div>

                        <!-- Button -->
                        <button
                            type="button"
                            @click="handlePayment"
                            :disabled="isInvalidQty || loading"
                            class="mt-6 w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
                        >
                            <span v-if="loading">Processing...</span>
                            <span v-else>Pay</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
