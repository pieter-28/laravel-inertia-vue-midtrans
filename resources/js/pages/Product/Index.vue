<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Product } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Product', href: '/product' },
];

const props = defineProps<{
  products: Product[];
}>();
</script>

<template>

  <Head title="Product" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col flex-1 h-full gap-6 p-6">
      <div class="flex justify-between items-center gap-4">
        <h1 class="text-2xl font-semibold">List Product</h1>
        <div>
          <Link :href="route('product.create')"
            class="px-4 py-2 text-sm font-medium rounded-md border bg-white hover:bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 transition">
            + Tambah Product
          </Link>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <Card v-for="product in props.products" :key="product.id"
          class="transition shadow-sm hover:shadow-lg hover:-translate-y-1">
          <CardHeader>
            <CardTitle class="text-lg font-semibold">
              {{ product.name }}
            </CardTitle>

            <CardDescription class="line-clamp-2">
              {{ product.description || "No description available" }}
            </CardDescription>
          </CardHeader>

          <CardContent>
            <div class="space-y-2">
              <h4 class="text-xl font-bold text-indigo-600">
                Rp {{ product.price }}
              </h4>

              <p class="text-sm text-gray-500">Stock: {{ product.stock }}</p>
            </div>
          </CardContent>

          <CardFooter>
            <Link :href="route('checkout', { id: product.id })"
              class="w-full text-center px-4 py-2 font-medium text-white rounded-md transition" :class="product.stock === 0
                ? 'bg-gray-400 cursor-not-allowed'
                : 'bg-indigo-500 hover:bg-indigo-600'
                ">
              {{ product.stock === 0 ? "Out of Stock" : "Checkout" }}
            </Link>
          </CardFooter>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
