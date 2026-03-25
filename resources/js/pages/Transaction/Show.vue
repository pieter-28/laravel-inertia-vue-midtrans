<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Transaction } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    ArrowLeft,
    ShoppingCart,
    Package,
    User,
    CreditCard,
    Calendar,
    Hash,
    DollarSign,
    Phone,
    Mail,
    MapPin,
    CheckCircle,
    Clock,
    XCircle
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Transactions', href: '/transaction' },
    { title: `Detail Transaction`, href: '#' },
];

defineProps<{
    transaction: Transaction;
}>();

const getStatusBadgeVariant = (status: string) => {
    switch (status.toLowerCase()) {
        case 'completed':
        case 'paid':
            return 'success';
        case 'pending':
            return 'secondary';
        case 'failed':
        case 'cancelled':
            return 'destructive';
        default:
            return 'outline';
    }
};

const getStatusIcon = (status: string) => {
    switch (status.toLowerCase()) {
        case 'completed':
        case 'paid':
            return CheckCircle;
        case 'pending':
            return Clock;
        case 'failed':
        case 'cancelled':
            return XCircle;
        default:
            return Clock;
    }
};
</script>

<template>

    <Head title="Transaction Details" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 border-b bg-card">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg">
                        <ShoppingCart class="h-8 w-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">
                            Transaction Details
                        </h1>
                        <p class="text-muted-foreground mt-1">
                            Order #{{ transaction.order_id || transaction.id }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <Badge :variant="getStatusBadgeVariant(transaction.status)"
                        class="px-4 py-2 text-sm font-medium flex items-center gap-2">
                        <component :is="getStatusIcon(transaction.status)" class="h-4 w-4" />
                        {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                    </Badge>

                    <Link :href="route('transaction.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <ArrowLeft class="h-4 w-4" />
                        Back to Transactions
                    </Link>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Card class="bg-card border shadow-sm">
                        <CardContent class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">Total Amount</p>
                                    <p class="text-2xl font-bold">Rp {{ transaction.total_price }}</p>
                                </div>
                                <div class="p-3 bg-muted rounded-full">
                                    <DollarSign class="h-6 w-6" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="bg-card border shadow-sm">
                        <CardContent class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">Quantity</p>
                                    <p class="text-2xl font-bold">{{ transaction.quantity }}</p>
                                </div>
                                <div class="p-3 bg-muted rounded-full">
                                    <Package class="h-6 w-6" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="bg-card border shadow-sm">
                        <CardContent class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">Transaction ID</p>
                                    <p class="text-2xl font-bold">#{{ transaction.id }}</p>
                                </div>
                                <div class="p-3 bg-muted rounded-full">
                                    <Hash class="h-6 w-6" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Detailed Information Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Transaction Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-3">
                                <div class="p-2 bg-muted rounded-lg">
                                    <CreditCard class="h-5 w-5" />
                                </div>
                                Transaction Details
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Transaction ID</span>
                                    <span class="font-semibold">#{{ transaction.id }}</span>
                                </div>

                                <div v-if="transaction.order_id"
                                    class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Order ID</span>
                                    <span class="font-semibold">{{ transaction.order_id }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Quantity</span>
                                    <span class="font-semibold">{{ transaction.quantity }}</span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <span class="text-sm font-medium text-green-700 dark:text-green-400">Total
                                        Price</span>
                                    <span class="font-bold text-green-800 dark:text-green-300">Rp {{
                                        transaction.total_price
                                    }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Status</span>
                                    <Badge :variant="getStatusBadgeVariant(transaction.status)"
                                        class="flex items-center gap-1">
                                        <component :is="getStatusIcon(transaction.status)" class="h-3 w-3" />
                                        {{ transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1) }}
                                    </Badge>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Created Date</span>
                                    <span class="font-semibold flex items-center gap-2">
                                        <Calendar class="h-4 w-4" />
                                        {{ transaction.created_at }}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Product Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-3">
                                <div class="p-2 bg-muted rounded-lg">
                                    <Package class="h-5 w-5" />
                                </div>
                                Product Information
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Product ID</span>
                                    <span class="font-semibold">#{{ transaction.product.id }}</span>
                                </div>

                                <div class="p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium block mb-1">Product Name</span>
                                    <span class="font-semibold text-lg">{{ transaction.product.name }}</span>
                                </div>

                                <div v-if="transaction.product.description" class="p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium block mb-1">Description</span>
                                    <span>{{ transaction.product.description }}</span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-400">Unit Price</span>
                                    <span class="font-bold text-blue-800 dark:text-blue-300">Rp {{
                                        transaction.product.price
                                    }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Stock Available</span>
                                    <span class="font-semibold">{{ transaction.product.stock }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Customer Details -->
                    <Card class="bg-card border shadow-sm">
                        <CardHeader class="pb-4">
                            <CardTitle class="flex items-center gap-3">
                                <div class="p-2 bg-muted rounded-lg">
                                    <User class="h-5 w-5" />
                                </div>
                                Customer Information
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex items-center justify-between p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium">Customer ID</span>
                                    <span class="font-semibold">#{{ transaction.customer.id }}</span>
                                </div>

                                <div class="p-3 bg-muted/50 rounded-lg">
                                    <span class="text-sm font-medium block mb-1">Full Name</span>
                                    <span class="font-semibold text-lg">{{ transaction.customer.name }}</span>
                                </div>

                                <div class="flex items-center gap-3 p-3 bg-muted/50 rounded-lg">
                                    <Mail class="h-4 w-4 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <span class="text-sm font-medium block">Email Address</span>
                                        <span class="font-semibold">{{ transaction.customer.email }}</span>
                                    </div>
                                </div>

                                <div v-if="transaction.customer.phone_number"
                                    class="flex items-center gap-3 p-3 bg-muted/50 rounded-lg">
                                    <Phone class="h-4 w-4 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <span class="text-sm font-medium block">Phone Number</span>
                                        <span class="font-semibold">{{ transaction.customer.phone_number }}</span>
                                    </div>
                                </div>

                                <div v-if="transaction.customer.address"
                                    class="flex items-center gap-3 p-3 bg-muted/50 rounded-lg">
                                    <MapPin class="h-4 w-4 text-slate-500 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <span class="text-sm font-medium block">Address</span>
                                        <span class="font-semibold">{{ transaction.customer.address }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>


    </AppLayout>
</template>
