import { Paginated } from '@/types';
import { computed, Ref } from 'vue';

export function usePaginatedFilter<T>(paginatedData: Ref<Paginated<T>>, searchQuery: Ref<string>, fields: string[]) {
    const filteredData = computed(() => {
        const data = paginatedData.value?.data ?? [];

        if (!searchQuery.value) return data;

        const keyword = searchQuery.value.toLowerCase();

        return data.filter((item: any) =>
            fields.some((field) => {
                const value = field.split('.').reduce((acc, part) => acc?.[part], item);

                return value !== null && value !== undefined && String(value).toLowerCase().includes(keyword);
            }),
        );
    });

    const getRowNumber = (index: number) => {
        const currentPage = paginatedData.value?.current_page ?? 1;
        const perPage = paginatedData.value?.per_page ?? 10;

        return (currentPage - 1) * perPage + index + 1;
    };

    return {
        filteredData,
        getRowNumber,
    };
}
