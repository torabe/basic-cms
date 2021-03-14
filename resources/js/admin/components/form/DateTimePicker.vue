<template>
  <div>
    <div class="d-flex mb-2">
      <div>
        <v-dialog ref="dialog" v-model="isShowData" width="290px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="internalValueDate"
              :label="label + ' (日付)'"
              :readonly="readonly"
              :disabled="disabled"
              clearable
              :error-messages="errorMessages"
              hide-details
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="internalValueDate" scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="hide()">Close</v-btn>
          </v-date-picker>
        </v-dialog>
      </div>
      <div class="ml-1">
        <v-dialog ref="dialog" v-model="isShowTime" width="290px">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="internalValueTime"
              :label="label + ' (時間)'"
              :readonly="readonly"
              :disabled="disabled"
              clearable
              :error-messages="errorMessages"
              hide-details
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker v-model="internalValueTime" scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="hide()">Close</v-btn>
          </v-time-picker>
        </v-dialog>
      </div>
    </div>
    <v-messages color="error" v-if="errorMessages.length !== 0" :value="errorMessages" />
    <v-messages v-if="hint !== '' && errorMessages.length === 0" :value="[hint]" />
  </div>
</template>

<script>
import dayjs from 'dayjs';
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
      isShowTime: false,
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

        this.$emit('input', this.$dayjs(value + ' ' + this.dateTime.time).format('YYYY-MM-DD HH:mm:ss'));
      },
    },
    internalValueTime: {
      get() {
        if (this.value === '' || this.value === null) return this.value;
        return this.$dayjs(this.value).format('HH:mm');
      },
      set(value) {
        if (value === null) {
          this.$emit('input', '');
          return;
        }

        this.$emit('input', this.$dayjs(this.dateTime.date + ' ' + value).format('YYYY-MM-DD HH:mm:ss'));
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
                time: this.$dayjs(newState).format('HH:mm:ss'),
              }
            : { date: '', time: '' };
      },
      immediate: true,
    },
  },
  methods: {
    hide() {
      this.isShowData = false;
      this.isShowTime = false;
    },
  },
};
</script>
