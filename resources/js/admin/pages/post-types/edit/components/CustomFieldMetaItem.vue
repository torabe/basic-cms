<template>
  <div>
    <v-list-group elevation="1" :prepend-icon="'mdi-arrow-all'" color="primary">
      <template v-slot:activator>
        <v-list-item-content>
          <v-list-item-title>{{ getName }}</v-list-item-title>
        </v-list-item-content>
        <v-list-item-action>
          <DeleteButton @click="destroy()"> <v-icon small v-text="'mdi-delete'" />削除 </DeleteButton>
        </v-list-item-action>
      </template>

      <v-list-item>
        <v-container dense>
          <v-row dense>
            <v-col>
              <v-row dense>
                <v-col>
                  <TextField
                    label="フィールド名"
                    :error-messages="$store.getters['error/validate'](`custom_field_metas.${index}.name`)"
                    v-model="fieldMeta.name"
                    dense
                  />
                </v-col>
              </v-row>
              <v-row dense>
                <v-col>
                  <TextField
                    label="キー"
                    :error-messages="$store.getters['error/validate'](`custom_field_metas.${index}.key`)"
                    hint="半角英数記号"
                    v-model="fieldMeta.key"
                    dense
                  />
                </v-col>
              </v-row>
              <v-row dense>
                <v-col>
                  <Select
                    label="タイプ"
                    :items="types"
                    :error-messages="$store.getters['error/validate'](`custom_field_metas.${index}.type`)"
                    v-model="fieldMeta.type"
                    dense
                  />
                </v-col>
              </v-row>
              <template v-if="fieldMeta.type">
                <v-row dense>
                  <v-col>
                    <Select
                      label="バリデーション"
                      :items="validates[fieldMeta.type]"
                      :error-messages="
                        $store.getters['error/validate'](`custom_field_metas.${index}.validate`)
                      "
                      multiple
                      v-if="validates[fieldMeta.type]"
                      v-model="fieldMeta.validate"
                      dense
                    />
                  </v-col>
                </v-row>
                <v-row dense v-if="fieldMeta.type === 'text' || fieldMeta.type === 'textarea'">
                  <v-col cols="auto">
                    <TextField
                      label="接頭辞"
                      :error-messages="
                        $store.getters['error/validate'](`custom_field_metas.${index}.options.prefix`)
                      "
                      v-model="fieldMeta.options.prefix"
                      dense
                    />
                  </v-col>
                  <v-col cols="auto">
                    <TextField
                      label="接尾辞"
                      :error-messages="
                        $store.getters['error/validate'](`custom_field_metas.${index}.options.suffix`)
                      "
                      v-model="fieldMeta.options.suffix"
                      dense
                    />
                  </v-col>
                </v-row>
                <v-row dense v-if="fieldMeta.type !== 'loop'">
                  <v-col>
                    <TextField
                      label="入力時のヒント"
                      :error-messages="
                        $store.getters['error/validate'](`custom_field_metas.${index}.options.hint`)
                      "
                      v-model="fieldMeta.options.hint"
                      dense
                    />
                  </v-col>
                </v-row>
              </template>
            </v-col>
          </v-row>
        </v-container>
      </v-list-item>
    </v-list-group>
    <v-divider />
  </div>
</template>

<script>
import draggable from 'vuedraggable';

import DeleteButton from '../../../../components/buttons/DeleteButton';
import File from '../../../../components/form/File';
import TextField from '../../../../components/form/TextField';
import Select from '../../../../components/form/Select';
import ToggleSwitch from '../../../../components/form/ToggleSwitch';

export default {
  components: {
    draggable,
    DeleteButton,
    File,
    TextField,
    ToggleSwitch,
    Select,
  },
  props: {
    types: {
      type: Array,
      required: true,
      default: () => [],
    },
    validates: {
      type: Object,
      required: false,
      default: () => {},
    },
    index: {
      type: Number,
      required: true,
      default: 0,
    },
    value: {
      type: Object,
      required: true,
      default: () => {},
    },
  },
  data() {
    return {
      fieldMeta: this.value,
    };
  },
  computed: {
    getName() {
      return this.fieldMeta.name ? this.fieldMeta.name : `カスタムフィールド${this.index + 1}`;
    },
  },
  watch: {
    value: {
      handler(newValue) {
        this.fieldMeta = newValue;
      },
      immediate: true,
    },
    fieldMeta: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
  methods: {
    destroy() {
      this.$emit('destroy', { field: this.fieldMeta, index: this.index });
    },
  },
};
</script>
