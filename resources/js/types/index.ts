import type { Component } from 'vue';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: Component;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: {
        location: string;
        href: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
    [key: string]: any;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Product {
    id: number;
    name: string;
    description?: string;
    price: number;
    stock: number;
    created_at?: string;
    updated_at?: string;
}

export interface MidtransResponse {
    transaction_id?: string;
    order_id?: string;
    gross_amount?: string;
    payment_type?: string;
    transaction_status?: string;
    fraud_status?: string;
    [key: string]: unknown;
}

export interface Customer {
    id: number;
    name: string;
    email: string;
    phone_number?: string;
    address?: string;
    created_at: string;
    updated_at: string;
}

export interface Transaction {
    id: number;
    order_id?: string;
    product_id: number;
    product: Product;
    customer: Customer;
    customer_id: number;
    quantity: number;
    total_price: number;
    status: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
    last_page: number;
    current_page: number;
    per_page: number;
    total: number;
}

export type BreadcrumbItemType = BreadcrumbItem;
