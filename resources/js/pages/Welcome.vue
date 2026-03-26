<script setup lang="ts">
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Activity, ArrowRight, ChevronRight, Code2, Globe2, Layout, ShieldCheck, Sparkles, Star, Zap } from 'lucide-vue-next';

interface PageProps extends Record<string, unknown> {
    auth?: {
        user?: unknown;
    };
}

const page = usePage<PageProps>();

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const features = [
    {
        title: 'Lightning Fast',
        description: 'Built on top of Vite and Vue 3 for incredible performance. Your users will love the snappy experience.',
        icon: Zap,
        gridClass: 'md:col-span-2 md:row-span-2',
    },
    {
        title: 'Secure by Default',
        description: 'Enterprise-grade security features built-in from day one. Rest easy knowing your data is safe.',
        icon: ShieldCheck,
        gridClass: 'col-span-1',
    },
    {
        title: 'Global Reach',
        description: 'Deploy anywhere in the world with zero configuration seamlessly.',
        icon: Globe2,
        gridClass: 'col-span-1',
    },
    {
        title: 'Beautiful UI',
        description: 'Professionally designed components that look amazing out of the box without tweaking.',
        icon: Layout,
        gridClass: 'col-span-1',
    },
    {
        title: 'Developer Focused',
        description: 'Extensive documentation and APIs built for maximum developer productivity.',
        icon: Code2,
        gridClass: 'col-span-1',
    },
];

const testimonials = [
    {
        name: 'Sarah Connor',
        role: 'CTO at TechCorp',
        content:
            'This platform has completely transformed how we build and deploy applications. The speed and reliability are unmatched in the industry.',
        avatar: 'SC',
        image: 'https://i.pravatar.cc/150?u=sarah',
    },
    {
        name: 'Alex Rivera',
        role: 'Lead Developer',
        content: 'The component quality is fantastic. It saved our team months of development time while delivering a better looking product.',
        avatar: 'AR',
        image: 'https://i.pravatar.cc/150?u=alex',
    },
    {
        name: 'Emily Chen',
        role: 'Product Manager',
        content: 'Our users noticed the difference immediately. The interface is snappy, beautiful, and intuitive. Highly recommended!',
        avatar: 'EC',
        image: 'https://i.pravatar.cc/150?u=emily',
    },
    {
        name: 'Michael Chang',
        role: 'Founder',
        content: 'Getting started was incredibly easy. The documentation is clear and the components just work perfectly out of the box.',
        avatar: 'MC',
        image: 'https://i.pravatar.cc/150?u=michael',
    },
];

const faqs = [
    {
        value: 'item-1',
        title: 'Is it really free?',
        content: 'Yes! The core platform is completely free and open source. We only charge for premium enterprise features and dedicated support.',
    },
    {
        value: 'item-2',
        title: 'Can I use it for commercial projects?',
        content: 'Absolutely. Our license allows you to build commercial applications without any restrictions or hidden fees.',
    },
    {
        value: 'item-3',
        title: 'How often do you release updates?',
        content: 'We push minor updates weekly and major features every month. You can always check our changelog for the latest information.',
    },
    {
        value: 'item-4',
        title: 'Do you offer technical support?',
        content: 'Community support is available via our Discord server. Priority email and chat support are available on our Pro tier.',
    },
];
</script>

<template>
    <Head title="Welcome" />

    <div class="relative min-h-screen bg-background font-sans text-foreground antialiased selection:bg-primary/30">
        <!-- Ambient Background Glow -->
        <div class="pointer-events-none absolute inset-0 flex justify-center overflow-hidden">
            <div
                class="absolute -top-[20%] h-[600px] w-[1000px] rounded-full bg-primary/40 opacity-20 blur-[120px] duration-1000 animate-in fade-in"
            ></div>
        </div>

        <!-- Navigation -->
        <header
            class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/60 backdrop-blur-xl supports-[backdrop-filter]:bg-background/60"
        >
            <div class="container mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="group flex cursor-pointer items-center gap-2">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground transition-transform group-hover:scale-105"
                    >
                        <Activity class="h-5 w-5" />
                    </div>
                    <span class="text-xl font-bold tracking-tight">Acme</span>
                </div>
                <nav class="flex items-center gap-4">
                    <Link v-if="page.props.auth?.user" :href="route('dashboard')">
                        <Button variant="ghost" class="text-sm font-medium">Dashboard</Button>
                    </Link>
                    <template v-else>
                        <Link :href="route('login')">
                            <Button variant="ghost" class="hidden text-sm font-medium sm:inline-flex">Log in</Button>
                        </Link>
                        <Link :href="route('register')">
                            <Button class="text-sm font-medium shadow-sm transition-all hover:scale-105 active:scale-95">Get Started</Button>
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <main class="relative flex-1">
            <!-- Hero Section -->
            <section class="relative space-y-6 overflow-hidden pb-12 pt-24 md:pb-20 md:pt-32 lg:pb-32 lg:pt-40">
                <div class="container relative z-10 mx-auto flex max-w-[64rem] flex-col items-center gap-6 px-4 text-center sm:px-6 lg:px-8">
                    <div class="duration-700 animate-in fade-in slide-in-from-bottom-4">
                        <Badge variant="outline" class="mb-6 rounded-full border-primary/20 bg-primary/5 px-4 py-1.5 text-sm text-primary">
                            <Sparkles class="mr-2 inline h-4 w-4" />
                            V2.0 is now live and ready
                        </Badge>
                    </div>

                    <h1
                        class="text-4xl font-bold tracking-tighter delay-150 duration-700 animate-in fade-in slide-in-from-bottom-6 fill-mode-both sm:text-5xl md:text-6xl lg:text-7xl"
                    >
                        Build better applications, <br class="hidden sm:inline" />
                        <span class="bg-gradient-to-r from-primary via-purple-500 to-primary/60 bg-clip-text text-transparent"
                            >faster than ever.</span
                        >
                    </h1>

                    <p
                        class="mt-4 max-w-[42rem] leading-normal text-muted-foreground delay-300 duration-700 animate-in fade-in slide-in-from-bottom-6 fill-mode-both sm:text-lg sm:leading-8"
                    >
                        An open-source platform that helps you build modern, beautiful, and performant web applications with just a few clicks. Stop
                        wasting time on boilerplate.
                    </p>

                    <div
                        class="mt-8 flex w-full flex-col gap-4 px-4 delay-500 duration-700 animate-in fade-in slide-in-from-bottom-8 fill-mode-both sm:w-auto sm:flex-row"
                    >
                        <Link :href="route('register')" class="w-full sm:w-auto" as="div">
                            <Button
                                size="lg"
                                class="h-14 w-full gap-2 px-8 text-base shadow-lg shadow-primary/20 ring-1 ring-primary/20 transition-all hover:scale-105"
                            >
                                Start Building for Free <ArrowRight class="h-4 w-4" />
                            </Button>
                        </Link>
                        <Button size="lg" variant="secondary" class="h-14 w-full gap-2 px-8 text-base transition-all hover:bg-secondary/80">
                            Book a Demo <ChevronRight class="h-4 w-4 text-muted-foreground" />
                        </Button>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="container relative z-10 mx-auto space-y-16 px-4 py-16 sm:px-6 md:py-24 lg:px-8">
                <div class="mx-auto flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Engineered for growth</h2>
                    <p class="max-w-[42rem] leading-normal text-muted-foreground sm:text-lg sm:leading-7">
                        Everything you need to build incredible user experiences out of the box. No configuration required, just pure productivity.
                    </p>
                </div>

                <div class="mx-auto grid max-w-6xl auto-rows-[250px] justify-center gap-6 sm:grid-cols-2 md:grid-cols-3">
                    <Card
                        v-for="(feature, index) in features"
                        :key="index"
                        :class="[
                            'group relative overflow-hidden border-border/50 bg-background/50 text-left backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl',
                            feature.gridClass,
                        ]"
                    >
                        <!-- Glow effect on hover -->
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        ></div>

                        <CardHeader class="relative z-10 flex h-full flex-col justify-start">
                            <div
                                class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 ring-1 ring-primary/20 transition-colors duration-300 group-hover:bg-primary/20"
                            >
                                <component :is="feature.icon" class="h-6 w-6 text-primary" />
                            </div>
                            <CardTitle class="mb-2 text-2xl">{{ feature.title }}</CardTitle>
                            <CardDescription class="text-base leading-relaxed text-muted-foreground/80">{{ feature.description }}</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="container relative mx-auto px-4 py-24 sm:px-6 md:py-32 lg:px-8">
                <div
                    class="absolute inset-0 -z-10 bg-muted/30 [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)]"
                ></div>

                <div class="mx-auto mb-16 flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <Badge variant="outline" class="mb-4 bg-background">Testimonials</Badge>
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Loved by Developers</h2>
                    <p class="max-w-[42rem] leading-normal text-muted-foreground sm:text-lg sm:leading-7">
                        Don't just take our word for it. Here's what the community has to say.
                    </p>
                </div>

                <div class="mx-auto max-w-6xl px-4 sm:px-8">
                    <Carousel :opts="{ align: 'start', loop: true }" class="w-full">
                        <CarouselContent class="-ml-4 py-4 md:-ml-6">
                            <CarouselItem v-for="(testimonial, index) in testimonials" :key="index" class="pl-4 md:basis-1/2 md:pl-6 lg:basis-1/3">
                                <Card class="h-full border-border/50 bg-background/50 shadow-sm backdrop-blur-sm transition-shadow hover:shadow-md">
                                    <CardHeader>
                                        <div class="mb-4 flex gap-1">
                                            <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-amber-400 text-amber-400" />
                                        </div>
                                        <CardDescription class="mt-2 text-base font-medium italic leading-relaxed text-foreground">
                                            "{{ testimonial.content }}"
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent class="mt-auto border-t border-border/40 pt-6">
                                        <div class="flex items-center gap-4">
                                            <Avatar class="border-2 border-background shadow-sm ring-1 ring-border">
                                                <AvatarImage :src="testimonial.image" :alt="testimonial.name" />
                                                <AvatarFallback>{{ testimonial.avatar }}</AvatarFallback>
                                            </Avatar>
                                            <div class="flex flex-col">
                                                <p class="text-sm font-bold">{{ testimonial.name }}</p>
                                                <p class="text-xs font-medium text-muted-foreground">{{ testimonial.role }}</p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </CarouselItem>
                        </CarouselContent>
                        <div class="hidden sm:block">
                            <CarouselPrevious class="-left-12" />
                            <CarouselNext class="-right-12" />
                        </div>
                    </Carousel>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="container mx-auto px-4 py-16 sm:px-6 md:py-32 lg:px-8">
                <div class="mx-auto mb-12 flex max-w-[58rem] flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold leading-[1.1] sm:text-3xl md:text-5xl">Frequently Asked Questions</h2>
                    <p class="max-w-[42rem] leading-normal text-muted-foreground sm:text-lg sm:leading-7">
                        Got questions? We've got answers. If you can't find what you're looking for, reach out to our team.
                    </p>
                </div>
                <div class="mx-auto max-w-3xl">
                    <Accordion type="single" collapsible class="w-full">
                        <AccordionItem v-for="item in faqs" :key="item.value" :value="item.value" class="border-b-border/40">
                            <AccordionTrigger class="py-6 text-left text-lg font-medium transition-colors hover:text-primary">{{
                                item.title
                            }}</AccordionTrigger>
                            <AccordionContent class="pb-6 text-base leading-relaxed text-muted-foreground">
                                {{ item.content }}
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="container mx-auto px-4 py-24 sm:px-6 md:py-32 lg:px-8">
                <div
                    class="relative mx-auto max-w-[64rem] overflow-hidden rounded-3xl border border-primary/20 bg-primary/5 p-8 text-center shadow-2xl sm:p-12 md:p-16"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-primary/5"></div>
                    <div class="relative z-10 flex flex-col items-center space-y-6">
                        <h2 class="text-3xl font-bold sm:text-4xl md:text-5xl">Ready to get started?</h2>
                        <p class="max-w-[42rem] leading-normal text-muted-foreground sm:text-lg sm:leading-7">
                            Join thousands of developers who are already building the future with our platform. Start for free today.
                        </p>
                        <div class="mt-4 flex w-full flex-col justify-center gap-4 sm:flex-row">
                            <Link :href="route('register')" as="div">
                                <Button
                                    size="lg"
                                    class="h-14 w-full px-10 text-base shadow-xl shadow-primary/20 transition-transform hover:scale-105 sm:w-auto"
                                >
                                    Create your free account
                                </Button>
                            </Link>
                            <Link :href="route('login')" as="div">
                                <Button size="lg" variant="outline" class="h-14 w-full bg-background/50 px-10 text-base backdrop-blur sm:w-auto">
                                    Sign In
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-border/40 bg-background pb-8 pt-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-12 grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-5">
                    <div class="col-span-2 space-y-4 lg:col-span-2">
                        <div class="flex items-center gap-2">
                            <Activity class="h-6 w-6 text-primary" />
                            <span class="text-xl font-bold tracking-tight">Acme</span>
                        </div>
                        <p class="max-w-xs text-sm leading-relaxed text-muted-foreground">
                            A powerful platform out of the box. Giving you all the tools required to build robust next generation applications.
                        </p>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold">Product</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li><a href="#" class="transition-colors hover:text-foreground">Features</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Pricing</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Integrations</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Changelog</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold">Company</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li><a href="#" class="transition-colors hover:text-foreground">About</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Blog</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Careers</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Contact</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-semibold">Legal</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li><a href="#" class="transition-colors hover:text-foreground">Privacy Policy</a></li>
                            <li><a href="#" class="transition-colors hover:text-foreground">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <div
                    class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-border/40 pt-8 text-sm text-muted-foreground md:flex-row"
                >
                    <p>© {{ new Date().getFullYear() }} Acme Inc. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <span class="cursor-pointer transition-colors hover:text-foreground">Twitter</span>
                        <span class="cursor-pointer transition-colors hover:text-foreground">GitHub</span>
                        <span class="cursor-pointer transition-colors hover:text-foreground">Discord</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
