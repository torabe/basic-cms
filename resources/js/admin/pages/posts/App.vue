<template>
  <Content :title="postType.name + '管理'" :icon="postType.admin_icon">
    <v-row>
      <v-col cols="auto">
        <ActionButton @click="preview()">一覧ページをプレビュー</ActionButton>
      </v-col>
      <v-spacer />
      <v-col cols="auto">
        <ActionButton :to="{ name: 'create', params: { slug: postType.slug } }">新規作成</ActionButton>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <List
            :post-type="postType"
            v-model="items"
            @update="update"
            @destroy="destroy"
            v-if="!postType.is_sortable && !postType.is_customize"
          />
          <Sortable
            :post-type="postType"
            v-model="items"
            @update="update"
            @sort="sort"
            @destroy="destroy"
            v-if="postType.is_sortable && !postType.is_customize"
          />
        </v-card>
      </v-col>
    </v-row>
  </Content>
</template>

<script>
import axios from 'axios';
import { APP_URL, API_URL } from '../../../config/const';
import { OK, ERROR_MESSAGES } from '../../../modules/util';

import Content from '../../components/layouts/Content';
import ActionButton from '../../components/buttons/ActionButton';
import List from './components/List';
import Sortable from './components/Sortable';

import getBetweenPublish from '../_mixins/getBetweenPublish';

export default {
  mixins: [getBetweenPublish],
  components: {
    Content,
    ActionButton,
    List,
    Sortable,
  },
  data() {
    const postType = this.$store.getters['page/postType'](this.$route.params.slug);
    return {
      postType: postType,
      items: [],
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
  /**
   * ルーティングのパラメータの変更を検知する
   *
   * @param {any} to
   * @param {any} from
   * @param {any} next
   * @return void
   */
  beforeRouteUpdate(to, from, next) {
    if (to.path !== from.path) {
      this.postType = this.$store.getters['page/postType'](to.params.slug);
      this.fetch();
    }
    next();
  },
  methods: {
    /**
     * 情報の読み込み
     *
     * @return void
     */
    async fetch() {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/' + this.postType.slug + '/posts', null);

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
     * 並べ替え
     *
     * @return void
     */
    async sort() {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.put(
          API_URL + '/admin/' + this.postType.slug + '/sort',
          this.items.map((item, index) => ({ id: item.id, sort: index }))
        );

        if (response.status === OK) {
          this.items = response.data;

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
        const response = await axios.put(
          API_URL + '/admin/' + this.postType.slug + '/posts/' + item.id,
          item
        );

        if (response.status === OK) {
          this.items = this.items.map((item) => (item.id === response.data.id ? response.data : item));

          this.$store.dispatch('system/createLog', {
            response: response,
            message:
              this.postType.name +
              '「' +
              response.data.title +
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
      if (!confirm(this.postType.name + '「' + item.title + '」削除をします。\nよろしいですか？'))
        return false;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.delete(API_URL + '/admin/' + this.postType.slug + '/posts/' + item.id);

        if (response.status === OK) {
          const [destroyedItem] = this.items.filter((item) => item.id === response.data);
          this.items = this.items.filter((item) => item.id !== response.data);

          this.$store.dispatch('system/createLog', {
            response: response,
            message: this.postType.name + '「' + destroyedItem.title + '」を削除しました',
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
     * プレビュー
     *
     * @return void
     */
    preview() {
      open(APP_URL + '/' + this.postType.slug + '/?preview', '_blank');
    },
  },
};
</script>
