<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement, ArcElement } from 'chart.js';
import { Line, Doughnut } from 'vue-chartjs';
import { Box, Users, User, CreditCard, DollarSign, TrendingUp, PieChart, Filter } from 'lucide-vue-next';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, ArcElement, Title, Tooltip, Legend);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps<{
    cards: Array<{ label: string; value: number; icon: string }>;
    totalRevenue: number;
    averageOrderValue: number;
    salesTotal: number;
    statusSummary: Record<string, number>;
    chartLabels: string[];
    chartData: number[];
    selectedRange: string;
    selectedYear: number;
    yearOptions: number[];
    topProducts: Array<{ product_name: string; quantity: number; revenue: number }>;
}>();

const range = ref(props.selectedRange ?? 'month');
const year = ref(props.selectedYear ?? new Date().getFullYear());
const isLoading = ref(false);

const lineData = computed(() => ({
    labels: props.chartLabels,
    datasets: [
        {
            label: 'Sales',
            data: props.chartData,
            fill: true,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.15)',
            tension: 0.3,
        },
    ],
}));

const donutData = computed(() => ({
    labels: Object.keys(props.statusSummary),
    datasets: [
        {
            data: Object.values(props.statusSummary),
            backgroundColor: ['#22c55e', '#f97316', '#0ea5e9', '#e11d48', '#a855f7'],
            borderWidth: 0,
        },
    ],
}));

const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top' as const,
        },
        title: {
            display: true,
            text: 'Sales Revenue',
        },
    },
};

const donutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
        },
        title: {
            display: true,
            text: 'Transactions by Status',
        },
    },
};

const applyFilter = () => {
    isLoading.value = true;
    const params = new URLSearchParams({
        range: range.value,
        year: String(year.value),
    });
    window.location.href = `${route('dashboard')}?${params.toString()}`;
};

const getIcon = (iconName: string) => {
    const icons = {
        box: Box,
        users: Users,
        user: User,
        'credit-card': CreditCard,
    };
    return icons[iconName as keyof typeof icons] || Box;
};

const chartDescription = computed(() => {
    if (range.value === 'month') return `Monthly revenue for ${year.value}`;
    if (range.value === 'year') return 'Annual revenue trend (last 5 years)';
    return 'All-time revenue trend';
});

const getStatusColor = (status: string) => {
    const colors = {
        pending: 'bg-yellow-400',
        success: 'bg-green-400',
        failed: 'bg-red-400',
        cancelled: 'bg-gray-400',
    };
    return colors[status as keyof typeof colors] || 'bg-gray-400';
};
</script>

<template>

    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="card in props.cards" :key="card.label"
                    class="shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-1">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">{{ card.label }}</CardTitle>
                        <component :is="getIcon(card.icon)" class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ card.value.toLocaleString() }}</div>
                        <p class="text-xs text-muted-foreground">
                            Total {{ card.label.toLowerCase() }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <Card
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950 dark:to-indigo-950 border-blue-200 dark:border-blue-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Revenue</CardTitle>
                        <DollarSign class="h-8 w-8 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">Rp {{ props.totalRevenue.toLocaleString() }}</div>
                        <p class="text-xs text-muted-foreground">
                            +{{ ((props.salesTotal / props.totalRevenue) * 100).toFixed(1) }}% from selected period
                        </p>
                    </CardContent>
                </Card>

                <Card
                    class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950 dark:to-emerald-950 border-green-200 dark:border-green-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Average Order Value</CardTitle>
                        <TrendingUp class="h-8 w-8 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">Rp {{ props.averageOrderValue.toLocaleString() }}</div>
                        <p class="text-xs text-muted-foreground">
                            Per transaction
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Chart Filters</CardTitle>
                        <Filter class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Time Range</label>
                            <Select v-model="range">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select range" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="month">Monthly</SelectItem>
                                    <SelectItem value="year">Last 5 Years</SelectItem>
                                    <SelectItem value="all">All Time</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2" v-if="range === 'month'">
                            <label class="text-sm font-medium">Year</label>
                            <Select v-model="year">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="y in props.yearOptions" :key="y" :value="y">
                                        {{ y }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <Button size="sm" variant="default" :disabled="isLoading" @click="applyFilter" class="w-full">
                            <Filter class="h-4 w-4 mr-2" />
                            {{ isLoading ? 'Updating...' : 'Apply Filters' }}
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <Card
                class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-950 dark:to-pink-950 border-purple-200 dark:border-purple-800">
                <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                    <CardTitle class="text-sm font-medium">Transaction Status</CardTitle>
                    <PieChart class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div v-for="(count, status) in props.statusSummary" :key="status"
                            class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" :class="getStatusColor(status)"></div>
                                <span class="text-sm capitalize">{{ status }}</span>
                            </div>
                            <Badge variant="secondary" class="font-mono">{{ count }}</Badge>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <TrendingUp class="h-5 w-5" />
                            Revenue Trend
                        </CardTitle>
                        <CardDescription>
                            {{ chartDescription }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="h-80">
                        <Line :data="lineData" :options="lineOptions" />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <PieChart class="h-5 w-5" />
                            Status Distribution
                        </CardTitle>
                        <CardDescription>Transaction status breakdown</CardDescription>
                    </CardHeader>
                    <CardContent class="h-80">
                        <Doughnut :data="donutData" :options="donutOptions" />
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Box class="h-5 w-5" />
                        Top Products
                    </CardTitle>
                    <CardDescription>Best-selling products by quantity</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="overflow-hidden rounded-lg border border-border">
                        <table class="min-w-full divide-y divide-border">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                        Product</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                        Quantity</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                        Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-background divide-y divide-border">
                                <tr v-for="item in props.topProducts" :key="item.product_name"
                                    class="hover:bg-muted/30 transition-colors">
                                    <td class="px-4 py-3 font-medium">{{ item.product_name }}</td>
                                    <td class="px-4 py-3 text-right font-mono">{{ item.quantity.toLocaleString() }}</td>
                                    <td class="px-4 py-3 text-right font-mono">Rp {{ item.revenue.toLocaleString() }}
                                    </td>
                                </tr>
                                <tr v-if="props.topProducts.length === 0">
                                    <td colspan="3" class="px-4 py-8 text-center text-muted-foreground">
                                        <Box class="h-8 w-8 mx-auto mb-2 opacity-50" />
                                        No product sales data available
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
