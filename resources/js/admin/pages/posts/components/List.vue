<template>
  <v-data-table
    calculate-widths
    :headers="headers"
    :items="items"
    :footer-props="{
      'items-per-page-options': [10, 20, 50, 100, -1],
      showFirstLastPage: true,
    }"
    :options.sync="options"
    :loading="$store.getters['page/loading']"
    loading-text="Loading..."
    :no-data-text="noDataText"
  >
    <template v-slot:body>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td>{{ item.id }}</td>
          <td>{{ getBetweenPublish(item) }}</td>
          <td>
            <router-link :to="{ name: 'edit', params: { slug: postType.slug, id: item.id } }">{{
              item.title
            }}</router-link>
          </td>
          <td v-for="categoryType in postType.category_types" :key="categoryType.elug">
            {{ getCategories(item.categories, categoryType) }}
          </td>
          <td>{{ item.author.name }}</td>
          <td>
            <ToggleSwitch
              :label="item.is_enable ? '公開' : '非公開'"
              :error-messages="$store.getters['error/validate']('is_enable')"
              @event="update(item)"
              v-model="item.is_enable"
            />
          </td>
          <td>
            <v-row dense>
              <v-col cols="auto">
                <MainButton
                  @click="$router.push({ name: 'edit', params: { slug: postType.slug, id: item.id } })"
                >
                  <v-icon small v-text="'mdi-pencil'" />編集
                </MainButton>
              </v-col>
              <v-col cols="auto">
                <DeleteButton @click="destroy(item)">
                  <v-icon small v-text="'mdi-delete'" />削除
                </DeleteButton>
              </v-col>
            </v-row>
          </td>
        </tr>
      </tbody>
    </template>
  </v-data-table>
</template>

<script>
import DeleteButton from '../../../components/buttons/DeleteButton';
import MainButton from '../../../components/buttons/MainButton';
import ToggleSwitch from '../../../components/form/ToggleSwitch';

import getBetweenPublish from '../../_mixins/getBetweenPublish';

export default {
  mixins: [getBetweenPublish],
  components: {
    DeleteButton,
    MainButton,
    ToggleSwitch,
  },
  props: {
    postType: {
      type: Object,
      required: true,
      defalut: () => {},
    },
    value: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  data() {
    const headers = this.postType.category_types.map((categoryType) => ({
      text: categoryType.name,
      value: categoryType.slug,
    }));
    return {
      items: this.value,
      headers: [
        { text: 'ID', value: 'id' },
        { text: '公開期間', value: 'publish_at' },
        { text: 'タイトル', value: 'title' },
        ...headers,
        { text: '著者', value: 'author.name' },
        { text: '状態', value: 'is_enable' },
        { text: '操作', value: 'action', sortable: false, width: 200 },
      ],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ['publish_at', 'unpublish_at', 'sort', 'id'],
        sortDesc: [true, true, false, true],
      },
      noDataText: this.postType.name + 'の登録はありません',
    };
  },
  computed: {
    getCategories() {
      return (categories, categoryType) =>
        categories
          .filter((category) => category.category_type.id === categoryType.id)
          .map((category) => category.name)
          .join(',');
    },
  },
  watch: {
    value: {
      handler(newValue) {
        this.items = newValue;
      },
      immediate: true,
    },
    items: {
      handler(newValue) {
        this.$emit('input', newValue);
      },
      deep: true,
    },
  },
  methods: {
    update(item) {
      this.$emit('update', item);
    },
    destroy(item) {
      this.$emit('destroy', item);
    },
  },
};
</script>
