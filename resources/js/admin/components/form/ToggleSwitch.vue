<template>
  <v-switch
    :prepend-icon="icon"
    :label="label"
    :hint="hint"
    :readonly="readonly"
    :disabled="disabled"
    :error-count="errorMessages.length"
    :error-messages="errorMessages"
    @change="handleChange"
    v-model="internalValue"
    color="success"
  />
</template>

<script>
export default {
  props: {
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
      type: [Boolean, Number, String],
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
  methods: {
    handleChange() {
      this.$emit('event');
    },
  },
};
</script>
