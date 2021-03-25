<template>
  <Content :title="categoryType.name + '管理'" icon="mdi-group">
    <v-row>
      <v-spacer> </v-spacer>
      <v-col cols="auto">
        <ActionButton
          :to="{
            name: 'category-create',
            params: { slug: postType.slug, categorySlug: categoryType.slug },
          }"
        >
          新規作成
        </ActionButton>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <v-list>
              <draggable v-model="items" @update="sort(items)">
                <div v-for="item in items" :key="item.id">
                  <v-list-group class="v-list-group--category-item" :value="item.children.length">
                    <template v-slot:activator>
                      <ListItem
                        :item="item"
                        :post-type="postType"
                        :category-type="categoryType"
                        @update="update"
                        @destroy="destroy"
                      />
                    </template>

                    <draggable
                      v-model="item.children"
                      @update="sort(item.children)"
                      v-if="item.children.length > 0"
                    >
                      <template v-for="child in item.children">
                        <v-divider :key="'divider-' + child.id" />
                        <ListItem
                          class="v-list-item--child"
                          :item="child"
                          :key="child.id"
                          :post-type="postType"
                          :category-type="categoryType"
                          @update="update"
                          @destroy="destroy"
                        />
                      </template>
                    </draggable>
                    <v-list-item v-else color="info">子カテゴリはありません。</v-list-item>
                  </v-list-group>
                  <v-divider />
                </div>
              </draggable>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../../config/const';
import { OK, ERROR_MESSAGES } from '../../../../modules/util';

import Content from '../../../components/layouts/Content';
import ActionButton from '../../../components/buttons/ActionButton';
import DeleteButton from '../../../components/buttons/DeleteButton';
import MainButton from '../../../components/buttons/MainButton';
import ToggleSwitch from '../../../components/form/ToggleSwitch';

import ListItem from './components/ListItem';

import draggable from 'vuedraggable';

export default {
  components: {
    Content,
    ActionButton,
    DeleteButton,
    MainButton,
    ToggleSwitch,
    draggable,
    ListItem,
  },
  data() {
    const postType = this.$store.getters['page/postType'](this.$route.params.slug);
    const [categoryType] = postType.category_types.filter(
      (categoryType) => categoryType.slug === this.$route.params.categorySlug
    );
    return {
      postType: postType,
      categoryType: categoryType,
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
      const [categoryType] = postType.category_types.filter(
        (categoryType) => categoryType.slug === this.$route.params.categorySlug
      );
      this.categoryType = categoryType;
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
        const response = await axios.get(API_URL + '/admin/' + this.categoryType.slug + '/categories', null);

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
        const response = await axios.put(
          API_URL + '/admin/' + this.categoryType.slug + '/categories/' + item.id,
          item
        );

        if (response.status === OK) {
          this.items = this.items.map((item) => {
            return item.id === response.data.id
              ? response.data
              : {
                  ...item,
                  children: item.children.map((child) =>
                    child.id === response.data.id ? response.data : child
                  ),
                };
          });

          this.$store.dispatch('system/createLog', {
            response: response,
            message:
              this.categoryType.name +
              '「' +
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
     * 並べ替え
     *
     * @return void
     */
    async sort(items) {
      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.put(
          API_URL + '/admin/' + this.categoryType.slug + '/categories/sort',
          items.map((item, index) => ({ id: item.id, sort: index }))
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
     * 削除
     *
     * @return void
     */
    async destroy(item) {
      if (!confirm(this.categoryType.name + '「' + item.name + '」削除をします。\nよろしいですか？'))
        return false;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.delete(
          API_URL + '/admin/' + this.categoryType.slug + '/categories/' + item.id
        );

        if (response.status === OK) {
          const [destroyedItem] = this.items
            .flatMap((item) => [item, ...item.children])
            .filter((item) => item.id === response.data);
          this.items = this.items.filter((item) => {
            item.children = item.children.filter((child) => child.id !== response.data);
            return item.id !== response.data;
          });

          this.$store.dispatch('system/createLog', {
            response: response,
            message: this.categoryType.name + '「' + destroyedItem.name + '」を削除しました',
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
.v-list-item {
  &--child {
    padding-left: 72px;
  }
}
</style>
