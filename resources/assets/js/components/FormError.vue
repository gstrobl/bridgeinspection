<template>
  <div class="input-group__messages form-error">
    <div class="input-group__error">
      <slot></slot>
    </div>
</div>
</template>

<script>
    export default {
        props: ['errors'],
    }
</script>
