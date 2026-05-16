<template>
    <div class="min-h-screen p-8 bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <Card>
                <template #title>
                    <div class="flex items-center gap-2">
                        <i class="pi pi-ticket text-primary-500 text-2xl"></i>
                        <span>Concert Booking Platform</span>
                    </div>
                </template>
                <template #content>
                    <p class="mb-4 text-gray-600">
                        Chào mừng bạn đến với hệ thống đặt vé. PrimeVue đã được cấu hình thành công!
                    </p>
                    <div class="flex gap-2">
                        <Button label="Xem danh sách Concert" icon="pi pi-search" />
                        <Button label="Đặt vé ngay" icon="pi pi-shopping-cart" severity="success" />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import Button from 'primevue/button';
import Card from 'primevue/card';
</script>

<style scoped>
/* Bạn có thể thêm style riêng ở đây */
</style>
