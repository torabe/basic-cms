<template>
  <v-card>
    <v-card-title>
      <v-icon class="mr-1" v-text="'mdi-text-box-outline'" color="primary" />
      カスタムフィールド
    </v-card-title>
    <v-card-text>
      <v-list>
        <draggable v-model="custom_field_metas" tag="div" @update="sort">
          <v-list-group
            elevation="1"
            v-for="(field_meta, index) in custom_field_metas"
            :key="index"
            :prepend-icon="'mdi-arrow-all'"
            color="primary"
          >
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>{{ getName(field_meta, index) }}</v-list-item-title>
              </v-list-item-content>
            </template>

            <v-list-item>
              <v-container dense>
                <v-row dense>
                  <v-col>
                    <v-row dense>
                      <v-col>
                        <TextField
                          label="フィールド名"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.name`)
                          "
                          v-model="field_meta.name"
                          dense
                        />
                      </v-col>
                    </v-row>
                    <v-row dense>
                      <v-col>
                        <TextField
                          label="キー"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.key`)
                          "
                          hint="半角英数記号"
                          v-model="field_meta.key"
                          dense
                        />
                      </v-col>
                    </v-row>
                    <v-row dense>
                      <v-col>
                        <Select
                          label="タイプ"
                          :items="types"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.type`)
                          "
                          v-model="field_meta.type"
                          dense
                        />
                      </v-col>
                    </v-row>
                    <v-row dense>
                      <v-col>
                        <Select
                          label="バリデーション"
                          :items="validates[field_meta.type]"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.validate`)
                          "
                          multiple
                          v-if="validates[field_meta.type]"
                          v-model="field_meta.validate"
                          dense
                        />
                      </v-col>
                    </v-row>
                    <v-row dense v-if="field_meta.type === 'text' || field_meta.type === 'textarea'">
                      <v-col cols="auto">
                        <TextField
                          label="プレフィクス"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.options.prefix`)
                          "
                          v-model="field_meta.options.prefix"
                          dense
                        />
                      </v-col>
                      <v-col cols="auto">
                        <TextField
                          label="サフィックス"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.options.suffix`)
                          "
                          v-model="field_meta.options.suffix"
                          dense
                        />
                      </v-col>
                    </v-row>
                    <v-row dense v-if="field_meta.type !== 'loop'">
                      <v-col>
                        <TextField
                          label="ヒント"
                          :error-messages="
                            $store.getters['error/validate'](`custom_field_metas.${index}.options.hint`)
                          "
                          v-model="field_meta.options.hint"
                          dense
                        />
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col cols="auto">
                    <v-spacer />
                    <DeleteButton @click="destroy(field_meta, index)">
                      <v-icon small v-text="'mdi-delete'" />削除
                    </DeleteButton>
                  </v-col>
                </v-row>
              </v-container>
            </v-list-item>
          </v-list-group>
        </draggable>
      </v-list>
    </v-card-text>
    <v-card-text
      class="error--text"
      v-if="$store.getters['error/validate']('custom_custom_field_metas').length > 0"
    >
      <template v-for="message in $store.getters['error/validate']('custom_custom_field_metas')">{{
        message
      }}</template>
    </v-card-text>
    <v-card-actions>
      <ActionButton @click="add">追加</ActionButton>
    </v-card-actions>
  </v-card>
</template>

<script>
import draggable from 'vuedraggable';

import ActionButton from '../../../../components/buttons/ActionButton';
import DeleteButton from '../../../../components/buttons/DeleteButton';
import File from '../../../../components/form/File';
import TextField from '../../../../components/form/TextField';
import Select from '../../../../components/form/Select';
import ToggleSwitch from '../../../../components/form/ToggleSwitch';

export default {
  components: {
    draggable,
    ActionButton,
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
    value: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  data() {
    return { custom_field_metas: this.value };
  },
  computed: {
    getName() {
      return (field, index) => (field.name ? field.name : `カスタムフィールド${index + 1}`);
    },
  },
  watch: {
    value: {
      handler(newValue) {
        this.custom_field_metas = newValue;
      },
      immediate: true,
    },
    custom_field_metas: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
  methods: {
    add() {
      this.custom_field_metas = [
        ...this.custom_field_metas,
        {
          id: null,
          name: '',
          type: '',
          key: '',
          validate: [],
          options: {},
          parent_id: null,
          sort: this.custom_field_metas.length + 1,
        },
      ];
    },
    sort() {
      this.custom_field_metas = this.custom_field_metas.map((field_meta, index) => ({
        ...field_meta,
        sort: index + 1,
      }));
    },
    async destroy(field, index) {
      if (field.id === null) {
        this.custom_field_metas = this.custom_field_metas.filter((field_meta, i) => field_meta !== index);
        return;
      }
      this.custom_field_metas = this.custom_field_metas.map((field_meta) => {
        if (field.id === field_meta.id) field_meta.is_delete = true;
        return field_meta;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.v-list-group {
  border-bottom: solid 1px currentcolor;
}
</style>
