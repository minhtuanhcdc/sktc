<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    classButton:''
});
</script>

<template>
    <button :type="type" :class="classButton">
        <slot />
    </button>
</template>
