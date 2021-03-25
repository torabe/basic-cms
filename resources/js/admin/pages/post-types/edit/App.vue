<template>
  <Content :title="'投稿タイプ ' + title" :icon="'mdi-file-tree'">
    <v-form ref="edit" @submit.prevent="storeOrUpdate">
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="auto">
          <SubButton :to="{ name: 'post-types' }">一覧に戻る</SubButton>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4" order="1" order-md="2">
          <v-card>
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-information'" color="primary" />
              メタ情報
            </v-card-title>
            <v-card-text>
              <v-row dense align="center">
                <v-col cols="auto">公開設定 : </v-col>
                <v-col>
                  <ToggleSwitch
                    :label="form.is_enable ? '公開' : '非公開'"
                    :error-messages="$store.getters['error/validate']('is_enable')"
                    v-model="form.is_enable"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col>
                  <TextField
                    label="スラッグ"
                    :error-messages="$store.getters['error/validate']('slug')"
                    hint="半角英数字"
                    v-model="form.slug"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col>
                  <TextField
                    label="管理画面上のアイコン"
                    :error-messages="$store.getters['error/validate']('admin_icon')"
                    hint="https://materialdesignicons.com/"
                    :icon="form.admin_icon"
                    v-model="form.admin_icon"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col>
                  <TextField
                    label="1ページ当たりの表示件数"
                    type="number"
                    :error-messages="$store.getters['error/validate']('per_page')"
                    hint="上限なしの場合は「-1」と入力"
                    v-model="form.per_page"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col>
                  <Select
                    label="親投稿"
                    :items="select.post_types"
                    item-text="name"
                    item-value="id"
                    :error-messages="$store.getters['error/validate']('parent_id')"
                    v-if="select.post_types && select.post_types.length > 0"
                    v-model="form.parent_id"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col cols="auto">並び順 : </v-col>
                <v-col>
                  <ToggleSwitch
                    :label="form.is_sortable ? '手動で並び順を変更する' : '投稿日時でソート'"
                    :error-messages="$store.getters['error/validate']('is_sortable')"
                    v-model="form.is_sortable"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col cols="auto">編集画面のカスタマイズ : </v-col>
                <v-col>
                  <ToggleSwitch
                    :label="form.is_customize ? '有効' : '無効'"
                    :error-messages="$store.getters['error/validate']('is_customize')"
                    v-model="form.is_customize"
                  />
                </v-col>
                <v-messages :value="['※有効の場合、開発者が管理画面のVueコンポーネント作成してください']" />
              </v-row>
            </v-card-text>
            <v-card-actions>
              <v-spacer />
              <MainButton type="submit">{{ action }}</MainButton>
            </v-card-actions>
          </v-card>
        </v-col>

        <v-col cols="12" md="8" order="2" order-md="1">
          <v-card>
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-information-outline'" color="primary" />
              基本情報
            </v-card-title>
            <v-card-text>
              <v-row dense align="center">
                <v-col>
                  <TextField
                    label="名称"
                    :error-messages="$store.getters['error/validate']('name')"
                    v-model="form.name"
                  />
                </v-col>
              </v-row>
              <v-row dense align="center">
                <v-col>
                  <Textarea
                    label="概要"
                    :error-messages="$store.getters['error/validate']('description')"
                    v-model="form.description"
                  />
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <v-card v-if="select.category_types && select.category_types.length > 0">
            <v-card-title>
              <v-icon class="mr-1" v-text="'mdi-file-tree'" color="primary" />
              カテゴリタイプ
            </v-card-title>
            <v-card-text>
              <v-row dense align="center">
                <v-col>
                  <Select
                    :items="select.category_types"
                    item-text="name"
                    item-value="id"
                    :error-messages="$store.getters['error/validate']('category_type_ids')"
                    multiple
                    v-model="form.category_type_ids"
                  />
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <CustomFieldMetas
            :types="select.field_types"
            :validates="select.field_validates"
            v-model="form.custom_field_metas"
          />
        </v-col>
      </v-row>
    </v-form>
  </Content>
</template>

<script>
import axios from 'axios';
import { APP_URL, API_URL } from '../../../../config/const';
import { OK, CREATED, UNPROCESSABLE_ENTITY, ERROR_MESSAGES } from '../../../../modules/util';

import Content from '../../../components/layouts/Content';
import ActionButton from '../../../components/buttons/ActionButton';
import MainButton from '../../../components/buttons/MainButton';
import SubButton from '../../../components/buttons/SubButton';
import TextField from '../../../components/form/TextField';
import Textarea from '../../../components/form/Textarea';
import ToggleSwitch from '../../../components/form/ToggleSwitch';
import Select from '../../../components/form/Select';

import CustomFieldMetas from './components/CustomFieldMetas';

const CREATE_NAME = 'post-type-create';

export default {
  components: {
    Content,
    ActionButton,
    MainButton,
    SubButton,
    TextField,
    Textarea,
    ToggleSwitch,
    Select,
    CustomFieldMetas,
  },
  /**
   * stateのセット
   *
   * @return void
   */
  data() {
    return {
      single: {},
      form: {},
      select: {},
      title: this.$route.name === CREATE_NAME ? '新規登録' : '更新',
      action: this.$route.name === CREATE_NAME ? '登録' : '更新',
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
  watch: {},
  methods: {
    /**
     * フォームデータの初期化
     *
     * @return void
     */
    initForm() {
      this.form = {
        id: null,
        name: '',
        slug: '',
        description: '',
        parent_id: null,
        admin_icon: '',
        per_page: '',
        is_sortable: false,
        is_customize: false,
        is_enable: true,
        category_type_ids: [],
        custom_field_metas: [],
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
        const response = await axios.get(API_URL + '/admin/post-types/' + id);

        if (response.status === OK) {
          this.select = {
            ...this.select,
            post_types: response.data.post_types.filter((postType) => postType.id !== parseInt(id)),
            category_types: response.data.category_types,
            field_types: response.data.field_types,
            field_validates: response.data.field_validates,
          };
          if (this.isCreate) return false;

          this.single = { ...response.data.single };
          this.form = {
            ...response.data.single,
            category_type_ids: response.data.single.category_types.map((categoryType) => categoryType.id),
          };

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
          ? await axios.post(API_URL + '/admin/post-types', this.form)
          : await axios.put(API_URL + '/admin/post-types/' + this.single.id, this.form);

        if (response.status === OK || response.status === CREATED) {
          this.single = { ...response.data };
          this.form = {
            ...response.data,
            category_type_ids: response.data.category_types.map((categoryType) => categoryType.id),
          };

          await this.$store.dispatch('page/postTypes');

          this.$store.dispatch('system/createLog', {
            response: response,
            message: '投稿タイプを' + this.action + 'しました',
          });

          if (this.isCreate) {
            this.$router.replace({
              name: 'post-type-edit',
              params: { id: this.single.id },
            });
          }

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

<style lang="scss" scoped>
.v-input:not(:last-child) {
  margin-bottom: 15px;
}
.v-card:not(:first-child) {
  margin-top: 24px;
}
</style>
