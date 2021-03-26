<template>
  <Content :title="'カテゴリタイプ管理'" :icon="'mdi-file-tree'">
    <v-row>
      <v-spacer />
      <v-col cols="auto">
        <ActionButton :to="{ name: 'category-type-create' }">新規作成</ActionButton>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
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
            <template v-slot:item.title="{ item }">
              <router-link :to="{ name: 'category-type-edit', params: { id: item.id } }">
                {{ item.name }}
              </router-link>
            </template>

            <template v-slot:item.is_enable="{ item }">
              <ToggleSwitch
                :label="item.is_enable ? '公開' : '非公開'"
                :error-messages="$store.getters['error/validate']('is_enable')"
                @event="update(item)"
                v-model="item.is_enable"
              />
            </template>

            <template v-slot:item.action="{ item }">
              <v-row dense>
                <v-col cols="auto">
                  <MainButton @click="$router.push({ name: 'category-type-edit', params: { id: item.id } })">
                    <v-icon small v-text="'mdi-pencil'" />編集
                  </MainButton>
                </v-col>
                <v-col cols="auto">
                  <DeleteButton @click="destroy(item)">
                    <v-icon small v-text="'mdi-delete'" />削除
                  </DeleteButton>
                </v-col>
              </v-row>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../config/const';
import { OK, ERROR_MESSAGES } from '../../../modules/util';

import Content from '../../components/layouts/Content';
import ActionButton from '../../components/buttons/ActionButton';
import DeleteButton from '../../components/buttons/DeleteButton';
import MainButton from '../../components/buttons/MainButton';
import ToggleSwitch from '../../components/form/ToggleSwitch';

export default {
  components: {
    Content,
    ActionButton,
    DeleteButton,
    MainButton,
    ToggleSwitch,
  },
  data() {
    return {
      headers: [
        { text: 'ID', value: 'id' },
        { text: '名称', value: 'name' },
        { text: 'スラッグ', value: 'slug' },
        { text: '状態', value: 'is_enable' },
        { text: '操作', value: 'action', sortable: false, width: 200 },
      ],
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: ['id'],
        sortDesc: [false],
      },
      items: [],
      noDataText: 'カテゴリタイプの登録はありません',
    };
  },
  beforeRouteEnter(to, from, next) {
    /**
     * ページ遷移前に必要な情報を読み込み+セット
     *
     * @param {any} (vm)
     * @return void
     */
    next((vm) => {
      vm.fetch();
    });
  },
  methods: {
    /**
     * 情報の読み込み
     *
     * @return void
     */
    async fetch() {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/category-types', null);

        if (response.status === OK) {
          this.items = response.data.items;

          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
    /**
     * 更新
     *
     * @return void
     */
    async update(item) {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.put(API_URL + '/admin/category-types/' + item.id, item);

        if (response.status === OK) {
          this.items = this.items.map((item) => (item.id === response.data.id ? response.data : item));

          await this.$store.dispatch('page/postTypes');

          this.$store.dispatch('system/createLog', {
            response: response,
            message:
              'カテゴリタイプ「' +
              response.data.name +
              '」を' +
              (response.data.is_enable ? '公開' : '非公開に') +
              'しました',
          });

          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
    /**
     * 削除
     *
     * @return void
     */
    async destroy(item) {
      if (!confirm('カテゴリタイプ「' + item.name + '」削除をします。\nよろしいですか？')) return false;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.delete(API_URL + '/admin/category-types/' + item.id);

        if (response.status === OK) {
          const [destroyedItem] = this.items.filter((item) => item.id === response.data);
          this.items = this.items.filter((item) => item.id !== response.data);

          await this.$store.dispatch('page/postTypes');

          this.$store.dispatch('system/createLog', {
            response: response,
            message: 'カテゴリタイプ「' + destroyedItem.name + '」を削除しました',
          });
          return false;
        }

        this.$store.dispatch(
          'system/createLog',
          { response: response, message: ERROR_MESSAGES[response.status] },
          { root: true }
        );
      });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
