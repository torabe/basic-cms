<template>
  <v-select
    :prepend-icon="icon"
    :multiple="multiple"
    :chips="multiple"
    :label="label"
    :items="items"
    :hint="hint"
    :readonly="readonly"
    :disabled="disabled"
    :error-count="errorMessages.length"
    :error-messages="errorMessages"
    :clearable="!readonly && clearable"
    :item-text="itemText"
    :item-value="itemValue"
    :dense="dense"
    persistent-hint
    v-model="internalValue"
    @change="handleChange"
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
    multiple: {
      type: Boolean,
      required: false,
      default: false,
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
    clearable: {
      type: Boolean,
      required: false,
      default: true,
    },
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
    itemText: {
      type: String,
      required: false,
      default: 'text',
    },
    itemValue: {
      type: String,
      required: false,
      default: 'value',
    },
    errorMessages: {
      type: Array,
      required: false,
      default: () => [],
    },
    value: {
      type: [String, Number, Array],
      required: false,
      default: '',
    },
    dense: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    internalValue: {
      get() {
        return this.value;
      },
      set(value) {
        if (this.value !== value) this.$emit('input', value === undefined ? null : value);
      },
    },
  },
  methods: {
    handleChange(e) {
      this.$emit('change', e);
    },
  },
};
</script>
