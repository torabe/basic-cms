<template>
  <v-card>
    <v-card-title>
      <v-icon class="mr-1" v-text="'mdi-text-box-outline'" color="primary" />
      カスタムフィールド
    </v-card-title>
    <v-card-text>
      <v-list>
        <draggable v-model="custom_field_metas" tag="div" @update="sort">
          <template v-for="(fieldMeta, index) in custom_field_metas">
            <CustomFieldMetaItem
              :key="index"
              :types="types"
              :validates="validates"
              :index="index"
              v-model="custom_field_metas[index]"
              @destroy="destroy"
              v-if="!fieldMeta.is_delete"
            />
          </template>
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
import CustomFieldMetaItem from './CustomFieldMetaItem';

export default {
  components: {
    draggable,
    ActionButton,
    CustomFieldMetaItem,
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
          children: [],
        },
      ];
    },
    sort() {
      this.custom_field_metas = this.custom_field_metas.map((field_meta, index) => ({
        ...field_meta,
        sort: index + 1,
      }));
    },
    destroy({ field, index }) {
      if (field.id === null) {
        this.custom_field_metas = this.custom_field_metas.filter((field_meta, i) => i !== index);
        return;
      }
      this.custom_field_metas = this.custom_field_metas.map((field_meta) => {
        if (field.id === field_meta.id) field_meta.is_delete = true;
        console.log(field_meta);
        return field_meta;
      });
    },
  },
};
</script>
