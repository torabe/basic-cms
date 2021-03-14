<template>
  <div class="checkboxes">
    <div class="subtitle-1" v-if="label !== ''">{{ label }}</div>
    <div :class="'checkboxes__items' + (!column ? ' d-flex flex-wrap' : '')">
      <v-checkbox
        v-for="item in items"
        class="checkboxes__item mt-0"
        :key="item.value"
        :label="item.text"
        :value="item.value"
        :disabled="item.disabled"
        multiple
        v-model="internalValue"
      ></v-checkbox>
    </div>
    <v-messages :value="[hint]" v-if="hint !== ''" />
    <div class="error--text" v-for="message in errorMessages" :key="message">{{ message }}</div>
  </div>
</template>

<script>
export default {
  props: {
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
      type: Array,
      required: false,
      default: () => [],
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
.checkboxes {
  &__items.d-flex &__item {
    &:not(:last-child) {
      margin-right: 16px;
    }
  }
}
</style>
