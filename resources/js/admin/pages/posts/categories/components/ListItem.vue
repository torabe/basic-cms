<template>
  <v-list-item>
    <v-list-item-icon>
      <v-icon v-text="'mdi-arrow-all'" color="info" />
    </v-list-item-icon>
    <v-list-item-content>
      <v-list-item-title>
        <router-link
          :to="{
            name: 'category-edit',
            params: { slug: postType.slug, categorySlug: categoryType.slug, id: item.id },
          }"
        >
          {{ item.name }}
        </router-link>
      </v-list-item-title>
      <v-list-item-subtitle>{{ 'スラッグ : ' + item.slug }}</v-list-item-subtitle>
    </v-list-item-content>
    <v-list-item-action>
      <ToggleSwitch
        :label="item.is_enable ? '有効' : '無効'"
        :error-messages="$store.getters['error/validate']('is_enable')"
        @event="update(item)"
        v-model="item.is_enable"
      />
    </v-list-item-action>
    <v-list-item-action>
      <MainButton
        @click="
          $router.push({
            name: 'category-edit',
            params: {
              slug: postType.slug,
              categorySlug: categoryType.slug,
              id: item.id,
            },
          })
        "
      >
        <v-icon small v-text="'mdi-pencil'" />編集
      </MainButton>
    </v-list-item-action>
    <v-list-item-action>
      <DeleteButton @click="destroy(item)"><v-icon small v-text="'mdi-delete'" />削除</DeleteButton>
    </v-list-item-action>
  </v-list-item>
</template>

<script>
import DeleteButton from '../../../../components/buttons/DeleteButton';
import MainButton from '../../../../components/buttons/MainButton';
import ToggleSwitch from '../../../../components/form/ToggleSwitch';

export default {
  components: {
    DeleteButton,
    MainButton,
    ToggleSwitch,
  },
  props: {
    item: {
      type: Object,
      required: true,
      default: () => {},
    },
    postType: {
      type: Object,
      required: true,
      default: () => {},
    },
    categoryType: {
      type: Object,
      required: true,
      default: () => {},
    },
  },
  methods: {
    /**
     * 更新
     *
     * @return void
     */
    async update(item) {
      this.$emit('update', item);
    },
    /**
     * 削除
     *
     * @return void
     */
    async destroy(item) {
      this.$emit('destroy', item);
    },
  },
};
</script>

<style lang="scss" scoped>
.v-list-item {
  &__action {
    margin: 0 16px;
  }
}
</style>
