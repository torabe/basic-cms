<template>
  <v-row>
    <v-col>
      <v-dialog ref="dialog" v-model="isShowData" width="290px">
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="internalValueDate"
            :label="label"
            :hint="hint"
            :readonly="readonly"
            :disabled="disabled"
            clearable
            :error-count="errorMessages.length"
            :error-messages="errorMessages"
            persistent-hint
            v-bind="attrs"
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker v-model="internalValueDate" scrollable>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="hide()">Close</v-btn>
        </v-date-picker>
      </v-dialog>
    </v-col>
  </v-row>
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
      default: true,
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
  },
  data() {
    return {
      dateTime: '',
      isShowData: false,
    };
  },
  computed: {
    internalValueDate: {
      get() {
        if (this.value === '' || this.value === null) return this.value;
        return this.$dayjs(this.value).format('YYYY-MM-DD');
      },
      set(value) {
        if (value === null) {
          this.$emit('input', '');
          return;
        }

        this.$emit('input', this.$dayjs(value).format('YYYY-MM-DD'));
      },
    },
  },
  watch: {
    value: {
      handler(newState) {
        this.dateTime =
          this.value !== ''
            ? {
                date: this.$dayjs(newState).format('YYYY-MM-DD'),
              }
            : { date: '' };
      },
      immediate: true,
    },
  },
  methods: {
    hide() {
      this.isShowData = false;
    },
  },
};
</script>
