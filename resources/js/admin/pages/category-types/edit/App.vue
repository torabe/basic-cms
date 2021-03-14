<template>
  <Content :title="'カテゴリタイプ ' + title" :icon="'mdi-file-tree'">
    <v-form ref="edit" @submit.prevent="storeOrUpdate">
      <v-row>
        <v-spacer></v-spacer>
        <v-col cols="auto">
          <SubButton :to="{ name: 'category-types' }">一覧に戻る</SubButton>
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
              <ToggleSwitch
                :label="form.is_enable ? '公開' : '非公開'"
                :error-messages="$store.getters['error/validate']('is_enable')"
                v-model="form.is_enable"
              />
              <TextField
                label="名称"
                :error-messages="$store.getters['error/validate']('name')"
                v-model="form.name"
              />
              <TextField
                label="スラッグ"
                :error-messages="$store.getters['error/validate']('slug')"
                hint="半角英数字"
                v-model="form.slug"
              />
              <Select
                label="選択形式"
                :items="select.selects"
                :error-messages="$store.getters['error/validate']('select')"
                v-model="form.select"
              />
              <ToggleSwitch
                :label="form.is_multiple ? '複数選択可' : '複数選択不可'"
                :error-messages="$store.getters['error/validate']('is_multiple')"
                v-if="form.select === 'select'"
                v-model="form.is_multiple"
              />
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <MainButton type="submit">{{ action }}</MainButton>
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

const CREATE_NAME = 'category-type-create';

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
  watch: {
    'form.select': {
      handler(newState) {
        switch (newState) {
          case 'checkbox':
            this.form.is_multiple = true;
            return;
          case 'radio':
            this.form.is_multiple = false;
            return;
        }
      },
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
        name: '',
        slug: '',
        select: '',
        is_multiple: false,
        is_enable: true,
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
        const response = await axios.get(API_URL + '/admin/category-types/' + id);

        if (response.status === OK) {
          this.select = { selects: response.data.selects };

          if (this.isCreate) return false;

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
          ? await axios.post(API_URL + '/admin/category-types', this.form)
          : await axios.put(API_URL + '/admin/category-types/' + this.single.id, this.form);

        if (response.status === OK || response.status === CREATED) {
          this.single = { ...response.data };
          this.form = { ...response.data };

          this.$store.dispatch('system/createLog', {
            response: response,
            message: 'カテゴリタイプを' + this.action + 'しました',
          });

          if (this.isCreate)
            this.$router.replace({
              name: 'category-type-edit',
              params: { id: this.single.id },
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

<style lang="scss" scoped>
.v-input:not(:last-child) {
  margin-bottom: 15px;
}
.v-card:not(:first-child) {
  margin-top: 24px;
}
</style>
