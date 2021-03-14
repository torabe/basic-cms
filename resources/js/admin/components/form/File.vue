<template>
  <v-sheet>
    <v-img :src="url" v-if="url && isImage" contain :aspect-ratio="1" />
    <a :href="url" v-else-if="url" target="_blank">ファイルプレビュー</a>
    <v-file-input
      :accept="accept"
      :prepend-icon="icon"
      :label="label"
      :hint="hint"
      :readonly="readonly"
      :disabled="disabled"
      :error-count="errorMessages.length"
      :error-messages="errorMessages"
      :clearable="false"
      persistent-hint
      @change="handleChange"
      v-model="internalValue"
    />
  </v-sheet>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: false,
      default: '',
    },
    icon: {
      type: String,
      required: false,
      default: undefined,
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
    accept: {
      type: String,
      required: false,
      default: '',
    },
    isImage: {
      type: Boolean,
      required: false,
      default: false,
    },
    value: {
      required: false,
    },
  },
  data() {
    return { url: this.value };
  },
  computed: {
    internalValue: {
      get() {
        return typeof this.value === 'string' || this.value instanceof String ? null : this.value;
      },
      set(value) {
        if (this.value !== value) this.$emit('input', value ? value : null);
      },
    },
  },
  watch: {
    value: {
      handler(newState) {
        this.handleChange(newState);
      },
      immediate: true,
    },
  },
  methods: {
    handleChange(file) {
      if (file === null || file === undefined) {
        this.url = null;
        return;
      }

      if (typeof file === 'string' || file instanceof String) {
        this.url = file;
        return;
      }

      if (!this.isImage) return;

      if (file.name.lastIndexOf('.') <= 0) {
        return;
      }

      const fr = new FileReader();
      fr.readAsDataURL(file);
      fr.addEventListener('load', () => {
        this.url = fr.result;
      });
    },
  },
};
</script>
