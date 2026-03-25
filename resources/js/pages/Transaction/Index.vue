<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Transaction, type Paginated } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import {
    Card,
    CardContent,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ref, computed } from 'vue';
import { usePaginatedFilter } from '@/composables/usePaginatedFilter';
import { Input } from '@/components/ui/input';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Transaction', href: '/transaction' },
];

const props = defineProps<{
    transactions: Paginated<Transaction>;
}>();

const searchQuery = ref('');
const dataSearch = computed(() => props.transactions);

const { filteredData, getRowNumber } = usePaginatedFilter(
    dataSearch,
    searchQuery,
    ['name', 'status', 'product.name', 'customer.name'],
);

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'success':
            return 'bg-green-100 text-green-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

const formatPaginationLabel = (label: string) =>
    label
        .replace(/&laquo;/g, '«')
        .replace(/&raquo;/g, '»')
        .replace(/&nbsp;/g, ' ');
</script>

<template>

    <Head title="Transaction" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-6 p-6">
            <div class="flex justify-between items-center gap-4">
                <h1 class="text-2xl font-semibold">List Transaction</h1>
            </div>

            <Card class="transition shadow-lg">
                <CardContent>
                    <div class="w-full max-w-full gap-2 mt-4">
                        <div v-if="props.transactions?.data?.length > 0"
                            class="mb-4 flex items-center justify-between gap-2">
                            <div class="relative w-64">
                                <Search class="absolute top-2.5 left-2 h-4 w-4 text-muted-foreground" />
                                <Input v-model="searchQuery" type="search" placeholder="Search..." class="pl-8" />
                            </div>
                            <div>
                                <Link :href="route('product.index')"
                                    class="px-4 py-2 text-sm font-medium rounded-md border bg-white hover:bg-blue-100 dark:bg-blue-800 dark:text-white dark:border-blue-600 dark:hover:bg-blue-700 transition">
                                    + Add Transaction
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-border">
                        <Table class="min-w-full divide-y divide-border">
                            <TableHeader class="bg-muted/50">
                                <TableRow>
                                    <TableHead class="w-[40px] px-2 py-3"> # </TableHead>
                                    <TableHead class="px-2 py-3">Order ID</TableHead>
                                    <TableHead class="px-2 py-3">Product Name</TableHead>
                                    <TableHead class="px-2 py-3">Customer Name</TableHead>
                                    <TableHead class="px-2 py-3">Quantity</TableHead>
                                    <TableHead class="px-2 py-3">Total Price</TableHead>
                                    <TableHead class="px-2 py-3">Status</TableHead>
                                    <TableHead class="px-2 py-3">Created At</TableHead>
                                    <TableHead class="px-2 py-3">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody class="bg-background divide-y divide-border">
                                <template v-if="filteredData.length > 0">
                                    <TableRow v-for="(transaction, index) in filteredData" :key="transaction.id">
                                        <TableCell class="font-medium px-2 py-2">
                                            {{ getRowNumber(index) }}
                                        </TableCell>
                                        <TableCell class="px-2 py-2">{{ transaction.order_id || "null" }}</TableCell>
                                        <TableCell class="px-2 py-2">{{
                                            transaction.product?.name || "-"
                                        }}</TableCell>
                                        <TableCell class="px-2 py-2">{{
                                            transaction.customer?.name || "-"
                                        }}</TableCell>
                                        <TableCell class="px-2 py-2">{{ transaction.quantity }}</TableCell>
                                        <TableCell class="px-2 py-2">{{ transaction.total_price }}</TableCell>
                                        <TableCell class="px-2 py-2">
                                            <span
                                                :class="['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold', getStatusColor(transaction.status)]">
                                                {{ transaction.status ?? 'unknown' }}
                                            </span>
                                        </TableCell>
                                        <TableCell class="px-2 py-2">{{ transaction.created_at }}</TableCell>
                                        <TableCell class="px-2 py-2">
                                            <Link :href="route('transaction.show', { transaction: transaction.id })"
                                                class="px-2.5 py-2 text-xs font-semibold text-white bg-indigo-500 hover:bg-indigo-600 rounded-md transition">
                                                View
                                            </Link>
                                        </TableCell>
                                    </TableRow>
                                </template>
                                <TableRow v-else>
                                    <TableCell colspan="9" class="text-center py-8 text-muted-foreground">
                                        No transaction data available.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 flex items-center justify-between" v-if="props.transactions.total > 0">
                        <p class="text-sm text-muted-foreground">
                            Page {{ props.transactions.current_page }} of
                            {{ props.transactions.last_page }} —
                            {{ props.transactions.total }} total data
                        </p>

                        <div class="flex items-center gap-1">
                            <Link v-for="(link, i) in props.transactions.links" :key="i" :href="link.url ?? ''"
                                preserve-state preserve-scroll class="rounded-md px-3 py-1 text-sm" :class="{
                                    'bg-primary text-primary-foreground':
                                        link.active,
                                    'pointer-events-none opacity-50': !link.url,
                                    'hover:bg-muted': link.url && !link.active,
                                }">
                                {{ formatPaginationLabel(link.label) }}
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
