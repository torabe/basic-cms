<template>
  <v-btn
    :type="type"
    color="primary"
    :outlined="!active"
    :disabled="disabled"
    :to="to"
    @click="$emit('click')"
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
    active: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
};
</script>
