<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
    DrawerTrigger,
} from '@/components/ui/drawer';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Drawer>
        <DrawerTrigger as-child>
            <Button variant="secondary"> Register </Button>
        </DrawerTrigger>
        <DrawerContent>
            <div class="mx-auto w-full max-w-sm">
                <DrawerHeader>
                    <DrawerTitle>Oops!</DrawerTitle>
                    <DrawerDescription>You cannot register on this app.</DrawerDescription>
                </DrawerHeader>
                <DrawerFooter>
                    <Button as-child>
                        <Link :href="route('login')"> Login Now </Link>
                    </Button>
                    <DrawerClose as-child>
                        <Button variant="outline"> Cancel </Button>
                    </DrawerClose>
                </DrawerFooter>
            </div>
        </DrawerContent>
    </Drawer>
</template>
