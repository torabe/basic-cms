<template>
  <Content :title="'投稿タイプ管理'" :icon="'mdi-file-tree'">
    <v-row>
      <v-spacer> </v-spacer>
      <v-col cols="auto">
        <ActionButton :to="{ name: 'post-type-create' }">新規作成</ActionButton>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-data-table
            calculate-widths
            :headers="headers"
            :items="items"
            hide-default-footer
            :options.sync="options"
            :loading="$store.getters['page/loading']"
            loading-text="Loading..."
            :no-data-text="noDataText"
          >
            <template v-slot:body>
              <draggable v-model="items" tag="tbody" @update="sort">
                <tr v-for="item in items" :key="item.id">
                  <td>
                    <v-icon small class="grab">mdi-arrow-all</v-icon>
                  </td>
                  <td>{{ item.id }}</td>
                  <td>
                    <router-link :to="{ name: 'post-type-edit', params: { id: item.id } }">
                      {{ item.name }}
                    </router-link>
                  </td>
                  <td>{{ item.slug }}</td>
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
                          @click="$router.push({ name: 'post-type-edit', params: { id: item.id } })"
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
              </draggable>
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

import draggable from 'vuedraggable';

export default {
  components: {
    Content,
    ActionButton,
    DeleteButton,
    MainButton,
    ToggleSwitch,
    draggable,
  },
  data() {
    return {
      headers: [
        { text: '', value: 'sort', sortable: false },
        { text: 'ID', value: 'id', sortable: false },
        { text: '名称', value: 'name', sortable: false },
        { text: 'スラッグ', value: 'slug', sortable: false },
        { text: '状態', value: 'is_enable', sortable: false },
        { text: '操作', value: 'action', sortable: false, width: 200 },
      ],
      options: {
        sortBy: ['sort', 'id'],
        sortDesc: [false, true],
      },
      items: [],
      noDataText: '投稿タイプの登録はありません',
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
        const response = await axios.get(API_URL + '/admin/post-types', null);

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
        const response = await axios.put(API_URL + '/admin/post-types/' + item.id, item);

        if (response.status === OK) {
          this.items = this.items.map((item) => (item.id === response.data.id ? response.data : item));

          await this.$store.dispatch('page/postTypes');

          this.$store.dispatch('system/createLog', {
            response: response,
            message:
              '投稿タイプ「' +
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
      if (!confirm('投稿タイプ「' + item.name + '」削除をします。\nよろしいですか？')) return false;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.delete(API_URL + '/admin/post-types/' + item.id);

        if (response.status === OK) {
          const [destroyedItem] = this.items.filter((item) => item.id === response.data);
          this.items = this.items.filter((item) => item.id !== response.data);

          await this.$store.dispatch('page/postTypes');

          this.$store.dispatch('system/createLog', {
            response: response,
            message: '投稿タイプ「' + destroyedItem.name + '」を削除しました',
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
     * 並べ替え
     *
     * @return void
     */
    async sort() {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.put(
          API_URL + '/admin/post-types/sort',
          this.items.map((item, index) => ({ id: item.id, sort: index }))
        );

        if (response.status === OK) {
          this.items = response.data;

          await this.$store.dispatch('page/postTypes');

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
