<template>
  <Content>
    <v-row>
      <v-col v-for="postType in postTypes" :key="postType.id" md="6" sm="12" cols="12">
        <v-card>
          <v-card-title>
            <v-icon class="mr-1" v-text="postType.admin_icon" color="primary" />
            {{ postType.name }}
          </v-card-title>
          <v-card-subtitle>最近の投稿</v-card-subtitle>
          <v-card-text>
            <v-data-table
              calculate-widths
              :headers="headers"
              :items="postType.posts"
              :hide-default-footer="true"
              :options.sync="options"
              :loading="$store.getters['page/loading']"
              loading-text="Loading..."
              :no-data-text="postType.name + 'の投稿はありません。'"
            >
              <template v-slot:item.publish_at="{ item }">{{ getBetweenPublish(item) }}</template>
              <template v-slot:item.is_enable="{ item }">
                {{ item.is_enable ? '公開' : '非公開' }}
              </template>
              <template v-slot:item.action="{ item }">
                <MainButton
                  @click="$router.push({ name: 'edit', params: { slug: postType.slug, id: item.id } })"
                >
                  編集
                </MainButton>
              </template>
            </v-data-table>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <ActionButton :to="{ name: 'index', params: { slug: postType.slug } }">一覧へ</ActionButton>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../config/const';
import { OK, CREATED, UNPROCESSABLE_ENTITY, ERROR_MESSAGES, numberFormat } from '../../../modules/util';

import Content from '../../components/layouts/Content';
import ActionButton from '../../components/buttons/ActionButton';
import MainButton from '../../components/buttons/MainButton';

import getBetweenPublish from '../_mixins/getBetweenPublish';

export default {
  mixins: [getBetweenPublish],
  components: {
    Content,
    ActionButton,
    MainButton,
  },
  data() {
    return {
      postTypes: [],
      headers: [
        { text: '更新日', value: 'updated_at', sortable: false, width: 105 },
        { text: 'タイトル', value: 'title', sortable: false },
        { text: '公開', value: 'is_enable', sortable: false, width: 105 },
        { text: '操作', value: 'action', sortable: false, width: 100 },
      ],
      options: {
        sortBy: ['updated_at', 'created_at', 'id'],
        sortDesc: [true, true, true],
      },
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
        const response = await axios.get(API_URL + '/admin/dashboard');

        if (response.status === OK) {
          this.postTypes = response.data.postTypes;

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
.v-card {
  height: 100%;
  display: flex;
  flex-flow: column nowrap;
  &__actions {
    margin-top: auto;
  }
}
</style>
