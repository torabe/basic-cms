<template>
  <Content :title="categoryType.name + ' ' + title" icon="mdi-group">
    <v-form ref="edit" @submit.prevent="storeOrUpdate">
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="auto">
          <SubButton
            :to="{ name: 'categories', params: { slug: postType.slug, categorySlug: categoryType.slug } }"
          >
            一覧に戻る
          </SubButton>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-card>
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-information-outline'" color="primary" />
              基本情報
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row dense>
                  <v-col cols="auto">
                    <ToggleSwitch
                      :label="form.is_enable ? '公開' : '非公開'"
                      :error-messages="$store.getters['error/validate']('is_enable')"
                      v-model="form.is_enable"
                    />
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="auto">
                    <TextField
                      label="名称"
                      :error-messages="$store.getters['error/validate']('name')"
                      v-model="form.name"
                    />
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="auto">
                    <TextField
                      label="スラッグ"
                      :error-messages="$store.getters['error/validate']('slug')"
                      hint="半角英数字 URLに使用されます"
                      v-model="form.slug"
                    />
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="auto">
                    <Select
                      label="親カテゴリ"
                      :items="select.categories"
                      item-text="name"
                      item-value="id"
                      :error-messages="$store.getters['error/validate']('parent_id')"
                      v-model="form.parent_id"
                    />
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <MainButton type="submit">{{ buttonText }}</MainButton>
        </v-col>
      </v-row>
    </v-form>
  </Content>
</template>

<script>
import axios from 'axios';
import { API_URL } from '../../../../../config/const';
import { OK, CREATED, UNPROCESSABLE_ENTITY, ERROR_MESSAGES } from '../../../../../modules/util';

import Content from '../../../../components/layouts/Content';
import MainButton from '../../../../components/buttons/MainButton';
import SubButton from '../../../../components/buttons/SubButton';
import ToggleSwitch from '../../../../components/form/ToggleSwitch';
import TextField from '../../../../components/form/TextField';
import Select from '../../../../components/form/Select';

const CREATE_NAME = 'category-create';

export default {
  components: {
    Content,
    MainButton,
    SubButton,
    ToggleSwitch,
    TextField,
    Select,
  },
  /**
   * stateのセット
   *
   * @return void
   */
  data() {
    const postType = this.$store.getters['page/postType'](this.$route.params.slug);
    const [categoryType] = postType.category_types.filter(
      (categoryType) => categoryType.slug === this.$route.params.categorySlug
    );
    return {
      postType: postType,
      categoryType: categoryType,
      single: {},
      form: {},
      select: {
        categories: [],
      },
      title: this.$route.name === CREATE_NAME ? '新規登録' : '更新',
      buttonText: this.$route.name === CREATE_NAME ? '登録' : '更新',
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
      vm.initForm();
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
      this.initForm();
      this.fetch();
    }
    next();
  },
  computed: {
    /**
     * 新規登録 or 更新
     *
     * @return void
     */
    isCreate() {
      return this.$route.name === CREATE_NAME;
    },
  },
  methods: {
    /**
     * フォームデータの初期化
     *
     * @return void
     */
    initForm() {
      this.form = {
        id: null,
        category_type_id: this.categoryType.id,
        name: '',
        slug: '',
        parent_id: null,
        sort: 0,
        is_enable: 1,
      };
    },
    /**
     * 情報の読み込み
     *
     * @return void
     */
    async fetch() {
      this.single = {};
      const id = this.$route.params.id;

      await this.$store.dispatch('page/loading', async () => {
        const response = await axios.get(API_URL + '/admin/' + this.categoryType.slug + '/categories/' + id);

        if (response.status === OK) {
          this.select = {
            ...this.select,
            categories: response.data.categories,
          };

          if (response.data.single === null) return false;

          this.single = { ...response.data.single };
          this.form = { ...response.data.single };

          return false;
        }

        this.$store.dispatch('system/createLog', {
          response: response,
          message: ERROR_MESSAGES[response.status],
        });
      });
    },
    /**
     * 登録 or 更新
     *
     * @return void
     */
    async storeOrUpdate() {
      await this.$store.dispatch('page/loading', async () => {
        this.$store.commit('error/setValidate', null);
        const response = this.isCreate
          ? await axios.post(API_URL + '/admin/' + this.categoryType.slug + '/categories', this.form)
          : await axios.put(
              API_URL + '/admin/' + this.categoryType.slug + '/categories/' + this.single.id,
              this.form
            );

        if (response.status === OK || response.status === CREATED) {
          this.single = { ...response.data };
          this.form = { ...response.data };

          this.$store.dispatch('system/createLog', {
            response: response,
            message: this.categoryType.name + 'を' + this.buttonText + 'しました',
          });

          if (this.isCreate)
            this.$router.replace({
              name: 'category-edit',
              params: { slug: this.postType.slug, categorySlug: this.categoryType.slug, id: this.single.id },
            });

          return false;
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
          this.$store.commit('error/setValidate', response.data.errors);
        }

        this.$store.dispatch('system/createLog', {
          response: response,
          message: ERROR_MESSAGES[response.status],
        });
      });
    },
  },
};
</script>
