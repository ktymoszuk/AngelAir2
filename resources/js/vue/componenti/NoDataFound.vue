<template>
    <div class="w-100 py-5 text-center">
        <span class="fs-1 fw-light text-uppercase">
            {{ contenuto }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        'contenuto': {
            required: true,
        }
    }
}
</script>