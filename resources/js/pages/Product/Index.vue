<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Product, type Paginated } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
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
  products: Paginated<Product>;
}>();

const searchQuery = ref('');
const filteredProducts = computed(() => {
  const list = props.products?.data || [];
  if (!searchQuery.value) return list;
  const keyword = searchQuery.value.toLowerCase();

  return list.filter((product) =>
    [product.name, product.description]
      .filter(Boolean)
      .some((value) => String(value).toLowerCase().includes(keyword)),
  );
});

const formatPaginationLabel = (label: string) =>
  label
    .replace(/&laquo;/g, '«')
    .replace(/&raquo;/g, '»')
    .replace(/&nbsp;/g, ' ');

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

      <div class="mb-4">
        <InputGroup>
          <InputGroupAddon align="inline-start" class="text-muted-foreground">
            <Search class="h-4 w-4" />
          </InputGroupAddon>
          <InputGroupInput type="search" v-model="searchQuery" placeholder="Search product by name or description..."
            class="h-full border-none focus-visible:ring-0" />
          <InputGroupAddon align="inline-end" class="text-sm text-muted-foreground" v-if="filteredProducts.length > 0">
            {{ filteredProducts.length }} results
          </InputGroupAddon>
        </InputGroup>
      </div>

      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <Card v-for="product in filteredProducts" :key="product.id"
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

      <div class="mt-4 flex items-center justify-between" v-if="props.products.total > 0">
        <p class="text-sm text-muted-foreground">
          Page {{ props.products.current_page }} of
          {{ props.products.last_page }} —
          {{ props.products.total }} total products
        </p>

        <div class="flex items-center gap-1">
          <Link v-for="(link, i) in props.products.links" :key="i" :href="link.url ?? ''" preserve-state preserve-scroll
            class="rounded-md px-3 py-1 text-sm" :class="{
              'bg-primary text-primary-foreground': link.active,
              'pointer-events-none opacity-50': !link.url,
              'hover:bg-muted': link.url && !link.active,
            }">
            {{ formatPaginationLabel(link.label) }}
          </Link>
        </div>
      </div>

      <div v-if="filteredProducts.length === 0" class="text-center py-10 text-slate-500">
        Tidak ada produk yang sesuai dengan pencarian.
      </div>
    </div>
  </AppLayout>
</template>
