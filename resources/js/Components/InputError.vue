<script setup>
defineProps({
    message: String,
    classError:''
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600 bg-white" :class="classError">
            {{ message }}
        </p>
    </div>
</template>
