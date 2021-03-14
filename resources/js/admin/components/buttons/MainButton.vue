<template>
  <v-btn
    :type="type"
    color="success"
    dark
    :disabled="disabled"
    :to="to"
    @click="$emit('click')"
    :loading="isLoading"
  >
    <slot />
  </v-btn>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: false,
      default: 'button',
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
    to: {
      type: Object,
      required: false,
      default: () => {},
    },
  },
  computed: {
    isLoading() {
      if (this.type !== 'submit') return false;
      return this.$store.getters['page/loading'];
    },
  },
};
</script>
