<template>
  <div class="custom-fields" v-if="customFields">
    <v-row class="custom-field" v-for="(meta, index) in postType.custom_field_metas" :key="index">
      <v-col v-if="meta.type === 'text'">
        <TextField
          :label="meta.name"
          :hint="getHint(meta)"
          :prefix="meta.options.prefix"
          :suffix="meta.options.suffix"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col cols="auto" v-if="meta.type === 'date'">
        <DatePicker
          :label="meta.name"
          :hint="getHint(meta)"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col cols="auto" v-if="meta.type === 'datetime'">
        <DateTimePicker
          :label="meta.name"
          :hint="getHint(meta)"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col v-if="meta.type === 'textarea'">
        <Textarea
          :label="meta.name"
          :hint="getHint(meta)"
          :prefix="meta.options.prefix"
          :suffix="meta.options.suffix"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col v-if="meta.type === 'wysiwyg'">
        <WYSIWYG
          :label="meta.name"
          :hint="getHint(meta)"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col cols="12" md="6" v-if="meta.type === 'image'">
        <File
          :label="meta.name"
          :hint="getHint(meta)"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          icon="mdi-image-outline"
          accept="image/*"
          is-image
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col cols="12" md="6" v-if="meta.type === 'file'">
        <File
          :label="meta.name"
          :hint="getHint(meta)"
          :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value`)"
          icon="mdi-file-outline"
          v-model="customFields[index].value"
        />
      </v-col>
      <v-col v-if="meta.type === 'link'">
        <div class="subtitle-1">{{ meta.name }}</div>
        <v-row dense>
          <v-col cols="auto" md="4">
            <TextField
              label="URL"
              :hint="getHint(meta)"
              :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value.url`)"
              dense
              v-model="customFields[index].value.url"
            />
          </v-col>
          <v-col cols="auto" md="4">
            <TextField
              label="テキスト"
              :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value.text`)"
              dense
              v-model="customFields[index].value.text"
            />
          </v-col>
          <v-col cols="auto" md="4">
            <Select
              label="ターゲット"
              :items="[
                { value: '_self', text: '内部リンク' },
                { value: '_blank', text: '外部リンク' },
              ]"
              :error-messages="$store.getters['error/validate'](`custom_fields.${index}.value.target`)"
              dense
              v-model="customFields[index].value.target"
            />
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import DatePicker from '../../../components/form/DatePicker';
import DateTimePicker from '../../../components/form/DateTimePicker';
import File from '../../../components/form/File';
import RadioGroup from '../../../components/form/RadioGroup';
import Select from '../../../components/form/Select';
import TextField from '../../../components/form/TextField';
import Textarea from '../../../components/form/Textarea';
import WYSIWYG from '../../../components/form/WYSIWYG';

export default {
  components: {
    DatePicker,
    DateTimePicker,
    File,
    RadioGroup,
    Select,
    TextField,
    Textarea,
    WYSIWYG,
  },
  props: {
    postType: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    value: {
      type: Array,
      required: false,
      defalut: () => {},
    },
  },
  data() {
    return {
      customFields: this.value,
    };
  },
  computed: {
    getHint() {
      return (meta) =>
        (~meta.validate.indexOf('required') ? '※必須入力 ' : '') + meta.options.hint ? meta.options.hint : '';
    },
  },
  watch: {
    value: {
      handler(newValue) {
        this.customFields = newValue;
      },
      immediate: true,
    },
    customFields: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
};
</script>
