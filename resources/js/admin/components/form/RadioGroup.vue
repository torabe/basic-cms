<template>
  <div class="radio-group">
    <div class="subtitle-1" v-if="label !== ''">{{ label }}</div>
    <v-radio-group
      class="mt-0"
      :column="column"
      :prepend-icon="icon"
      :hint="hint"
      :readonly="readonly"
      :disabled="disabled"
      :error-count="errorMessages.length"
      :error-messages="errorMessages"
      :clearable="!readonly"
      persistent-hint
      v-model="internalValue"
    >
      <v-radio
        class="radio-group__item"
        v-for="item in items"
        :key="item.value"
        :label="item.text"
        :value="item.value"
      ></v-radio>
    </v-radio-group>
  </div>
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
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
    errorMessages: {
      type: Array,
      required: false,
      default: () => [],
    },
    column: {
      type: Boolean,
      required: false,
      default: false,
    },
    value: {
      type: [String, Number],
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

<style lang="scss" scoped>
.v-label {
  flex: 1 1 100%;
}
.radio-group {
  &__item {
    &:not(:last-child) {
      margin-right: 16px;
    }
  }
}
</style>
