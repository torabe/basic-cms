<template>
  <v-text-field
    :type="type"
    :prepend-icon="icon"
    :label="label"
    :hint="hint"
    :readonly="readonly"
    :disabled="disabled"
    :error-count="errorMessages.length"
    :error-messages="errorMessages"
    :clearable="!readonly"
    :dense="dense"
    :prefix="prefix"
    :suffix="suffix"
    persistent-hint
    v-model="internalValue"
  />
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: false,
      default: 'text',
    },
    icon: {
      type: String,
      required: false,
      default: '',
    },
    label: {
      type: String,
      required: false,
      default: '',
    },
    hint: {
      type: String,
      required: false,
      default: '',
    },
    readonly: {
      type: Boolean,
      required: false,
      default: false,
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
    errorMessages: {
      type: Array,
      required: false,
      default: () => [],
    },
    value: {
      type: [String, Number],
      required: false,
      default: '',
    },
    dense: {
      type: Boolean,
      required: false,
      default: false,
    },
    prefix: {
      type: String,
      required: false,
      default: '',
    },
    suffix: {
      type: String,
      required: false,
      default: '',
    },
  },
  computed: {
    internalValue: {
      get() {
        return this.value;
      },
      set(value) {
        if (this.value !== value) this.$emit('input', value);
      },
    },
  },
};
</script>
